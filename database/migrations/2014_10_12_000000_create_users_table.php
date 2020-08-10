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
            $table->bigIncrements('id');
            $table->string('nama');
            $table->string('password');
            $table->string('email')->nullable();
            $table->string('username')->unique();
            $table->string('kodeunit',5)->nullable();
            $table->string('kodebps',4);
            $table->string('nohp',25)->nullable();
            $table->boolean('aktif')->default(1);
            $table->boolean('level')->default(1);
            $table->string('lastip',20)->nullable();
            $table->dateTime('lastlogin')->nullable();
            $table->rememberToken();
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
