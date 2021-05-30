<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LogAktivitas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_aktivitas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('log_username');
            $table->text('log_pesan');
            $table->string('log_ip',20)->nullable();
            $table->string('log_useragent',255)->nullable();
            $table->tinyInteger('log_jenis')->unsigned();
            $table->boolean('log_flag')->nullable()->default(0);
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
        Schema::dropIfExists('t_aktivitas');
    }
}
