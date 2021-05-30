<script>
$(".hapusnotif").click(function (e) {
    e.preventDefault();
    var idnotif = $(this).data('idnotif');
    var notifdari = $(this).data('notifdari');
    Swal.fire({
                title: 'Akan dihapus?',
                text: "Notifikasi dari "+notifdari+" akan dihapus permanen?",
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
                        url : '{{route('notif.hapus')}}',
                        method : 'post',
                        data: {
                            id: idnotif
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
                                'Koneksi Error ('+idnotif+')',
                                'danger'
                            );
                        }

                    });

                }
            })
});
</script>
