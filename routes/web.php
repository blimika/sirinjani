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
Route::get('/kegiatan/tambah', 'KegiatanController@tambah')->name('kegiatan.tambah');
Route::post('/kegiatan/simpan', 'KegiatanController@simpan')->name('kegiatan.simpan');
Route::get('/pegawai/list', 'PegawaiController@index')->name('pegawai.list');
Route::post('/pegawai/sync', 'PegawaiController@syncData')->name('pegawai.sync');
Route::post('/cek/community', 'PegawaiController@cekCommunity')->name('cek.community');