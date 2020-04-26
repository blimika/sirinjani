<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MasterKegiatan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_keg', function (Blueprint $table) {
            $table->bigIncrements('keg_id');
            $table->string('keg_nama',255);
            $table->string('keg_unitkerja',5);
            $table->date('keg_start');
            $table->date('keg_end');
            $table->tinyInteger('keg_jenis');
            $table->integer('keg_total_target');
            $table->string('keg_target_satuan',254);
            $table->tinyInteger('keg_spj');
            $table->text('keg_info')->nullable();
            $table->string('keg_dibuat_oleh',254);
            $table->string('keg_diupdate_oleh',254);
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
        Schema::dropIfExists('m_keg');
    }
}
