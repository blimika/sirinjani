<table>
    <thead>
        <tr>
            <th colspan="25">Rekap Poin dan CKP Kabupaten/Kota Tahun {{$tahun}}</th>
        </tr>
        <tr>
            <th colspan="25">&nbsp;</th>
        </tr>
        <tr>
            <th colspan="25" class="text-right">Keadaan : {{$waktu}}</th>
        </tr>
        <tr>
            <th rowspan="3">Nama Kabupaten/Kota</th>
            <th colspan="24">Tahun {{$tahun}}</th>
        </tr>
        <tr>
            <th colspan="2">Januari</th>
            <th colspan="2">Februari</th>
            <th colspan="2">Maret</th>
            <th colspan="2">April</th>
            <th colspan="2">Mei</th>
            <th colspan="2">Juni</th>
            <th colspan="2">Juli</th>
            <th colspan="2">Agustus</th>
            <th colspan="2">September</th>
            <th colspan="2">Oktober</th>
            <th colspan="2">November</th>
            <th colspan="2">Desember</th>
        </tr>
        <tr>
            <th>POIN</th>
            <th>CKP</th>
            <th>POIN</th>
            <th>CKP</th>
            <th>POIN</th>
            <th>CKP</th>
            <th>POIN</th>
            <th>CKP</th>
            <th>POIN</th>
            <th>CKP</th>
            <th>POIN</th>
            <th>CKP</th>
            <th>POIN</th>
            <th>CKP</th>
            <th>POIN</th>
            <th>CKP</th>
            <th>POIN</th>
            <th>CKP</th>
            <th>POIN</th>
            <th>CKP</th>
            <th>POIN</th>
            <th>CKP</th>
            <th>POIN</th>
            <th>CKP</th>
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
