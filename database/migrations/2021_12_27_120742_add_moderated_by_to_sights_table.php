<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddModeratedByToSightsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sights', function (Blueprint $table) {
            $table->integer('moderator')->nullable();
        });

        DB::statement('update sights set moderator = 1;');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sights', function (Blueprint $table) {
            $table->dropColumn('moderator');

        });
    }
}
