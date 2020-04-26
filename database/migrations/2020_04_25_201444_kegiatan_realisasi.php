<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class KegiatanRealisasi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_keg_realisasi', function (Blueprint $table) {
            $table->bigIncrements('keg_r_id');
            $table->bigInteger('keg_id');
            $table->string('keg_r_unitkerja',5);
            $table->integer('keg_r_jumlah')->default(0);
            $table->date('keg_r_tgl');
            $table->boolean('keg_r_jenis');
            $table->string('keg_r_link',254)->nullable();
            $table->string('keg_r_ket',254)->nullable();
            $table->string('keg_r_dibuat_oleh',254);
            $table->string('keg_r_diupdate_oleh',254);
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
        Schema::dropIfExists('m_keg_realisasi');
    }
}
