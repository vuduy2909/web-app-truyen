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
        if (Schema::hasColumn('stories', 'author')) {
            Schema::table('stories', function(Blueprint $table) {
                $table->foreignId('author_id')
                    ->after('status')->change()
                    ->constrained('authors');
            });
        }
        if (Schema::hasColumn('stories', 'author_2')) {
            Schema::table('stories', function(Blueprint $table) {
                $table->foreignId('author_2_id')->nullable()
                    ->after('author_id')->change()
                    ->constrained('authors');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stories', function (Blueprint $table) {
            //
        });
    }
};
