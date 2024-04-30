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
            ['id'=>1,'kode' => 0, 'nama' => 'Konsep'],
            ['id'=>2,'kode' => 1, 'nama' => 'Publik'],
        ]);

        DB::table('t_flag')->delete();
        DB::table('t_flag')->insert([
            ['id'=>1,'kode' => 0, 'nama' => 'Nonaktif'],
            ['id'=>2,'kode' => 1, 'nama' => 'Aktif'],
        ]);
    }
}
