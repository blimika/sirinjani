<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TabelHakAkses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_hak_akses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('hak_userid');
            $table->string('hak_username');
            $table->string('hak_kodeunit',5);
            $table->boolean('hak_role')->default(1);
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
        Schema::dropIfExists('t_hak_akses');
    }
}
