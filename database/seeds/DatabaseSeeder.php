<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{

    /**
     * @var array
     */
    protected $tables = [
        'users', 'phones', 'erasers', 'bulks', 'bulk_eraser'
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->cleanDatabase();

        // Create the Admin User
        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('admin'),
            'remember_token' => str_random(10),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        factory(App\User::class, 19)->create();
        factory(App\Phone::class, 100)->create();
        factory(App\Bulk::class, 100)->create();
        factory(App\Cluster::class, 5)->create();
        factory(App\Eraser::class, 100)
            ->create()
            ->each(function($e) {
                $bulk = App\Bulk::find(rand(1,100));
                $e->bulks()->attach($bulk);
            });

        Model::reguard();
    }

    /**
     * Clean out the database for a new seed generation
     */
    private function cleanDatabase()
    {

        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        foreach($this->tables as $table)
        {

            DB::table($table)->truncate();

        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');


    }
}
