<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBulksErasersPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bulks_erasers', function (Blueprint $table) {
            $table->integer('bulks_id')->unsigned()->index();
            $table->foreign('bulks_id')->references('id')->on('bulks')->onDelete('cascade');
            $table->integer('erasers_id')->unsigned()->index();
            $table->foreign('erasers_id')->references('id')->on('erasers')->onDelete('cascade');
            $table->primary(['bulks_id', 'erasers_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('bulk_eraser');
    }
}
