<script>
$('#DetilModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
  var nipbps = button.data('nipbps')
  $.ajax({
        url : '{{route("cari.pegawai","")}}/'+nipbps,
        method : 'get',
        cache: false,
        dataType: 'json',
        success: function(data) 
        {
           if (data.isLokal==1)
           {
               var lokal = 'via lokal akses';
           }
           else 
           {
               var lokal = 'via Community BPS';
           }
           if (data.aktif==1)
           {
               var status = 'Aktif';
           }
           else 
           {
               var status = 'Tidak Aktif';
           }
            $('#DetilModal .modal-body #nama').text(data.nama)
            $('#DetilModal .modal-body #username').text(data.username)
            $('#DetilModal .modal-body #level').text(data.level_nama)
            $('#DetilModal .modal-body #unitnama').text(data.satuankerja)
            $('#DetilModal .modal-body #bpskode').text(data.namaunit)
            $('#DetilModal .modal-body #nip').text('NIP : '+data.nipbps+' / '+data.nipbarupecah)
            $('#DetilModal .modal-body #email').text(data.email)
            $('#DetilModal .modal-body #akses').text(lokal)
            $('#DetilModal .modal-body #status').text(status)
            $('#DetilModal .modal-body #lastip').text(data.lastip)
            $('#DetilModal .modal-body #profil').attr("src",data.urlfoto)
            $('#DetilModal .modal-body #lastlogin').text(data.lastlogin_nama)
        },
        error: function(){
            alert(data.hasil);
        }

    });
});
$('#EditPegModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
  var nipbps = button.data('nipbps')
  $.ajax({
        url : '{{route('cari.pegawai','')}}/'+nipbps,
        method : 'get',
        cache: false,
        dataType: 'json',
        success: function(data) 
        {
            $('#EditPegModal .modal-body #peg_nama').val(data.nama)
            $('#EditPegModal .modal-body #peg_unitkerja').val(data.namaunit)
            $('#EditPegModal .modal-body #peg_nipbps').val(data.nipbps)
            $('#EditPegModal .modal-body #peg_nohp').val(data.nohp)
            $('#EditPegModal .modal-body #peg_id').val(data.peg_id)
            if (data.data_level_jumlah > 0)
            {
                    var jumlah = data.data_level_jumlah;
                    $('#EditPegModal .modal-body #peg_level').html("");
                    $('#EditPegModal .modal-body #peg_level').append('<option value="">Pilih Level Akses</option>');
                    for (i = 0; i < jumlah; i++) {
                        if (data.data_level[i].level_id == data.level)
                        { 
                            $('#EditPegModal .modal-body #peg_level').append('<option value="'+ data.data_level[i].level_id +'" selected>'+ data.data_level[i].level_nama +'</option>');
                        }
                        else 
                        {
                            $('#EditPegModal .modal-body #peg_level').append('<option value="'+ data.data_level[i].level_id +'">'+ data.data_level[i].level_nama +'</option>');
                        }
                        
                    }

            }
        },
        error: function(){
            alert(data.hasil);
        }

    });
});
$(".hapuspegawai").click(function (e) {
    e.preventDefault();
    var id = $(this).data('id');
    var nama = $(this).data('nama');    
    Swal.fire({
                title: 'Akan dihapus?',
                text: "Data "+nama+" akan dihapus permanen",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Ubah'
            }).then((result) => {
                if (result.value) {
                    //response ajax disini
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url : '{{route('pegawai.hapus')}}',
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
$(".flagPegawai").click(function (e) {
    e.preventDefault();
    var id = $(this).data('id');
    var flag = $(this).data('flag');
    if (flag == 1)
    {
        var flagtext = 'Tidak Aktif';
    }
    else
    {
        var flagtext = 'Aktif';
    }
    
    Swal.fire({
                title: 'Edit Flag Pegawai?',
                text: "Flag pegawai akan "+flagtext,
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Ubah'
            }).then((result) => {
                if (result.value) {
                    //response ajax disini
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url : '{{route('pegawai.flag')}}',
                        method : 'post',
                        data: {
                            id: id,
                            flag: flag 
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

$('#EditLokalModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
  var peg_id = button.data('id')
  var unitjenis = button.data('unitjenis')
  $.ajax({
        url : '{{route('cari.pegawai','')}}/'+peg_id,
        method : 'get',
        cache: false,
        dataType: 'json',
        success: function(data) 
        {
            $('#EditLokalModal .modal-body #peg_nama').val(data.nama)
            $('#EditLokalModal .modal-body #peg_level').val(data.level)
            $('#EditLokalModal .modal-body #peg_unitkerja').val(data.namaunit)
            $('#EditLokalModal .modal-body #peg_nohp').val(data.nohp)
            $('#EditLokalModal .modal-body #peg_id').val(data.peg_id)
            $('#EditLokalModal .modal-body #peg_username').val(data.username)
            $('#EditLokalModal .modal-body #wilayah_nama').val(data.kodebps_nama)
            $('#EditLokalModal .modal-body #peg_email').val(data.email)
            $('#EditLokalModal .modal-body #peg_nohp').val(data.nohp)
            if (unitjenis == 1)
            {
                $('#EditLokalModal .modal-body #peg_kodeunit').val(data.kodeunit)
            }
            if (data.data_level_jumlah > 0)
            {
                    var jumlah = data.data_level_jumlah;
                    $('#EditLokalModal .modal-body #peg_level').html("");
                    $('#EditLokalModal .modal-body #peg_level').append('<option value="">Pilih Level Akses</option>');
                    for (i = 0; i < jumlah; i++) {
                        if (data.data_level[i].level_id == data.level)
                        { 
                            $('#EditLokalModal .modal-body #peg_level').append('<option value="'+ data.data_level[i].level_id +'" selected>'+ data.data_level[i].level_nama +'</option>');
                        }
                        else 
                        {
                            $('#EditLokalModal .modal-body #peg_level').append('<option value="'+ data.data_level[i].level_id +'">'+ data.data_level[i].level_nama +'</option>');
                        }
                        
                    }

            }
        },
        error: function(){
            alert(data.hasil);
        }

    });
});

$('#TambahOperator').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
  var nipbps = button.data('nipbps')
  
});

$('#GantiPasswordModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
  var nipbps = button.data('nipbps')
  $.ajax({
        url : '{{route('cari.pegawai','')}}/'+nipbps,
        method : 'get',
        cache: false,
        dataType: 'json',
        success: function(data) 
        {
            $('#GantiPasswordModal .modal-body #peg_nama').val(data.nama)
            $('#GantiPasswordModal .modal-body #peg_id').val(data.peg_id)
        },
        error: function(){
            alert(data.hasil);
        }

    });
    $('#GantiPasswordModal .modal-footer #btn_gantipasswd').prop('disabled',true)
    $('#peg_password_baru').on('change paste keyup',function(e){
        var passwd_baru =  e.target.value;
        var passwd_ulangi = $('#GantiPasswordModal .modal-body #peg_password_baru_ulangi').val()
        if (passwd_baru != passwd_ulangi)
        {
            $('#GantiPasswordModal .modal-body #pesan_error').text("Password baru dengan ulangi password tidak sama")
            $('#GantiPasswordModal .modal-footer #btn_gantipasswd').prop('disabled',true)
        }
        else 
        {
            $('#GantiPasswordModal .modal-body #pesan_error').text("Password sama")
            $('#GantiPasswordModal .modal-footer #btn_gantipasswd').prop('disabled',false)
        }
    });

    $('#peg_password_baru_ulangi').on('change paste keyup',function(e){
        var passwd_ulangi =  e.target.value;
        var passwd_baru = $('#GantiPasswordModal .modal-body #peg_password_baru').val()
        if (passwd_baru != passwd_ulangi)
        {
            $('#GantiPasswordModal .modal-body #pesan_error').text("Ulangi password baru dengan password baru tidak sama")
            $('#GantiPasswordModal .modal-footer #btn_gantipasswd').prop('disabled',true)
        }
        else 
        {
            $('#GantiPasswordModal .modal-body #pesan_error').text("Password sama")
            $('#GantiPasswordModal .modal-footer #btn_gantipasswd').prop('disabled',false)
        }
    });
});
</script>