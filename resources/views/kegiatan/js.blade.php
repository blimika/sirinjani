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
    $('.spj').prop('required', false);
});
$('#simpan').on('click', function(e) {
    var target_kabkota=0;
    var total_target = $('#keg_total_target').val();
    $(".target_kabkota").each(function(){
        target_kabkota+=parseInt($(this).val());
    });
    var keg_nama = $('#keg_nama').val();
    var keg_unitkerja = $('#keg_unitkerja').val();
    var keg_jenis = $('#keg_jenis').val();
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
    if(target_kabkota == total_target)
    {
      Swal.fire({
            type: 'success',
            title: 'Sukses',
            text: 'Data berhasil di simpan'
            });
      return true;
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