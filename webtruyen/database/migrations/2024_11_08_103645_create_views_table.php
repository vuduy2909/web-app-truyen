<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('views', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->nullable();
            $table->bigInteger('story_id')->unsigned()->nullable();
            $table->foreign('story_id')->references('id')->on('stories')->onDelete('cascade');
            $table->bigInteger('chapter_id')->unsigned()->nullable();
            $table->foreign('chapter_id')->references('id')->on('chapters')->onDelete('cascade');

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
        Schema::dropIfExists('views');
    }
};
