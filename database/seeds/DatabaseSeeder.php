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
        array('level_id'=>'1', 'level_nama'=>'Pemantau'),
        array('level_id'=>'2', 'level_nama'=>'Operator Kabkota'),
        array('level_id'=>'3', 'level_nama'=>'Operator Provinsi'),
        array('level_id'=>'4', 'level_nama'=>'Admin'),
        array('level_id'=>'9', 'level_nama'=>'Superadmin'),
         ));
        DB::table('t_jenis')->delete();
        //insert some dummy records
        DB::table('t_jenis')->insert(array(
        array('jenis_kode'=>'1', 'jenis_nama'=>'Provinsi'),
        array('jenis_kode'=>'2', 'jenis_nama'=>'Kabkota'),
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
        array('nama'=>'Super Admin', 'password'=>bcrypt('super'),'nipbps'=>'520000000','nipbaru'=>'520000000','email'=>'admin@bpsntb.id','username'=>'admin','jabatan'=>'Kepala','satuankerja'=>'Admin BPSNTB','kodeunit'=>'52000','kodebps'=>'5200','urlfoto'=>'https://via.placeholder.com/100x100','jk'=>'1','aktif'=>'1','level'=>'9','isLokal'=>'1','created_at'=>NOW(),'updated_at'=>NOW()),
        array('nama'=>'Bagian Tata Usaha', 'password'=>bcrypt('tatausaha'),'nipbps'=>'520000001','nipbaru'=>'520000001','email'=>'tatausaha@bpsntb.id','username'=>'tatausaha','jabatan'=>'Kepala','satuankerja'=>'Operator Tata Usaha','kodeunit'=>'52510','kodebps'=>'5200','urlfoto'=>'https://via.placeholder.com/100x100','jk'=>'1','aktif'=>'1','level'=>'3','isLokal'=>'1','created_at'=>NOW(),'updated_at'=>NOW()),
        array('nama'=>'Bidang Statistik Sosial', 'password'=>bcrypt('sosial'),'nipbps'=>'520000002','nipbaru'=>'520000002','email'=>'sosial@bpsntb.id','username'=>'sosial','jabatan'=>'Kepala','satuankerja'=>'Operator Bidang Sosial','kodeunit'=>'52520','kodebps'=>'5200','urlfoto'=>'https://via.placeholder.com/100x100','jk'=>'1','aktif'=>'1','level'=>'3','isLokal'=>'1','created_at'=>NOW(),'updated_at'=>NOW()),
        array('nama'=>'Bidang Statistik Produksi', 'password'=>bcrypt('produksi'),'nipbps'=>'520000003','nipbaru'=>'520000003','email'=>'produksi@bpsntb.id','username'=>'produksi','jabatan'=>'Kepala','satuankerja'=>'Operator Bidang Produksi','kodeunit'=>'52530','kodebps'=>'5200','urlfoto'=>'https://via.placeholder.com/100x100','jk'=>'1','aktif'=>'1','level'=>'3','isLokal'=>'1','created_at'=>NOW(),'updated_at'=>NOW()),
        array('nama'=>'Bidang Statistik Distribusi', 'password'=>bcrypt('distribusi'),'nipbps'=>'520000004','nipbaru'=>'520000004','email'=>'distribusi@bpsntb.id','username'=>'distribusi','jabatan'=>'Kepala','satuankerja'=>'Operator Bidang Distribusi','kodeunit'=>'52540','kodebps'=>'5200','urlfoto'=>'https://via.placeholder.com/100x100','jk'=>'1','aktif'=>'1','level'=>'3','isLokal'=>'1','created_at'=>NOW(),'updated_at'=>NOW()),
        array('nama'=>'Bidang NWAS', 'password'=>bcrypt('nwas'),'nipbps'=>'520000005','nipbaru'=>'520000005','email'=>'nwas@bpsntb.id','username'=>'nwas','jabatan'=>'Kepala','satuankerja'=>'Operator Bidang NWAS','kodeunit'=>'52550','kodebps'=>'5200','urlfoto'=>'https://via.placeholder.com/100x100','jk'=>'1','aktif'=>'1','level'=>'3','isLokal'=>'1','created_at'=>NOW(),'updated_at'=>NOW()),
        array('nama'=>'Bidang IPDS', 'password'=>bcrypt('ipds'),'nipbps'=>'520000006','nipbaru'=>'520000006','email'=>'ipds@bpsntb.id','username'=>'ipds','jabatan'=>'Kepala','satuankerja'=>'Operator Bidang IPDS','kodeunit'=>'52560','kodebps'=>'5200','urlfoto'=>'https://via.placeholder.com/100x100','jk'=>'1','aktif'=>'1','level'=>'3','isLokal'=>'1','created_at'=>NOW(),'updated_at'=>NOW()),
        array('nama'=>'Operator Lombok Barat', 'password'=>bcrypt('bps5201'),'nipbps'=>'520100000','nipbaru'=>'520100000','email'=>'bps5201@bpsntb.id','username'=>'bps5201','jabatan'=>'Kepala','satuankerja'=>'Operator Lombok Barat','kodeunit'=>'52010','kodebps'=>'5201','urlfoto'=>'https://via.placeholder.com/100x100','jk'=>'1','aktif'=>'1','level'=>'2','isLokal'=>'1','created_at'=>NOW(),'updated_at'=>NOW()),
        array('nama'=>'Operator Lombok Tengah', 'password'=>bcrypt('bps5202'),'nipbps'=>'520200000','nipbaru'=>'520200000','email'=>'bps5202@bpsntb.id','username'=>'bps5202','jabatan'=>'Kepala','satuankerja'=>'Operator Lombok Tengah','kodeunit'=>'52020','kodebps'=>'5202','urlfoto'=>'https://via.placeholder.com/100x100','jk'=>'1','aktif'=>'1','level'=>'2','isLokal'=>'1','created_at'=>NOW(),'updated_at'=>NOW()),
        array('nama'=>'Operator Lombok Timur', 'password'=>bcrypt('bps5203'),'nipbps'=>'520300000','nipbaru'=>'520300000','email'=>'bps5203@bpsntb.id','username'=>'bps5203','jabatan'=>'Kepala','satuankerja'=>'Operator Lombok Timur','kodeunit'=>'52030','kodebps'=>'5203','urlfoto'=>'https://via.placeholder.com/100x100','jk'=>'1','aktif'=>'1','level'=>'2','isLokal'=>'1','created_at'=>NOW(),'updated_at'=>NOW()),
        array('nama'=>'Operator Sumbawa', 'password'=>bcrypt('bps5204'),'nipbps'=>'520400000','nipbaru'=>'520400000','email'=>'bps5204@bpsntb.id','username'=>'bps5204','jabatan'=>'Kepala','satuankerja'=>'Operator Sumbawa','kodeunit'=>'52040','kodebps'=>'5204','urlfoto'=>'https://via.placeholder.com/100x100','jk'=>'1','aktif'=>'1','level'=>'2','isLokal'=>'1','created_at'=>NOW(),'updated_at'=>NOW()),
        array('nama'=>'Operator Dompu', 'password'=>bcrypt('bps5205'),'nipbps'=>'520500000','nipbaru'=>'520500000','email'=>'bps5205@bpsntb.id','username'=>'bps5205','jabatan'=>'Kepala','satuankerja'=>'Operator Dompu','kodeunit'=>'52050','kodebps'=>'5205','urlfoto'=>'https://via.placeholder.com/100x100','jk'=>'1','aktif'=>'1','level'=>'2','isLokal'=>'1','created_at'=>NOW(),'updated_at'=>NOW()),
        array('nama'=>'Operator BimaKab', 'password'=>bcrypt('bps5206'),'nipbps'=>'520600000','nipbaru'=>'520600000','email'=>'bps5206@bpsntb.id','username'=>'bps5206','jabatan'=>'Kepala','satuankerja'=>'Operator Bimakab','kodeunit'=>'52060','kodebps'=>'5206','urlfoto'=>'https://via.placeholder.com/100x100','jk'=>'1','aktif'=>'1','level'=>'2','isLokal'=>'1','created_at'=>NOW(),'updated_at'=>NOW()),
        array('nama'=>'Operator Sumbawa Barat', 'password'=>bcrypt('bps5207'),'nipbps'=>'520700000','nipbaru'=>'520700000','email'=>'bps5207@bpsntb.id','username'=>'bps5207','jabatan'=>'Kepala','satuankerja'=>'Operator Sumbawa Barat','kodeunit'=>'52070','kodebps'=>'5207','urlfoto'=>'https://via.placeholder.com/100x100','jk'=>'1','aktif'=>'1','level'=>'2','isLokal'=>'1','created_at'=>NOW(),'updated_at'=>NOW()),
        array('nama'=>'Operator Lombok Utara', 'password'=>bcrypt('bps5208'),'nipbps'=>'520800000','nipbaru'=>'520800000','email'=>'bps5208@bpsntb.id','username'=>'bps5208','jabatan'=>'Kepala','satuankerja'=>'Operator Lombok Utara','kodeunit'=>'52080','kodebps'=>'5208','urlfoto'=>'https://via.placeholder.com/100x100','jk'=>'1','aktif'=>'1','level'=>'2','isLokal'=>'1','created_at'=>NOW(),'updated_at'=>NOW()),
        array('nama'=>'Operator Kota Mataram', 'password'=>bcrypt('bps5271'),'nipbps'=>'527100000','nipbaru'=>'527100000','email'=>'bps5271@bpsntb.id','username'=>'bps5271','jabatan'=>'Kepala','satuankerja'=>'Operator Kota Mataram','kodeunit'=>'52710','kodebps'=>'5271','urlfoto'=>'https://via.placeholder.com/100x100','jk'=>'1','aktif'=>'1','level'=>'2','isLokal'=>'1','created_at'=>NOW(),'updated_at'=>NOW()),
        array('nama'=>'Operator Kota Bima', 'password'=>bcrypt('bps5272'),'nipbps'=>'527200000','nipbaru'=>'527200000','email'=>'bps5272@bpsntb.id','username'=>'bps5272','jabatan'=>'Kepala','satuankerja'=>'Operator Kota Bima','kodeunit'=>'52720','kodebps'=>'5272','urlfoto'=>'https://via.placeholder.com/100x100','jk'=>'1','aktif'=>'1','level'=>'2','isLokal'=>'1','created_at'=>NOW(),'updated_at'=>NOW()),
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
