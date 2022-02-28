<table>
    <thead>
        <tr>
            <th colspan="8">{{$judul}}</th>
        </tr>
        <tr>
            <th colspan="8">&nbsp;</th>
        </tr>
        <tr>
            <th colspan="8">Keadaan : {{$waktu}}</th>
        </tr>
        
        <tr style="background-color: #C6D2D2;color:black;">
            <th>Sub Fungsi</th>
            <th>Keg ID</th>
            <th>Kegiatan</th>
            <th>Tanggal Mulai</th>
            <th>Tanggal Berakhir</th>
            <th>Target</th>
            <th>Dikirim</th>
            <th>Diterima</th>
        </tr>
    </thead>
    <tbody>
    @foreach($data as $row)
    	<tr>
        @foreach ($row as $value)
    	    <td>{{ $value }}</td>
        @endforeach
	</tr>
    @endforeach
    <tr>
        <td colspan="8">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="8">{{$catatan}}</td>
    </tr>
    </tbody>
</table>
