<?php

use Illuminate\Database\Seeder;

class JenisLog extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('t_jenislog')->delete();
        //insert some dummy records
        DB::table('t_jenislog')->insert(array(
        array('jlog_id'=>'1', 'jlog_nama'=>'Login'),
        array('jlog_id'=>'2', 'jlog_nama'=>'Logout'),
        array('jlog_id'=>'3', 'jlog_nama'=>'Kegiatan'),
        array('jlog_id'=>'4', 'jlog_nama'=>'Pengiriman'),
        array('jlog_id'=>'5', 'jlog_nama'=>'Penerimaan'),
        array('jlog_id'=>'6', 'jlog_nama'=>'Operator'),
        array('jlog_id'=>'7', 'jlog_nama'=>'Lainnya'),
        ));
    }
}
