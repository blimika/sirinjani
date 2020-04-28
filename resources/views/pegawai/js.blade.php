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
</script>