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
        Schema::create('stories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->tinyInteger('status')->nullable();
            $table->string('author');
            $table->string('author_2')->nullable();
            $table->text('descriptions')->nullable();
            $table->tinyInteger('level')->nullable();
            $table->tinyInteger('pin')->nullable();
            $table->foreignId('user_id')->constrained();
            $table->string('image', 1000)->nullable();
            $table->string('slug', 1000)->nullable();
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stories');
    }
};
