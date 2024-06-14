<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Helpers\CommunityBPS;
use Carbon\Carbon;
use Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\KodeWilayah;
use App\KodeLevel;
use App\UnitKerja;
use Excel;
use App\Kegiatan;
use App\KegDetil;
use App\KegJenis;
use App\KegRealisasi;
use App\KegTarget;
use App\SpjTarget;
use App\Helpers\Generate;
use App\Helpers\Tanggal;
use App\SpjRealisasi;
use App\Notifikasi;
use App\JenisNotifikasi;
use App\LogAktivitas;
use Illuminate\Support\Facades\Mail;
use App\Mail\MailKegiatan;
use App\Mail\MailPenerimaan;
use App\Mail\MailPengiriman;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Api;
use App\FlagKegiatan;

class KegiatanController extends Controller
{
    //
    /*
    protected $telegram;
    protected $chat_id;
    protected $message_id;
    protected $msg_id;
    protected $chan_notif_id;
    protected $chan_log_id;
    public function __construct()
    {
        $this->telegram = new Api(env('TELEGRAM_BOT_TOKEN'));
        $this->chan_notif_id = env('TELEGRAM_CHAN_NOTIF');
        $this->chan_log_id = env('TELEGRAM_CHAN_LOG');
    }
    */
    public function index()
    {
        $data_bulan = array(
            1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'
        );
        $data_tahun = DB::table('m_keg')
                    ->selectRaw('year(keg_start) as tahun')
                    ->groupBy('tahun')
                    ->orderBy('tahun','asc')
                      ->get();
        //dd($data_tahun);
        if (request('tahun')==NULL)
        {
            $tahun_filter=date('Y');
        }
        elseif (request('tahun')==0)
        {
            $tahun_filter=date('Y');
        }
        else
        {
            $tahun_filter = request('tahun');
        }
        if (request('bulan')==NULL)
        {
            $bulan_filter= (int) date('m');
        }
        elseif (request('bulan')==0)
        {
            $bulan_filter = NULL;
        }
        else
        {
            $bulan_filter = request('bulan');
        }
        //dd($bulan_filter);
        $dataUnit = UnitKerja::where([['unit_jenis','=','1'],['unit_eselon','=','3']])->get();
        //dd($dataUnit);
        $dataKegiatan = Kegiatan::when(request('unit'),function ($query){
                            return $query->where('keg_timkerja',request('unit'));
                        })
                        ->when($bulan_filter,function ($query) use ($bulan_filter){
                            return $query->whereMonth('keg_start','=',$bulan_filter);
                        })
                        ->orderBy('m_keg.created_at','desc')->whereYear('keg_start','=',$tahun_filter)->get();
        //dd($dataKegiatan);
        return view('kegiatan.index',['dataKeg'=>$dataKegiatan,'dataUnitkerja'=>$dataUnit,'bulan'=>$bulan_filter,'tahun'=>$tahun_filter,'dataBulan'=>$data_bulan,'dataTahun'=>$data_tahun,'unit'=>request('unit')]);
    }
    public function SyncTimKerja(Request $request)
    {
        $arr = array(
            'status'=>false,
            'hasil'=>'Data kegiatan tidak tersedia'
        );
        if (Auth::User())
        {
            //hanya superadmin
            if (Auth::User()->role > 5)
            {
                $data = Kegiatan::get();
                if ($data)
                {
                    $jumlah = 0;
                    foreach ($data as $item) {
                        if ($item->keg_timkerja == 0)
                        {
                            if ($item->Unitkerja->unit_eselon == 4)
                            {
                                $kode_timkerja = $item->Unitkerja->unit_parent;
                            }
                            else
                            {
                                $kode_timkerja = $item->keg_unitkerja;
                            }
                            //update data
                            $update_data = Kegiatan::where('keg_id',$item->keg_id)->first();
                            $update_data->keg_timkerja = $kode_timkerja;
                            $update_data->update();
                            $jumlah++;
                        }
                    }
                    $arr = array(
                        'status'=>true,
                        'hasil'=>'Ada '.$jumlah.' kegiatan yang telah disinkronisasi'
                    );
                }
            }
            else
            {
                $arr = array(
                    'status'=>false,
                    'hasil'=>'Anda tidak memiliki akses untuk fitur ini'
                );
            }
        }
        return Response()->json($arr);

    }
    public function NewList()
    {
        $data_bulan = array(
            1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'
        );
        $data_tahun = DB::table('m_keg')
                    ->selectRaw('year(keg_start) as tahun')
                    ->groupBy('tahun')
                    ->orderBy('tahun','asc')
                      ->get();
        //dd($data_tahun);
        if (request('tahun')==NULL)
        {
            $tahun_filter=date('Y');
        }
        elseif (request('tahun')==0)
        {
            $tahun_filter=date('Y');
        }
        else
        {
            $tahun_filter = request('tahun');
        }
        if (request('bulan')==NULL or request('bulan')==0)
        {
            $bulan_filter = 0;
        }
        else
        {
            $bulan_filter = request('bulan');
        }
        if (request('unit')==NULL)
        {
            $unit_filter='';
        }
        else
        {
            $unit_filter=request('unit');
        }
        $dataUnit = UnitKerja::where([['unit_jenis','=','1'],['unit_eselon','=','3'],['unit_flag','1']])->get();
        return view('kegiatan.newlist',[
            'dataTahun'=>$data_tahun,
            'dataBulan'=>$data_bulan,
            'bulan_filter'=>$bulan_filter,
            'tahun_filter'=>$tahun_filter,
            'dataUnit'=>$dataUnit,
            'unit_filter'=>$unit_filter,
        ]);
    }
    public function PageList(Request $request)
    {
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // Rows display per page

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value

        // Total records
        //jika operator provinsi/superadmin semua record
        //selain itu tampilkan yg flag = 1 (publik)
        //operator
        $flag_publik = false;
        if (Auth::User())
        {
            if (Auth::User()->role < 4)
            {
                $flag_publik = true;
            }
            else
            {
                $flag_publik = false;
            }
        }
        $totalRecords = Kegiatan::count();
        $totalRecordswithFilter =  DB::table('m_keg')
        ->when($searchValue, function ($q) use ($searchValue) {
            return $q->where('keg_nama', 'like', '%' . $searchValue . '%')
                     ->orWhere('keg_unitkerja', 'like', '%' . $searchValue . '%')
                     ->orWhere('keg_start', 'like', '%' . $searchValue . '%')
                     ->orWhere('keg_end', 'like', '%' . $searchValue . '%')
                     ->orWhere('keg_target_satuan', 'like', '%' . $searchValue . '%');
        })
        ->count();

        $records = Kegiatan::when($searchValue, function ($q) use ($searchValue) {
            return $q->where('keg_nama', 'like', '%' . $searchValue . '%')
                     ->orWhere('keg_unitkerja', 'like', '%' . $searchValue . '%')
                     ->orWhere('keg_start', 'like', '%' . $searchValue . '%')
                     ->orWhere('keg_end', 'like', '%' . $searchValue . '%')
                     ->orWhere('keg_target_satuan', 'like', '%' . $searchValue . '%');
        })
        ->skip($start)
        ->take($rowperpage)
        ->orderBy($columnName, $columnSortOrder)
        ->get();
        $data_arr = array();
        $aksi='';
        foreach ($records as $record) {
            $id = $record->keg_id;
            $keg_nama = ' <div class="text-info">
                        <a href="'.route('kegiatan.detil',$record->keg_id).'" class="text-info">'.$record->keg_nama.'</a>
                        </div>
                        <small class="badge badge-success">'.$record->JenisKeg->jkeg_nama.'</small>';
            $keg_timkerja = $record->TimKerja->unit_nama;
            $keg_start = array(
                'teks'=>Tanggal::Panjang($record->keg_start),
                'sort'=>$record->keg_start
            );
            $keg_end = array(
                'teks'=>Tanggal::Panjang($record->keg_end),
                'sort'=>$record->keg_end
            );
            $keg_total_target = $record->keg_total_target;
            $keg_satuan = $record->keg_target_satuan;
            $keg_realisasi = $record->RealisasiKirim->count();
            $keg_spj = $record->keg_spj;
            if (Auth::User())
            {
                if (Auth::user()->role > 3)
                {
                    $aksi = '
                    <div class="btn-group">
                    <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="ti-settings"></i>
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="'.route('kegiatan.edit',$record->keg_id).'" target="_blank" data-id="' . $record->keg_id . '">Edit</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item hapusunitkerja" href="#" data-id="' . $record->keg_id . '" data-kode="' . $record->keg_timkerja . '"  data-kegnama="' . $record->keg_nama . '">Hapus Kegiatan</a>

                    </div>
                </div>
                ';
                }
                else
                {
                    $aksi='';
                }
            }

            /*
             { data: 'id' },
                    { data: 'keg_nama' },
                    { data: 'keg_unitkerja' },
                    { data: 'keg_start' },
                    { data: 'keg_end' },
                    { data: 'keg_total_target' },
                    { data: 'keg_realisasi' },
                    { data: 'keg_satuan' },
                    { data: 'keg_spj' },
                    { data: 'keg_flag' },
                    { data: 'aksi', orderable: false },
                    */
            $data_arr[] = array(
                "keg_id" => $id,
                "keg_nama" => $keg_nama,
                "keg_timkerja" => $keg_timkerja,
                "keg_start" => $keg_start,
                "keg_end" => $keg_end,
                "keg_total_target" => $keg_total_target,
                "keg_realisasi" => $keg_realisasi,
                "keg_target_satuan" => $keg_satuan,
                "keg_spj"=>$keg_spj,
                "aksi" => $aksi
            );
        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr
        );

        echo json_encode($response);
        exit;
    }
    public function bidang()
    {
        $data_bulan = array(
            1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'
        );
        $data_tahun = DB::table('m_keg')
                    ->selectRaw('year(keg_start) as tahun')
                    ->groupBy('tahun')
                    ->orderBy('tahun','asc')
                      ->get();
        //dd($data_tahun);
        if (request('tahun')==NULL)
        {
            $tahun_filter=date('Y');
        }
        else
        {
            $tahun_filter = request('tahun');
        }
        $dataUnit = UnitKerja::where([['unit_jenis','=','1'],['unit_eselon','=','3']])->get();
        $dataKegiatan = Kegiatan::leftJoin('t_unitkerja','m_keg.keg_unitkerja','=','t_unitkerja.unit_kode')
                        ->when(request('unit'),function ($query){
                            return $query->where('t_unitkerja.unit_parent','=',request('unit'));
                        })
                        ->when(request('bulan'),function ($query){
                            return $query->whereMonth('keg_start','=',request('bulan'));
                        })
                        ->whereYear('keg_start','=',$tahun_filter)
                        ->orderBy('m_keg.keg_unitkerja','asc')
                        ->orderBy('m_keg.keg_start','asc')
                        ->get();
        //dd($dataUnit);
        return view('kegiatan.bidang',['dataKeg'=>$dataKegiatan,'dataUnitkerja'=>$dataUnit,'bulan'=>request('bulan'),'tahun'=>$tahun_filter,'dataBulan'=>$data_bulan,'dataTahun'=>$data_tahun,'unit'=>request('unit')]);
    }
    public function tambah()
    {
        if (Auth::user()->role > 3) //role 4,5,9 bisa
        {
            //selain operator provinsi dan admin
            if (Auth::user()->role == 4)
            {
                //operator provinsi list unitProv hanya di TIM nya
                $unitProv = UnitKerja::where([['unit_jenis','1'],['unit_eselon','3'],['unit_kode',Auth::user()->kodeunit],['unit_flag',1]])->get();
                //dd($unitProv);
            }
            else
            {
                //list semua unitProv di provinsi
                $unitProv = UnitKerja::where([['unit_jenis','1'],['unit_eselon','3'],['unit_flag',1]])->get();
            }
            $unitTarget = UnitKerja::where([['unit_jenis','=','2'],['unit_eselon','=','3']])->get();
            $kegJenis = KegJenis::get();
            return view('kegiatan.tambah',[
                'unitTarget'=>$unitTarget,
                'unitProv'=>$unitProv,
                'kegJenis'=>$kegJenis
            ]);

        }
        else
        {
            //operator kabkota dan pemantau tidak bisa mengakses ini
            //beri info warning
            return view('kegiatan.warning',['keg_id'=>0]);
        }
    }
    public function copyKegiatan($kegId)
    {
        if (Auth::user()->role > 3)
        {
            $dataKegiatan = Kegiatan::where('keg_id',$kegId)->first();
            if (Auth::user()->role == 4)
            {
                //operator provinsi list unitProv hanya di bidangnya
                if ($dataKegiatan->keg_timkerja != Auth::user()->kodeunit)
                {
                        //peringatan tidak bisa mengedit kegiatan ini
                        return view('kegiatan.warning',['keg_id'=>$kegId]);
                }
                $unitProv = UnitKerja::where([['unit_jenis',1],['unit_eselon',3],['unit_kode',Auth::user()->kodeunit]])->get();
            }
            else
            {
                //list semua unitProv eselon 4 di provinsi
                $unitProv = UnitKerja::where([['unit_jenis','=','1'],['unit_eselon','=','3'],['unit_flag',1]])->get();
            }
            //dd($dataKegiatan);
            //$unitTarget = UnitKerja::where([['unit_jenis','=','2'],['unit_eselon','=','3']])->get();
            $kegJenis = KegJenis::get();
            return view('kegiatan.copy',[
                'unitProv'=>$unitProv,
                'kegJenis'=>$kegJenis,
                'dataKegiatan'=>$dataKegiatan
            ]);
        }
        else
        {
            //operator kabkota dan pemantau tidak bisa mengakses ini
            //beri info warning
            return view('kegiatan.warning',['keg_id'=>$kegId]);
        }
    }
    public function simpan(Request $request)
    {
        //dd($request->all());
        //cek nama kegiatan di bidang tsb sudah pernah di input dngn judul yg sama
        $count = Kegiatan::where([
            ['keg_nama','=',trim($request->keg_nama)],
            ['keg_timkerja','=',$request->keg_unitkerja]
            ])->count();
        if ($count > 0)
        {
            //sudah pernah ada kegiatan
            $pesan_error='Nama kegiatan ('.trim($request->keg_nama).') sudah tersedia';
            $pesan_warna='danger';
        }
        else
        {
            $data = new Kegiatan();
            $data->keg_nama = trim($request->keg_nama);
            $data->keg_unitkerja = $request->keg_unitkerja;
            $data->keg_timkerja = $request->keg_unitkerja;
            $data->keg_jenis = $request->keg_jenis;
            $data->keg_start = $request->keg_start;
            $data->keg_end = $request->keg_end;
            $data->keg_target_satuan = $request->keg_satuan;
            $data->keg_total_target = $request->keg_total_target;
            $data->keg_spj = $request->keg_spj;
            $data->keg_dibuat_oleh = Auth::user()->username;
            $data->keg_diupdate_oleh = Auth::user()->username;
            //$data->keg_flag = $request->keg_flag;
            $data->save();

            $keg_id = $data->keg_id;
            $unit_nama = $data->TimKerja->unit_nama;
            //target masing2 kabkota
            //pesan notif ke channel notif
            /*
            $objEmail->keg_nama = $data_keg->keg_nama;
            $objEmail->keg_jenis = $data_keg->JenisKeg->jkeg_nama;
            $objEmail->keg_target = $target;
            $objEmail->keg_satuan = $data_keg->keg_target_satuan;
            $objEmail->keg_tgl_mulai = Tanggal::HariPanjang($data_keg->keg_start);
            $objEmail->keg_tgl_selesai = Tanggal::HariPanjang($data_keg->keg_end);
            $objEmail->keg_sm = $data_keg->Unitkerja->unit_nama;
            $objEmail->keg_spj = $keg_spj;
            $objEmail->keg_total_target = $data_keg->keg_total_target;
            $objEmail->keg_tgl_dibuat = Tanggal::LengkapHariPanjang($data_keg->created_at);
            $objEmail->keg_operator = $data_keg->keg_dibuat_oleh
            */
            if ($data->keg_spj == 1)
            {
                $kegspj = 'Ada';
            }
            else
            {
                $kegspj = 'Tidak ada';
            }
            /*
            $message = '<b>♻️♻️♻️ ADA KEGIATAN BARU ♻️♻️♻️</b>' .chr(10);
            $message .= '-----------------------'.chr(10);
            $message .= '🟢 ID : <b>#'.$keg_id.'</b>'.chr(10);
            $message .= '🟢 NAMA KEGIATAN : <b>'. trim($request->keg_nama).'</b>' .chr(10);
            $message .= '🟢 SM : <b>'. $data->Unitkerja->unit_nama.'</b>' .chr(10);
            $message .= '🟢 Jenis : <b>'. $data->JenisKeg->jkeg_nama.'</b>' .chr(10);
            $message .= '🟢 Total target : <b>'. $request->keg_total_target .' '.$data->keg_target_satuan.'</b>' .chr(10);
            $message .= '🟢 Tanggal mulai : <b>'. Tanggal::HariPanjang($data->keg_start).'</b>' .chr(10);
            $message .= '🟢 Tanggal selesai : <b>'. Tanggal::HariPanjang($data->keg_end).'</b>' .chr(10);
            $message .= '🟢 Ada SPJ : <b>'. $kegspj.'</b>' .chr(10);
            $message .= '🟢 Tanggal dibuat : <b>'. Tanggal::LengkapHariPanjang($data->created_at).'</b>' .chr(10);
            $message .= '🟢 Operator : <b>'. Auth::user()->username.'</b>' .chr(10);
            $message .= '-----------------------'.chr(10);
            $message .= 'Target masing-masing kabkota' .chr(10);
            $message .= '-----------------------'.chr(10);
            */
            foreach ($request->keg_kabkota as $key => $v)
            {
                if ($v > 0)
                {
                    //ada isian targetnya
                    $target = $v;
                }
                else
                {
                    //target tetep di set nol
                    $target = 0;
                }
                $dataTarget = new KegTarget();
                $dataTarget->keg_id = $keg_id;
                $dataTarget->keg_t_unitkerja = $key;
                $dataTarget->keg_t_target = $target;
                $dataTarget->keg_t_point_waktu = 0;
                $dataTarget->keg_t_point_jumlah = 0;
                $dataTarget->keg_t_point = 0;
                $dataTarget->keg_t_dibuat_oleh = Auth::user()->username;
                $dataTarget->keg_t_diupdate_oleh = Auth::user()->username;
                $dataTarget->save();

                //jika target lebih besar 0 (nol)
                //kirim notif
                if ($target > 0)
                {
                    //buat pesan telegram per kabkota
                    //$message .= '🔸 ['.$key.'] '.$dataTarget->Unitkerja->unit_nama.': <b>'. $target.' '.$request->keg_satuan.'</b>' .chr(10);
                    //batasannya
                    //jenisnotif kegiatan = 3
                    //cari dulu operator kabkotanya
                    $count_op = User::where([['kodeunit',$key],['level','>','1']])->count();
                    if ($count_op > 0)
                    {
                        //ada operator kabkotanya
                        //level > 1 (pemantau)
                        $nofif_isi = 'Kegiatan Baru ['.$keg_id.'] <i>'.trim($request->keg_nama).'</i> mulai tanggal '.Tanggal::HariPanjang($request->keg_start).' s.d. '.Tanggal::HariPanjang($request->keg_end).' dengan target '.$target.' '.$request->keg_satuan;
                        $data_notif = User::where([['kodeunit',$key],['level','>','1']])->get();
                        foreach ($data_notif as $item)
                        {
                            $notif = new Notifikasi();
                            $notif->keg_id = $keg_id;
                            $notif->notif_dari = Auth::user()->username;
                            $notif->notif_untuk = $item->username;
                            $notif->notif_isi = $nofif_isi;
                            $notif->notif_jenis = '3';
                            $notif->save();
                            //testing notif ke email
                            if (env('APP_MAIL_MODE') == true)
                            {
                                $data_keg = Kegiatan::where('keg_id',$keg_id)->first();
                                if ($data_keg->keg_spj == 1)
                                {
                                    $keg_spj = 'Ada';
                                }
                                else
                                {
                                    $keg_spj = 'Tidak';
                                }
                                $objEmail = new \stdClass();
                                $objEmail->keg_id = $keg_id;
                                $objEmail->keg_nama = $data_keg->keg_nama;
                                $objEmail->keg_jenis = $data_keg->JenisKeg->jkeg_nama;
                                $objEmail->keg_target = $target;
                                $objEmail->keg_satuan = $data_keg->keg_target_satuan;
                                $objEmail->keg_tgl_mulai = Tanggal::HariPanjang($data_keg->keg_start);
                                $objEmail->keg_tgl_selesai = Tanggal::HariPanjang($data_keg->keg_end);
                                $objEmail->keg_sm = $data_keg->TimKerja->unit_nama;
                                $objEmail->keg_spj = $keg_spj;
                                $objEmail->keg_total_target = $data_keg->keg_total_target;
                                $objEmail->keg_tgl_dibuat = Tanggal::LengkapHariPanjang($data_keg->created_at);
                                $objEmail->keg_operator = $data_keg->keg_dibuat_oleh;
                                //coba email
                                $dataemail = $item->email;
                                //$dataemail = "pdyatmika@gmail.com";
                                Mail::to($dataemail)->send(new MailKegiatan($objEmail));

                            }
                            //batas testing
                        }
                    }
                }
            }
            if ($request->keg_spj == 1)
            {
                //ada permintaan spj
                //data spj di insert
                //spj_kabkota

                foreach ($request->spj_kabkota as $key => $v)
                {
                    if ($v > 0)
                    {
                        //ada isian targetnya
                        $target = $v;
                    }
                    else
                    {
                        //target tetep di set nol
                        $target = 0;
                    }
                    $dataSpj = new SpjTarget();
                    $dataSpj->keg_id = $keg_id;
                    $dataSpj->spj_t_unitkerja = $key;
                    $dataSpj->spj_t_target = $target;
                    $dataSpj->spj_t_point_waktu = 0;
                    $dataSpj->spj_t_point_jumlah = 0;
                    $dataSpj->spj_t_point = 0;
                    $dataSpj->spj_t_dibuat_oleh = Auth::user()->username;
                    $dataSpj->spj_t_diupdate_oleh = Auth::user()->username;
                    $dataSpj->save();
                }
            }
            //kirim pesan ke channel notif
            /*
            $message .= '-----------------------'.chr(10);
            $message .= '🟢 Link : <a href="'.route('kegiatan.detil',$keg_id).'">Kegiatan Detil</a>' .chr(10);
            if (env('APP_TELEGRAM_MODE') == true)
            {
                $response = Telegram::sendMessage([
                    'chat_id' => $this->chan_notif_id,
                    'text' => $message,
                    'parse_mode'=> 'HTML'
                ]);
            }
            //batasannya
            */
            $pesan_error='Kegiatan ('.trim($request->keg_nama).') sudah di simpan';
            $pesan_warna='success';
            if (env('APP_AKTIVITAS_MODE') == true)
            {
                //catat aktivitas tambah kegiatan operator prov
                $data_log = new LogAktivitas();
                $data_log->log_username = Auth::user()->username;
                $data_log->log_ip = Generate::GetIpAddress();
                $data_log->log_jenis = 3;
                $data_log->log_useragent = Generate::GetUserAgent();
                $data_log->log_pesan = 'berhasil menambah kegiatan ['.$keg_id.'] '. trim($request->keg_nama) .' dengan SM ['. $request->keg_unitkerja .'] '. $unit_nama;
                $data_log->save();
                //batas catat aktivitas tambah kegiatan
                //kirim ke sistem channel
                //kirim ke channel log
                /*
                $message = '### KEGIATAN BARU  ###' .chr(10);
                $message .= '-----------------------'.chr(10);
                $message .= '🟢 Username : '.Auth::user()->username .chr(10);
                $message .= '🟢 IP Address : '. Generate::GetIpAddress() .chr(10);
                $message .= '🟢 Useragent : '. Generate::GetUserAgent() .chr(10);
                $message .= '🟢 Pesan : berhasil menambah kegiatan ['.$keg_id.'] '. trim($request->keg_nama) .' dengan SM ['. $request->keg_unitkerja .'] '. $unit_nama .chr(10);
                //$message .= '🟢 Link : <a href="https://sirinjani.bpsntb.id/kegiatan/detil/'.$keg_id.'">Kegiatan Detil</a>'. chr(10);
                $message .= '🟢 Link : <a href="'.route('kegiatan.detil',$keg_id).'">Kegiatan Detil</a>' .chr(10);
                $message .= '-----------------------'.chr(10);
                //dd($message);
                //kirim pesan ke channel log
                if (env('APP_TELEGRAM_MODE') == true)
                {
                    $response = Telegram::sendMessage([
                        'chat_id' => $this->chan_log_id,
                        'text' => $message,
                        'parse_mode'=> 'HTML'
                    ]);
                }
                //batasannya
                */
            }
        }
        Session::flash('message', $pesan_error);
        Session::flash('message_type', $pesan_warna);
        return redirect()->route('kegiatan.list');
    }
    public function UpdateKegiatan(Request $request)
    {
        //dd($request->all());
        $count = Kegiatan::where('keg_id',$request->keg_id)->count();
        if ($count > 0)
        {
            $data = Kegiatan::where('keg_id',$request->keg_id)->first();
            $data->keg_nama = trim($request->keg_nama);
            $data->keg_unitkerja = $request->keg_unitkerja;
            $data->keg_timkerja = $request->keg_unitkerja;
            $data->keg_jenis = $request->keg_jenis;
            $data->keg_start = $request->keg_start;
            $data->keg_end = $request->keg_end;
            $data->keg_target_satuan = $request->keg_satuan;
            $data->keg_total_target = $request->keg_total_target;
            $data->keg_spj = $request->keg_spj;
            //$data->keg_flag = $request->keg_flag;
            $data->keg_diupdate_oleh = Auth::user()->username;
            $data->update();

            //target masing2 kabkota
            foreach ($request->keg_kabkota as $key => $v)
            {
                if ($v > 0)
                {
                    //ada isian targetnya
                    $target = $v;
                }
                else
                {
                    //target tetep di set nol
                    $target = 0;
                }
                $t_count = KegTarget::where([['keg_id',$request->keg_id],['keg_t_unitkerja',$key]])->count();
                if ($t_count > 0)
                {
                    //unitkerja sudah ada update aja
                    $dataTarget = KegTarget::where([['keg_id',$request->keg_id],['keg_t_unitkerja',$key]])->first();
                    $dataTarget->keg_t_target = $target;
                    $dataTarget->keg_t_diupdate_oleh = Auth::user()->username;
                    $dataTarget->update();
                }
                else
                {
                    //unitkerja belum ada target input
                    $dataTarget = new KegTarget();
                    $dataTarget->keg_id = $request->keg_id;
                    $dataTarget->keg_t_unitkerja = $key;
                    $dataTarget->keg_t_target = $target;
                    $dataTarget->keg_t_point_waktu = 0;
                    $dataTarget->keg_t_point_jumlah = 0;
                    $dataTarget->keg_t_point = 0;
                    $dataTarget->keg_t_dibuat_oleh = Auth::user()->username;
                    $dataTarget->keg_t_diupdate_oleh = Auth::user()->username;
                    $dataTarget->save();
                }


            }
            if ($request->keg_spj == 1)
            {
                //ada permintaan spj
                //data spj di insert
                //spj_kabkota

                foreach ($request->spj_kabkota as $key => $v)
                {
                    if ($v > 0)
                    {
                        //ada isian targetnya
                        $target = $v;
                    }
                    else
                    {
                        //target tetep di set nol
                        $target = 0;
                    }
                    $s_count = SpjTarget::where([['keg_id',$request->keg_id],['spj_t_unitkerja',$key]])->count();
                    if ($s_count > 0)
                    {
                        //spj target sudah ada hnay update saja
                        $dataSpj = SpjTarget::where([['keg_id',$request->keg_id],['spj_t_unitkerja',$key]])->first();
                        $dataSpj->spj_t_target = $target;
                        $dataSpj->spj_t_diupdate_oleh = Auth::user()->username;
                        $dataSpj->update();
                    }
                    else
                    {
                        $dataSpj = new SpjTarget();
                        $dataSpj->keg_id = $request->keg_id;
                        $dataSpj->spj_t_unitkerja = $key;
                        $dataSpj->spj_t_target = $target;
                        $dataSpj->spj_t_point_waktu = 0;
                        $dataSpj->spj_t_point_jumlah = 0;
                        $dataSpj->spj_t_point = 0;
                        $dataSpj->spj_t_dibuat_oleh = Auth::user()->username;
                        $dataSpj->spj_t_diupdate_oleh = Auth::user()->username;
                        $dataSpj->save();
                    }
                }
            }
            else
            {
                //hapus semua target SPJ
                $dataSpj = SpjTarget::where('keg_id',$request->keg_id)->delete();
            }
            $pesan_error='Kegiatan ('.trim($request->keg_nama).') sudah di update';
            $pesan_warna='success';
        }
        else
        {
            //kegiatan ada di update
            $pesan_error='Nama kegiatan ('.trim($request->keg_nama).') tidak ada';
            $pesan_warna='danger';
        }

        Session::flash('message', $pesan_error);
        Session::flash('message_type', $pesan_warna);
        return redirect()->route('kegiatan.list');

    }
    public function hapusKegiatan(Request $request)
    {
        $count = Kegiatan::where('keg_id','=',$request->keg_id)->count();
        $arr = array(
            'status'=>false,
            'hasil'=>'data kegiatan ini tidak tersedia'
        );
        if ($count>0)
        {
            if (Auth::user()->role > 3)
            {
                //user admin atau operator provinsi
                $data = Kegiatan::where('keg_id',$request->keg_id)->first();
                if (Auth::user()->role > 4 or $data->keg_timkerja == Auth::user()->kodeunit)
                {
                    //admin atau operator provinsi sesuai unitkodenya
                    $nama = $data->keg_nama;
                    $unit_kode = $data->keg_timkerja;
                    $unit_nama = $data->TimKerja->unit_nama;
                    $keg_spj = $data->keg_spj;
                    $data->delete();
                    $target = KegTarget::where('keg_id',$request->keg_id)->delete();
                    $realisasi = KegRealisasi::where('keg_id',$request->keg_id)->delete();
                    if ($keg_spj==1)
                    {
                        $spj = SpjTarget::where('keg_id',$request->keg_id)->delete();
                        $spjrealisasi = SpjRealisasi::where('keg_id',$request->keg_id)->delete();
                    }
                    //hapus notifikasi juga
                    Notifikasi::where('keg_id',$request->keg_id)->delete();
                    //batas hapus
                    $arr = array(
                        'status'=>true,
                        'hasil'=>'Data kegiatan ('.$nama.') dari '.$unit_nama.' berhasil dihapus beserta target dan realisasinya'
                    );
                    if (env('APP_AKTIVITAS_MODE') == true)
                    {
                        //catat aktivitas hapus kegiatan operator prov
                        $data_log = new LogAktivitas();
                        $data_log->log_username = Auth::user()->username;
                        $data_log->log_ip = Generate::GetIpAddress();
                        $data_log->log_jenis = 3;
                        $data_log->log_useragent = Generate::GetUserAgent();
                        $data_log->log_pesan = 'berhasil menghapus kegiatan ['.$request->keg_id.'] '. trim($nama) .' dengan SM ['. $unit_kode .'] '. $unit_nama;
                        $data_log->save();
                        //batas catat aktivitas hapus kegiatan
                        //kirim ke sistem channel
                        //kirim ke channel log
                        /*
                        $message = '### HAPUS KEGIATAN ###' .chr(10);
                        $message .= '-----------------------'.chr(10);
                        $message .= '🟢 Username : '.Auth::user()->username .chr(10);
                        $message .= '🟢 IP Address : '. Generate::GetIpAddress() .chr(10);
                        $message .= '🟢 Useragent : '. Generate::GetUserAgent() .chr(10);
                        $message .= '🟢 Pesan : berhasil menghapus kegiatan ['.$request->keg_id.'] '. trim($nama) .' dengan SM ['. $unit_kode .'] '. $unit_nama .chr(10);
                        $message .= '-----------------------'.chr(10);
                        if (env('APP_TELEGRAM_MODE') == true)
                        {
                            $response = Telegram::sendMessage([
                                'chat_id' => $this->chan_log_id,
                                'text' => $message,
                                'parse_mode'=> 'HTML'
                            ]);
                            $messageId = $response->getMessageId();
                        }
                        */
                    }
                }
                else
                {
                    //error selain unitkode yg beda
                    $arr = array(
                        'status'=>false,
                        'hasil'=>'Operator Provinsi ('.Auth::user()->nama.') tidak mempunyai hak untuk menghapus kegiatan '.$data->keg_nama
                    );
                }
            }


        }
        return Response()->json($arr);
    }
    public function DetilKegiatan($kegId)
    {
        $count = Kegiatan::where('keg_id',$kegId)->count();
        if ($count > 0)
        {
            //kegiatan ada
            $status = true;
            $dataKegiatan = Kegiatan::where('keg_id',$kegId)->first();
            //$dataTarget = KegTarget::where([['keg_id',$kegId],['keg_t_target','>',0]])->get();
            //$dataRealisasi = KegRealisasi::where('keg_id',$kegId)->get();
        }
        else
        {
            //kegiatan tidak ada
            //tampilan error 404
            $status = false;
            $dataKegiatan='';
        }
        //dd($dataKegiatan->Target);
        return view('kegiatan.detil.index',['status'=>$status,'dataKegiatan'=>$dataKegiatan]);
    }
    public function editKegiatan($kegId)
    {
        if (Auth::user()->role > 3)
        {
            $dataKegiatan = Kegiatan::where('keg_id',$kegId)->first();
            if (Auth::user()->role == 4)
            {
                //operator provinsi list unitProv hanya di bidangnya
                if ($dataKegiatan->keg_timkerja != Auth::user()->kodeunit)
                {
                        //peringatan tidak bisa mengedit kegiatan ini
                        return view('kegiatan.warning',['keg_id'=>$kegId]);
                }
                $unitProv = UnitKerja::where([['unit_jenis',1],['unit_eselon',3],['unit_kode',Auth::user()->kodeunit]])->get();
            }
            else
            {
                //list semua unitProv eselon 4 di provinsi
                $unitProv = UnitKerja::where([['unit_jenis','=','1'],['unit_eselon','=','3'],['unit_flag',1]])->get();
            }
            //dd($dataKegiatan);
            //$unitTarget = UnitKerja::where([['unit_jenis','=','2'],['unit_eselon','=','3']])->get();
            $kegJenis = KegJenis::get();
            return view('kegiatan.edit',[
                'unitProv'=>$unitProv,
                'kegJenis'=>$kegJenis,
                'dataKegiatan'=>$dataKegiatan
            ]);
        }
        else
        {
            //operator kabkota dan pemantau tidak bisa mengakses ini
            //beri info warning
            return view('kegiatan.warning',['keg_id'=>$kegId]);
        }
    }
    public function cariKegiatan($kegId)
    {
        $count = Kegiatan::where('keg_id',$kegId)->count();
        $arr = array(
            'status'=>false,
            'hasil'=>'Data kegiatan ini tidak tersedia'
        );
        if ($count > 0)
        {
            //unitkerja
            $data = Kegiatan::where('keg_id',$kegId)->first();
            if ($data->keg_spj==1)
            {
                $spj = 'Ada';
            }
            else
            {
                $spj = 'Tidak';
            }
            $arr = array(
                'status'=>true,
                'keg_id'=>$data->keg_id,
                'keg_nama'=>$data->keg_nama,
                'keg_unitkerja'=>$data->keg_unitkerja,
                'keg_unitkerja_nama'=>$data->Unitkerja->unit_nama,
                'keg_timkerja'=>$data->keg_timkerja,
                'keg_timkerja_nama'=>$data->TimKerja->unit_nama,
                'keg_target'=>$data->keg_total_target,
                'keg_satuan'=>$data->keg_target_satuan,
                'keg_start'=>$data->keg_start,
                'keg_start_nama'=>Tanggal::HariPanjang($data->keg_start),
                'keg_end'=>$data->keg_end,
                'keg_end_nama'=>Tanggal::HariPanjang($data->keg_end),
                'keg_jenis'=>$data->keg_jenis,
                'keg_jenis_nama'=>$data->JenisKeg->jkeg_nama,
                'keg_spj'=>$data->keg_spj,
                'keg_spj_nama'=>$spj,
                'keg_info'=>$data->keg_info,
                'keg_dibuat_oleh'=>$data->keg_dibuat_oleh,
                'keg_diupdate_oleh'=>$data->keg_diupdate_oleh,
                'created_at'=>$data->created_at,
                'updated_at'=>$data->updated_at
            );
        }
        return Response()->json($arr);
    }
    public function cariKegByUnitkirim($kegid,$unitkirim)
    {
        $count = Kegiatan::where('keg_id',$kegid)->count();
        $arr = array(
            'status'=>false,
            'hasil'=>'Data kegiatan ini tidak tersedia'
        );
        if ($count > 0)
        {
            //unitkerja
            $data = Kegiatan::where('keg_id',$kegid)->first();
            if ($data->keg_spj==1)
            {
                $spj = 'Ada';
            }
            else
            {
                $spj = 'Tidak';
            }
            //query realisasi pengiriman
            $data_kirim = KegRealisasi::where([['keg_id',$kegid],['keg_r_jenis','1'],['keg_r_unitkerja',$unitkirim]])->orderBy('created_at','asc')->get();
            $data_terima = KegRealisasi::where([['keg_id',$kegid],['keg_r_jenis','2'],['keg_r_unitkerja',$unitkirim]])->orderBy('created_at','asc')->get();
            $data_target = $data->Target->where('keg_t_target','>','0')->where('keg_t_unitkerja',$unitkirim)->first();
            //dd($data_kirim,$data_terima);
            $arr = array(
                'status'=>true,
                'keg_id'=>$data->keg_id,
                'keg_nama'=>$data->keg_nama,
                'keg_unitkerja'=>$data->keg_unitkerja,
                'keg_unitkerja_nama'=>$data->Unitkerja->unit_nama,
                'keg_timkerja'=>$data->keg_timkerja,
                'keg_timkerja_nama'=>$data->TimKerja->unit_nama,
                'keg_target'=>$data->keg_total_target,
                'keg_satuan'=>$data->keg_target_satuan,
                'keg_start'=>$data->keg_start,
                'keg_start_nama'=>Tanggal::HariPanjang($data->keg_start),
                'keg_end'=>$data->keg_end,
                'keg_end_nama'=>Tanggal::HariPanjang($data->keg_end),
                'keg_jenis'=>$data->keg_jenis,
                'keg_jenis_nama'=>$data->JenisKeg->jkeg_nama,
                'keg_spj'=>$data->keg_spj,
                'keg_spj_nama'=>$spj,
                'keg_info'=>$data->keg_info,
                'keg_dibuat_oleh'=>$data->keg_dibuat_oleh,
                'keg_diupdate_oleh'=>$data->keg_diupdate_oleh,
                'created_at'=>$data->created_at,
                'updated_at'=>$data->updated_at,
                'hasil_target'=>$data_target,
                'hasil_kirim'=>$data_kirim,
                'hasil_terima'=>$data_terima
            );
        }
        return Response()->json($arr);
    }
    public function CariRealisasi($kegrid)
    {
        $count = KegRealisasi::where('keg_r_id',$kegrid)->count();
        $arr = array(
            'status'=>false,
            'hasil'=>'Realisasi kegiatan ini tidak tersedia'
        );
        if ($count > 0)
        {
            //unitkerja
            $data = KegRealisasi::where('keg_r_id',$kegrid)->first();
            if ($data->MasterKegiatan->keg_spj==1)
            {
                $spj = 'Ada';
            }
            else
            {
                $spj = 'Tidak';
            }
            $arr = array(
                'status'=>true,
                'keg_id'=>$data->keg_id,
                'keg_nama'=>$data->MasterKegiatan->keg_nama,
                'keg_unitkerja'=>$data->MasterKegiatan->keg_unitkerja,
                'keg_unitkerja_nama'=>$data->MasterKegiatan->Unitkerja->unit_nama,
                'keg_timkerja'=>$data->MasterKegiatan->keg_timkerja,
                'keg_timkerja_nama'=>$data->MasterKegiatan->TimKerja->unit_nama,
                'keg_target'=>$data->MasterKegiatan->keg_total_target,
                'keg_satuan'=>$data->MasterKegiatan->keg_target_satuan,
                'keg_start'=>$data->MasterKegiatan->keg_start,
                'keg_start_nama'=>Tanggal::HariPanjang($data->MasterKegiatan->keg_start),
                'keg_end'=>$data->MasterKegiatan->keg_end,
                'keg_end_nama'=>Tanggal::HariPanjang($data->MasterKegiatan->keg_end),
                'keg_jenis'=>$data->MasterKegiatan->keg_jenis,
                'keg_jenis_nama'=>$data->MasterKegiatan->JenisKeg->jkeg_nama,
                'keg_spj'=>$data->MasterKegiatan->keg_spj,
                'keg_spj_nama'=>$spj,
                'keg_dibuat_oleh'=>$data->MasterKegiatan->keg_dibuat_oleh,
                'keg_diupdate_oleh'=>$data->MasterKegiatan->keg_diupdate_oleh,
                'keg_created_at'=>$data->MasterKegiatan->created_at,
                'keg_updated_at'=>$data->MasterKegiatan->updated_at,
                'keg_r_id'=>$data->keg_r_id,
                'keg_r_unitkerja'=>$data->keg_r_unitkerja,
                'keg_r_unitkerja_nama'=>$data->Unitkerja->unit_nama,
                'keg_r_jumlah'=>$data->keg_r_jumlah,
                'keg_r_tgl'=>$data->keg_r_tgl,
                'keg_r_tgl_nama'=>Tanggal::HariPanjang($data->keg_r_tgl),
                'keg_r_jenis'=>$data->keg_r_jenis,
                'keg_r_jenis_nama'=>$data->JenisRealisasi->rkeg_nama,
                'keg_r_link'=>$data->keg_r_link,
                'keg_r_ket'=>$data->keg_r_ket,
                'keg_r_dibuat_oleh'=>$data->keg_r_dibuat_oleh,
                'keg_r_diupdate_oleh'=>$data->keg_r_diupdate_oleh,
                'created_at'=>$data->created_at,
                'updated_at'=>$data->updated_at
            );
        }
        return Response()->json($arr);
    }
    public function kirimKegiatan(Request $request)
    {
        //dd($request->all());
        $count = Kegiatan::where('keg_id',$request->keg_id)->count();
        if ($count > 0)
        {
            //kegiatan ini ada
            $data = new KegRealisasi();
            $data->keg_id = $request->keg_id;
            $data->keg_r_unitkerja = $request->keg_r_unitkerja;
            $data->keg_r_tgl = $request->keg_r_tgl;
            $data->keg_r_jumlah = $request->keg_r_jumlah;
            $data->keg_r_jenis = 1;
            $data->keg_r_link = $request->keg_r_link;
            $data->keg_r_ket = $request->keg_r_ket;
            $data->keg_r_dibuat_oleh = Auth::user()->username;
            $data->keg_r_diupdate_oleh = Auth::user()->username;
            $data->save();
            $waktu_simpan_penerimaan = $data->created_at;

            $data_target = KegTarget::where([
				['keg_id',$request->keg_id],
				['keg_t_unitkerja',$request->keg_r_unitkerja],
            ])->first();
            $target_kabkota = $data_target->keg_t_target;
            $nama_kabkota = $data_target->Unitkerja->unit_nama;

            $data_keg = Kegiatan::where('keg_id',$request->keg_id)->first();
            $nofif_isi = '['.$request->keg_id.'] ada pengiriman dari '.Auth::user()->username .' sebanyak '. $request->keg_r_jumlah .' '.$data_keg->keg_target_satuan.' tanggal '.Tanggal::HariPanjang($request->keg_r_tgl).' dengan keterangan '.$request->keg_r_ket;
            $data_user = User::where([['kodeunit',$data_keg->Unitkerja->unit_parent],['level','>','1']])->get();
            foreach ($data_user as $item)
            {
                $notif = new Notifikasi();
                $notif->keg_id = $request->keg_id;
                $notif->notif_dari = Auth::user()->username;
                $notif->notif_untuk = $item->username;
                $notif->notif_isi = $nofif_isi;
                $notif->notif_jenis = '1';
                $notif->save();

                //testing notif pengiriman ke email
                if (env('APP_MAIL_MODE') == true)
                {
                    /*
                    <div>
                    <p>Detil Kegiatan yang dikirim :<br/>
                    <b>ID :</b>&nbsp;{{ $objEmail->keg_id}}<br/>
                    <b>Judul :</b>&nbsp;{{ $objEmail->keg_nama }}<br/>
                    <b>Jenis :</b>&nbsp;{{ $objEmail->keg_jenis }}<br/>
                    <b>Target dikirim :</b>&nbsp;{{ $objEmail->keg_dikirim }} &nbsp;{{ $objEmail->keg_satuan }}<br/>
                    <b>Tanggal dikirim :</b>&nbsp;{{ $objEmail->keg_tgl_dikirim }}<br/>
                    <b>Keterangan :</b>&nbsp;{{ $objEmail->keg_ket }}<br/>
                    <b>Kabupaten/Kota :</b>&nbsp;{{ $objEmail->keg_kabkota }}<br/>
                    </p>
                    </div>
                    <div>
                        <p>Info Tambahan :<br/>
                        <b>Target Kabkota :</b>&nbsp;{{ $objEmail->keg_target_kabkota}} &nbsp;{{ $objEmail->keg_satuan }}<br/>
                        <b>Tanggal dibuat :</b>&nbsp;{{ $objEmail->keg_tgl_dibuat }}<br/>
                        <b>Operator Pengirim :</b>&nbsp;{{ $objEmail->keg_operator }}<br/>
                    </p>
                    </div>
                    */
                    //$data_keg = Kegiatan::where('keg_id',$keg_id)->first();

                    $objEmail = new \stdClass();
                    $objEmail->keg_id = $data_keg->keg_id;
                    $objEmail->keg_nama = $data_keg->keg_nama;
                    $objEmail->keg_jenis = $data_keg->JenisKeg->jkeg_nama;
                    $objEmail->keg_dikirim = $request->keg_r_jumlah;
                    $objEmail->keg_satuan = $data_keg->keg_target_satuan;
                    $objEmail->keg_tgl_dikirim = Tanggal::HariPanjang($request->keg_r_tgl);
                    $objEmail->keg_kabkota = $nama_kabkota;
                    $objEmail->keg_ket = $request->keg_r_ket;
                    $objEmail->keg_target_kabkota = $target_kabkota;
                    $objEmail->keg_tgl_dibuat = Tanggal::LengkapHariPanjang($waktu_simpan_penerimaan);
                    $objEmail->keg_operator = Auth::user()->username;
                    //coba email
                    $dataemail = $item->email;
                    //$dataemail = "pdyatmika@gmail.com";
                    //dd($objEmail);
                    Mail::to($dataemail)->send(new MailPengiriman($objEmail));

                }
                //batas testing
            }
            //pengiriman ke channel notif
            if ($data_keg->keg_spj == 1)
            {
                $kegspj = 'Ada';
            }
            else
            {
                $kegspj = 'Tidak ada';
            }
            /*
            $message = '<b>🎯🎯🎯 PENGIRIMAN 🎯🎯🎯</b>' .chr(10);
            $message .= '-----------------------'.chr(10);
            $message .= '🟢 ID : <b>#'.$data_keg->keg_id.'</b>'.chr(10);
            $message .= '🟢 NAMA KEGIATAN : <b>'. $data_keg->keg_nama.'</b>' .chr(10);
            $message .= '🟢 SM : <b>'. $data_keg->Unitkerja->unit_nama.'</b>' .chr(10);
            $message .= '🟢 Jenis : <b>'. $data_keg->JenisKeg->jkeg_nama.'</b>' .chr(10);
            $message .= '🟢 Tanggal mulai : <b>'. Tanggal::HariPanjang($data_keg->keg_start).'</b>' .chr(10);
            $message .= '🟢 Tanggal selesai : <b>'. Tanggal::HariPanjang($data_keg->keg_end).'</b>' .chr(10);
            $message .= '🟢 Ada SPJ : <b>'. $kegspj.'</b>' .chr(10);
            $message .= '-----------------------'.chr(10);
            $message .= '🔹 Target dikirim : <b>'. $request->keg_r_jumlah .' '.$data_keg->keg_target_satuan.'</b>' .chr(10);
            $message .= '🔹 Tanggal pengiriman : <b>'. Tanggal::HariPanjang($request->keg_r_tgl).'</b>' .chr(10);
            $message .= '🔹 Keterangan : <b>'. $request->keg_r_ket.'</b>' .chr(10);
            $message .= '🔹 Kabkota : <b>'. $nama_kabkota.'</b>' .chr(10);
            $message .= '-----------------------'.chr(10);
            $message .= '<b>💡💡 Info Tambahan 💡💡</b>' .chr(10);
            $message .= '-----------------------'.chr(10);
            $message .= '📡 Target Kabkota : <b>'. $target_kabkota .' '.$data_keg->keg_target_satuan.'</b>' .chr(10);
            $message .= '⌛️ Tanggal dibuat : <b>'. Tanggal::LengkapHariPanjang($waktu_simpan_penerimaan).'</b>' .chr(10);
            $message .= '👤 Operator Pengirim : <b>'. Auth::user()->username.'</b>' .chr(10);
            //kirim pesan ke channel notif
            $message .= '-----------------------'.chr(10);
            $message .= '🟢 Link : <a href="'.route('kegiatan.detil',$data_keg->keg_id).'">Kegiatan Detil</a>' .chr(10);
            if (env('APP_TELEGRAM_MODE') == true)
            {
                $response = Telegram::sendMessage([
                    'chat_id' => $this->chan_notif_id,
                    'text' => $message,
                    'parse_mode'=> 'HTML'
                ]);
            }
            //batasannya
            */
            if (env('APP_AKTIVITAS_MODE') == true)
            {
                //catat pengiriman oleh operator kabkota
                $data_log = new LogAktivitas();
                $data_log->log_username = Auth::user()->username;
                $data_log->log_ip = Generate::GetIpAddress();
                $data_log->log_jenis = 4;
                $data_log->log_useragent = Generate::GetUserAgent();
                $data_log->log_pesan = 'berhasil menambah pengiriman untuk kegiatan ['.$request->keg_id.'] '. trim($data_keg->keg_nama) .' tanggal '.Tanggal::HariPanjang($request->keg_r_tgl).' sebanyak '. $request->keg_r_jumlah .' '.$data_keg->keg_target_satuan;
                $data_log->save();
                //batas pengiriman oleh operator kabkota
                //kirim ke sistem channel
                //kirim ke channel log
                /*
                $message = '### PENGIRIMAN  ###' .chr(10);
                $message .= '-----------------------'.chr(10);
                $message .= '🟢 Username : '.Auth::user()->username .chr(10);
                $message .= '🟢 IP Address : '. Generate::GetIpAddress() .chr(10);
                $message .= '🟢 Useragent : '. Generate::GetUserAgent() .chr(10);
                $message .= '🟢 Pesan : berhasil menambah pengiriman untuk kegiatan ['.$request->keg_id.'] '. trim($data_keg->keg_nama) .' tanggal '.Tanggal::HariPanjang($request->keg_r_tgl).' sebanyak '. $request->keg_r_jumlah .' '.$data_keg->keg_target_satuan .chr(10);
                $message .= '🟢 Link : <a href="'.route('kegiatan.detil',$request->keg_id).'">Kegiatan Detil</a>' .chr(10);
                $message .= '-----------------------'.chr(10);
                //dd($message);
                //kirim pesan ke channel log
                if (env('APP_TELEGRAM_MODE') == true)
                {
                    $response = Telegram::sendMessage([
                        'chat_id' => $this->chan_log_id,
                        'text' => $message,
                        'parse_mode'=> 'HTML'
                    ]);
                    //batasannya
                }
                */
            }
            $pesan_error="Pengiriman oleh ". $data->Unitkerja->unit_nama.' sudah disimpan';
            $pesan_warna="success";
        }
        else
        {
            //kegiatan ini tidak ada
            $pesan_error="Kegiatan ini tidak ada";
            $pesan_warna="danger";
        }
        Session::flash('message', $pesan_error);
        Session::flash('message_type', $pesan_warna);
        return redirect()->route('kegiatan.detil',$request->keg_id);
    }
    public function UpdatePengiriman(Request $request)
    {
        //dd($request->all());
        $count = KegRealisasi::where('keg_r_id',$request->keg_r_id)->count();
        if ($count > 0)
        {
            //realisasi kegiatan ini ada
            $data = KegRealisasi::where('keg_r_id',$request->keg_r_id)->first();
            $data->keg_r_tgl = $request->keg_r_tgl;
            $data->keg_r_jumlah = $request->keg_r_jumlah;
            $data->keg_r_link = $request->keg_r_link;
            $data->keg_r_ket = $request->keg_r_ket;
            $data->keg_r_diupdate_oleh = Auth::user()->username;
            $data->update();

            $pesan_error="Konfirmasi pengiriman oleh ". $data->Unitkerja->unit_nama.' sudah diupdate';
            $pesan_warna="success";
        }
        else
        {
            //realiasi kegiatan ini tidak ada
            $pesan_error="Realiasi kegiatan ini tidak ada";
            $pesan_warna="danger";
        }
        Session::flash('message', $pesan_error);
        Session::flash('message_type', $pesan_warna);
        return redirect()->route('kegiatan.detil',$request->keg_id);
    }
    public function HapusPengiriman(Request $request)
    {
        $count = KegRealisasi::where('keg_r_id','=',$request->id)->count();
        $arr = array(
            'status'=>false,
            'hasil'=>'data realiasi pengiriman ini tidak tersedia'
        );
        if ($count>0)
        {
            $data = KegRealisasi::where('keg_r_id','=',$request->id)->first();
            $data_keg = Kegiatan::where('keg_id',$data->keg_id)->first();
            $keg_r_jumlah = $data->keg_r_jumlah;
            $nama = $data->Unitkerja->unit_nama;
            $tgl = Tanggal::Panjang($data->keg_r_tgl);
            $data->delete();
            $arr = array(
                'status'=>true,
                'hasil'=>'Data pengiriman oleh '.$nama.' tanggal '.$tgl.' berhasil dihapus'
            );
            if (env('APP_AKTIVITAS_MODE') == true)
            {
                //catat hapus pengiriman
                $data_log = new LogAktivitas();
                $data_log->log_username = Auth::user()->username;
                $data_log->log_ip = Generate::GetIpAddress();
                $data_log->log_jenis = 4;
                $data_log->log_useragent = Generate::GetUserAgent();
                $data_log->log_pesan = 'berhasil menghapus pengiriman untuk kegiatan ['.$data_keg->keg_id.'] '. trim($data_keg->keg_nama) .' tanggal '.$tgl.' sebanyak '. $keg_r_jumlah .' '.$data_keg->keg_target_satuan;
                $data_log->save();
                //batas hapus pengiriman
                //kirim ke sistem channel
                //kirim ke channel log
                /*
                $message = '### HAPUS PENGIRIMAN  ###' .chr(10);
                $message .= '-----------------------'.chr(10);
                $message .= '🟢 Username : '.Auth::user()->username .chr(10);
                $message .= '🟢 IP Address : '. Generate::GetIpAddress() .chr(10);
                $message .= '🟢 Useragent : '. Generate::GetUserAgent() .chr(10);
                $message .= '🟢 Pesan : berhasil menghapus pengiriman untuk kegiatan ['.$data_keg->keg_id.'] '. trim($data_keg->keg_nama) .' tanggal '.$tgl.' sebanyak '. $keg_r_jumlah .' '.$data_keg->keg_target_satuan .chr(10);
                $message .= '🟢 Link : <a href="'.route('kegiatan.detil',$data_keg->keg_id).'">Kegiatan Detil</a>' .chr(10);
                $message .= '-----------------------'.chr(10);
                //dd($message);
                //kirim pesan ke channel log
                if (env('APP_TELEGRAM_MODE') == true)
                {
                    $response = Telegram::sendMessage([
                        'chat_id' => $this->chan_log_id,
                        'text' => $message,
                        'parse_mode'=> 'HTML'
                    ]);
                    //batasannya
                }
                */
            }
        }
        return Response()->json($arr);
    }
    public function terimaKegiatan(Request $request)
    {
        //dd($request->all());
        $count = Kegiatan::where('keg_id',$request->keg_id)->count();
        if ($count > 0)
        {
            //kegiatan ini ada
            //buat realisasi
            //dan nilai di tabel keg_target
            //$data_keg = Kegiatan::where('keg_id',$request->keg_id)->first();
            $data = new KegRealisasi();
            $data->keg_id = $request->keg_id;
            $data->keg_r_unitkerja = $request->keg_r_unitkerja;
            $data->keg_r_tgl = $request->keg_r_tgl;
            $data->keg_r_jumlah = $request->keg_r_jumlah;
            $data->keg_r_jenis = 2;
            $data->keg_r_ket = Auth::user()->username;
            $data->keg_r_dibuat_oleh = Auth::user()->username;
            $data->keg_r_diupdate_oleh = Auth::user()->username;
            $data->save();
            $waktu_simpan_penerimaan = $data->created_at;
            $nilai = Generate::NilaiKegRealiasi($request->keg_id,$request->keg_r_unitkerja);
            //update nilai KegTarget
            $dataNilai = KegTarget::where([
				['keg_id',$request->keg_id],
				['keg_t_unitkerja',$request->keg_r_unitkerja],
            ])->first();
            $dataNilai->keg_t_point_waktu = $nilai['nilai_waktu'];
            $dataNilai->keg_t_point_jumlah = $nilai['nilai_volume'];
            $dataNilai->keg_t_point = $nilai['nilai_total'];
            if ($dataNilai->MasterKegiatan->keg_spj == '1')
            {
                //ada SPJ
                //nilai total = (nilai keg + nilai spj)/2
                $nilai_spj = $dataNilai->spj_t_point;
                $dataNilai->keg_t_point_total = ($nilai['nilai_total'] + $nilai_spj)/2;
            }
            else
            {
                //nilai total = nilai kegiatan
                $dataNilai->keg_t_point_total = $nilai['nilai_total'];
            }
            $dataNilai->keg_t_diupdate_oleh = Auth::user()->username;
            $dataNilai->update();
            $target_kabkota = $dataNilai->keg_t_target;
            $nama_kabkota = $dataNilai->Unitkerja->unit_nama;
            $data_keg = Kegiatan::where('keg_id',$request->keg_id)->first();
            $nofif_isi = '['.$request->keg_id.'] ada penerimaan oleh '.Auth::user()->username .' tanggal '.Tanggal::HariPanjang($request->keg_r_tgl).' sebanyak '. $request->keg_r_jumlah .' '.$data_keg->keg_target_satuan;
            $data_user = User::where([['kodeunit',$request->keg_r_unitkerja],['level','>','1']])->get();
            foreach ($data_user as $item)
            {
                $notif = new Notifikasi();
                $notif->keg_id = $request->keg_id;
                $notif->notif_dari = Auth::user()->username;
                $notif->notif_untuk = $item->username;
                $notif->notif_isi = $nofif_isi;
                $notif->notif_jenis = '2';
                $notif->save();
                //testing notif ke email
                if (env('APP_MAIL_MODE') == true)
                {
                    /*
                    <p>Detil Kegiatan yang diterima :<br/>
                    <b>ID :</b>&nbsp;{{ $objEmail->keg_id}}<br/>
                    <b>Judul :</b>&nbsp;{{ $objEmail->keg_nama }}<br/>
                    <b>Jenis :</b>&nbsp;{{ $objEmail->keg_jenis }}<br/>
                    <b>Target diterima :</b>&nbsp;{{ $objEmail->keg_diterima }} &nbsp;{{ $objEmail->keg_satuan }}<br/>
                    <b>Tgl diterima :</b>&nbsp;{{ $objEmail->keg_tgl_diterima }}<br/>
                    <b>Subject Matter :</b>&nbsp;{{ $objEmail->keg_sm }}<br/>
                    </p>
                    </div>
                    <div>
                        <p>Info Tambahan :<br/>
                        <b>Target Kabkota :</b>&nbsp;{{ $objEmail->keg_target_kabkota}} &nbsp;{{ $objEmail->keg_satuan }}<br/>
                        <b>Tanggal dibuat :</b>&nbsp;{{ $objEmail->keg_tgl_dibuat }}<br/>
                        <b>Operator Penerima :</b>&nbsp;{{ $objEmail->keg_operator }}<br/>
                    </p>
                    </div>
                    <br/>
                    */
                    //$data_keg = Kegiatan::where('keg_id',$keg_id)->first();
                    $objEmail = new \stdClass();
                    $objEmail->keg_id = $data_keg->keg_id;
                    $objEmail->keg_nama = $data_keg->keg_nama;
                    $objEmail->keg_jenis = $data_keg->JenisKeg->jkeg_nama;
                    $objEmail->keg_diterima = $request->keg_r_jumlah;
                    $objEmail->keg_satuan = $data_keg->keg_target_satuan;
                    $objEmail->keg_tgl_diterima = Tanggal::HariPanjang($request->keg_r_tgl);
                    $objEmail->keg_sm = $data_keg->Unitkerja->unit_nama;
                    $objEmail->keg_target_kabkota = $target_kabkota;
                    $objEmail->keg_tgl_dibuat = Tanggal::LengkapHariPanjang($waktu_simpan_penerimaan);
                    $objEmail->keg_operator = Auth::user()->username;
                    //coba email
                    $dataemail = $item->email;
                    //$dataemail = "pdyatmika@gmail.com";
                    Mail::to($dataemail)->send(new MailPenerimaan($objEmail));

                }
                //batas testing
            }
            //telegram penerimaan
            if ($data_keg->keg_spj == 1)
            {
                $kegspj = 'Ada';
            }
            else
            {
                $kegspj = 'Tidak ada';
            }
            /*
            $message = '<b>▶️▶️▶️ PENERIMAAN ◀️◀️◀️</b>' .chr(10);
            $message .= '-----------------------'.chr(10);
            $message .= '🟢 ID : <b>#'.$data_keg->keg_id.'</b>'.chr(10);
            $message .= '🟢 NAMA KEGIATAN : <b>'. $data_keg->keg_nama.'</b>' .chr(10);
            $message .= '🟢 SM : <b>'. $data_keg->Unitkerja->unit_nama.'</b>' .chr(10);
            $message .= '🟢 Jenis : <b>'. $data_keg->JenisKeg->jkeg_nama.'</b>' .chr(10);
            $message .= '🟢 Tanggal mulai : <b>'. Tanggal::HariPanjang($data_keg->keg_start).'</b>' .chr(10);
            $message .= '🟢 Tanggal selesai : <b>'. Tanggal::HariPanjang($data_keg->keg_end).'</b>' .chr(10);
            $message .= '🟢 Ada SPJ : <b>'. $kegspj.'</b>' .chr(10);
            $message .= '-----------------------'.chr(10);
            $message .= '🔴 Target diterima : <b>'. $request->keg_r_jumlah .' '.$data_keg->keg_target_satuan.'</b>' .chr(10);
            $message .= '🔴 Tanggal penerimaan : <b>'. Tanggal::HariPanjang($request->keg_r_tgl).'</b>' .chr(10);
            $message .= '🔴 Dari : <b>['.$request->keg_r_unitkerja.'] '. $nama_kabkota.'</b>' .chr(10);
            $message .= '-----------------------'.chr(10);
            $message .= '<b>💡💡 Info Tambahan 💡💡</b>' .chr(10);
            $message .= '-----------------------'.chr(10);
            $message .= '📡 Target Kabkota : <b>'. $target_kabkota .' '.$data_keg->keg_target_satuan.'</b>' .chr(10);
            $message .= '⌛️ Tanggal dibuat : <b>'. Tanggal::LengkapHariPanjang($waktu_simpan_penerimaan).'</b>' .chr(10);
            $message .= '👤 Operator penerima : <b>'. Auth::user()->username.'</b>' .chr(10);
            //kirim pesan ke channel notif
            $message .= '-----------------------'.chr(10);
            $message .= '🟢 Link : <a href="'.route('kegiatan.detil',$data_keg->keg_id).'">Kegiatan Detil</a>' .chr(10);
            if (env('APP_TELEGRAM_MODE') == true)
            {
                $response = Telegram::sendMessage([
                    'chat_id' => $this->chan_notif_id,
                    'text' => $message,
                    'parse_mode'=> 'HTML'
                ]);
                //batasannya
            }
            */
            if (env('APP_AKTIVITAS_MODE') == true)
            {
                //catat penerimaan oleh operator provinsi
                $data_log = new LogAktivitas();
                $data_log->log_username = Auth::user()->username;
                $data_log->log_ip = Generate::GetIpAddress();
                $data_log->log_jenis = 5;
                $data_log->log_useragent = Generate::GetUserAgent();
                $data_log->log_pesan = 'berhasil menambah penerimaan untuk kegiatan ['.$request->keg_id.'] '. trim($data_keg->keg_nama) .' tanggal '.Tanggal::HariPanjang($request->keg_r_tgl).' sebanyak '. $request->keg_r_jumlah .' '.$data_keg->keg_target_satuan;
                $data_log->save();
                //batas penerimaan oleh operator provinsi
                //kirim ke sistem channel
                //kirim ke channel log
                /*
                $message = '### PENERIMAAN  ###' .chr(10);
                $message .= '-----------------------'.chr(10);
                $message .= '🟢 Username : '.Auth::user()->username .chr(10);
                $message .= '🟢 IP Address : '. Generate::GetIpAddress() .chr(10);
                $message .= '🟢 Useragent : '. Generate::GetUserAgent() .chr(10);
                $message .= '🟢 Pesan : berhasil menambah penerimaan untuk kegiatan ['.$request->keg_id.'] '. trim($data_keg->keg_nama) .' tanggal '.Tanggal::HariPanjang($request->keg_r_tgl).' sebanyak '. $request->keg_r_jumlah .' '.$data_keg->keg_target_satuan .chr(10);
                $message .= '🟢 Link : <a href="'.route('kegiatan.detil',$request->keg_id).'">Kegiatan Detil</a>' .chr(10);
                $message .= '-----------------------'.chr(10);
                //dd($message);
                //kirim pesan ke channel log
                if (env('APP_TELEGRAM_MODE') == true)
                {
                    $response = Telegram::sendMessage([
                        'chat_id' => $this->chan_log_id,
                        'text' => $message,
                        'parse_mode'=> 'HTML'
                    ]);
                    //batasannya
                }
                */
            }
            $pesan_error="Konfirmasi penerimaan dari ". $data->Unitkerja->unit_nama.' sudah disimpan';
            $pesan_warna="success";


        }
        else
        {
            //kegiatan ini tidak ada
            $pesan_error="Kegiatan ini tidak ada";
            $pesan_warna="danger";
        }
        Session::flash('message', $pesan_error);
        Session::flash('message_type', $pesan_warna);
        return redirect()->route('kegiatan.detil',$request->keg_id);
    }
    public function HapusPenerimaan(Request $request)
    {
        //dd($request->all());
        $count = KegRealisasi::where('keg_r_id','=',$request->id)->count();
        $arr = array(
            'status'=>false,
            'hasil'=>'data realiasi konfirmasi penerimaan ini tidak tersedia'
        );
        if ($count>0)
        {
            $data = KegRealisasi::where('keg_r_id','=',$request->id)->first();
            $nama = $data->Unitkerja->unit_nama;
            $keg_id = $data->keg_id;
            $keg_r_unitkerja = $data->keg_r_unitkerja;
            $keg_r_jumlah = $data->keg_r_jumlah;
            $tgl = Tanggal::Panjang($data->keg_r_tgl);
            $data->delete();

            $nilai = Generate::NilaiKegRealiasi($keg_id,$keg_r_unitkerja);
            //update nilai KegTarget
            $dataNilai = KegTarget::where([
				['keg_id',$keg_id],
				['keg_t_unitkerja',$keg_r_unitkerja],
            ])->first();
            $dataNilai->keg_t_point_waktu = $nilai['nilai_waktu'];
            $dataNilai->keg_t_point_jumlah = $nilai['nilai_volume'];
            $dataNilai->keg_t_point = $nilai['nilai_total'];
            $dataNilai->keg_t_diupdate_oleh = Auth::user()->username;
            $dataNilai->update();
            $arr = array(
                'status'=>true,
                'hasil'=>'Data konfirmasi penerimaan oleh '.$nama.' tanggal '.$tgl.' berhasil dihapus'
            );
            if (env('APP_AKTIVITAS_MODE') == true)
            {
                $data_keg = Kegiatan::where('keg_id',$keg_id)->first();
                //catat hapus penerimaan oleh operator provinsi
                $data_log = new LogAktivitas();
                $data_log->log_username = Auth::user()->username;
                $data_log->log_ip = Generate::GetIpAddress();
                $data_log->log_jenis = 5;
                $data_log->log_useragent = Generate::GetUserAgent();
                $data_log->log_pesan = 'berhasil menghapus penerimaan untuk kegiatan ['.$keg_id.'] '. trim($data_keg->keg_nama) .' tanggal '.$tgl.' sebanyak '. $keg_r_jumlah .' '.$data_keg->keg_target_satuan;
                $data_log->save();
                //batas hapus penerimaan oleh operator provinsi
                //kirim ke sistem channel
                //kirim ke channel log
                /*
                $message = '### HAPUS PENERIMAAN  ###' .chr(10);
                $message .= '-----------------------'.chr(10);
                $message .= '🟢 Username : '.Auth::user()->username .chr(10);
                $message .= '🟢 IP Address : '. Generate::GetIpAddress() .chr(10);
                $message .= '🟢 Useragent : '. Generate::GetUserAgent() .chr(10);
                $message .= '🟢 Pesan : berhasil menghapus penerimaan untuk kegiatan ['.$keg_id.'] '. trim($data_keg->keg_nama) .' tanggal '.$tgl.' sebanyak '. $keg_r_jumlah .' '.$data_keg->keg_target_satuan .chr(10);
                $message .= '🟢 Link : <a href="'.route('kegiatan.detil',$request->keg_id).'">Kegiatan Detil</a>' .chr(10);
                $message .= '-----------------------'.chr(10);
                //dd($message);
                //kirim pesan ke channel log
                if (env('APP_TELEGRAM_MODE') == true)
                {
                    $response = Telegram::sendMessage([
                        'chat_id' => $this->chan_log_id,
                        'text' => $message,
                        'parse_mode'=> 'HTML'
                    ]);
                    //batasannya
                }
                */
            }
        }
        return Response()->json($arr);
    }
    public function UpdatePenerimaan(Request $request)
    {
        //dd($request->all());
        $count = KegRealisasi::where('keg_r_id',$request->keg_r_id)->count();
        if ($count > 0)
        {
            //realisasi kegiatan ini ada
            $data = KegRealisasi::where('keg_r_id',$request->keg_r_id)->first();
            $data->keg_r_tgl = $request->keg_r_tgl;
            $data->keg_r_jumlah = $request->keg_r_jumlah;
            $data->keg_r_ket = Auth::user()->username;
            $data->keg_r_diupdate_oleh = Auth::user()->username;
            $data->update();

            $nilai = Generate::NilaiKegRealiasi($data->keg_id,$data->keg_r_unitkerja);
            //update nilai KegTarget
            $dataNilai = KegTarget::where([
				['keg_id',$data->keg_id],
				['keg_t_unitkerja',$data->keg_r_unitkerja],
            ])->first();
            $dataNilai->keg_t_point_waktu = $nilai['nilai_waktu'];
            $dataNilai->keg_t_point_jumlah = $nilai['nilai_volume'];
            $dataNilai->keg_t_point = $nilai['nilai_total'];
            if ($dataNilai->MasterKegiatan->keg_spj == '1')
            {
                //ada SPJ
                //nilai total = (nilai keg + nilai spj)/2
                $nilai_spj = $dataNilai->spj_t_point;
                $dataNilai->keg_t_point_total = ($nilai['nilai_total'] + $nilai_spj)/2;
            }
            else
            {
                //nilai total = nilai kegiatan
                $dataNilai->keg_t_point_total = $nilai['nilai_total'];
            }
            $dataNilai->keg_t_diupdate_oleh = Auth::user()->username;
            $dataNilai->update();
            $pesan_error="Konfirmasi penerimaan dari ". $data->Unitkerja->unit_nama.' sudah diupdate';
            $pesan_warna="success";
        }
        else
        {
            //realiasi kegiatan ini tidak ada
            $pesan_error="Realisasi konfirmasi penerimaan tidak ada";
            $pesan_warna="danger";
        }
        Session::flash('message', $pesan_error);
        Session::flash('message_type', $pesan_warna);
        return redirect()->route('kegiatan.detil',$request->keg_id);
    }
    public function UpdateInfo(Request $request)
    {
        //dd($request->all());
        $count = Kegiatan::where('keg_id',$request->keg_id)->count();
        if ($count > 0)
        {
            $data = Kegiatan::where('keg_id',$request->keg_id)->first();
            $data->keg_info = trim($request->keg_info);
            $data->keg_diupdate_oleh = Auth::user()->username;
            $data->update();

            $pesan_error='Info lanjutan kegiatan ('.$data->keg_nama.') pada '.$data->Unitkerja->unit_nama.' sudah di update';
            $pesan_warna='success';
        }
        else
        {
            //kegiatan ada di update
            $pesan_error='Nama kegiatan (ini) tidak ada';
            $pesan_warna='danger';
        }

        Session::flash('message', $pesan_error);
        Session::flash('message_type', $pesan_warna);
        return redirect()->route('kegiatan.detil',$request->keg_id);
    }
    public function ListPoin()
    {
        if (Auth::user()->level > 5)
        {
            $data_bulan = array(
                1=>'Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'
            );
            $Kabkota = UnitKerja::where([['unit_jenis','=','2'],['unit_eselon','=','3']])->get();
            $data_tahun = DB::table('m_keg')
            ->selectRaw('year(keg_end) as tahun')
            ->groupBy('tahun')
            //->whereYear('keg_end','<=',NOW())
            ->orderBy('tahun','asc')
              ->get();
            if (request('tahun')<=0)
            {
            $tahun_filter=date('Y');
            }
            else
            {
            $tahun_filter = request('tahun');
            }
            if (request('bulan')<=0)
            {
                $bulan_filter=date('m');
            }
            else
            {
                $bulan_filter = request('bulan');
            }

            return view('poin.list',['dataKabkota'=>$Kabkota,'dataTahun'=>$data_tahun,'tahun'=>$tahun_filter,'unit'=>request('unit'),'dataBulan'=>$data_bulan]);
        }
        else
        {
            return view('error.aksesditolak');
        }
    }
    public function MasterKegiatan()
    {
        if (Auth::user()->level > 5)
        {
            $time_start = microtime(true);
            $data_bulan = array(
                1=>'Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'
            );
            $data_bulan_panjang = array(
                1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'
            );
            $Kabkota = UnitKerja::where([['unit_jenis','=','2'],['unit_eselon','=','3']])->get();
            $data_tahun = DB::table('m_keg')
            ->selectRaw('year(keg_end) as tahun')
            ->groupBy('tahun')
            //->whereYear('keg_end','<=',NOW())
            ->orderBy('tahun','asc')
              ->get();
            if (request('tahun')<=0)
            {
            $tahun_filter=date('Y');
            }
            else
            {
            $tahun_filter = request('tahun');
            }
            if (request('bulan')<=0)
            {
                $bulan_filter=date('m');
                $awal = 0;
            }
            else
            {
                $awal = 1;
                $bulan_filter = request('bulan');
            }
            if ($awal > 0)
            {
            //ambil semua kegiatan berdasarkan tahun
            //generate nilai berdasarkan kabkotanya
            $datakeg = Kegiatan::whereMonth('keg_end','=',$bulan_filter)->whereYear('keg_end','=',$tahun_filter)->get();
            //dd($datakeg);
            foreach ($datakeg as $item)
            {
                //jika ada kegiatan spj eksekusi dulu baru kegiatannya
                //dd($item);
                //dd($item->Target->where('keg_t_target','>','0'));
                if ($item->keg_spj == 1)
                {
                    //ada spj
                    //eksekusi spj dulu
                    //$Kabkota = UnitKerja::where([['unit_jenis','=','2'],['unit_eselon','=','3']])->get();
                    foreach ($item->Target->where('keg_t_target','>','0') as $unitkerja)
                    {
                        //$nilai = '';
                        $nilai = Generate::NilaiSpjRealisasi($item->keg_id,$unitkerja->keg_t_unitkerja);
                        //update nilai KegTarget
                        $dataNilaiSpj = SpjTarget::where([
                            ['keg_id',$item->keg_id],
                            ['spj_t_unitkerja',$unitkerja->keg_t_unitkerja],
                        ])->first();
                        if ($dataNilaiSpj)
                        {
                            $dataNilaiSpj->spj_t_point_waktu = $nilai['nilai_waktu'];
                            $dataNilaiSpj->spj_t_point_jumlah = $nilai['nilai_volume'];
                            $dataNilaiSpj->spj_t_point = $nilai['nilai_total'];
                            $dataNilaiSpj->update();
                            $poin_spj = $nilai['nilai_total'];
                        }
                        else
                        {
                            $poin_spj = 0;
                        }
                        //nilai spj ini diupdate sama dgn di keg_t_target
                        $dataNilaiKegSpj = KegTarget::where([
                            ['keg_id',$item->keg_id],
                            ['keg_t_unitkerja',$unitkerja->keg_t_unitkerja],
                        ])->first();
                        $dataNilaiKegSpj->spj_t_point_waktu = $nilai['nilai_waktu'];
                        $dataNilaiKegSpj->spj_t_point_jumlah = $nilai['nilai_volume'];
                        $dataNilaiKegSpj->spj_t_point = $nilai['nilai_total'];
                        $dataNilaiKegSpj->keg_t_point_total = ($nilai['nilai_total'] + $dataNilaiKegSpj->keg_t_point)/2;
                        $dataNilaiKegSpj->update();

                        //update nilai kegiatan
                        //$nilai = '';
                        $nilai_keg = Generate::NilaiKegRealiasi($item->keg_id,$unitkerja->keg_t_unitkerja);
                        //update nilai KegTarget
                        $dataNilaiKeg = KegTarget::where([
                            ['keg_id',$item->keg_id],
                            ['keg_t_unitkerja',$unitkerja->keg_t_unitkerja],
                        ])->first();
                        $dataNilaiKeg->keg_t_point_waktu = $nilai_keg['nilai_waktu'];
                        $dataNilaiKeg->keg_t_point_jumlah = $nilai_keg['nilai_volume'];
                        $dataNilaiKeg->keg_t_point = $nilai_keg['nilai_total'];
                        $dataNilaiKeg->keg_t_point_total = ($nilai_keg['nilai_total'] + $poin_spj)/2;
                        $dataNilaiKeg->update();
                    }

                }
                else
                {
                    //kegiatan tanpa ada pengiriman spj
                    //$Kabkota = UnitKerja::where([['unit_jenis','=','2'],['unit_eselon','=','3']])->get();
                    foreach ($item->Target->where('keg_t_target','>','0') as $unitkerja)
                    {
                        //$nilai = '';
                        $nilai = Generate::NilaiKegRealiasi($item->keg_id,$unitkerja->keg_t_unitkerja);
                        //update nilai KegTarget
                        $dataNilai = KegTarget::where([
                            ['keg_id',$item->keg_id],
                            ['keg_t_unitkerja',$unitkerja->keg_t_unitkerja],
                        ])->first();
                        $dataNilai->keg_t_point_waktu = $nilai['nilai_waktu'];
                        $dataNilai->keg_t_point_jumlah = $nilai['nilai_volume'];
                        $dataNilai->keg_t_point = $nilai['nilai_total'];
                        $dataNilai->keg_t_point_total = $nilai['nilai_total'];
                        $dataNilai->update();
                    }
                }
            }
            $time_end = microtime(true);
            $execution_time = ($time_end - $time_start);
                $pesan_error="Data sudah diproses dalam ". (int) $execution_time ." detik";
                $pesan_warna="success";
                Session::flash('message', $pesan_error);
                Session::flash('message_type', $pesan_warna);
            }
            return view('master.kegiatan',['dataKabkota'=>$Kabkota,'dataTahun'=>$data_tahun,'tahun'=>$tahun_filter,'unit'=>request('unit'),'dataBulan'=>$data_bulan,'bulan'=>$bulan_filter,'dataBulanPanjang'=>$data_bulan_panjang]);
        }
        else
        {
            return view('error.aksesditolak');
        }
    }
    public function GenNilaiKeg($bulan,$tahun)
    {
            $time_start = microtime(true);
            $data_bulan = array(
                1=>'Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'
            );
            $data_bulan_panjang = array(
                1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'
            );
            $Kabkota = UnitKerja::where([['unit_jenis','=','2'],['unit_eselon','=','3']])->get();

            //ambil semua kegiatan berdasarkan tahun
            //generate nilai berdasarkan kabkotanya
            $datakeg = Kegiatan::whereMonth('keg_end','=',$bulan)->whereYear('keg_end','=',$tahun)->get();
            //dd($datakeg);
            foreach ($datakeg as $item)
            {
                //jika ada kegiatan spj eksekusi dulu baru kegiatannya
                //dd($item);
                //dd($item->Target->where('keg_t_target','>','0'));
                if ($item->keg_spj == 1)
                {
                    //ada spj
                    //eksekusi spj dulu
                    //$Kabkota = UnitKerja::where([['unit_jenis','=','2'],['unit_eselon','=','3']])->get();
                    foreach ($item->Target->where('keg_t_target','>','0') as $unitkerja)
                    {
                        //$nilai = '';
                        $nilai = Generate::NilaiSpjRealisasi($item->keg_id,$unitkerja->keg_t_unitkerja);
                        //update nilai KegTarget
                        $dataNilaiSpj = SpjTarget::where([
                            ['keg_id',$item->keg_id],
                            ['spj_t_unitkerja',$unitkerja->keg_t_unitkerja],
                        ])->first();
                        if ($dataNilaiSpj)
                        {
                            $dataNilaiSpj->spj_t_point_waktu = $nilai['nilai_waktu'];
                            $dataNilaiSpj->spj_t_point_jumlah = $nilai['nilai_volume'];
                            $dataNilaiSpj->spj_t_point = $nilai['nilai_total'];
                            $dataNilaiSpj->update();
                            $poin_spj = $nilai['nilai_total'];
                        }
                        else
                        {
                            $poin_spj = 0;
                        }
                        //nilai spj ini diupdate sama dgn di keg_t_target
                        $dataNilaiKegSpj = KegTarget::where([
                            ['keg_id',$item->keg_id],
                            ['keg_t_unitkerja',$unitkerja->keg_t_unitkerja],
                        ])->first();
                        $dataNilaiKegSpj->spj_t_point_waktu = $nilai['nilai_waktu'];
                        $dataNilaiKegSpj->spj_t_point_jumlah = $nilai['nilai_volume'];
                        $dataNilaiKegSpj->spj_t_point = $nilai['nilai_total'];
                        $dataNilaiKegSpj->keg_t_point_total = ($nilai['nilai_total'] + $dataNilaiKegSpj->keg_t_point)/2;
                        $dataNilaiKegSpj->update();

                        //update nilai kegiatan
                        //$nilai = '';
                        $nilai_keg = Generate::NilaiKegRealiasi($item->keg_id,$unitkerja->keg_t_unitkerja);
                        //update nilai KegTarget
                        $dataNilaiKeg = KegTarget::where([
                            ['keg_id',$item->keg_id],
                            ['keg_t_unitkerja',$unitkerja->keg_t_unitkerja],
                        ])->first();
                        $dataNilaiKeg->keg_t_point_waktu = $nilai_keg['nilai_waktu'];
                        $dataNilaiKeg->keg_t_point_jumlah = $nilai_keg['nilai_volume'];
                        $dataNilaiKeg->keg_t_point = $nilai_keg['nilai_total'];
                        $dataNilaiKeg->keg_t_point_total = ($nilai_keg['nilai_total'] + $poin_spj)/2;
                        $dataNilaiKeg->update();
                    }
                }
                else
                {
                    //kegiatan tanpa ada pengiriman spj
                    //$Kabkota = UnitKerja::where([['unit_jenis','=','2'],['unit_eselon','=','3']])->get();
                    foreach ($item->Target->where('keg_t_target','>','0') as $unitkerja)
                    {
                        //$nilai = '';
                        $nilai = Generate::NilaiKegRealiasi($item->keg_id,$unitkerja->keg_t_unitkerja);
                        //update nilai KegTarget
                        $dataNilai = KegTarget::where([
                            ['keg_id',$item->keg_id],
                            ['keg_t_unitkerja',$unitkerja->keg_t_unitkerja],
                        ])->first();
                        $dataNilai->keg_t_point_waktu = $nilai['nilai_waktu'];
                        $dataNilai->keg_t_point_jumlah = $nilai['nilai_volume'];
                        $dataNilai->keg_t_point = $nilai['nilai_total'];
                        $dataNilai->keg_t_point_total = $nilai['nilai_total'];
                        $dataNilai->update();
                    }
                }
            }
            $time_end = microtime(true);
            $execution_time = ($time_end - $time_start);
            $pesan_error="Data sudah diproses dalam ". (int) $execution_time ." detik";
            return response($pesan_error);
    }
}
