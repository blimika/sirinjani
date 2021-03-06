<script>
$('#TambahOperator').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
  $('#TambahOperator .modal-footer #btn_tambahoperator').prop('disabled',true)
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
                $('#TambahOperator .modal-body #adminprov_error_teks').text("username "+usercek+" bisa digunakan");
                $('#TambahOperator .modal-body #adminprov_error_username').removeClass('text-danger').addClass('text-success');
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
                $('#TambahOperator .modal-body #adminprov_error_teks').text("username "+usercek+" tidak bisa digunakan");
                $('#TambahOperator .modal-body #adminprov_error_username').removeClass('text-success').addClass('text-danger');
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
                $('#TambahOperator .modal-body #adminprov_error_password_teks').text("Password baru dengan ulangi password tidak sama")
                $('#TambahOperator .modal-footer #btn_tambahoperator').prop('disabled',true)
            }
            else
            {
                $('#TambahOperator .modal-body #adminprov_error_password_teks').text("Password sama")
                $('#TambahOperator .modal-body #adminprov_error_password').removeClass('text-danger').addClass('text-success');
                $('#TambahOperator .modal-footer #btn_tambahoperator').prop('disabled',false)
            }
        }
        else
        {
            $('#TambahOperator .modal-body #adminprov_error_password_teks').text("Password tidak boleh kosong")
            $('#TambahOperator .modal-body #adminprov_error_password').removeClass('text-success').addClass('text-danger');
            $('#TambahOperator .modal-footer #btn_tambahoperator').prop('disabled',true)
        }
    });

    $('#operator_ulangi_password').on('change paste keyup',function(e){
        var passwd_ulangi =  e.target.value;
        var passwd_baru = $('#TambahOperator .modal-body #operator_password').val()
        if (passwd_baru != passwd_ulangi)
        {
            $('#TambahOperator .modal-body #adminprov_error_password_teks').text("Ulangi password baru dengan password baru tidak sama")
            $('#TambahOperator .modal-body #adminprov_error_password').removeClass('text-success').addClass('text-danger');
            $('#TambahOperator .modal-footer #btn_tambahoperator').prop('disabled',true)
        }
        else
        {
            $('#TambahOperator .modal-body #adminprov_error_password_teks').text("Password sama")
            $('#TambahOperator .modal-body #adminprov_error_password').removeClass('text-danger').addClass('text-success');
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
            $('#EditModal .modal-body #edit_operator_nama').val(data.nama)
            $('#EditModal .modal-body #edit_adminprov_username').val(data.username)
            $('#EditModal .modal-body #edit_operator_email').val(data.email)
            $('#EditModal .modal-body #edit_operator_no_wa').val(data.nohp)
            $('#EditModal .modal-body #edit_operator_id').val(data.id)
            $('#EditModal .modal-body #edit_adminprov_unitkode').val(data.kodeunit)
            $('#EditModal .modal-body #edit_adminprov_level').val(data.level)
            $('#EditModal .modal-body #edit_adminprov_username').prop('readonly', true);
        },
        error: function(){
            alert(data.hasil);
        }

    });
});
$('#EditModal').on('hidden.bs.modal', function(e) {
  $(this).find('.modal-body #formEditOperator')[0].reset();
});
</script>
