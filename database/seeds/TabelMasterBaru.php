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
    }
}
