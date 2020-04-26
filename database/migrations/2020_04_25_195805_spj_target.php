<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SpjTarget extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_spj_target', function (Blueprint $table) {
            $table->bigIncrements('spj_t_id');
            $table->bigInteger('keg_id');
            $table->string('spj_t_unitkerja',5);
            $table->integer('spj_t_target')->default(0);
            $table->decimal('spj_t_point_waktu',6,4);
            $table->decimal('spj_t_point_jumlah',6,4);
            $table->decimal('spj_t_point',6,4);
            $table->string('spj_t_dibuat_oleh',254);
            $table->string('spj_t_diupdate_oleh',254);
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
        Schema::dropIfExists('m_spj_target');
    }
}
