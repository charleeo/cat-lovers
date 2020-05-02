<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMoviesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->increments('movie_id');
            $table->unsignedBigInteger('user_id');
            $table->string('movie_title', 55);
            $table->string('cat_1_image');
            $table->string('cat_1_name', 55);
            $table->string('cat_2_image');
            $table->string('cat_2_name', 55);
            $table->string('fight_video');
            $table->string('fight_duration')->nullable();
            $table->text('description')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('movies');
    }
}
