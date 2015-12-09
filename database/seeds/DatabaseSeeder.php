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
        'users', 'roles', 'role_user'
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

        // Create the Admin Role
        $adminRole = \App\Role::create([
            'name' => 'Administrator',
            'display_name' => 'administrator',
            'description' => 'All access system admin account',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        // Create the Admin User
        $adminUser = \App\User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('admin'),
            'remember_token' => str_random(10),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        $adminUser->roles()->attach($adminRole->id);

        factory(App\User::class, 19)->create();
        factory(App\Phone::class, 100)->create();
        factory(App\Eraser::class, 100)
            ->create();
            // ->each(function($e) {
            //     $bulk = App\Bulk::find(rand(1,100));
            //     $e->bulks()->attach($bulk);
            // });

//        factory(App\Cluster::class, 50)->create();
        /*
         * TODO: Add features for these roles.....
         */

        // Create the SQL Admin Role
//        \App\Role::create([
//            'name' => 'SQL Admin',
//            'display_name' => 'sql-admin',
//            'description' => 'Create, Delete and run SQL queries',
//            'created_at' => Carbon::now(),
//            'updated_at' => Carbon::now()
//        ]);
        // Create the SQL User Role
//        \App\Role::create([
//            'name' => 'SQL User',
//            'display_name' => 'sql-user',
//            'description' => 'Re-Run existing SQL queries',
//            'created_at' => Carbon::now(),
//            'updated_at' => Carbon::now()
//        ]);

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
