<script>
    $(".hapuskegiatan").click(function (e) {
    e.preventDefault();
    var id = $(this).data('kegid');
    var nama = $(this).data('kegnama');    
    Swal.fire({
                title: 'Akan dihapus?',
                text: "Kegiatan "+nama+" akan dihapus permanen. semua target dan realisasi kegiatan akan terhapus juga.",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus'
            }).then((result) => {
                if (result.value) {
                    //response ajax disini
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url : '{{route('kegiatan.hapus')}}',
                        method : 'post',
                        data: {
                            keg_id: id
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
                                    location.href="{{route('kegiatan.list')}}";
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

$('#PengirimanModal').on('show.bs.modal', function (event) {
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
            $('#PengirimanModal .modal-body #keg_nama').text(data.keg_nama)
            $('#PengirimanModal .modal-body #sm').text(data.keg_unitkerja_nama)
            $('#PengirimanModal .modal-body #kabkota').text(nama_kabkota)
            $('#PengirimanModal .modal-body #keg_end').text(data.keg_end_nama)
            $('#PengirimanModal .modal-body #keg_target').text(target_kabkota+" "+data.keg_satuan)
            $('#PengirimanModal .modal-body #keg_id').val(kegid)
            $('#PengirimanModal .modal-body #keg_r_unitkerja').val(kode_kabkota)
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
$(".tglkirim").datepicker({
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

$('#EditPengirimanModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
  var kegrid = button.data('kegrid')
  var target_kabkota = button.data('targetkabkota')
  var tglstart = button.data('tglstart')
  var tglkirim = button.data('tglkirim')
  $.ajax({
        url : '{{route('realisasi.cari','')}}/'+kegrid,
        method : 'get',
        cache: false,
        dataType: 'json',
        success: function(data){
           if (data.status == true)
           {
            $('#EditPengirimanModal .modal-body #keg_nama').text(data.keg_nama)
            $('#EditPengirimanModal .modal-body #sm').text(data.keg_unitkerja_nama)
            $('#EditPengirimanModal .modal-body #kabkota').text(data.keg_r_unitkerja_nama)
            $('#EditPengirimanModal .modal-body #keg_end').text(data.keg_end_nama)
            $('#EditPengirimanModal .modal-body #keg_target').text(target_kabkota+" "+data.keg_satuan)
            $('#EditPengirimanModal .modal-body #keg_r_id').val(data.keg_r_id)
            $('#EditPengirimanModal .modal-body #keg_id').val(data.keg_id)
            $('#EditPengirimanModal .modal-body #keg_r_tgl').val(data.keg_r_tgl)
            $('#EditPengirimanModal .modal-body #keg_r_jumlah').val(data.keg_r_jumlah)
            $('#EditPengirimanModal .modal-body #keg_r_ket').val(data.keg_r_ket)
            $('#EditPengirimanModal .modal-body #keg_r_link').val(data.keg_r_link)
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
$(".tglkirim").datepicker({
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

$(".hapuskirim").click(function (e) {
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
                confirmButtonText: 'Ya, Hapus'
            }).then((result) => {
                if (result.value) {
                    //response ajax disini
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url : '{{route('pengiriman.hapus')}}',
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

$('#PenerimaanModal').on('show.bs.modal', function (event) {
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
            $('#PenerimaanModal .modal-body #keg_nama').text(data.keg_nama)
            $('#PenerimaanModal .modal-body #sm').text(data.keg_unitkerja_nama)
            $('#PenerimaanModal .modal-body #kabkota').text(nama_kabkota)
            $('#PenerimaanModal .modal-body #keg_end').text(data.keg_end_nama)
            $('#PenerimaanModal .modal-body #keg_target').text(target_kabkota+" "+data.keg_satuan)
            $('#PenerimaanModal .modal-body #total_pengiriman').text(totalkirim+" "+data.keg_satuan)
            $('#PenerimaanModal .modal-body #keg_id').val(kegid)
            $('#PenerimaanModal .modal-body #keg_r_unitkerja').val(kode_kabkota)
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
$(".tglterima").datepicker({
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

$(".hapusterima").click(function (e) {
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
                confirmButtonText: 'Ya, Hapus'
            }).then((result) => {
                if (result.value) {
                    //response ajax disini
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url : '{{route('penerimaan.hapus')}}',
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

$('#EditPenerimaanModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
  var kegrid = button.data('kegrid')
  var target_kabkota = button.data('targetkabkota')
  var tglstart = button.data('tglstart')
  $.ajax({
        url : '{{route('realisasi.cari','')}}/'+kegrid,
        method : 'get',
        cache: false,
        dataType: 'json',
        success: function(data){
           if (data.status == true)
           {
            $('#EditPenerimaanModal .modal-body #keg_nama').text(data.keg_nama)
            $('#EditPenerimaanModal .modal-body #sm').text(data.keg_unitkerja_nama)
            $('#EditPenerimaanModal .modal-body #kabkota').text(data.keg_r_unitkerja_nama)
            $('#EditPenerimaanModal .modal-body #keg_end').text(data.keg_end_nama)
            $('#EditPenerimaanModal .modal-body #keg_target').text(target_kabkota+" "+data.keg_satuan)
            $('#EditPenerimaanModal .modal-body #keg_r_id').val(data.keg_r_id)
            $('#EditPenerimaanModal .modal-body #keg_id').val(data.keg_id)
            $('#EditPenerimaanModal .modal-body #keg_r_tgl').val(data.keg_r_tgl)
            $('#EditPenerimaanModal .modal-body #keg_r_jumlah').val(data.keg_r_jumlah)
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
$(".tglterima").datepicker({
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
</script>