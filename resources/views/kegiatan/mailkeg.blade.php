<h3>Sistem Monitoring Kinerja Online v2.0</h3>
<p>Pemberitahuan Kegiatan Baru</p>

<div>
<p>Detil Kegiatan :<br/>
<b>ID :</b>&nbsp;{{ $objEmail->keg_id}}<br/>
<b>Judul :</b>&nbsp;{{ $objEmail->keg_nama }}<br/>
<b>Jenis :</b>&nbsp;{{ $objEmail->keg_jenis }}<br/>
<b>Target :</b>&nbsp;{{ $objEmail->keg_target }} &nbsp;{{ $objEmail->keg_satuan }}<br/>
<b>Tgl Mulai :</b>&nbsp;{{ $objEmail->keg_tgl_mulai }}<br/>
<b>Tgl Berakhir :</b>&nbsp;{{ $objEmail->keg_tgl_selesai }}<br/>
<b>Subject Matter :</b>&nbsp;{{ $objEmail->keg_sm }}<br/>
<b>Laporan SPJ :</b>&nbsp;{{ $objEmail->keg_spj }}<br/>
</p>
</div>
<div>
    <p>Info Tambahan :<br/>
    <b>Total target semua :</b>&nbsp;{{ $objEmail->keg_total_target}} &nbsp;{{ $objEmail->keg_satuan }}<br/>
    <b>Tanggal dibuat :</b>&nbsp;{{ $objEmail->keg_tgl_dibuat }}<br/>
    <b>Operator Provinsi :</b>&nbsp;{{ $objEmail->keg_operator }}<br/>
</p>
</div>
<br/>
<p><i>Silakan Masuk ke sistem <a href="{{route('kegiatan.detil',$objEmail->keg_id)}}" target="_blank">SiRinjani v2.0</a> untuk lebih detil.</i></p>
<p><img src="{{ asset('images/logo-sirinjani.png')}}"  height="40px" /></p>
<br/>
Terimakasih,
