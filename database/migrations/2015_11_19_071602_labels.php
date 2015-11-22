<?php

use \DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Labels extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('labels', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('name', 50);
            $table->tinyInteger('color');
        });

        Schema::create('label_note', function (Blueprint $table) {
            $table->integer('label_id', false, true)->length(10)->index();
            $table->integer('note_id', false, true)->length(10)->index();

            $table->foreign('label_id')->references('id')->on('labels')->onDelete('cascade');
            $table->foreign('note_id')->references('id')->on('notes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('labels');
        Schema::drop('label_note');
    }
}
