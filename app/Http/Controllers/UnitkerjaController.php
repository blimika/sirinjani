<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UnitKerja;
use App\User;
use Carbon\Carbon;
use Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Svg\Tag\Rect;
use App\FlagUmum;

class UnitkerjaController extends Controller
{
    //
    public function CariUnitkerjaLama($jenis,$eselon)
    {
        $count = UnitKerja::where([
            ['unit_jenis','=',$jenis],
            ['unit_eselon','=',$eselon]
        ])->count();
        $arr = array(
            'status'=>false,
            'hasil'=>'Data jenis unit tidak tersedia'
        );
        if ($count > 0)
        {
            //unitkerja
            $data = UnitKerja::where([
                ['unit_jenis','=',$jenis],
                ['unit_eselon','=',$eselon]
            ])->orderBy('unit_kode','asc')->get();
            foreach ($data as $item)
            {
                $hasil[]= array(
                    'unit_id'=>$item->id,
                    'unit_kode'=>$item->unit_kode,
                    'unit_nama'=>$item->unit_nama,
                    'unit_parent'=>$item->unit_parent,
                    'unit_jenis'=>$item->unit_jenis,
                    'unit_eselon'=>$item->unit_eselon,
                    'unit_flag'=>$item->unit_flag
                );
            }
            $arr = array(
                'status'=>true,
                'count'=>$count,
                'hasil'=>$hasil
            );
        }
        return Response()->json($arr);
    }
    public function ListProvinsi()
    {
       return view('unitkerja.prov');
    }
    public function ListKabkota()
    {
        return view('unitkerja.kabkota');
    }
    public function UnitKabkotaPagelist(Request $request)
    {

    }
    public function UnitProvPagelist(Request $request)
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
        $totalRecords = Unitkerja::where('unit_jenis','1')->count();
        //total record searching

        $totalRecordswithFilter =  DB::table('t_unitkerja')
        ->when($searchValue, function ($q) use ($searchValue) {
            return $q->where('unit_nama', 'like', '%' . $searchValue . '%')
                     ->orWhere('unit_kode', 'like', '%' . $searchValue . '%')
                     ->orWhere('unit_parent', 'like', '%' . $searchValue . '%')
                     ->orWhere('unit_eselon', 'like', '%' . $searchValue . '%')
                     ->orWhere('unit_flag', 'like', '%' . $searchValue . '%');
        })
        ->where('unit_jenis',1)->count();

        // Fetch records
        $records = DB::table('t_unitkerja')
            ->leftJoin(DB::raw('(select keg_unitkerja, count(*) as jumlah_keg from m_keg group by keg_unitkerja) as kegiatan'),'kegiatan.keg_unitkerja','t_unitkerja.unit_kode')
            ->leftJoin('t_flag','t_unitkerja.unit_flag','=','t_flag.kode')
            ->when($searchValue, function ($q) use ($searchValue) {
                return $q->where('unit_nama', 'like', '%' . $searchValue . '%')
                         ->orWhere('unit_kode', 'like', '%' . $searchValue . '%')
                         ->orWhere('unit_parent', 'like', '%' . $searchValue . '%')
                         ->orWhere('unit_eselon', 'like', '%' . $searchValue . '%')
                         ->orWhere('unit_flag', 'like', '%' . $searchValue . '%');
            })
            ->select('t_unitkerja.*','kegiatan.*','t_flag.nama')
            ->skip($start)
            ->take($rowperpage)
            ->where('t_unitkerja.unit_jenis','1')
            ->orderBy($columnName, $columnSortOrder)
            ->groupBy('unit_kode')
            ->get();

        $data_arr = array();
        $sno = $start + 1;

