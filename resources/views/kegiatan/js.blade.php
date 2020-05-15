<script>
jQuery('#tanggal_kegiatan').datepicker({
    format: 'yyyy-mm-dd',
    toggleActive: true,
    todayHighlight: true
});

$('#spj1').on('click change', function(e) {
    $('.spj').prop('readonly', false);
    $('.spj').prop('required', true);
});

$('#spj2').on('click change', function(e) {
    $('.spj').prop('readonly', true);
    $('.spj').prop('required',false);
});
$('#simpan').on('click', function(e) {
    e.preventDefault();
    var target_kabkota=0;
    var total_target = $('#keg_total_target').val();
    $(".target_kabkota").each(function(){
        target_kabkota+=Number($(this).val());
    });
    var keg_nama = $('#keg_nama').val();
    var keg_unitkerja = $('#keg_unitkerja').val();
    var keg_jenis = $('#keg_jenis').val();
    var keg_start = $('#keg_start').val();
    var keg_end = $('#keg_end').val();
    var keg_satuan = $('#keg_satuan').val();
    if (keg_nama=="")
    {
        Swal.fire({
            type: 'error',
            title: 'error',
            text: 'Nama kegiatan tidak boleh kosong'
            });
        return false;
    }
    if (keg_unitkerja=="")
    {
        Swal.fire({
            type: 'error',
            title: 'error',
            text: 'Unitkerja silakan dipilih'
            });
       return false;
    }
    if (keg_jenis=="")
    {
        Swal.fire({
            type: 'error',
            title: 'error',
            text: 'Jenis kegiatan silakan dipilih'
            });
       return false;
    }
    //tanggal
    if (keg_start=="")
    {
        Swal.fire({
            type: 'error',
            title: 'error',
            text: 'tanggal awal kegiatan tidak boleh kosong'
            });
       return false;
    }
    if (keg_end=="")
    {
        Swal.fire({
            type: 'error',
            title: 'error',
            text: 'tanggal akhir kegiatan tidak boleh kosong'
            });
       return false;
    }
    if (keg_satuan=="")
    {
        Swal.fire({
            type: 'error',
            title: 'error',
            text: 'satuan kegiatan tidak boleh kosong'
            });
       return false;
    }
    if (total_target=="")
    {
        Swal.fire({
            type: 'error',
            title: 'error',
            text: 'total kegiatan tidak boleh kosong'
            });
       return false;
    }
    if (total_target < 1)
    {
        Swal.fire({
            type: 'error',
            title: 'error',
            text: 'total kegiatan minimal bernilai 1'
            });
       return false;
    }
    if(target_kabkota == total_target)
    {
      /* Swal.fire({
            type: 'success',
            title: 'Sukses',
            text: 'Data berhasil di simpan'
            });
        */
        Swal.fire({
                title: 'Anda yakin?',
                text: "Data kegiatan akan disimpan",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'YA, SIMPAN',
                cancelBUttonText: 'BATAL'
            }).then((result) => {
                if (result.value) {
                    Swal.fire(
                        'Berhasil!',
                        'Data kegiatan sudah disimpan',
                        'success'
                    ).then(function(submit) {
                        $('#keg_form').submit();
                    });
                }
            })
        
    }
    else
    {
       //alert('Jumlah total target tidak sama dengan target kabkota');
       Swal.fire({
            type: 'error',
            title: 'error',
            text: 'Jumlah total target ('+total_target+') tidak sama dengan target kabkota ('+target_kabkota+')'
            });
       return false;
    }
    
});

$('#update').on('click', function(e) {
    e.preventDefault();
    var target_kabkota=0;
    var total_target = $('#keg_total_target').val();
    $(".target_kabkota").each(function(){
        target_kabkota+=Number($(this).val());
    });
    var keg_nama = $('#keg_nama').val();
    var keg_unitkerja = $('#keg_unitkerja').val();
    var keg_jenis = $('#keg_jenis').val();
    var keg_start = $('#keg_start').val();
    var keg_end = $('#keg_end').val();
    var keg_satuan = $('#keg_satuan').val();
    if (keg_nama=="")
    {
        Swal.fire({
            type: 'error',
            title: 'error',
            text: 'Nama kegiatan tidak boleh kosong'
            });
        return false;
    }
    if (keg_unitkerja=="")
    {
        Swal.fire({
            type: 'error',
            title: 'error',
            text: 'Unitkerja silakan dipilih'
            });
       return false;
    }
    if (keg_jenis=="")
    {
        Swal.fire({
            type: 'error',
            title: 'error',
            text: 'Jenis kegiatan silakan dipilih'
            });
       return false;
    }
    //tanggal
    if (keg_start=="")
    {
        Swal.fire({
            type: 'error',
            title: 'error',
            text: 'tanggal awal kegiatan tidak boleh kosong'
            });
       return false;
    }
    if (keg_end=="")
    {
        Swal.fire({
            type: 'error',
            title: 'error',
            text: 'tanggal akhir kegiatan tidak boleh kosong'
            });
       return false;
    }
    if (keg_satuan=="")
    {
        Swal.fire({
            type: 'error',
            title: 'error',
            text: 'satuan kegiatan tidak boleh kosong'
            });
       return false;
    }
    if (total_target=="")
    {
        Swal.fire({
            type: 'error',
            title: 'error',
            text: 'total kegiatan tidak boleh kosong'
            });
       return false;
    }
    if (total_target < 1)
    {
        Swal.fire({
            type: 'error',
            title: 'error',
            text: 'total kegiatan minimal bernilai 1'
            });
       return false;
    }
    if(target_kabkota == total_target)
    {
      /* Swal.fire({
            type: 'success',
            title: 'Sukses',
            text: 'Data berhasil di simpan'
            });
        */
        Swal.fire({
                title: 'Anda yakin?',
                text: "Data kegiatan akan diupdate",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'YA, UPDATE',
                cancelButtonText: 'BATAL'
            }).then((result) => {
                if (result.value) {
                    Swal.fire(
                        'Berhasil!',
                        'Data kegiatan sudah disimpan',
                        'success'
                    ).then(function(submit) {
                        $('#keg_form').submit();
                    });
                }
            })
        
    }
    else
    {
       //alert('Jumlah total target tidak sama dengan target kabkota');
       Swal.fire({
            type: 'error',
            title: 'error',
            text: 'Jumlah total target ('+total_target+') tidak sama dengan target kabkota ('+target_kabkota+')'
            });
       return false;
    }
    
});
</script>