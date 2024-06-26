<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Auth::routes();
/*
Route::get('/', function () {
    return view('depan');
});
*/

Route::get('/', 'DepanController@depan')->name('depan');
Route::get('/kegiatan/list', 'KegiatanController@index')->name('kegiatan.list');
Route::get('/kegiatan/newlist', 'KegiatanController@NewList')->name('kegiatan.newlist');
Route::get('/kegiatan/pagelist', 'KegiatanController@PageList')->name('kegiatan.pagelist');
Route::get('/kegiatan/bidang', 'KegiatanController@bidang')->name('kegiatan.bidang');
Route::get('/kegiatan/cari/{kegId}', 'KegiatanController@cariKegiatan')->name('kegiatan.cari');
Route::get('/kegiatan/caribyunit/{kegid}/{unitkerja}', 'KegiatanController@cariKegByUnitkirim')->name('kegiatan.caribyunit');
Route::get('/realisasi/cari/{kegrid}', 'KegiatanController@CariRealisasi')->name('realisasi.cari');
Route::get('/spj/realisasi/cari/{spjrid}', 'SpjController@CariSpj')->name('spjrealisasi.cari');
Route::post(env('TELEGRAM_HASH_URL') . '/webhook', 'NotifikasiController@WebHook')->name('webhook');
//untuk cli
Route::get('/gen/nilai/{bulan}/{tahun}', 'KegiatanController@GenNilaiKeg')->name('gen.keg');