        foreach ($records as $record) {
            $id = $record->id;
            $unit_kode = $record->unit_kode;
            $unit_nama = $record->unit_nama;
            $unit_parent = $record->unit_parent;
            $unit_eselon = $record->unit_eselon;
            $jumlah_keg = $record->jumlah_keg;
            $unit_jenis = $record->unit_jenis;
            if ($record->unit_flag == 1) {
                $flag = '<span class="badge badge-success badge-pill">'.$record->nama.'</span>';
            } else {
                $flag = '<span class="badge badge-danger badge-pill">'.$record->nama.'</span>';
            }
            $aksi = '
                <div class="btn-group">
                <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="ti-settings"></i>
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="#" target="_blank" data-toggle="modal" data-target="#EditModal" data-id="' . $record->id . '">Edit</a>
                    <a class="dropdown-item ubahflagunit" href="#" data-id="' . $record->id . '" data-kode="' . $record->unit_kode . '"  data-nama="' . $record->unit_nama . '" data-flag="' . $record->unit_flag . '" data-toggle="tooltip" title="Ubah Flag">Ubah Flag</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item hapusunitkerja" href="#" data-id="' . $record->id . '" data-kode="' . $record->unit_kode . '"  data-nama="' . $record->unit_nama . '" data-flag="' .$record->unit_flag.'" data-jumlahkeg="' .$record->jumlah_keg.'">Hapus Unitkerja</a>

                </div>
            </div>
            ';
            $data_arr[] = array(
                "id" => $id,
                "nomor" => $sno,
                "unit_kode" => $unit_kode,
                "unit_nama" => $unit_nama,
                "unit_parent" => $unit_parent,
                "unit_eselon" => $unit_eselon,
                "unit_flag" => $flag,
                "jumlah_keg" => $jumlah_keg,
                "unit_jenis"=>$unit_jenis,
                "aksi" => $aksi
            );
            $sno++;
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
    public function SimpanUnitProv(Request $request)
    {
        //simpan
        //eselon 3 unit_parent = 52000 default flag 1 (aktif)
        //cek dulu unit_kode
        $data = UnitKerja::where('unit_kode',trim($request->unit_kode))
            ->orWhere('unit_nama',trim($request->unit_nama))
            ->first();
        $arr = array(
            'status' => false,
            'hasil' => 'Error: Unitkode ('.trim($request->unit_kode).') Unitnama ('.trim($request->unit_nama).') sudah tersedia'
        );
        if (!$data)
        {
            //tidak ada tambahkan ke unitkerja
            $data = new UnitKerja();
            $data->unit_kode = trim($request->unit_kode);
            $data->unit_nama = trim($request->unit_nama);
            $data->unit_parent = '52000';
            $data->unit_jenis = 1;
            $data->unit_eselon = 3;
            $data->unit_flag = 1;
            $data->save();
            $arr = array(
                'status' => true,
                'hasil' => 'Data Unitkerja Unitkode ('.trim($request->unit_kode).') & Unitnama ('.trim($request->unit_nama).') berhasil ditambahkan'
            );
        }
        return Response()->json($arr);
    }
    public function UbahFlagUnitProv(Request $request)
    {
        $data = UnitKerja::where('id',trim($request->id))->first();
        $arr = array(
            'status' => false,
            'hasil' => 'Error data Unitkode ('.trim($request->unit_kode).') Unitnama ('.trim($request->unit_nama).') tidak ditemukan'
        );
        if ($data)
        {
            $data->unit_flag = $request->flag_baru;
            $data->update();
            $arr = array(
                'status' => true,
                'hasil' => 'Data Unitkerja Unitkode ('.trim($request->unit_kode).') & Unitnama ('.trim($request->unit_nama).') berhasil diubah dari '.$request->unit_flag.' ke '.$request->flag_baru
            );
        }
        return Response()->json($arr);
    }
    public function HapusUnitProv(Request $request)
    {
        if ($request->jumlah_keg > 0) {
            $arr = array(
                'status' => false,
                'hasil' => 'Error data Unitkode ('.trim($request->unit_kode).') Unitnama ('.trim($request->unit_nama).') masih data kegiatan di tabel master kegiatan'
            );
        }
        else
        {
            $cek = UnitKerja::where('unit_parent',$request->unit_kode)->first();
            if (!$cek)
            {
                $data = UnitKerja::where('id',$request->id)->first();
                $data->delete();
                $arr = array(
                    'status' => true,
                    'hasil' => 'Berhasil data Unitkode ('.trim($request->unit_kode).') Unitnama ('.trim($request->unit_nama).') telah dihapus'
                );
            }
            else
            {
                $arr = array(
                    'status' => false,
                    'hasil' => 'Error data Unitkode ('.trim($request->unit_kode).') Unitnama ('.trim($request->unit_nama).') masih ada unit child dibawahnya'
                );
            }
        }
        return Response()->json($arr);
    }
    public function UpdateUnitProv(Request $request)
    {
        $data = UnitKerja::where('id',trim($request->id))->first();
        $arr = array(
            'status' => false,
            'hasil' => 'Error data Unitkode #'.$request->id.'('.trim($request->unit_kode).') Unitnama ('.trim($request->unit_nama).') tidak ditemukan'
        );
        if ($data)
        {
            $data->unit_kode = trim($request->unit_kode);
            $data->unit_nama = trim($request->unit_nama);
            $data->unit_flag = trim($request->unit_flag);
            $data->update();
            $arr = array(
                'status' => true,
                'hasil' => 'Data Unitkerja Unitkode ('.trim($request->unit_kode).') & Unitnama ('.trim($request->unit_nama).') berhasil diperbaharui'
            );
        }
        return Response()->json($arr);
    }
    public function CariUnitkerja($id)
    {
        $data = Unitkerja::where('id',$id)->first();
        $arr = array(
            'status'=>false,
            'hasil'=>'Data unitkerja tidak tersedia'
        );
        if ($data)
        {
            //unitkerja

            $hasil = array(
                'unit_id'=>$data->id,
                'unit_kode'=>$data->unit_kode,
                'unit_nama'=>$data->unit_nama,
                'unit_parent'=>$data->unit_parent,
                'unit_jenis'=>$data->unit_jenis,
                'unit_eselon'=>$data->unit_eselon,
                'unit_flag'=>$data->unit_flag
            );
            $arr = array(
                'status'=>true,
                'hasil'=>$hasil
            );
        }
        return Response()->json($arr);
    }
    public function EselonIVNonAktif(Request $request)
    {
        $arr = array(
            'status'=>false,
            'hasil'=>'Data unitkerja tidak tersedia'
        );
        if (Auth::User()->role > 4)
        {
            $data = UnitKerja::where('unit_eselon',4)->update(['unit_flag' => 0]);
            if ($data)
            {
                $arr = array(
                    'status'=>true,
                    'hasil'=>'Data unitkerja eselon IV berhasil di non-aktifkan'
                );
            }
            else
            {
                $arr = array(
                    'status'=>false,
                    'hasil'=>'[ERROR] Data unitkerja eselon IV sudah status non-aktif'
                );
            }

        }
        else
        {
            $arr = array(
                'status'=>false,
                'hasil'=>'Anda tidak memiliki akses untuk perintah ini'
            );
        }
        return Response()->json($arr);
    }
}
