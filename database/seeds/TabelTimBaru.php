<?php

use Illuminate\Database\Seeder;

class TabelTimBaru extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        //tambah tim baru
        DB::table('t_unitkerja')->insert(array(
            array('unit_kode'=>'52570', 'unit_nama'=>'Tim Sensus', 'unit_parent'=>'52000', 'unit_jenis'=> '1','unit_eselon'=> '3'),
            array('unit_kode'=>'52571', 'unit_nama'=>'Tim Statistik Pertanian, Industri dan PEK', 'unit_parent'=>'52000', 'unit_jenis'=> '1','unit_eselon'=> '3'),
            array('unit_kode'=>'52572', 'unit_nama'=>'Tim Reformasi Birokrasi', 'unit_parent'=>'52000', 'unit_jenis'=> '1','unit_eselon'=> '3'),
            array('unit_kode'=>'52573', 'unit_nama'=>'Tim Statistik Rumah Tangga', 'unit_parent'=>'52000', 'unit_jenis'=> '1','unit_eselon'=> '3'),
            array('unit_kode'=>'52574', 'unit_nama'=>'Tim Diseminasi dan Pembinaan Statistik Sektoral', 'unit_parent'=>'52000', 'unit_jenis'=> '1','unit_eselon'=> '3'),
            array('unit_kode'=>'52575', 'unit_nama'=>'Tim Humas dan Protokoler', 'unit_parent'=>'52000', 'unit_jenis'=> '1','unit_eselon'=> '3'),
            array('unit_kode'=>'52576', 'unit_nama'=>'Tim Pengolahan dan TI', 'unit_parent'=>'52000', 'unit_jenis'=> '1','unit_eselon'=> '3'),
            array('unit_kode'=>'52577', 'unit_nama'=>'Tim UKK dan Penjaminan Kualitas', 'unit_parent'=>'52000', 'unit_jenis'=> '1','unit_eselon'=> '3'),
            array('unit_kode'=>'52578', 'unit_nama'=>'Tim Statistik Distribusi dan Jasa', 'unit_parent'=>'52000', 'unit_jenis'=> '1','unit_eselon'=> '3'),
            array('unit_kode'=>'52579', 'unit_nama'=>'Tim Manajemen Lapangan dan Mitra', 'unit_parent'=>'52000', 'unit_jenis'=> '1','unit_eselon'=> '3'),
            array('unit_kode'=>'52580', 'unit_nama'=>'Tim Neraca dan Analisis Statistik', 'unit_parent'=>'52000', 'unit_jenis'=> '1','unit_eselon'=> '3'),
        ));
    }
}
