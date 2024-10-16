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
                $table->dropColumn('author');
            });
        }
        if (Schema::hasColumn('stories', 'author_2')) {
            Schema::table('stories', function(Blueprint $table) {
                $table->dropColumn('author_2');
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

    }
};