Route::group(['middleware' => ['auth']], function () {
    Route::get('/kegiatan/tambah', 'KegiatanController@tambah')->name('kegiatan.tambah');
    Route::get('/kegiatan/copy/{kegId}', 'KegiatanController@copyKegiatan')->name('kegiatan.copy');
    Route::get('/kegiatan/detil/{kegId}', 'KegiatanController@DetilKegiatan')->name('kegiatan.detil');
    Route::post('/kegiatan/simpan', 'KegiatanController@simpan')->name('kegiatan.simpan');
    Route::post('/kegiatan/update', 'KegiatanController@UpdateKegiatan')->name('kegiatan.update');
    Route::post('/kegiatan/hapus', 'KegiatanController@hapusKegiatan')->name('kegiatan.hapus');
    Route::post('/kegiatan/sinkrontimkerja', 'KegiatanController@SyncTimKerja')->name('kegiatan.synctimkerja');
    Route::post('/kegiatan/penerimaan', 'KegiatanController@terimaKegiatan')->name('kegiatan.penerimaan');
    Route::post('/kegiatan/updatepenerimaan', 'KegiatanController@UpdatePenerimaan')->name('penerimaan.update');
    Route::post('/kegiatan/hapuspenerimaan', 'KegiatanController@HapusPenerimaan')->name('penerimaan.hapus');
    Route::post('/kegiatan/pengiriman', 'KegiatanController@kirimKegiatan')->name('kegiatan.pengiriman');
    Route::post('/kegiatan/updatepengiriman', 'KegiatanController@UpdatePengiriman')->name('pengiriman.update');
    Route::post('/kegiatan/hapuspengiriman', 'KegiatanController@HapusPengiriman')->name('pengiriman.hapus');
    Route::post('/kegiatan/updateinfo', 'KegiatanController@UpdateInfo')->name('info.update');
    Route::get('/kegiatan/edit/{kegId}', 'KegiatanController@editKegiatan')->name('kegiatan.edit');
    Route::post('/spj/pengiriman', 'SpjController@kirimSpj')->name('spj.pengiriman');
    Route::post('/spj/updatepengiriman', 'SpjController@UpdatekirimSpj')->name('spj.updatepengiriman');
    Route::post('/spj/hapuspengiriman', 'SpjController@HapuskirimSpj')->name('spj.hapuspengiriman');
    Route::post('/spj/penerimaan', 'SpjController@terimaSpj')->name('spj.penerimaan');
    Route::post('/spj/updatepenerimaan', 'SpjController@UpdateterimaSpj')->name('spj.updatepenerimaan');
    Route::post('/spj/hapuspenerimaan', 'SpjController@HapusterimaSpj')->name('spj.hapuspenerimaan');
    Route::get('/peringkat/bulanan', 'PeringkatController@bulanan')->name('peringkat.bulanan');
    Route::get('/peringkat/tahunan', 'PeringkatController@tahunan')->name('peringkat.tahunan');
    Route::get('/peringkat/rincian', 'PeringkatController@rincian')->name('peringkat.rincian');
    Route::get('/peringkat/export/{unitkerja}/{bulan}/{tahun}', 'PeringkatController@ExportExcel')->name('peringkat.export');
    Route::get('/peringkat/ckp', 'PeringkatController@Ckp')->name('peringkat.ckp');
    Route::get('/peringkat/rekapnilai', 'PeringkatController@RekapNilaiBulanan')->name('peringkat.rekapnilai');
    Route::get('/rekapnilai/export/{unitkode}/{tahun}', 'PeringkatController@RekapNilaiBulananExport')->name('rekapnilai.export');
    Route::get('/ckp/export/{tahun}', 'PeringkatController@ExportCkpExcel')->name('ckp.export');
    Route::get('/laporan/bulanan', 'LaporanController@bulanan')->name('laporan.bulanan');
    Route::get('/laporan/bulanan/export/{unitkerja}/{bulan}/{tahun}', 'LaporanController@bulananExport')->name('bulanan.export');
    Route::get('/laporan/tahunan', 'LaporanController@tahunan')->name('laporan.tahunan');
    Route::get('/laporan/tahunan/export/{unitkerja}/{tahun}', 'LaporanController@tahunanExport')->name('tahunan.export');
    Route::get('/laporan/kabkota/bulanan', 'LaporanController@KabkotaBulanan')->name('laporan.kabkotabulan');
    Route::get('/laporan/kabkota/tahunan', 'LaporanController@KabkotaTahunan')->name('laporan.kabkotatahun');
    Route::get('/db/list', 'DataController@index')->name('db.index');
    Route::get('/db/sinkron', 'DataController@Sinkron')->name('db.sinkron');
    Route::get('/db/kosongkan', 'DataController@Kosongkan')->name('db.kosongkan');
    //operator
    Route::get('/operator/list', 'OperatorController@list')->name('operator.list');
    Route::get('/operator/cari/{id}', 'OperatorController@cariOperator')->name('operator.cari');
    Route::get('/operator/cek/{username}', 'OperatorController@cekOperator')->name('operator.cek');
    Route::post('/operator/hapus', 'OperatorController@OperatorHapus')->name('operator.hapus');
    Route::post('/operator/flag', 'OperatorController@FlagOperator')->name('operator.flag');
    Route::post('/operator/flagliatckp', 'OperatorController@FlagLiatCkp')->name('operator.flagliatckp');
    Route::post('/operator/supersimpan', 'OperatorController@SuperSimpan')->name('operator.supersimpan');
    Route::post('/operator/superupdate', 'OperatorController@SuperUpdate')->name('operator.superupdate');
    Route::post('/operator/adminprovsimpan', 'OperatorController@AdminProvSimpan')->name('operator.adminprovsimpan');
    Route::post('/operator/adminprovupdate', 'OperatorController@AdminProvUpdate')->name('operator.adminprovupdate');
    Route::post('/operator/adminkabsimpan', 'OperatorController@AdminKabSimpan')->name('operator.adminkabsimpan');
    Route::post('/operator/adminkabupdate', 'OperatorController@AdminKabUpdate')->name('operator.adminkabupdate');
    Route::post('/operator/gantipasswd', 'OperatorController@GantiPassword')->name('operator.gantipasswd');
    Route::post('/operator/updatehakakses', 'OperatorController@UpdateHakAkses')->name('operator.updatehakakses');
    Route::get('/operator/perbaikirole', 'OperatorController@PerbaikiRole')->name('operator.perbaikirole');
    Route::get('/myprofile', 'MyProfileController@MyProfile')->name('my.profile');
    Route::post('/profile/update', 'MyProfileController@UpdateProfile')->name('profile.update');
    Route::post('/profile/generatetoken', 'MyProfileController@GenerateToken')->name('profile.newtoken');
    Route::post('/profile/gantipasswd', 'MyProfileController@GantiPassword')->name('profile.gantipassword');
    //notifikasi
    Route::get('/notifikasi/list', 'NotifikasiController@list')->name('notif.list');
    Route::get('/notifikasi/get/{id}', 'NotifikasiController@getNotif')->name('notif.get');
    Route::post('/notifikasi/hapus', 'NotifikasiController@HapusNotif')->name('notif.hapus');
    //bot telegram
    Route::get('/bot/telegram', 'NotifikasiController@BotListTelegram')->name('bot.telegram');
    Route::get('/bot/telegram/status', 'NotifikasiController@BotStatus')->name('bot.status');
    Route::get('/bot/telegram/setwebhook', 'NotifikasiController@setWebHook')->name('bot.setwebhook');
    Route::get('/bot/telegram/offwebhook', 'NotifikasiController@OffWebHook')->name('bot.offwebhook');
    Route::get('/bot/telegram/getme', 'NotifikasiController@GetMeBot')->name('bot.getme');
    //aktivitas
    Route::get('/aktivitas/list', 'LogAktivitasController@ListLog')->name('aktivitas.list');
    //point
    Route::get('/poin/list', 'KegiatanController@ListPoin')->name('poin.list');
    //master kegiatan
    Route::get('/master/kegiatan', 'KegiatanController@MasterKegiatan')->name('master.kegiatan');
    //unitkerja
    Route::get('/unitkerja/provinsi', 'UnitkerjaController@ListProvinsi')->name('unitkerja.prov');
    Route::get('/unitkerja/unitprovpagelist', 'UnitkerjaController@UnitProvPagelist')->name('unitprov.pagelist');
    Route::post('/unitkerja/prov/simpan', 'UnitkerjaController@SimpanUnitProv')->name('unitprov.simpan');
    Route::post('/unitkerja/prov/ubahflag', 'UnitkerjaController@UbahFlagUnitProv')->name('unitprov.ubahflag');
    Route::post('/unitkerja/prov/hapus', 'UnitkerjaController@HapusUnitProv')->name('unitprov.hapus');
    Route::post('/unitkerja/prov/eseloniv', 'UnitkerjaController@EselonIVNonAktif')->name('unitprov.eseloniv');
    Route::post('/unitkerja/prov/update', 'UnitkerjaController@UpdateUnitProv')->name('unitprov.updatedata');
    Route::get('/unitkerja/kabkota', 'UnitkerjaController@ListKabkota')->name('unitkerja.kabkota');
    Route::get('/unitkerja/kabkotapagelist', 'UnitkerjaController@UnitKabkotaPagelist')->name('unitkabkota.pagelist');
    Route::get('/unitkerja/cari/{id}', 'UnitkerjaController@CariUnitkerja')->name('unitkerja.cari');
});
Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout')->name('logout');
