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
        if (!Schema::hasColumn('categories', 'slug')) {
            Schema::table('categories', function(Blueprint $table) {
                $table->string('slug')->nullable()->after('descriptions');
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
        if (Schema::hasColumn('categories', 'slug')) {
            Schema::table('categories', function(Blueprint $table) {
                $table->dropColumn('slug');
            });
        }
    }
};
