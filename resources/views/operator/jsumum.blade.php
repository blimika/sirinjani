<script>
$(".flagoperator").click(function (e) {
    e.preventDefault();
    var id = $(this).data('id');
    var flag = $(this).data('flag');
    if (flag == 1)
    {
        var flagtext = 'Tidak Aktif?';
    }
    else
    {
        var flagtext = 'Aktif?';
    }

    Swal.fire({
                title: 'Edit Flag Pegawai?',
                text: "Flag operator akan dibuah ke "+flagtext,
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
                        url : '{{route('operator.flag')}}',
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

$(".hapusoperator").click(function (e) {
    e.preventDefault();
    var id = $(this).data('id');
    var nama = $(this).data('nama');
    Swal.fire({
                title: 'Akan dihapus?',
                text: "Data operator "+nama+" akan dihapus permanen?",
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
                        url : '{{route('operator.hapus')}}',
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
                                'Koneksi Error ('+id+')',
                                'danger'
                            );
                        }

                    });

                }
            })
});
$('#GantiPasswordModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
  var opid = button.data('id')
  $.ajax({
        url : '{{route('operator.cari','')}}/'+opid,
        method : 'get',
        cache: false,
        dataType: 'json',
        success: function(data)
        {
            $('#GantiPasswordModal .modal-body #pass_operator_nama').val(data.nama)
            $('#GantiPasswordModal .modal-body #pass_operator_id').val(data.id)
        },
        error: function(){
            alert(data.hasil);
        }

    });
    $('#GantiPasswordModal .modal-footer #btn_gantipasswd').prop('disabled',true)
    $('#pass_operator_password_baru').on('change paste keyup',function(e){
        var passwd_baru =  e.target.value;
        var passwd_ulangi = $('#GantiPasswordModal .modal-body #pass_password_baru_ulangi').val()
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

    $('#pass_password_baru_ulangi').on('change paste keyup',function(e){
        var passwd_ulangi =  e.target.value;
        var passwd_baru = $('#GantiPasswordModal .modal-body #pass_operator_password_baru').val()
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
$('#GantiPasswordModal').on('hidden.bs.modal', function(e) {
  $(this).find('.modal-body #formGantiPassword')[0].reset();
});

$('#DetilModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
  var idop = button.data('idop')
  $.ajax({
        url : '{{route("operator.cari","")}}/'+idop,
        method : 'get',
        cache: false,
        dataType: 'json',
        success: function(data)
        {
            if (data.aktif == 1)
            {
                var status = "Aktif";
            }
            else
            {
                var status = "Tidak Aktif"
            }
            $('#DetilModal .modal-body #nama').text(data.nama)
            $('#DetilModal .modal-body #username').text(data.username)
            $('#DetilModal .modal-body #level').text("Level : "+data.level_nama)
            $('#DetilModal .modal-body #unitnama').text(data.namaunit)
            $('#DetilModal .modal-body #email').text(data.email)
            $('#DetilModal .modal-body #nowa').text(data.nohp)
            $('#DetilModal .modal-body #status').text(status)
            $('#DetilModal .modal-body #lastip').text(data.lastip)
            $('#DetilModal .modal-body #lastlogin').text(data.lastlogin_nama)
        },
        error: function(){
            alert(data.hasil);
        }

    });
});
</script>
