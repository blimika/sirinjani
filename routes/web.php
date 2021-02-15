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
Route::get('/', function () {
    return view('depan');
});


Route::get('/kegiatan/list', 'KegiatanController@index')->name('kegiatan.list');
Route::get('/kegiatan/bidang', 'KegiatanController@bidang')->name('kegiatan.bidang');
Route::get('/kegiatan/cari/{kegId}', 'KegiatanController@cariKegiatan')->name('kegiatan.cari');
Route::get('/realisasi/cari/{kegrid}', 'KegiatanController@CariRealisasi')->name('realisasi.cari');
Route::get('/spj/realisasi/cari/{spjrid}', 'SpjController@CariSpj')->name('spjrealisasi.cari');

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
    Route::post('/pegawai/sync', 'PegawaiController@syncData')->name('pegawai.sync');
    Route::post('/pegawai/flag', 'PegawaiController@FlagPegawai')->name('pegawai.flag');
    Route::post('/pegawai/hapus', 'PegawaiController@HapusPegawai')->name('pegawai.hapus');
    Route::post('/pegawai/simpan', 'PegawaiController@SimpanPegawai')->name('pegawai.simpan');
    Route::post('/pegawai/updatepegawai', 'PegawaiController@UpdatePegawai')->name('pegawai.updatenet');
    Route::post('/pegawai/updatelokal', 'PegawaiController@UpdateLokal')->name('pegawai.updatelokal');
    Route::get('/unitkerja/{jenis}/{eselon}', 'UnitkerjaController@CariUnitkerja')->name('cari.unitkerja');
    Route::post('/cek/community', 'PegawaiController@cekCommunity')->name('cek.community');
    Route::get('/pegawai/list', 'PegawaiController@index')->name('pegawai.list');
    Route::get('/pegawai/{pegID}', 'PegawaiController@CariPegawai')->name('cari.pegawai');
    Route::post('/pegawai/gantipasswd', 'PegawaiController@GantiPassword')->name('pegawai.gantipasswd');
    Route::get('/peringkat/bulanan', 'PeringkatController@bulanan')->name('peringkat.bulanan');
    Route::get('/peringkat/tahunan', 'PeringkatController@tahunan')->name('peringkat.tahunan');
    Route::get('/peringkat/rincian', 'PeringkatController@rincian')->name('peringkat.rincian');
    Route::get('/peringkat/export/{unitkerja}/{bulan}/{tahun}', 'PeringkatController@ExportExcel')->name('peringkat.export');
    Route::get('/peringkat/ckp', 'PeringkatController@Ckp')->name('peringkat.ckp');
    Route::get('/laporan/bulanan', 'LaporanController@bulanan')->name('laporan.bulanan');
    Route::get('/laporan/tahunan', 'LaporanController@tahunan')->name('laporan.tahunan');
    Route::get('/db/list', 'DataController@index')->name('db.index');
    Route::get('/db/sinkron', 'DataController@Sinkron')->name('db.sinkron');
    Route::get('/db/kosongkan', 'DataController@Kosongkan')->name('db.kosongkan');
});
Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout')->name('logout');
