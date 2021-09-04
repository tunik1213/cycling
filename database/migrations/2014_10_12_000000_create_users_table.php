<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->string('refresh_token',40)->nullable();
            $table->string('access_token',40)->nullable();
            $table->integer('athlete_id')->unique();
            $table->string('firstname');
            $table->string('lastname');
            $table->string('sex',1)->nullable();
            $table->boolean('premium');
            $table->datetime('strava_created_at');
            $table->binary('avatar')->nullable();
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
        Schema::dropIfExists('users');
    }
}
