<table>
    <thead>
        <tr>
            <th colspan="9">{{$judul}}</th>
        </tr>
        <tr>
            <th colspan="9">&nbsp;</th>
        </tr>
        <tr>
            <th colspan="9">Keadaan : {{$waktu}}</th>
        </tr>
        
        <tr style="background-color: #C6D2D2;color:black;">
            <th>No</th>
            <th>Keg ID</th>
            <th>Kegiatan</th>
            <th>Tanggal Mulai</th>
            <th>Tanggal Berakhir</th>
            <th>Target</th>
            <th>Dikirim</th>
            <th>Diterima</th>
            <th>Nilai</th>
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
    </tbody>
</table>
