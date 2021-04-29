<script>
$('#TambahOperator').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
  $('#TambahOperator .modal-footer #btn_tambahoperator').prop('disabled',true)
    $('#super_wilayah').change(function(){
        var kodewilayah = $('#super_wilayah').val();
        var kodelevel;
        if (kodewilayah == "5200")
        {
            $('#TambahOperator .modal-body #super_unitkode').prop('disabled', false);
            $('#TambahOperator .modal-body #super_level').html("");
            var kodelevel = '<option value="">Pilih Level Akses</option>'
            @foreach ($dataLevel->whereIn('level_id',['1','3','5','9']) as $l)
                kodelevel += '<option value="{{$l->level_id}}">{{$l->level_nama}}</option>';
            @endforeach
            $('#TambahOperator .modal-body #super_level').append(kodelevel);
        }
        else
        {
            $('#TambahOperator .modal-body #super_unitkode').prop('disabled', true);
            $('#TambahOperator .modal-body #super_level').html("");
            var kodelevel = '<option value="">Pilih Level Akses</option>'
            @foreach ($dataLevel->whereIn('level_id',['1','2','4']) as $l)
                kodelevel += '<option value="{{$l->level_id}}">{{$l->level_nama}}</option>';
            @endforeach
            $('#TambahOperator .modal-body #super_level').append(kodelevel);
        }
    });
    $('#cek_username').click(function(){
        var usercek =  $('#super_username').val();
        $.ajax({
        url : '{{route("operator.cek","")}}/'+usercek,
        method : 'get',
        cache: false,
        dataType: 'json',
        success: function(data){
            if (data.status == true)
            {
                //bisa digunakan
                $('#TambahOperator .modal-body #super_error_teks').text("username "+usercek+" bisa digunakan");
                $('#TambahOperator .modal-body #super_error_username').removeClass('text-danger').addClass('text-success');
                var passwd_baru = $('#TambahOperator .modal-body #operator_password').val()
                var passwd_ulangi = $('#TambahOperator .modal-body #operator_ulangi_password').val()
                if (passwd_baru && passwd_ulangi)
                {
                    $('#TambahOperator .modal-footer #btn_tambahoperator').prop('disabled',false)
                }
            }
            else
            {
                //tidak bisa digunakan
                $('#TambahOperator .modal-body #super_error_teks').text("username "+usercek+" tidak bisa digunakan");
                $('#TambahOperator .modal-body #super_error_username').removeClass('text-success').addClass('text-danger');
                $('#TambahOperator .modal-footer #btn_tambahoperator').prop('disabled',true)
            }

        },
        error: function(){
            alert("error");
        }
        });
    });
    $('#operator_password').on('change paste keyup',function(e){
        var passwd_baru =  e.target.value;
        var passwd_ulangi = $('#TambahOperator .modal-body #operator_ulangi_password').val()
        if (passwd_baru)
        {
            if (passwd_baru != passwd_ulangi)
            {
                $('#TambahOperator .modal-body #super_error_password_teks').text("Password baru dengan ulangi password tidak sama")
                $('#TambahOperator .modal-footer #btn_tambahoperator').prop('disabled',true)
            }
            else
            {
                $('#TambahOperator .modal-body #super_error_password_teks').text("Password sama")
                $('#TambahOperator .modal-body #super_error_password').removeClass('text-danger').addClass('text-success');
                $('#TambahOperator .modal-footer #btn_tambahoperator').prop('disabled',false)
            }
        }
        else
        {
            $('#TambahOperator .modal-body #super_error_password_teks').text("Password tidak boleh kosong")
            $('#TambahOperator .modal-body #super_error_password').removeClass('text-success').addClass('text-danger');
            $('#TambahOperator .modal-footer #btn_tambahoperator').prop('disabled',true)
        }
    });

    $('#operator_ulangi_password').on('change paste keyup',function(e){
        var passwd_ulangi =  e.target.value;
        var passwd_baru = $('#TambahOperator .modal-body #operator_password').val()
        if (passwd_baru != passwd_ulangi)
        {
            $('#TambahOperator .modal-body #super_error_password_teks').text("Ulangi password baru dengan password baru tidak sama")
            $('#TambahOperator .modal-body #super_error_password').removeClass('text-success').addClass('text-danger');
            $('#TambahOperator .modal-footer #btn_tambahoperator').prop('disabled',true)
        }
        else
        {
            $('#TambahOperator .modal-body #super_error_password_teks').text("Password sama")
            $('#TambahOperator .modal-body #super_error_password').removeClass('text-danger').addClass('text-success');
            $('#TambahOperator .modal-footer #btn_tambahoperator').prop('disabled',false)
        }
    });
});

