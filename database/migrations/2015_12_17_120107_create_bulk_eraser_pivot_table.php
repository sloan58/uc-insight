<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBulkEraserPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bulk_eraser', function (Blueprint $table) {
            $table->integer('bulk_id')->unsigned()->index();
            $table->foreign('bulk_id')->references('id')->on('bulks')->onDelete('cascade');
            $table->integer('eraser_id')->unsigned()->index();
            $table->foreign('eraser_id')->references('id')->on('erasers')->onDelete('cascade');
            $table->primary(['bulk_id', 'eraser_id']);
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
