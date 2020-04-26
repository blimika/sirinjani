<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class KegiatanTarget extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_keg_target', function (Blueprint $table) {
            $table->bigIncrements('keg_t_id');
            $table->bigInteger('keg_id');
            $table->string('keg_t_unitkerja',5);
            $table->integer('keg_t_target')->default(0);
            $table->decimal('keg_t_point_waktu',6,4);
            $table->decimal('keg_t_point_jumlah',6,4);
            $table->decimal('keg_t_point',6,4);
            $table->string('keg_t_dibuat_oleh',254);
            $table->string('keg_t_diupdate_oleh',254);
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
        Schema::dropIfExists('m_keg_target');
    }
}
