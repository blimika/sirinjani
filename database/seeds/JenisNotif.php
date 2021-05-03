<?php

use Illuminate\Database\Seeder;

class JenisNotif extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('t_jenis_notif')->delete();
        //insert some dummy records
        DB::table('t_jenis_notif')->insert(array(
        array('jnotif_id'=>'1', 'jnotif_nama'=>'Pengiriman'),
        array('jnotif_id'=>'2', 'jnotif_nama'=>'Penerimaan'),
        array('jnotif_id'=>'3', 'jnotif_nama'=>'Kegiatan'),
        array('jnotif_id'=>'4', 'jnotif_nama'=>'Lainnya'),

        ));
    }
}
