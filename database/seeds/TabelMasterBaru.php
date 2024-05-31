<?php

use Illuminate\Database\Seeder;

class TabelMasterBaru extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('t_flag_keg')->delete();
        DB::table('t_flag_keg')->insert([
            ['id'=>1,'kode' => 0, 'nama' => 'Draft'],
            ['id'=>2,'kode' => 1, 'nama' => 'Publik'],
        ]);

        DB::table('t_flag')->delete();
        DB::table('t_flag')->insert([
            ['id'=>1,'kode' => 0, 'nama' => 'Nonaktif'],
            ['id'=>2,'kode' => 1, 'nama' => 'Aktif'],
        ]);
        //role baru
        DB::table('t_level')->delete();
        DB::table('t_level')->insert(array(
        array('level_id'=>'1', 'level_nama'=>'Pemantau','level_jenis'=>'3'),
        array('level_id'=>'2', 'level_nama'=>'Operator Kabkota','level_jenis'=>'2'),
        array('level_id'=>'3', 'level_nama'=>'Admin Kabkota','level_jenis'=>'2'),
        array('level_id'=>'4', 'level_nama'=>'Operator Provinsi','level_jenis'=>'1'),
        array('level_id'=>'5', 'level_nama'=>'Admin Provinsi','level_jenis'=>'1'),
        array('level_id'=>'9', 'level_nama'=>'Superadmin','level_jenis'=>'3'),
         ));
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
