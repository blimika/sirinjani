<table>
    <thead>
        <tr>
            <th colspan="13">Rekap Poin Kabupaten/Kota {{$menurut}} Tahun {{$tahun}}</th>
        </tr>
        <tr>
            <th colspan="13">&nbsp;</th>
        </tr>
        <tr>
            <th colspan="13">Keadaan : {{$waktu}}</th>
        </tr>
        <tr>
            <th rowspan="2">Nama Kabupaten/Kota</th>
            <th colspan="12">Tahun {{$tahun}}</th>
        </tr>
        <tr>
            <th>Januari</th>
            <th>Februari</th>
            <th>Maret</th>
            <th>April</th>
            <th>Mei</th>
            <th>Juni</th>
            <th>Juli</th>
            <th>Agustus</th>
            <th>September</th>
            <th>Oktober</th>
            <th>November</th>
            <th>Desember</th>
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
