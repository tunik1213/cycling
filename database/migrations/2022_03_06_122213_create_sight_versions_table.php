<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSightVersionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sight_versions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('sight_id');
            $table->integer('user_id');
            $table->integer('moderator')->nullable();
            $table->longText('data');
            $table->index('sight_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sight_versions');
    }
}
