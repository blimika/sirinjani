<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MKegUnitBaru extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m_keg', function (Blueprint $table) {
            //
            $table->string('keg_unitkerja',5)->default(0)->change();
            $table->string('keg_timkerja',5)->default(0)->after('keg_unitkerja');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('m_keg', function (Blueprint $table) {
            //
        });
    }
}
