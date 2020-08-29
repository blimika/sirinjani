<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        DB::table('t_level')->delete();
        //insert some dummy records
        DB::table('t_level')->insert(array(
        array('level_id'=>'1', 'level_nama'=>'Pemantau','level_jenis'=>'3'),
        array('level_id'=>'2', 'level_nama'=>'Operator Kabkota','level_jenis'=>'2'),
        array('level_id'=>'3', 'level_nama'=>'Operator Provinsi','level_jenis'=>'1'),
        array('level_id'=>'4', 'level_nama'=>'Admin Kabkota','level_jenis'=>'2'),
        array('level_id'=>'5', 'level_nama'=>'Admin Provinsi','level_jenis'=>'1'),
        array('level_id'=>'9', 'level_nama'=>'Superadmin','level_jenis'=>'3'),
         ));
        DB::table('t_jenis')->delete();
        //insert some dummy records
        DB::table('t_jenis')->insert(array(
        array('jenis_kode'=>'1', 'jenis_nama'=>'Provinsi'),
        array('jenis_kode'=>'2', 'jenis_nama'=>'Kabkota'),
        array('jenis_kode'=>'3', 'jenis_nama'=>'Semua'),
         ));
         DB::table('t_realisasi')->delete();
        //insert some dummy records
        DB::table('t_realisasi')->insert(array(
        array('rkeg_id'=>'1', 'rkeg_nama'=>'Pengiriman'),
        array('rkeg_id'=>'2', 'rkeg_nama'=>'Penerimaan'),
         ));
         DB::table('t_jk')->delete();
        //insert some dummy records
        DB::table('t_jk')->insert(array(
        array('jk_id'=>'1', 'jk_nama'=>'Laki-laki'),
        array('jk_id'=>'2', 'jk_nama'=>'Perempuan'),
         ));
         //add superadmin
         DB::table('users')->delete();
        //insert some dummy records
        DB::table('users')->insert(array(
        array('nama'=>'Super Admin', 'password'=>bcrypt('super'),'email'=>'admin@bpsntb.id','username'=>'admin','kodeunit'=>'52000','kodebps'=>'5200','aktif'=>'1','level'=>'9','created_at'=>NOW(),'updated_at'=>NOW()),
        array('nama'=>'Admin Provinsi', 'password'=>bcrypt('admin'),'email'=>'admin5200@bpsntb.id','username'=>'admin5200','kodeunit'=>'52000','kodebps'=>'5200','aktif'=>'1','level'=>'5','created_at'=>NOW(),'updated_at'=>NOW()),
        array('nama'=>'Admin Lombok Barat', 'password'=>bcrypt('admin'),'email'=>'admin5201@bpsntb.id','username'=>'admin5201','kodeunit'=>'52010','kodebps'=>'5201','aktif'=>'1','level'=>'4','created_at'=>NOW(),'updated_at'=>NOW()),
        array('nama'=>'Admin Lombok Tengah', 'password'=>bcrypt('admin'),'email'=>'admin5202@bpsntb.id','username'=>'admin5202','kodeunit'=>'52020','kodebps'=>'5202','aktif'=>'1','level'=>'4','created_at'=>NOW(),'updated_at'=>NOW()),
        array('nama'=>'Admin Lombok Timur', 'password'=>bcrypt('admin'),'email'=>'admin5203@bpsntb.id','username'=>'admin5203','kodeunit'=>'52030','kodebps'=>'5203','aktif'=>'1','level'=>'4','created_at'=>NOW(),'updated_at'=>NOW()),
        array('nama'=>'Admin Sumbawa', 'password'=>bcrypt('admin'),'email'=>'admin5204@bpsntb.id','username'=>'admin5204','kodeunit'=>'52040','kodebps'=>'5204','aktif'=>'1','level'=>'4','created_at'=>NOW(),'updated_at'=>NOW()),
        array('nama'=>'Admin Dompu', 'password'=>bcrypt('admin'),'email'=>'admin5205@bpsntb.id','username'=>'admin5205','kodeunit'=>'52050','kodebps'=>'5205','aktif'=>'1','level'=>'4','created_at'=>NOW(),'updated_at'=>NOW()),
        array('nama'=>'Admin Kab Bima', 'password'=>bcrypt('admin'),'email'=>'admin5206@bpsntb.id','username'=>'admin5206','kodeunit'=>'52060','kodebps'=>'5206','aktif'=>'1','level'=>'4','created_at'=>NOW(),'updated_at'=>NOW()),
        array('nama'=>'Admin Sumbawa Barat', 'password'=>bcrypt('admin'),'email'=>'admin5207@bpsntb.id','username'=>'admin5207','kodeunit'=>'52070','kodebps'=>'5207','aktif'=>'1','level'=>'4','created_at'=>NOW(),'updated_at'=>NOW()),
        array('nama'=>'Admin Lombok Utara', 'password'=>bcrypt('admin'),'email'=>'admin5208@bpsntb.id','username'=>'admin5208','kodeunit'=>'52080','kodebps'=>'5208','aktif'=>'1','level'=>'4','created_at'=>NOW(),'updated_at'=>NOW()),
        array('nama'=>'Admin Kota Mataram', 'password'=>bcrypt('admin'),'email'=>'admin5271@bpsntb.id','username'=>'admin5271','kodeunit'=>'52710','kodebps'=>'5271','aktif'=>'1','level'=>'4','created_at'=>NOW(),'updated_at'=>NOW()),
        array('nama'=>'Admin Kota Bima', 'password'=>bcrypt('admin'),'email'=>'admin5272@bpsntb.id','username'=>'admin5272','kodeunit'=>'52720','kodebps'=>'5272','aktif'=>'1','level'=>'4','created_at'=>NOW(),'updated_at'=>NOW()),
        array('nama'=>'Operator 5201', 'password'=>bcrypt('bps5201'),'email'=>'bps5201@bpsntb.id','username'=>'bps5201','kodeunit'=>'52010','kodebps'=>'5201','aktif'=>'1','level'=>'2','created_at'=>NOW(),'updated_at'=>NOW()),
        array('nama'=>'Operator 5202', 'password'=>bcrypt('bps5202'),'email'=>'bps5202@bpsntb.id','username'=>'bps5202','kodeunit'=>'52020','kodebps'=>'5202','aktif'=>'1','level'=>'2','created_at'=>NOW(),'updated_at'=>NOW()),
        array('nama'=>'Operator 5203', 'password'=>bcrypt('bps5203'),'email'=>'bps5203@bpsntb.id','username'=>'bps5203','kodeunit'=>'52030','kodebps'=>'5203','aktif'=>'1','level'=>'2','created_at'=>NOW(),'updated_at'=>NOW()),
        array('nama'=>'Operator 5204', 'password'=>bcrypt('bps5204'),'email'=>'bps5204@bpsntb.id','username'=>'bps5204','kodeunit'=>'52040','kodebps'=>'5204','aktif'=>'1','level'=>'2','created_at'=>NOW(),'updated_at'=>NOW()),
        array('nama'=>'Operator 5205', 'password'=>bcrypt('bps5205'),'email'=>'bps5205@bpsntb.id','username'=>'bps5205','kodeunit'=>'52050','kodebps'=>'5205','aktif'=>'1','level'=>'2','created_at'=>NOW(),'updated_at'=>NOW()),
        array('nama'=>'Operator 5206', 'password'=>bcrypt('bps5206'),'email'=>'bps5206@bpsntb.id','username'=>'bps5206','kodeunit'=>'52060','kodebps'=>'5206','aktif'=>'1','level'=>'2','created_at'=>NOW(),'updated_at'=>NOW()),
        array('nama'=>'Operator 5207', 'password'=>bcrypt('bps5207'),'email'=>'bps5207@bpsntb.id','username'=>'bps5207','kodeunit'=>'52070','kodebps'=>'5207','aktif'=>'1','level'=>'2','created_at'=>NOW(),'updated_at'=>NOW()),
        array('nama'=>'Operator 5208', 'password'=>bcrypt('bps5208'),'email'=>'bps5208@bpsntb.id','username'=>'bps5208','kodeunit'=>'52080','kodebps'=>'5208','aktif'=>'1','level'=>'2','created_at'=>NOW(),'updated_at'=>NOW()),
        array('nama'=>'Operator 5271', 'password'=>bcrypt('bps5271'),'email'=>'bps5271@bpsntb.id','username'=>'bps5271','kodeunit'=>'52710','kodebps'=>'5271','aktif'=>'1','level'=>'2','created_at'=>NOW(),'updated_at'=>NOW()),
        array('nama'=>'Operator 5272', 'password'=>bcrypt('bps5272'),'email'=>'bps5272@bpsntb.id','username'=>'bps5272','kodeunit'=>'52720','kodebps'=>'5272','aktif'=>'1','level'=>'2','created_at'=>NOW(),'updated_at'=>NOW()),
        array('nama'=>'Operator TU', 'password'=>bcrypt('tatausaha'),'email'=>'tatausaha@bpsntb.id','username'=>'tatausaha','kodeunit'=>'52510','kodebps'=>'5200','aktif'=>'1','level'=>'3','created_at'=>NOW(),'updated_at'=>NOW()),
        array('nama'=>'Operator Sosial', 'password'=>bcrypt('sosial'),'email'=>'sosial@bpsntb.id','username'=>'sosial','kodeunit'=>'52520','kodebps'=>'5200','aktif'=>'1','level'=>'3','created_at'=>NOW(),'updated_at'=>NOW()),
        array('nama'=>'Operator Produksi', 'password'=>bcrypt('produksi'),'email'=>'produksi@bpsntb.id','username'=>'produksi','kodeunit'=>'52530','kodebps'=>'5200','aktif'=>'1','level'=>'3','created_at'=>NOW(),'updated_at'=>NOW()),
        array('nama'=>'Operator Distribusi', 'password'=>bcrypt('distribusi'),'email'=>'distribusi@bpsntb.id','username'=>'distribusi','kodeunit'=>'52540','kodebps'=>'5200','aktif'=>'1','level'=>'3','created_at'=>NOW(),'updated_at'=>NOW()),
        array('nama'=>'Operator NWAS', 'password'=>bcrypt('nwas'),'email'=>'nwas@bpsntb.id','username'=>'nwas','kodeunit'=>'52550','kodebps'=>'5200','aktif'=>'1','level'=>'3','created_at'=>NOW(),'updated_at'=>NOW()),
        array('nama'=>'Operator IPDS', 'password'=>bcrypt('ipds'),'email'=>'ipds@bpsntb.id','username'=>'ipds','kodeunit'=>'52560','kodebps'=>'5200','aktif'=>'1','level'=>'3','created_at'=>NOW(),'updated_at'=>NOW()),
        array('nama'=>'Isna Zuriatina', 'password'=>bcrypt('isna'),'email'=>'isna@bps.go.id','username'=>'isna','kodeunit'=>'52520','kodebps'=>'5200','aktif'=>'1','level'=>'3','created_at'=>NOW(),'updated_at'=>NOW()),
        array('nama'=>'Lukman', 'password'=>bcrypt('lukman'),'email'=>'lukman@bps.go.id','username'=>'lukman','kodeunit'=>'52710','kodebps'=>'5271','aktif'=>'1','level'=>'4','created_at'=>NOW(),'updated_at'=>NOW()),
        array('nama'=>'Ni Nyoman Ratna', 'password'=>bcrypt('omang'),'email'=>'nyomanratna@bps.go.id','username'=>'omang','kodeunit'=>'52550','kodebps'=>'5200','aktif'=>'1','level'=>'3','created_at'=>NOW(),'updated_at'=>NOW()),
        ));
        //golongan
        DB::table('t_gol')->delete();
        //insert some dummy records
        DB::table('t_gol')->insert(array(
        array('gol_id'=>'11', 'gol_nama'=>'I/a', 'gol_pangkat'=> 'JURU MUDA'),
        array('gol_id'=>'12', 'gol_nama'=>'I/b', 'gol_pangkat'=>'JURU MUDA TINGKAT I'),
        array('gol_id'=>'13', 'gol_nama'=>'I/c', 'gol_pangkat'=>'JURU'),
        array('gol_id'=>'14', 'gol_nama'=>'I/d', 'gol_pangkat'=>'JURU TINGKAT I'),
        array('gol_id'=>'21', 'gol_nama'=>'II/a','gol_pangkat'=> 'PENGATUR MUDA'),
        array('gol_id'=>'22', 'gol_nama'=>'II/b', 'gol_pangkat'=>'PENGATUR MUDA TINGKAT I'),
        array('gol_id'=>'23', 'gol_nama'=>'II/c', 'gol_pangkat'=>'PENGATUR'),
        array('gol_id'=>'24', 'gol_nama'=>'II/d', 'gol_pangkat'=>'PENGATUR TINGKAT I'),
        array('gol_id'=>'31', 'gol_nama'=>'III/a', 'gol_pangkat'=>'PENATA MUDA'),
        array('gol_id'=>'32', 'gol_nama'=>'III/b', 'gol_pangkat'=>'PENATA MUDA TINGKAT I'),
        array('gol_id'=> '33', 'gol_nama'=>'III/c','gol_pangkat'=> 'PENATA'),
        array('gol_id'=> '34', 'gol_nama'=>'III/d','gol_pangkat'=> 'PENATA TINGKAT I'),
        array('gol_id'=>'41', 'gol_nama'=>'IV/a', 'gol_pangkat'=>'PEMBINA'),
        array('gol_id'=> '42', 'gol_nama'=>'IV/b', 'gol_pangkat'=>'PEMBINA TINGKAT I'),
        array('gol_id'=> '43', 'gol_nama'=>'IV/c', 'gol_pangkat'=>'PEMBINA UTAMA MUDA'),
        array('gol_id'=>'44', 'gol_nama'=>'IV/d', 'gol_pangkat'=>'PEMBINA UTAMA MADYA'),
        array('gol_id'=>'45', 'gol_nama'=>'IV/e', 'gol_pangkat'=>'PEMBINA UTAMA'),
         ));
        
        DB::table('t_unitkerja')->delete();
         //insert some dummy records
         DB::table('t_unitkerja')->insert(array(
         array('unit_kode'=>'52000', 'unit_nama'=>'BPS Provinsi Nusa Tenggara Barat', 'unit_parent'=>NULL, 'unit_jenis'=>'1', 'unit_eselon'=> '2'),
         array('unit_kode'=>'52510', 'unit_nama'=>'Bagian Tata Usaha', 'unit_parent'=>'52000', 'unit_jenis'=>'1','unit_eselon'=> '3'),
         array('unit_kode'=>'52511', 'unit_nama'=>'Subbagian Bina Program', 'unit_parent'=>'52510', 'unit_jenis'=>'1', 'unit_eselon'=>'4'),
         array('unit_kode'=>'52512', 'unit_nama'=>'Subbagian Kepegawaian & Hukum', 'unit_parent'=>'52510', 'unit_jenis'=>'1',  'unit_eselon'=>'4'),
         array('unit_kode'=>'52513', 'unit_nama'=>'Subbagian Keuangan', 'unit_parent'=>'52510', 'unit_jenis'=>'1', 'unit_eselon'=>'4'),
         array('unit_kode'=>'52514', 'unit_nama'=>'Subbagian Umum', 'unit_parent'=>'52510', 'unit_jenis'=>'1', 'unit_eselon'=>'4'),
         array('unit_kode'=>'52515', 'unit_nama'=>'Subbagian Pengadaan Barang/Jasa', 'unit_parent'=>'52510','unit_jenis'=>'1', 'unit_eselon'=>'4'),
         array('unit_kode'=>'52520', 'unit_nama'=>'Bidang Statistik Sosial', 'unit_parent'=>'52000', 'unit_jenis'=>'1','unit_eselon'=> '3'),
         array('unit_kode'=>'52521', 'unit_nama'=>'Seksi Statistik Kependudukan', 'unit_parent'=>'52520', 'unit_jenis'=>  '1',  'unit_eselon'=>'4'),
         array('unit_kode'=>'52522', 'unit_nama'=>'Seksi Statistik Ketahanan Sosial', 'unit_parent'=>'52520', 'unit_jenis'=> '1',  'unit_eselon'=>'4'),
         array('unit_kode'=>'52523', 'unit_nama'=>'Seksi Statistik Kesejahteraan Rakyat', 'unit_parent'=>'52520', 'unit_jenis'=> '1',  'unit_eselon'=>'4'),
         array('unit_kode'=>'52530', 'unit_nama'=>'Bidang Statistik Produksi', 'unit_parent'=>'52000','unit_jenis'=>'1','unit_eselon'=> '3'),
         array('unit_kode'=>'52531', 'unit_nama'=>'Seksi Statistik Pertanian', 'unit_parent'=>'52530','unit_jenis'=> '1', 'unit_eselon'=> '4'),
         array('unit_kode'=>'52532', 'unit_nama'=>'Seksi Statistik Industri', 'unit_parent'=>'52530','unit_jenis'=> '1',  'unit_eselon'=>'4'),
         array('unit_kode'=>'52533', 'unit_nama'=>'Seksi Statistik Pertambangan, Energi dan Konstruksi ','unit_parent'=> '52530','unit_jenis'=> '1', 'unit_eselon'=>'4'),
         array('unit_kode'=>'52540', 'unit_nama'=>'Bidang Statistik Distribusi', 'unit_parent'=>'52000','unit_jenis'=> '1', 'unit_eselon'=> '3'),
         array('unit_kode'=>'52541', 'unit_nama'=>'Seksi Statistik Harga Konsumen dan Harga Perdagangan Besar', 'unit_parent'=>'52540','unit_jenis'=>  '1','unit_eselon'=> '4'),
         array('unit_kode'=>'52542', 'unit_nama'=>'Seksi Statistik Keuangan Dan Harga Produsen', 'unit_parent'=>'52540', 'unit_jenis'=>  '1', 'unit_eselon'=>'4'),
         array('unit_kode'=>'52543', 'unit_nama'=>'Seksi Statistik Niaga dan Jasa', 'unit_parent'=>'52540','unit_jenis'=>'1','unit_eselon'=> '4'),
         array('unit_kode'=>'52550', 'unit_nama'=>'Bidang Neraca Wilayah dan Analisis Statistik', 'unit_parent'=>'52000','unit_jenis'=> '1','unit_eselon'=>'3'),
         array('unit_kode'=>'52551', 'unit_nama'=>'Seksi Neraca Produksi','unit_parent'=> '52550','unit_jenis'=>  '1', 'unit_eselon'=>'4'),
         array('unit_kode'=>'52552', 'unit_nama'=>'Seksi Neraca Konsumsi','unit_parent'=> '52550', 'unit_jenis'=> '1', 'unit_eselon'=> '4'),
         array('unit_kode'=>'52553', 'unit_nama'=>'Seksi Analisis Statistik Lintas Sektor', 'unit_parent'=>'52550', 'unit_jenis'=>  '1', 'unit_eselon'=> '4'),
         array('unit_kode'=>'52560', 'unit_nama'=>'Bidang Integrasi Pengolahan dan Diseminasi Statistik', 'unit_parent'=>'52000', 'unit_jenis'=> '1','unit_eselon'=> '3'),
         array('unit_kode'=>'52561', 'unit_nama'=>'Seksi Integrasi Pengolahan Data', 'unit_parent'=>'52560','unit_jenis'=>'1', 'unit_eselon'=> '4'),
         array('unit_kode'=>'52562', 'unit_nama'=>'Seksi Jaringan dan Rujukan Statistik', 'unit_parent'=>'52560','unit_jenis'=>'1','unit_eselon'=>  '4'),
         array('unit_kode'=>'52563', 'unit_nama'=>'Seksi Diseminasi dan Layanan Statistik', 'unit_parent'=>'52560','unit_jenis'=>'1', 'unit_eselon'=> '4'),
         array('unit_kode'=>'52010', 'unit_nama'=>'BPS Kabupaten Lombok Barat', 'unit_parent'=>'52000','unit_jenis'=>'2', 'unit_eselon'=> '3'),
         array('unit_kode'=>'52020', 'unit_nama'=>'BPS Kabupaten Lombok Tengah', 'unit_parent'=>'52000','unit_jenis'=>'2', 'unit_eselon'=> '3'),
         array('unit_kode'=>'52030', 'unit_nama'=>'BPS Kabupaten Lombok Timur', 'unit_parent'=>'52000','unit_jenis'=>'2', 'unit_eselon'=> '3'),
         array('unit_kode'=>'52040', 'unit_nama'=>'BPS Kabupaten Sumbawa', 'unit_parent'=>'52000','unit_jenis'=>'2', 'unit_eselon'=> '3'),
         array('unit_kode'=>'52050', 'unit_nama'=>'BPS Kabupaten Dompu', 'unit_parent'=>'52000','unit_jenis'=>'2', 'unit_eselon'=> '3'),
         array('unit_kode'=>'52060', 'unit_nama'=>'BPS Kabupaten Bima', 'unit_parent'=>'52000','unit_jenis'=>'2', 'unit_eselon'=> '3'),
         array('unit_kode'=>'52070', 'unit_nama'=>'BPS Kabupaten Sumbawa Barat', 'unit_parent'=>'52000','unit_jenis'=>'2', 'unit_eselon'=> '3'),
         array('unit_kode'=>'52080', 'unit_nama'=>'BPS Kabupaten Lombok Utara', 'unit_parent'=>'52000','unit_jenis'=>'2', 'unit_eselon'=> '3'),
         array('unit_kode'=>'52710', 'unit_nama'=>'BPS Kota Mataram', 'unit_parent'=>'52000','unit_jenis'=>'2', 'unit_eselon'=> '3'),
         array('unit_kode'=>'52720', 'unit_nama'=>'BPS Kota Bima', 'unit_parent'=>'52000','unit_jenis'=>'2', 'unit_eselon'=> '3'),
          ));
          //kode bps
         DB::table('t_kodebps')->delete();
         //insert some dummy records
         DB::table('t_kodebps')->insert(array(
         array('bps_kode'=>'5200', 'bps_nama'=>'BPS Provinsi NTB','bps_jenis'=>'1'),
         array('bps_kode'=>'5201', 'bps_nama'=>'BPS Kabupaten Lombok Barat','bps_jenis'=>'2'),
         array('bps_kode'=>'5202', 'bps_nama'=>'BPS Kabupaten Lombok Tengah','bps_jenis'=>'2'),
         array('bps_kode'=>'5203', 'bps_nama'=>'BPS Kabupaten Lombok Timur','bps_jenis'=>'2'),
         array('bps_kode'=>'5204', 'bps_nama'=>'BPS Kabupaten Sumbawa','bps_jenis'=>'2'),
         array('bps_kode'=>'5205', 'bps_nama'=>'BPS Kabupaten Dompu','bps_jenis'=>'2'),
         array('bps_kode'=>'5206', 'bps_nama'=>'BPS Kabupaten Bima','bps_jenis'=>'2'),
         array('bps_kode'=>'5207', 'bps_nama'=>'BPS Kabupaten Sumbawa Barat','bps_jenis'=>'2'),
         array('bps_kode'=>'5208', 'bps_nama'=>'BPS Kabupaten Lombok Utara','bps_jenis'=>'2'),
         array('bps_kode'=>'5271', 'bps_nama'=>'BPS Kota Mataram','bps_jenis'=>'2'),
         array('bps_kode'=>'5272', 'bps_nama'=>'BPS Kota Bima','bps_jenis'=>'2'),
          ));
        DB::table('t_keg_jenis')->delete();
        //insert some dummy records
        DB::table('t_keg_jenis')->insert(array(
        array('jkeg_id'=>'1', 'jkeg_nama'=>'Bulanan'),
        array('jkeg_id'=>'2', 'jkeg_nama'=>'Triwulanan'),
        array('jkeg_id'=>'3', 'jkeg_nama'=>'Semesteran'),
        array('jkeg_id'=>'4', 'jkeg_nama'=>'Tahunan'),
        array('jkeg_id'=>'5', 'jkeg_nama'=>'Subround'),
        array('jkeg_id'=>'6', 'jkeg_nama'=>'AdHoc'),
        ));
    }
}
