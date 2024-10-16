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
        if (!Schema::hasColumn('authors', 'level')) {
            Schema::table('authors', function(Blueprint $table) {
                $table->tinyInteger('level')->nullable()->after('name');
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
        if (Schema::hasColumn('authors', 'level')) {
            Schema::table('authors', function(Blueprint $table) {
                $table->dropColumn('level');
            });
        }
    }
};
