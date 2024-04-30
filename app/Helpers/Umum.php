<?php
namespace App\Helpers;

//class Tanggal
class Tanggal {
    public static function Panjang($tgl) {
        $bln_panjang = array(1=>"Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
        $tahun=date("Y",strtotime($tgl));
	    $tgl_=date("j",strtotime($tgl));
	    $bln_indo=date("n",strtotime($tgl));
        $tanggal= $tgl_ .' '.$bln_panjang[$bln_indo].' '.$tahun;
        return $tanggal;
    }

    public static function Pendek($tgl) {
        $bln_panjang = array(1=>"Jan","Feb","Mar","Apr","Mei","Jun","Jul","Agu","Sep","Okt","Nov","Des");
        $tahun=date("Y",strtotime($tgl));
	    $tgl_=date("j",strtotime($tgl));
	    $bln_indo=date("n",strtotime($tgl));
        $tanggal= $tgl_ .' '.$bln_panjang[$bln_indo].' '.$tahun;
        return $tanggal;
    }

    public static function HariPanjang($tgl) {
        $nama_hari_indo = array (0=> "Minggu", 1=> "Senin", 2=> "Selasa", 3=> "Rabu", 4=> "Kamis", 5=> "Jumat", 6=> "Sabtu");
        $bln_panjang = array(1=>"Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
        $tahun=date("Y",strtotime($tgl));
	    $hari=date("w",strtotime($tgl));
	    $tgl_=date("j",strtotime($tgl));
	    $bln_indo=date("n",strtotime($tgl));
        $tanggal= $nama_hari_indo[$hari].', '. $tgl_ .' '.$bln_panjang[$bln_indo].' '.$tahun;
	    return $tanggal;
    }
    public static function HariPendek($tgl) {
        $nama_hari_indo = array (0=> "Min", 1=> "Sen", 2=> "Sel", 3=> "Rab", 4=> "Kam", 5=> "Jum", 6=> "Sab");
        $bln_panjang = array(1=>"Jan","Feb","Mar","Apr","Mei","Jun","Jul","Agu","Sep","Okt","Nov","Des");
        $tahun=date("Y",strtotime($tgl));
	    $hari=date("w",strtotime($tgl));
	    $tgl_=date("j",strtotime($tgl));
	    $bln_indo=date("n",strtotime($tgl));
        $tanggal= $nama_hari_indo[$hari].', '. $tgl_ .' '.$bln_panjang[$bln_indo].' '.$tahun;
	    return $tanggal;
	}
	public static function LengkapPanjang($tgl)
	{
		$bln_panjang = array(1=>"Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
        $tahun=date("Y",strtotime($tgl));
	    $tgl_=date("j",strtotime($tgl));
		$bln_indo=date("n",strtotime($tgl));
		$jam=date("H:i",strtotime($tgl));
        $tanggal= $tgl_ .' '.$bln_panjang[$bln_indo].' '.$tahun.' '.$jam;
        return $tanggal;
	}
	public static function LengkapPendek($tgl)
	{
		$bln_panjang = array(1=>"Jan","Feb","Mar","Apr","Mei","Jun","Jul","Agu","Sep","Okt","Nov","Des");
        $tahun=date("Y",strtotime($tgl));
	    $tgl_=date("j",strtotime($tgl));
		$bln_indo=date("n",strtotime($tgl));
		$jam=date("H:i",strtotime($tgl));
        $tanggal= $tgl_ .' '.$bln_panjang[$bln_indo].' '.$tahun.' '.$jam;
        return $tanggal;
	}
	public static function LengkapHariPanjang($tgl)
	{
		$nama_hari_indo = array (0=> "Minggu", 1=> "Senin", 2=> "Selasa", 3=> "Rabu", 4=> "Kamis", 5=> "Jumat", 6=> "Sabtu");
        $bln_panjang = array(1=>"Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
        $tahun=date("Y",strtotime($tgl));
	    $hari=date("w",strtotime($tgl));
	    $tgl_=date("j",strtotime($tgl));
		$bln_indo=date("n",strtotime($tgl));
		$jam=date("H:i",strtotime($tgl));
        $tanggal= $nama_hari_indo[$hari].', '. $tgl_ .' '.$bln_panjang[$bln_indo].' '.$tahun.' '.$jam;
	    return $tanggal;
	}
	public static function LengkapHariPendek($tgl)
	{
		$nama_hari_indo = array (0=> "Min", 1=> "Sen", 2=> "Sel", 3=> "Rab", 4=> "Kam", 5=> "Jum", 6=> "Sab");
        $bln_panjang = array(1=>"Jan","Feb","Mar","Apr","Mei","Jun","Jul","Agu","Sep","Okt","Nov","Des");
        $tahun=date("Y",strtotime($tgl));
	    $hari=date("w",strtotime($tgl));
	    $tgl_=date("j",strtotime($tgl));
		$bln_indo=date("n",strtotime($tgl));
		$jam=date("H:i",strtotime($tgl));
        $tanggal= $nama_hari_indo[$hari].', '. $tgl_ .' '.$bln_panjang[$bln_indo].' '.$tahun.' '.$jam;
	    return $tanggal;
	}
}
Class Generate {
    public static function Kode($length) {
        $kata='ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $code_gen = '';
        for ($i = 0; $i < $length; $i++) {
            $pos = rand(0, strlen($kata)-1);
            $code_gen .= $kata[$pos];
            }
        return $code_gen;
	}
	public static function NilaiKegRealiasi($kegId,$kabkota)
	{
		$count = \App\KegRealisasi::where([
			['keg_id',$kegId],
			['keg_r_unitkerja',$kabkota],
			['keg_r_jenis','=','2']
			])->count();
		$keg_nilai = array(
			'status'=>false,
			'nilai_waktu'=>0,
			'nilai_volume'=>0,
			'nilai_total'=>0
		);
		if ($count > 0)
		{
			//ada penerimaan
			$data = \App\KegRealisasi::where([
				['keg_id',$kegId],
				['keg_r_unitkerja',$kabkota],
				['keg_r_jenis','=','2']
				])->get();
			$keg_nilai=0;
			$nilai_waktu=0;
			$nilai_volume=0;
			$nilai_vol=0;
			$nilai_wkt=0;
			$target = \App\KegTarget::where([
				['keg_id',$kegId],
				['keg_t_unitkerja',$kabkota],
			])->first();
			foreach ($data as $item)
			{
				$nilai_vol += $item->keg_r_jumlah;
				$target_waktu = new \DateTime($item->MasterKegiatan->keg_end);
				$pengiriman = new \DateTime($item->keg_r_tgl);
				$interval = $pengiriman->diff($target_waktu);
				$int=$interval->format('%r%a');

				if ($int>=1) $nilai_wkt=5;
				elseif ($int>=0) $nilai_wkt=4;
				elseif ($int>=-1) $nilai_wkt=3;
				elseif ($int>=-2) $nilai_wkt=2;
				elseif ($int>=-3) $nilai_wkt=1;
				else $nilai_wkt=0;

				$nilai_waktu+=$nilai_wkt;
			}
			$nilai_waktu=($nilai_waktu/$count);
			$persen_vol=($nilai_vol/$target->keg_t_target)*100;
			if ($persen_vol>94) $nilai_volume=5;
			elseif ($persen_vol>89) $nilai_volume=3;
			elseif ($persen_vol>85) $nilai_volume=1;
			else $nilai_volume=0;
			$nilai_total=($nilai_volume*0.70)+($nilai_waktu*0.30);
			$keg_nilai = array(
				'status'=>true,
				'nilai_waktu'=>$nilai_waktu,
				'nilai_volume'=>$nilai_volume,
				'nilai_total'=>$nilai_total
			);
		}
		return $keg_nilai;
	}
	public static function NilaiSpjRealisasi($kegId,$kabkota)
	{
		$count = \App\SpjRealisasi::where([
			['keg_id',$kegId],
			['spj_r_unitkerja',$kabkota],
			['spj_r_jenis','=','2']
			])->count();
		$spj_nilai = array(
			'status'=>false,
			'nilai_waktu'=>0,
			'nilai_volume'=>0,
			'nilai_total'=>0
		);
		if ($count > 0)
		{
			//ada penerimaan spj
			$data = \App\SpjRealisasi::where([
				['keg_id',$kegId],
				['spj_r_unitkerja',$kabkota],
				['spj_r_jenis','=','2']
				])->get();
			$nilai_waktu=0;
			$nilai_volume=0;
			$nilai_vol=0;
			$nilai_wkt=0;
			$target = \App\SpjTarget::where([
				['keg_id',$kegId],
				['spj_t_unitkerja',$kabkota],
			])->first();
			foreach ($data as $item)
			{
				$nilai_vol += $item->spj_r_jumlah;
				$target_waktu = new \DateTime($item->MasterKegiatan->keg_end);
				$pengiriman = new \DateTime($item->spj_r_tgl);
				$interval = $pengiriman->diff($target_waktu);
				$int=$interval->format('%r%a');

				if ($int>=1) $nilai_wkt=5;
				elseif ($int>=0) $nilai_wkt=4;
				elseif ($int>=-1) $nilai_wkt=3;
				elseif ($int>=-2) $nilai_wkt=2;
				elseif ($int>=-3) $nilai_wkt=1;
				else $nilai_wkt=0;

				$nilai_waktu+=$nilai_wkt;
			}
			$nilai_waktu=($nilai_waktu/$count);
			$persen_vol=($nilai_vol/$target->spj_t_target)*100;
			if ($persen_vol>94) $nilai_volume=5;
			elseif ($persen_vol>89) $nilai_volume=3;
			elseif ($persen_vol>85) $nilai_volume=1;
			else $nilai_volume=0;
			$nilai_total=($nilai_volume*0.70)+($nilai_waktu*0.30);
			$spj_nilai = array(
				'status'=>true,
				'nilai_waktu'=>$nilai_waktu,
				'nilai_volume'=>$nilai_volume,
				'nilai_total'=>$nilai_total
			);
		}
		return $spj_nilai;
	}
	public static function TotalKegiatan($tahun)
	{
		if ($tahun==0)
		{
			//semua tahun
			$data = \App\Kegiatan::count();
		}
		else
		{
			//sesuai kodebps
			$data = \App\Kegiatan::whereYear('keg_start','=',$tahun)->count();
		}
		return $data;
	}
	public static function TotalTargetKegiatan($tahun)
	{
		if ($tahun==0)
		{
			//semua tahun
			$data = \App\Kegiatan::sum('keg_total_target');
		}
		else
		{
			//sesuai kodebps
			$data = \App\Kegiatan::whereYear('keg_start','=',$tahun)->sum('keg_total_target');
		}
		return $data;
	}
	public static function TotalPengiriman($tahun)
	{
		if ($tahun==0)
		{
			//semua tahun
			$data = \App\KegRealisasi::where('keg_r_jenis',1)->sum('keg_r_jumlah');
		}
		else
		{
			//sesuai kodebps
			$data = \App\KegRealisasi::where('keg_r_jenis',1)->whereYear('keg_r_tgl','=',$tahun)->sum('keg_r_jumlah');
		}
		return $data;
	}
	public static function TotalPenerimaan($tahun)
	{
		if ($tahun==0)
		{
			//semua tahun
			$data = \App\KegRealisasi::where('keg_r_jenis',1)->sum('keg_r_jumlah');
		}
		else
		{
			//sesuai kodebps
			$data = \App\KegRealisasi::where('keg_r_jenis',2)->whereYear('keg_r_tgl','=',$tahun)->sum('keg_r_jumlah');
		}
		return $data;
	}
	public static function KegiatanTerbanyak($tahun)
	{
		if ($tahun==0)
		{
			//semua tahun
			$data = \App\Kegiatan::select('keg_unitkerja', \DB::raw('count(*) as total'))->groupBy('keg_unitkerja')->orderBy('total','desc')->first();
		}
		else
		{
			//sesuai kodebps
			$data = \App\Kegiatan::whereYear('keg_start','=',$tahun)->select('keg_unitkerja', \DB::raw('count(*) as total'))->groupBy('keg_unitkerja')->orderBy('total','desc')->first();
		}
		$nama_seksi = $data->Unitkerja->unit_nama;
		$total_keg = $data->total;
		$arr = array(
			'nama_unit'=>$nama_seksi,
			'total_keg'=>$total_keg
		);
		return $arr;
	}
	public static function KegiatanDeadline()
	{
		$data = \App\Kegiatan::whereBetween('keg_end',array(\Carbon\Carbon::now()->format('Y-m-d'), \Carbon\Carbon::now()->addWeek()->format('Y-m-d')))->orderBy('keg_end')->get();
		return $data;
	}
	public static function ChartNilaiBulan($bulan,$tahun)
	{
		/*
		select keg_t_unitkerja, count(*) as keg_jml, sum(m_keg_target.keg_t_target) as keg_jml_target, sum(m_keg_target.keg_t_point_waktu) as point_waktu, sum(m_keg_target.keg_t_point_jumlah) as point_jumlah, sum(m_keg_target.keg_t_point) as point_total, avg(m_keg_target.keg_t_point) as point_rata from m_keg_target,m_keg where m_keg.keg_id=m_keg_target.keg_id and month(m_keg.keg_end)='5' and year(m_keg.keg_end)='2020' and m_keg_target.keg_t_target>0 group by keg_t_unitkerja order by point_rata desc, point_total desc
		*/
		/*
		$data = \DB::table('m_keg')
				->leftJoin('m_keg_target','m_keg.keg_id','=','m_keg_target.keg_id')
				->leftJoin('t_unitkerja','m_keg_target.keg_t_unitkerja','=','t_unitkerja.unit_kode')
				->whereMonth('m_keg.keg_end','=',$bulan)->whereYear('m_keg.keg_end','=',$tahun)
				->where('m_keg_target.keg_t_target','>','0')
				->select(\DB::raw("m_keg_target.keg_t_unitkerja,t_unitkerja.unit_nama, sum(m_keg_target.keg_t_target) as keg_jml_target, sum(m_keg_target.keg_t_point_waktu) as point_waktu, sum(m_keg_target.keg_t_point_jumlah) as point_jumlah, sum(m_keg_target.keg_t_point) as point_total, avg(m_keg_target.keg_t_point) as point_rata, count(*) as keg_jml"))
				->groupBy('m_keg_target.keg_t_unitkerja')
				->orderBy('point_rata','desc')
				->orderBy('keg_jml_target','desc')
                ->orderBy('keg_jml','desc')
                ->orderBy('m_keg_target.keg_t_unitkerja','asc')
				->get();
		*/
		$data = \DB::table('m_keg')
                ->leftJoin('m_keg_target','m_keg.keg_id','=','m_keg_target.keg_id')
                ->leftJoin(\DB::raw("(select unit_kode as unit_kode_prov, unit_nama as unit_nama_prov, unit_parent as unit_parent_prov from t_unitkerja where unit_jenis='1') as unit_prov"),'m_keg.keg_unitkerja','=','unit_prov.unit_kode_prov')
                ->leftJoin('t_unitkerja','m_keg_target.keg_t_unitkerja','=','t_unitkerja.unit_kode')
                ->whereMonth('m_keg.keg_end','=',$bulan)
				->whereYear('m_keg.keg_end','=',$tahun)
				->where('m_keg_target.keg_t_target','>','0')
				->select(\DB::raw("month(m_keg.keg_end) as bulan, year(m_keg.keg_end) as tahun,m_keg_target.keg_t_unitkerja,t_unitkerja.unit_nama, sum(m_keg_target.keg_t_target) as keg_jml_target, sum(m_keg_target.keg_t_point_waktu) as point_waktu, sum(m_keg_target.keg_t_point_jumlah) as point_volume, sum(m_keg_target.keg_t_point) as point_jumlah, avg(m_keg_target.keg_t_point) as point_keg, avg(m_keg_target.spj_t_point) as point_spj, avg(m_keg_target.keg_t_point_total) as point_total, count(*) as keg_jml"))
				->groupBy('m_keg_target.keg_t_unitkerja')
				->orderBy('point_total','desc')
                ->orderBy('keg_jml_target','desc')
                ->orderBy('keg_jml','desc')
                ->orderBy('m_keg_target.keg_t_unitkerja','asc')
                ->get();
		//return $data; number_format($k->point_rata,4,".",",");
		//dd($data);
		foreach ($data as $item) {
			$unit_nama[]=$item->unit_nama;
			$unit_kode[]=$item->keg_t_unitkerja;
			$keg_jml[]=$item->keg_jml;
			$keg_jml_target[]=$item->keg_jml_target;
			$point_waktu[]=$item->point_waktu;
			$point_volume[]=$item->point_volume;
			$point_jumlah[]=$item->point_jumlah;
			$point_keg[]=$item->point_keg;
			$point_spj[]=$item->point_spj;
			$point_total[]=$item->point_total;
			$point_rata[]=number_format($item->point_total,2,".",",");
		}
		$arr = array(
			'unit_nama'=>$unit_nama,
			'unit_kode'=>$unit_kode,
			'keg_jml'=>$keg_jml,
			'keg_jml_target'=>$keg_jml_target,
			'point_waktu'=>$point_waktu,
			'point_volume'=>$point_volume,
			'point_jumlah'=>$point_jumlah,
			'point_keg'=>$point_keg,
			'point_spj'=>$point_spj,
			'point_total'=>$point_total,
			'point_rata'=>$point_rata
		);
		//dd(json_encode($arr));
		return $arr;
	}
	public static function ChartNilaiTahunan($tahun)
	{
		/*
		select keg_t_unitkerja, count(*) as keg_jml, sum(m_keg_target.keg_t_target) as keg_jml_target, sum(m_keg_target.keg_t_point_waktu) as point_waktu, sum(m_keg_target.keg_t_point_jumlah) as point_jumlah, sum(m_keg_target.keg_t_point) as point_total, avg(m_keg_target.keg_t_point) as point_rata from m_keg_target,m_keg where m_keg.keg_id=m_keg_target.keg_id and year(m_keg.keg_end)='2019' and m_keg_target.keg_t_target>0 group by keg_t_unitkerja order by point_rata desc, point_total desc
		*/
		if ($tahun == date('Y'))
		{
			$bulan_filter = date('m');
		}
		else
		{
			$bulan_filter = 12;
		}
		/*
		$data = \DB::table('m_keg')
				->leftJoin('m_keg_target','m_keg.keg_id','=','m_keg_target.keg_id')
				->leftJoin('t_unitkerja','m_keg_target.keg_t_unitkerja','=','t_unitkerja.unit_kode')
				->whereMonth('m_keg.keg_end','<=',$bulan_filter)
				->whereYear('m_keg.keg_end','=',$tahun)
				->where('m_keg_target.keg_t_target','>','0')
				->select(\DB::raw("m_keg_target.keg_t_unitkerja,t_unitkerja.unit_nama, sum(m_keg_target.keg_t_target) as keg_jml_target, sum(m_keg_target.keg_t_point_waktu) as point_waktu, sum(m_keg_target.keg_t_point_jumlah) as point_jumlah, sum(m_keg_target.keg_t_point) as point_total, avg(m_keg_target.keg_t_point) as point_rata, count(*) as keg_jml"))
				->groupBy('m_keg_target.keg_t_unitkerja')
				->orderBy('point_rata','desc')
				->orderBy('keg_jml_target','desc')
                ->orderBy('keg_jml','desc')
                ->orderBy('m_keg_target.keg_t_unitkerja','desc')
				->get();
		*/
		$data = \DB::table('m_keg')
				->leftJoin('m_keg_target','m_keg.keg_id','=','m_keg_target.keg_id')
				->leftJoin(\DB::raw("(select unit_kode as unit_kode_prov, unit_nama as unit_nama_prov, unit_parent as unit_parent_prov from t_unitkerja where unit_jenis='1') as unit_prov"),'m_keg.keg_unitkerja','=','unit_prov.unit_kode_prov')
				->leftJoin('t_unitkerja','m_keg_target.keg_t_unitkerja','=','t_unitkerja.unit_kode')
				->whereMonth('m_keg.keg_end','<=',$bulan_filter)
				->whereYear('m_keg.keg_end','=',$tahun)
				->where('m_keg_target.keg_t_target','>','0')
				->select(\DB::raw("m_keg_target.keg_t_unitkerja,t_unitkerja.unit_nama, sum(m_keg_target.keg_t_target) as keg_jml_target, sum(m_keg_target.keg_t_point_waktu) as point_waktu, sum(m_keg_target.keg_t_point_jumlah) as point_volume, sum(m_keg_target.keg_t_point) as point_jumlah, avg(m_keg_target.keg_t_point) as point_keg, avg(m_keg_target.spj_t_point) as point_spj, avg(m_keg_target.keg_t_point_total) as point_total, count(*) as keg_jml"))
				->groupBy('m_keg_target.keg_t_unitkerja')
				->orderBy('point_total','desc')
				->orderBy('keg_jml_target','desc')
				->orderBy('keg_jml','desc')
				->orderBy('m_keg_target.keg_t_unitkerja','asc')
				->get();
		//return $data; number_format($k->point_rata,4,".",",");
		//dd($data);
		foreach ($data as $item) {
			$unit_nama[]=$item->unit_nama;
			$unit_kode[]=$item->keg_t_unitkerja;
			$keg_jml[]=$item->keg_jml;
			$keg_jml_target[]=$item->keg_jml_target;
			$point_waktu[]=$item->point_waktu;
			$point_volume[]=$item->point_volume;
			$point_jumlah[]=$item->point_jumlah;
			$point_keg[]=$item->point_keg;
			$point_spj[]=$item->point_spj;
			$point_total[]=$item->point_total;
			$point_rata[]=number_format($item->point_total,2,".",",");
		}
		$arr = array(
			'unit_nama'=>$unit_nama,
			'unit_kode'=>$unit_kode,
			'keg_jml'=>$keg_jml,
			'keg_jml_target'=>$keg_jml_target,
			'point_waktu'=>$point_waktu,
			'point_volume'=>$point_volume,
			'point_jumlah'=>$point_jumlah,
			'point_keg'=>$point_keg,
			'point_spj'=>$point_spj,
			'point_total'=>$point_total,
			'point_rata'=>$point_rata
		);
		//dd(json_encode($arr));
		return $arr;
	}
	public static function ChartNilaiRataRata($tahun)
	{
		/*
		select month(m_keg.keg_end) as bulan, year(m_keg.keg_end) as tahun, keg_t_unitkerja, count(*) as keg_jml, sum(m_keg_target.keg_t_target) as keg_jml_target, sum(m_keg_target.keg_t_point_waktu) as point_waktu, sum(m_keg_target.keg_t_point_jumlah) as point_jumlah, sum(m_keg_target.keg_t_point) as point_total, avg(m_keg_target.keg_t_point) as point_rata from m_keg left join m_keg_target on m_keg.keg_id=m_keg_target.keg_id where year(m_keg.keg_end)='2020' and m_keg_target.keg_t_target>0 group by bulan,tahun,keg_t_unitkerja order by bulan asc
		*/
		//get dulu unitkerja
		//get nilai unitkerja perbulan rata-rata

		$data_unit = \App\UnitKerja::where([['unit_eselon','=','3'],['unit_jenis','=','2']])->get();
		/*
		{
			name:,
			data:
		}
		*/

		foreach ($data_unit as $item)
		{
			$unit_nama[$item->unit_kode]= $item->unit_nama;
			/*
			$data = \DB::table('m_keg')
				->leftJoin('m_keg_target','m_keg.keg_id','=','m_keg_target.keg_id')
				->whereYear('m_keg.keg_end','=',$tahun)
				->where('keg_t_unitkerja','=',$item->unit_kode)
				->where('m_keg_target.keg_t_target','>','0')
				->select(\DB::raw("month(m_keg.keg_end) as bulan, year(m_keg.keg_end) as tahun, keg_t_unitkerja, count(*) as keg_jml, sum(m_keg_target.keg_t_target) as keg_jml_target, sum(m_keg_target.keg_t_point_waktu) as point_waktu, sum(m_keg_target.keg_t_point_jumlah) as point_jumlah, sum(m_keg_target.keg_t_point) as point_total, avg(m_keg_target.keg_t_point) as point_rata"))
				->groupBy(['bulan'],['tahun'],['keg_t_unitkerja'])
				->orderBy('bulan','asc')
				->get();
			*/
			$data = \DB::table('m_keg')
				->leftJoin('m_keg_target','m_keg.keg_id','=','m_keg_target.keg_id')
				->whereYear('m_keg.keg_end','=',$tahun)
				->where('keg_t_unitkerja','=',$item->unit_kode)
				->where('m_keg_target.keg_t_target','>','0')
				->select(\DB::raw("month(m_keg.keg_end) as bulan, year(m_keg.keg_end) as tahun, keg_t_unitkerja, count(*) as keg_jml, sum(m_keg_target.keg_t_target) as keg_jml_target, sum(m_keg_target.keg_t_point_waktu) as point_waktu, sum(m_keg_target.keg_t_point_jumlah) as point_volume, sum(m_keg_target.keg_t_point) as point_jumlah, avg(m_keg_target.keg_t_point) as point_keg, avg(m_keg_target.spj_t_point) as point_spj, avg(m_keg_target.keg_t_point_total) as point_total"))
				->groupBy(['bulan'],['tahun'],['keg_t_unitkerja'])
				->orderBy('bulan','asc')
				->get();
			foreach ($data as $row)
			{
				$point_rata[$item->unit_kode][] = number_format($row->point_total,2,".",",");
				$point_total[$item->unit_kode][] = $row->point_total;
			}
		}
		return ['unit_nama'=>$unit_nama,'point_rata'=>$point_rata,'point_total'=>$point_total];
	}
    public static function NilaiCkpBulan($kabkota,$bulan,$tahun)
    {
        $data = \DB::table('m_keg')
                ->leftJoin('m_keg_target','m_keg.keg_id','=','m_keg_target.keg_id')
                ->leftJoin(\DB::raw("(select unit_kode as unit_kode_prov, unit_nama as unit_nama_prov, unit_parent as unit_parent_prov from t_unitkerja where unit_jenis='1') as unit_prov"),'m_keg.keg_unitkerja','=','unit_prov.unit_kode_prov')
                ->leftJoin('t_unitkerja','m_keg_target.keg_t_unitkerja','=','t_unitkerja.unit_kode')
				->where('m_keg_target.keg_t_unitkerja',$kabkota)
                ->whereMonth('m_keg.keg_end','=',(int)$bulan)
				->whereYear('m_keg.keg_end','=',$tahun)
				->where('m_keg_target.keg_t_target','>','0')
				->select(\DB::raw("month(m_keg.keg_end) as bulan, year(m_keg.keg_end) as tahun,m_keg_target.keg_t_unitkerja,t_unitkerja.unit_nama, sum(m_keg_target.keg_t_target) as keg_jml_target, sum(m_keg_target.keg_t_point_waktu) as point_waktu, sum(m_keg_target.keg_t_point_jumlah) as point_volume, sum(m_keg_target.keg_t_point) as point_jumlah, avg(m_keg_target.keg_t_point) as point_keg, avg(m_keg_target.spj_t_point) as point_spj,sum(m_keg_target.keg_t_point_total) as point_total, avg(m_keg_target.keg_t_point_total) as point_rata, count(*) as keg_jml"))
				->groupBy('m_keg_target.keg_t_unitkerja')
				->orderBy('point_rata','desc')
                ->first();
        //dd($data);
		if ($data)
		{
			if ($data->point_rata)
			{
				$nilai_point = number_format($data->point_rata,2,".",",");
			}
			else
			{
				$nilai_point = 0;
			}

			/*
			1	5.00-4.50	99-97
			2	4.49-4.00	96-94
			3	3.99-3.50	93-91
			4	3.49-3.00	90-88
			5	2.99-2.50	87-85
			6	< 2.50	    84-80
			*/
			if ($nilai_point == 5)
				{
					$nilai_ckp = 99;
				}
			elseif ($nilai_point < 5 and $nilai_point >= 4.5)
				{
					$nilai_rill = $nilai_point - 4.5;
					$nilai_rill = $nilai_rill/0.5;
					$nilai_rill = $nilai_rill*2;
					$nilai_ckp = 97 + $nilai_rill;
				}
			elseif ($nilai_point == 4.49)
			{
				$nilai_ckp = 96;
			}
			elseif ($nilai_point < 4.49  and $nilai_point >= 4)
			{
				$nilai_rill = $nilai_point - 4;
				$nilai_rill = $nilai_rill/0.4;
				$nilai_rill = $nilai_rill*2;
				$nilai_ckp = 94 + $nilai_rill;
			}
			elseif ($nilai_point == 3.99)
			{
				$nilai_ckp = 93;
			}
			elseif ($nilai_point < 3.99  and $nilai_point >= 3.50)
			{
				$nilai_rill = $nilai_point - 3.5;
				$nilai_rill = $nilai_rill/0.4;
				$nilai_rill = $nilai_rill*2;
				$nilai_ckp = 91 + $nilai_rill;
			}
			elseif ($nilai_point == 3.49)
			{
				$nilai_ckp = 90;
			}
			elseif ($nilai_point < 3.49  and $nilai_point >= 3)
			{
				$nilai_rill = $nilai_point - 3;
				$nilai_rill = $nilai_rill/0.4;
				$nilai_rill = $nilai_rill*2;
				$nilai_ckp = 88 + $nilai_rill;
			}
			elseif ($nilai_point == 2.99)
			{
				$nilai_ckp = 87;
			}
			elseif ($nilai_point < 2.99  and $nilai_point >= 2.5)
			{
				$nilai_rill = $nilai_point - 2.5;
				$nilai_rill = $nilai_rill/0.4;
				$nilai_rill = $nilai_rill*2;
				$nilai_ckp = 85 + $nilai_rill;
			}
			elseif ($nilai_point == 2.49)
			{
				$nilai_ckp = 84;
			}
			elseif ($nilai_point < 2.49 and $nilai_point > 0)
			{
				/*
				5.0-4.5=0.5  (R1-R2=R) 2,49 – 2  = 0,49
				99-97=2   (C1-C2=C)  84 – 80 = 4
				4.7-4.5=0.2  (NS-R2 = A) 2 – 0 = 2
				0.2/0.5=0.4   (A/R = B) 2 / 0,49
				0.4/2=0.8  (B/C = D)
				97+0.8=97.8  (C2+D = CKP)
				*/
				$nilai_rill = $nilai_point;
				$nilai_rill = $nilai_rill/2.49;
				$nilai_rill = $nilai_rill*4;
				$nilai_ckp = 80 + $nilai_rill;
			}
			else
			{
				$nilai_ckp = $nilai_point;
			}
		}
		else
		{
			$nilai_point = 0;
			$nilai_ckp = $nilai_point;
		}

        $nilai_ckp = number_format($nilai_ckp,2,",",".");
        $nilai_point = number_format($nilai_point,2,",",".");
        $arr = array(
			'nilai_point'=>$nilai_point,
			'nilai_ckp'=>$nilai_ckp,
		);
        return $arr;
    }
	public static function ListNilaiTotal($kabkota,$bulan,$tahun)
	{
		$data = \DB::table('m_keg')
                ->leftJoin('m_keg_target','m_keg.keg_id','=','m_keg_target.keg_id')
                ->leftJoin(\DB::raw("(select unit_kode as unit_kode_prov, unit_nama as unit_nama_prov, unit_parent as unit_parent_prov from t_unitkerja where unit_jenis='1') as unit_prov"),'m_keg.keg_unitkerja','=','unit_prov.unit_kode_prov')
                ->leftJoin('t_unitkerja','m_keg_target.keg_t_unitkerja','=','t_unitkerja.unit_kode')
				->where('m_keg_target.keg_t_unitkerja',$kabkota)
                ->whereMonth('m_keg.keg_end','=',(int)$bulan)
				->whereYear('m_keg.keg_end','=',$tahun)
				->where('m_keg_target.keg_t_target','>','0')
				->select(\DB::raw("month(m_keg.keg_end) as bulan, year(m_keg.keg_end) as tahun,m_keg_target.keg_t_unitkerja,t_unitkerja.unit_nama, sum(m_keg_target.keg_t_target) as keg_jml_target, sum(m_keg_target.keg_t_point_waktu) as point_waktu, sum(m_keg_target.keg_t_point_jumlah) as point_keg_jumlah, sum(m_keg_target.keg_t_point) as point_keg_total, avg(m_keg_target.keg_t_point) as point_keg, avg(m_keg_target.spj_t_point) as point_spj, avg(m_keg_target.keg_t_point_total) as point_total, count(*) as keg_jml"))
				->groupBy('m_keg_target.keg_t_unitkerja')
				->orderBy('point_total','desc')
                ->first();
		//dd($data);
		if ($data)
		{
			if ($data->point_total)
			{
				$nilai_total = number_format($data->point_total,2,".",",");
			}
			else
			{
				$nilai_total = 0;
			}
			if ($data->point_keg)
			{
				$nilai_keg = number_format($data->point_keg,2,".",",");
			}
			else
			{
				$nilai_keg = 0;
			}
			if ($data->point_spj)
			{
				$nilai_spj = number_format($data->point_spj,2,".",",");
			}
			else
			{
				$nilai_spj = 0;
			}
		}
        else
		{
			$nilai_total = 0;
			$nilai_keg = 0;
			$nilai_spj = 0;
		}
		$nilai_total = number_format($nilai_total,2,",",".");
		$nilai_keg = number_format($nilai_keg,2,",",".");
		$nilai_spj = number_format($nilai_spj,2,",",".");
        $arr = array(
			'nilai_keg'=>$nilai_keg,
			'nilai_spj'=>$nilai_spj,
			'nilai_total'=>$nilai_total,
		);
        return $arr;
	}
	public static function ListNilaiMenurutFungsi($unitkode,$kabkota,$bulan,$tahun)
	{
		/*
		$data = \DB::table('m_keg')
                ->leftJoin('m_keg_target','m_keg.keg_id','=','m_keg_target.keg_id')
                ->leftJoin(\DB::raw("(select unit_kode as unit_kode_prov, unit_nama as unit_nama_prov, unit_parent as unit_parent_prov from t_unitkerja where unit_jenis='1') as unit_prov"),'m_keg.keg_unitkerja','=','unit_prov.unit_kode_prov')
                ->leftJoin('t_unitkerja','m_keg_target.keg_t_unitkerja','=','t_unitkerja.unit_kode')
				->where('m_keg_target.keg_t_unitkerja',$kabkota)
                ->whereMonth('m_keg.keg_end','=',(int)$bulan)
				->whereYear('m_keg.keg_end','=',$tahun)
				->where('m_keg_target.keg_t_target','>','0')
				->select(\DB::raw("month(m_keg.keg_end) as bulan, year(m_keg.keg_end) as tahun,m_keg_target.keg_t_unitkerja,t_unitkerja.unit_nama, sum(m_keg_target.keg_t_target) as keg_jml_target, sum(m_keg_target.keg_t_point_waktu) as point_waktu, sum(m_keg_target.keg_t_point_jumlah) as point_keg_jumlah, sum(m_keg_target.keg_t_point) as point_keg_total, avg(m_keg_target.keg_t_point) as point_keg, avg(m_keg_target.spj_t_point) as point_spj, avg(m_keg_target.keg_t_point_total) as point_total, count(*) as keg_jml"))
				->groupBy('m_keg_target.keg_t_unitkerja')
				->orderBy('point_total','desc')
                ->first();
				*/
		$data = \DB::table('m_keg')
                ->leftJoin('m_keg_target','m_keg.keg_id','=','m_keg_target.keg_id')
                ->leftJoin(\DB::raw("(select unit_kode as unit_kode_prov, unit_nama as unit_nama_prov, unit_parent as unit_parent_prov from t_unitkerja where unit_jenis='1') as unit_prov"),'m_keg.keg_unitkerja','=','unit_prov.unit_kode_prov')
                ->leftJoin('t_unitkerja','m_keg_target.keg_t_unitkerja','=','t_unitkerja.unit_kode')
                ->where('unit_prov.unit_parent_prov','=',$unitkode)
                ->whereMonth('m_keg.keg_end','=',(int)$bulan)
				->whereYear('m_keg.keg_end','=',(int)$tahun)
				->where('m_keg_target.keg_t_target','>','0')
				->select(\DB::raw("month(m_keg.keg_end) as bulan, year(m_keg.keg_end) as tahun,m_keg_target.keg_t_unitkerja,t_unitkerja.unit_nama, sum(m_keg_target.keg_t_target) as keg_jml_target, sum(m_keg_target.keg_t_point_waktu) as point_waktu, sum(m_keg_target.keg_t_point_jumlah) as point_volume, sum(m_keg_target.keg_t_point) as point_jumlah, avg(m_keg_target.keg_t_point) as point_keg, avg(m_keg_target.spj_t_point) as point_spj, avg(m_keg_target.keg_t_point_total) as point_total, count(*) as keg_jml"))
				->groupBy('m_keg_target.keg_t_unitkerja')
				->orderBy('point_total','desc')
                ->first();
		//dd($data);
		if ($data)
		{
			if ($data->point_total)
			{
				$nilai_total = number_format($data->point_total,2,".",",");
			}
			else
			{
				$nilai_total = 0;
			}
			if ($data->point_keg)
			{
				$nilai_keg = number_format($data->point_keg,2,".",",");
			}
			else
			{
				$nilai_keg = 0;
			}
			if ($data->point_spj)
			{
				$nilai_spj = number_format($data->point_spj,2,".",",");
			}
			else
			{
				$nilai_spj = 0;
			}
		}
        else
		{
			$nilai_total = 0;
			$nilai_keg = 0;
			$nilai_spj = 0;
		}
		$nilai_total = number_format($nilai_total,2,",",".");
		$nilai_keg = number_format($nilai_keg,2,",",".");
		$nilai_spj = number_format($nilai_spj,2,",",".");
        $arr = array(
			'nilai_keg'=>$nilai_keg,
			'nilai_spj'=>$nilai_spj,
			'nilai_total'=>$nilai_total,
		);
        return $arr;
	}
    public static function NotifikasiBelumRead($username)
	{
		$data_count = \App\Notifikasi::where([['notif_untuk',$username],['notif_flag','0']])->count();
		return $data_count;
	}
    public static function Notifikasi5Terakhir($username)
	{
		$data = \App\Notifikasi::where('notif_untuk',$username)->orderBy('notif_flag','asc')->orderBy('created_at','desc')->take(5)->get();
		return $data;
	}
    public static function GetIpAddress()
    {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_REAL_IP']))
            $ipaddress = $_SERVER['HTTP_X_REAL_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }
    public static function GetUserAgent()
    {
        $user_agent = '';
        if (isset($_SERVER['HTTP_USER_AGENT']))
        {
            $user_agent = $_SERVER['HTTP_USER_AGENT'];
        }
        else
        {
            $user_agent = 'TIDAK TAHU';
        }
        return $user_agent;
    }
}

class WebAkses
{
    private $cookie; // cookies
    private $ch; // curl

	// CONSTRUCTOR
	function __construct(){
		$this->cookie = "cookie.txt";
        $this->ch = curl_init();
        $this->tgapi = env('TELEGRAM_BOT_TOKEN');
	}

	// DESTRUCTOR
	function __destruct() {
        if($this->ch) curl_close($this->ch);
    }

    private function connectcurl($ch, $url){

		// set url
        curl_setopt($ch, CURLOPT_URL, $url);

        // set user agent
        curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');

        // return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);


		return $ch;
    }
    public function webinfo()
    {
        $url = 'https://api.telegram.org/bot'.$this->tgapi.'/getWebhookInfo';

		$ch = $this->connectcurl($this->ch, $url);
        $result = curl_exec ($ch);
        $result = json_decode($result, TRUE);
		return $result;
    }
    public function resetwebhook()
    {
        $url = 'https://api.telegram.org/bot'.$this->tgapi.'/deleteWebhook?drop_pending_updates=true';

		$ch = $this->connectcurl($this->ch, $url);
        $result = curl_exec ($ch);
        $result = json_decode($result, TRUE);
		return $result;
    }
    public function setwebhook($alamat_url)
    {
        $url = 'https://api.telegram.org/bot'.$this->tgapi.'/setWebhook?url='.$alamat_url;
        $ch = $this->connectcurl($this->ch, $url);
        $result = curl_exec ($ch);
        $result = json_decode($result, TRUE);
		return $result;
    }
}
