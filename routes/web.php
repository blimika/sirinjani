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
Route::get('/kegiatan/bidang', 'KegiatanController@bidang')->name('kegiatan.bidang');
Route::get('/kegiatan/cari/{kegId}', 'KegiatanController@cariKegiatan')->name('kegiatan.cari');
Route::get('/realisasi/cari/{kegrid}', 'KegiatanController@CariRealisasi')->name('realisasi.cari');
Route::get('/spj/realisasi/cari/{spjrid}', 'SpjController@CariSpj')->name('spjrealisasi.cari');
Route::post(env('TELEGRAM_HASH_URL') . '/webhook', 'NotifikasiController@WebHook')->name('webhook');

Route::group(['middleware' => ['auth']], function () {
    Route::get('/kegiatan/tambah', 'KegiatanController@tambah')->name('kegiatan.tambah');
    Route::get('/kegiatan/detil/{kegId}', 'KegiatanController@DetilKegiatan')->name('kegiatan.detil');
    Route::post('/kegiatan/simpan', 'KegiatanController@simpan')->name('kegiatan.simpan');
    Route::post('/kegiatan/update', 'KegiatanController@UpdateKegiatan')->name('kegiatan.update');
    Route::post('/kegiatan/hapus', 'KegiatanController@hapusKegiatan')->name('kegiatan.hapus');
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
    /*
    Route::post('/pegawai/sync', 'PegawaiController@syncData')->name('pegawai.sync');
    Route::post('/pegawai/flag', 'PegawaiController@FlagPegawai')->name('pegawai.flag');
    Route::post('/pegawai/hapus', 'PegawaiController@HapusPegawai')->name('pegawai.hapus');
    Route::post('/pegawai/simpan', 'PegawaiController@SimpanPegawai')->name('pegawai.simpan');
    Route::post('/pegawai/updatepegawai', 'PegawaiController@UpdatePegawai')->name('pegawai.updatenet');
    Route::post('/pegawai/updatelokal', 'PegawaiController@UpdateLokal')->name('pegawai.updatelokal');
    Route::get('/unitkerja/{jenis}/{eselon}', 'UnitkerjaController@CariUnitkerja')->name('cari.unitkerja');
    Route::get('/pegawai/list', 'PegawaiController@index')->name('pegawai.list');
    Route::get('/pegawai/{pegID}', 'PegawaiController@CariPegawai')->name('cari.pegawai');
    Route::post('/pegawai/gantipasswd', 'PegawaiController@GantiPassword')->name('pegawai.gantipasswd');
    */
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
    Route::get('/db/list', 'DataController@index')->name('db.index');
    Route::get('/db/sinkron', 'DataController@Sinkron')->name('db.sinkron');
    Route::get('/db/kosongkan', 'DataController@Kosongkan')->name('db.kosongkan');
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
});
Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout')->name('logout');
