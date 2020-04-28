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

Route::get('/pegawai/list', 'PegawaiController@index')->name('pegawai.list');

Route::group(['middleware' => ['auth']], function () {
    Route::get('/kegiatan/tambah', 'KegiatanController@tambah')->name('kegiatan.tambah');
    Route::post('/kegiatan/simpan', 'KegiatanController@simpan')->name('kegiatan.simpan'); 
    Route::post('/pegawai/sync', 'PegawaiController@syncData')->name('pegawai.sync');
    Route::get('/pegawai/{nipbps}', 'PegawaiController@CariPegawai')->name('cari.pegawai');
    Route::post('/cek/community', 'PegawaiController@cekCommunity')->name('cek.community');

});
Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout')->name('logout');