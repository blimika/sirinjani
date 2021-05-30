<script>
$('#ViewNotifikasi').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
  var notif_id = button.data('idnotif')
  $.ajax({
        url : '{{route('notif.get','')}}/'+notif_id,
        method : 'get',
        cache: false,
        dataType: 'json',
        success: function(data){
           if (data.status == true)
           {
            $('#ViewNotifikasi .modal-body #notif_dari').text(data.notif_dari)
            $('#ViewNotifikasi .modal-body #notif_untuk').text(data.notif_untuk)
            $('#ViewNotifikasi .modal-body #notif_jenis').text(data.notif_jenis_nama)
            $('#ViewNotifikasi .modal-body #notif_tgl_dibuat').text(data.notif_created_at_nama)
            $('#ViewNotifikasi .modal-body #notif_tgl_diupdate').text(data.notif_updated_at_nama)
            $('#ViewNotifikasi .modal-body #notif_isi').html(data.notif_isi)
            $('#ViewNotifikasi .modal-footer #ViewKegiatanDetil').attr("href","{{route('kegiatan.detil','')}}/"+data.notif_keg_id)
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
});
</script>
