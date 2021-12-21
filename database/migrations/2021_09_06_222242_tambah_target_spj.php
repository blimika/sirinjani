<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TambahTargetSpj extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m_keg_target', function (Blueprint $table) {
            //
            $table->decimal('spj_t_point_waktu',6,4)->default(0)->after('keg_t_point');
            $table->decimal('spj_t_point_jumlah',6,4)->default(0)->after('spj_t_point_waktu');
            $table->decimal('spj_t_point',6,4)->default(0)->after('spj_t_point_jumlah');
            $table->decimal('keg_t_point_total',6,4)->default(0)->after('spj_t_point');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('m_keg_target', function (Blueprint $table) {
            //
        });
    }
}
