<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SpjRealisasi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_spj_realisasi', function (Blueprint $table) {
            $table->bigIncrements('spj_r_id');
            $table->bigInteger('keg_id');
            $table->string('spj_r_unitkerja',5);
            $table->integer('spj_r_jumlah')->default(0);
            $table->date('spj_r_tgl');
            $table->boolean('spj_r_jenis');
            $table->string('spj_r_link',254)->nullable();
            $table->string('spj_r_ket',254)->nullable();
            $table->string('spj_r_dibuat_oleh',254);
            $table->string('spj_r_diupdate_oleh',254);
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
        Schema::dropIfExists('m_spj_realisasi');
    }
}
