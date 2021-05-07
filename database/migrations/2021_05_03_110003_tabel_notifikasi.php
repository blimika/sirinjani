<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TabelNotifikasi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_notifikasi', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('keg_id')->nullable();
            $table->string('notif_dari');
            $table->string('notif_untuk');
            $table->text('notif_isi');
            $table->boolean('notif_flag')->nullable()->default(0);
            $table->tinyInteger('notif_jenis')->unsigned();
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
        Schema::dropIfExists('t_notifikasi');
    }
}
