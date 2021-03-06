<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateRoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('routes', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('user_id');
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->boolean('finished')->default(false);
            $table->integer('moderator')->nullable();
            $table->string('license')->nullable();
            $table->index('user_id');

        });

        DB::statement("ALTER TABLE routes ADD logo_image MEDIUMBLOB");
        DB::statement("ALTER TABLE routes ADD map_image MEDIUMBLOB");

        Schema::create('route_sight',function(Blueprint $table) {
            $table->integer('row_number');
            $table->integer('sight_id');
            $table->integer('route_id');
            $table->index('sight_id');
            $table->index('route_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('routes');
        Schema::dropIfExists('route_sight');
    }
}