$('#TambahOperator').on('hidden.bs.modal', function(e) {
  $(this).find('.modal-body #FormTambahOperator')[0].reset();
});

$('#EditModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
  var opid = button.data('opid')
  $.ajax({
        url : '{{route('operator.cari','')}}/'+opid,
        method : 'get',
        cache: false,
        dataType: 'json',
        success: function(data)
        {
            $('#EditModal .modal-body #edit_super_wilayah').val(data.kodebps)
            $('#EditModal .modal-body #edit_operator_nama').val(data.nama)
            $('#EditModal .modal-body #edit_super_username').val(data.username)
            $('#EditModal .modal-body #edit_operator_email').val(data.email)
            $('#EditModal .modal-body #edit_operator_no_wa').val(data.nohp)
            $('#EditModal .modal-body #edit_operator_id').val(data.id)
            var kodelevel;
            if (data.kodebps == "5200")
            {
                $('#EditModal .modal-body #edit_super_unitkode').prop('disabled', false);
                $('#EditModal .modal-body #edit_super_level').html("");
                var kodelevel = '<option value="">Pilih Level Akses</option>'
                @foreach ($dataLevel->whereIn('level_id',['1','3','5','9']) as $l)
                    kodelevel += '<option value="{{$l->level_id}}">{{$l->level_nama}}</option>';
                @endforeach
                $('#EditModal .modal-body #edit_super_level').append(kodelevel);
                $('#EditModal .modal-body #edit_super_unitkode').val(data.kodeunit)
            }
            else
            {
                $('#EditModal .modal-body #edit_super_unitkode').prop('disabled', true);
                $('#EditModal .modal-body #edit_super_level').html("");
                var kodelevel = '<option value="">Pilih Level Akses</option>'
                @foreach ($dataLevel->whereIn('level_id',['1','2','4']) as $l)
                    kodelevel += '<option value="{{$l->level_id}}">{{$l->level_nama}}</option>';
                @endforeach
                $('#EditModal .modal-body #edit_super_level').append(kodelevel);
            }
            $('#EditModal .modal-body #edit_super_level').val(data.level)
            $('#EditModal .modal-body #edit_super_username').prop('readonly', true);
        },
        error: function(){
            alert(data.hasil);
        }

    });
    $('#edit_super_wilayah').change(function(){
        var kodewilayah = $('#edit_super_wilayah').val();
        var kodelevel;
        if (kodewilayah == "5200")
            {
                $('#EditModal .modal-body #edit_super_unitkode').prop('disabled', false);
                $('#EditModal .modal-body #edit_super_level').html("");
                var kodelevel = '<option value="">Pilih Level Akses</option>'
                @foreach ($dataLevel->whereIn('level_id',['1','3','5','9']) as $l)
                    kodelevel += '<option value="{{$l->level_id}}">{{$l->level_nama}}</option>';
                @endforeach
                $('#EditModal .modal-body #edit_super_level').append(kodelevel);
        }
        else
        {
            $('#EditModal .modal-body #edit_super_unitkode').prop('disabled', true);
            $('#EditModal .modal-body #edit_super_level').html("");
            var kodelevel = '<option value="">Pilih Level Akses</option>'
            @foreach ($dataLevel->whereIn('level_id',['1','2','4']) as $l)
                kodelevel += '<option value="{{$l->level_id}}">{{$l->level_nama}}</option>';
            @endforeach
            $('#EditModal .modal-body #edit_super_level').append(kodelevel);
        }
    });
});
$('#EditModal').on('hidden.bs.modal', function(e) {
  $(this).find('.modal-body #formEditOperator')[0].reset();
});

$(".flagliatckp").click(function (e) {
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
                title: 'Edit Flag Liat CKP?',
                text: "Flag akan dibuah ke "+flagtext,
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
                        url : '{{route('operator.flagliatckp')}}',
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
   
</script>
