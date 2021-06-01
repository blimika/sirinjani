<h3>Sistem Monitoring Kinerja Online v2.0</h3>
<p>Pemberitahuan Pengiriman</p>

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
<br/>
<p><i>Silakan masuk ke sistem <a href="{{route('kegiatan.detil',$objEmail->keg_id)}}" target="_blank">SiRinjani v2.0</a> untuk lebih detil.</i></p>
<p><img src="{{ asset('assets/images/logo-sirinjani.png')}}"  height="40px" /></p>
<br/>
Terimakasih,
