<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UnitKerja;
use App\User;
use App\Helpers\CommunityBPS;
use Carbon\Carbon;
use Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UnitkerjaController extends Controller
{
    //
    public function CariUnitkerja($jenis,$eselon)
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
}
