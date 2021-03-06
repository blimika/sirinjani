<script>
    $('#GantiPasswordModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal

    $('#GantiPasswordModal .modal-footer #btn_gantipasswd').prop('disabled',true)
    $('#passwd_baru').on('change paste keyup',function(e){
        var passwd_baru =  e.target.value;
        var passwd_ulangi = $('#GantiPasswordModal .modal-body #passwd_baru_ulangi').val()
        if (passwd_baru != passwd_ulangi)
        {
            $('#GantiPasswordModal .modal-body #pesan_error').text("Password baru dengan ulangi password tidak sama")
            $('#GantiPasswordModal .modal-body #pesan_error').removeClass('text-success').addClass('text-danger');
            $('#GantiPasswordModal .modal-footer #btn_gantipasswd').prop('disabled',true)
        }
        else
        {
            $('#GantiPasswordModal .modal-body #pesan_error').text("Password baru sama")
            $('#GantiPasswordModal .modal-body #pesan_error').removeClass('text-danger').addClass('text-success');
            $('#GantiPasswordModal .modal-footer #btn_gantipasswd').prop('disabled',false)
        }
    });

    $('#passwd_baru_ulangi').on('change paste keyup',function(e){
        var passwd_ulangi =  e.target.value;
        var passwd_baru = $('#GantiPasswordModal .modal-body #passwd_baru').val()
        if (passwd_baru != passwd_ulangi)
        {
            $('#GantiPasswordModal .modal-body #pesan_error').text("Ulangi password baru dengan password baru tidak sama")
            $('#GantiPasswordModal .modal-body #pesan_error').removeClass('text-success').addClass('text-danger');
            $('#GantiPasswordModal .modal-footer #btn_gantipasswd').prop('disabled',true)
        }
        else
        {
            $('#GantiPasswordModal .modal-body #pesan_error').text("Password baru sama")
            $('#GantiPasswordModal .modal-body #pesan_error').removeClass('text-danger').addClass('text-success');
            $('#GantiPasswordModal .modal-footer #btn_gantipasswd').prop('disabled',false)
        }
    });
});
</script>
