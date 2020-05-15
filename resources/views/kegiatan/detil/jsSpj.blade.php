<script>
$(".hapuskirimspj").click(function (e) {
    e.preventDefault();
    var id = $(this).data('kegrid');
    var nama = $(this).data('nama');    
    Swal.fire({
                title: 'Akan dihapus?',
                text: "Realisasi "+nama+" akan dihapus permanen. setelah dihapus tidak bisa dikembalikan.",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'YA, HAPUS',
                cancelButtonText: 'BATAL'
            }).then((result) => {
                if (result.value) {
                    //response ajax disini
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url : '{{route('spj.hapuspengiriman')}}',
                        method : 'post',
                        data: {
                            id: id
                        },
                        cache: false,
                        dataType: 'json',
                        success: function(data){
                            if (data.status == true)
                            {
                                Swal.fire(
                                    'Berhasil!',
                                    ''+data.hasil+'',
                                    'success'
                                ).then(function() {
                                    location.reload();
                                });
                            }
                            else
                            {
                                Swal.fire(
                                    'Error!',
                                    ''+data.hasil+'',
                                    'danger'
                                ); 
                            }
                            
                        },
                        error: function(){
                            Swal.fire(
                                'Error',
                                'Koneksi Error',
                                'danger'
                            );
                        }

                    });
                   
                }
            })
});

$('#PengirimanSpjModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
  var kegid = button.data('kegid')
  var kode_kabkota = button.data('kabkota')
  var nama_kabkota = button.data('kabkotanama')
  var target_kabkota = button.data('targetkabkota')
  var tglstart = button.data('tglstart')
  $.ajax({
        url : '{{route('kegiatan.cari','')}}/'+kegid,
        method : 'get',
        cache: false,
        dataType: 'json',
        success: function(data){
           if (data.status == true)
           {
            $('#PengirimanSpjModal .modal-body #keg_nama').text(data.keg_nama)
            $('#PengirimanSpjModal .modal-body #sm').text(data.keg_unitkerja_nama)
            $('#PengirimanSpjModal .modal-body #kabkota').text(nama_kabkota)
            $('#PengirimanSpjModal .modal-body #keg_end').text(data.keg_end_nama)
            $('#PengirimanSpjModal .modal-body #keg_target').text(target_kabkota+" SPJ")
            $('#PengirimanSpjModal .modal-body #keg_id').val(kegid)
            $('#PengirimanSpjModal .modal-body #spj_r_unitkerja').val(kode_kabkota)
           }
           else
           {
             alert(data.hasil);
           }
        },
        error: function(){
            alert("error: koneksi");
        }

    });
$(".tglkirimspj").datepicker({
    autoclose: true,
    language: 'id',
    format: 'yyyy-mm-dd',
    toggleActive: true,
    todayHighlight: true,
    startDate: tglstart
}).on('show.bs.modal', function(event) {
    // prevent datepicker from firing bootstrap modal "show.bs.modal"
    event.stopPropagation();
});
});

$('#EditPengirimanSpjModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
  var spjrid = button.data('spjrid')
  var target_kabkota = button.data('targetkabkota')
  var tglstart = button.data('tglstart')
  var tglkirim = button.data('tglkirim')
  $.ajax({
        url : '{{route('spjrealisasi.cari','')}}/'+spjrid,
        method : 'get',
        cache: false,
        dataType: 'json',
        success: function(data){
           if (data.status == true)
           {
            $('#EditPengirimanSpjModal .modal-body #keg_nama').text(data.keg_nama)
            $('#EditPengirimanSpjModal .modal-body #sm').text(data.keg_unitkerja_nama)
            $('#EditPengirimanSpjModal .modal-body #kabkota').text(data.spj_r_unitkerja_nama)
            $('#EditPengirimanSpjModal .modal-body #keg_end').text(data.keg_end_nama)
            $('#EditPengirimanSpjModal .modal-body #keg_target').text(target_kabkota+" "+data.keg_satuan)
            $('#EditPengirimanSpjModal .modal-body #spj_r_id').val(data.spj_r_id)
            $('#EditPengirimanSpjModal .modal-body #keg_id').val(data.keg_id)
            $('#EditPengirimanSpjModal .modal-body #spj_r_tgl').val(data.spj_r_tgl)
            $('#EditPengirimanSpjModal .modal-body #spj_r_jumlah').val(data.spj_r_jumlah)
            $('#EditPengirimanSpjModal .modal-body #spj_r_ket').val(data.spj_r_ket)
            $('#EditPengirimanSpjModal .modal-body #spj_r_link').val(data.spj_r_link)
            $('#EditPengirimanSpjModal .modal-body #spj_r_unitkerja').val(data.spj_r_unitkerja)
           }
           else
           {
             alert(data.hasil);
           }
        },
        error: function(){
            alert("error: koneksi");
        }

    });
$(".tglkirimspj").datepicker({
    autoclose: true,
    format: 'yyyy-mm-dd',
    toggleActive: true,
    todayHighlight: true,
    startDate: tglstart
}).on('show.bs.modal', function(event) {
    // prevent datepicker from firing bootstrap modal "show.bs.modal"
    event.stopPropagation();
});
});

