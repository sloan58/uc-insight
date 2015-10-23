<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateClustersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clusters', function (Blueprint $table) {
            $table->boolean('verify_peer')->after('version')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clusters', function (Blueprint $table) {
            $table->dropColumn('verify_peer');
        });
    }
}