$('#PenerimaanSpjModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
  var kegid = button.data('kegid')
  var kode_kabkota = button.data('kabkota')
  var nama_kabkota = button.data('kabkotanama')
  var target_kabkota = button.data('targetkabkota')
  var totalkirim = button.data('totalkirim')
  var tglstart = button.data('tglstart')
  $.ajax({
        url : '{{route('kegiatan.cari','')}}/'+kegid,
        method : 'get',
        cache: false,
        dataType: 'json',
        success: function(data){
           if (data.status == true)
           {
            $('#PenerimaanSpjModal .modal-body #keg_nama').text(data.keg_nama)
            $('#PenerimaanSpjModal .modal-body #sm').text(data.keg_unitkerja_nama)
            $('#PenerimaanSpjModal .modal-body #kabkota').text(nama_kabkota)
            $('#PenerimaanSpjModal .modal-body #keg_end').text(data.keg_end_nama)
            $('#PenerimaanSpjModal .modal-body #keg_target').text(target_kabkota+" SPJ")
            $('#PenerimaanSpjModal .modal-body #total_pengiriman').text(totalkirim+" SPJ")
            $('#PenerimaanSpjModal .modal-body #keg_id').val(kegid)
            $('#PenerimaanSpjModal .modal-body #spj_r_unitkerja').val(kode_kabkota)
           }
           else
           {
             alert(data.hasil);
           }
        },
        error: function(){
            alert("error: koneksi");
        }

    });
$(".tglterimaspj").datepicker({
    autoclose: true,
    format: 'yyyy-mm-dd',
    toggleActive: true,
    todayHighlight: true,
    startDate: tglstart
}).on('show.bs.modal', function(event) {
    // prevent datepicker from firing bootstrap modal "show.bs.modal"
    event.stopPropagation();
});
});

$('#EditPenerimaanSpjModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
  var spjrid = button.data('spjrid')
  var target_kabkota = button.data('targetkabkota')
  var tglstart = button.data('tglstart')
  var tglkirim = button.data('tglkirim')
  $.ajax({
        url : '{{route('spjrealisasi.cari','')}}/'+spjrid,
        method : 'get',
        cache: false,
        dataType: 'json',
        success: function(data){
           if (data.status == true)
           {
            $('#EditPenerimaanSpjModal .modal-body #keg_nama').text(data.keg_nama)
            $('#EditPenerimaanSpjModal .modal-body #sm').text(data.keg_unitkerja_nama)
            $('#EditPenerimaanSpjModal .modal-body #kabkota').text(data.spj_r_unitkerja_nama)
            $('#EditPenerimaanSpjModal .modal-body #keg_end').text(data.keg_end_nama)
            $('#EditPenerimaanSpjModal .modal-body #keg_target').text(target_kabkota+" "+data.keg_satuan)
            $('#EditPenerimaanSpjModal .modal-body #spj_r_id').val(data.spj_r_id)
            $('#EditPenerimaanSpjModal .modal-body #keg_id').val(data.keg_id)
            $('#EditPenerimaanSpjModal .modal-body #spj_r_tgl').val(data.spj_r_tgl)
            $('#EditPenerimaanSpjModal .modal-body #spj_r_jumlah').val(data.spj_r_jumlah)
            $('#EditPenerimaanSpjModal .modal-body #spj_r_ket').val(data.spj_r_ket)
            $('#EditPenerimaanSpjModal .modal-body #spj_r_link').val(data.spj_r_link)
           }
           else
           {
             alert(data.hasil);
           }
        },
        error: function(){
            alert("error: koneksi");
        }

    });
$(".tglterimaspj").datepicker({
    autoclose: true,
    format: 'yyyy-mm-dd',
    toggleActive: true,
    todayHighlight: true,
    startDate: tglstart
}).on('show.bs.modal', function(event) {
    // prevent datepicker from firing bootstrap modal "show.bs.modal"
    event.stopPropagation();
});
});

$(".hapusterimaspj").click(function (e) {
    e.preventDefault();
    var id = $(this).data('kegrid');
    var nama = $(this).data('nama');    
    Swal.fire({
                title: 'Akan dihapus?',
                text: "Realisasi "+nama+" akan dihapus permanen. setelah dihapus tidak bisa dikembalikan.",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'YA, HAPUS',
                cancelButtonText: 'BATAL'
            }).then((result) => {
                if (result.value) {
                    //response ajax disini
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url : '{{route('spj.hapuspenerimaan')}}',
                        method : 'post',
                        data: {
                            id: id
                        },
                        cache: false,
                        dataType: 'json',
                        success: function(data){
                            if (data.status == true)
                            {
                                Swal.fire(
                                    'Berhasil!',
                                    ''+data.hasil+'',
                                    'success'
                                ).then(function() {
                                    location.reload();
                                });
                            }
                            else
                            {
                                Swal.fire(
                                    'Error!',
                                    ''+data.hasil+'',
                                    'danger'
                                ); 
                            }
                            
                        },
                        error: function(){
                            Swal.fire(
                                'Error',
                                'Koneksi Error',
                                'danger'
                            );
                        }

                    });
                   
                }
            })
});
</script>