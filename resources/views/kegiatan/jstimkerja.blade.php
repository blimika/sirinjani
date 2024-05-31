<script>
    //perbaiki role
$("#synctimkerja").click(function (e) {
    e.preventDefault();
    /*
    Swal.fire({
            title: 'Generate Nilai...',
            html: 'Mohon menunggu... <br />sampai pesan ini otomatis tertutup',
            allowEscapeKey: false,
            allowOutsideClick: false,
            onOpen: () => {
            swal.showLoading();
            }
        });
        */
    Swal.fire({
                title: 'Sinkron Unit kerja ke Tim Kerja?',
                text: "semua unitkerja akan disinkron ke tim kerja",
                type: 'question',
                allowEscapeKey: false,
                allowOutsideClick: false,
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Sinkron'
            }).then((result) => {
                if (result.value) {
                    //response ajax disini
                    Swal.fire({
                        title: 'Sinkronisasi Data...',
                        html: 'Mohon menunggu... <br />sampai pesan ini otomatis tertutup',
                        allowEscapeKey: false,
                        allowOutsideClick: false,
                        onOpen: () => {
                        swal.showLoading();
                        }
                    });
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url : '{{route('kegiatan.synctimkerja')}}',
                        method : 'post',
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
                                    'error'
                                );
                            }

                        },
                        error: function(){
                            Swal.fire(
                                'Error',
                                'Koneksi Error',
                                'error'
                            );
                        }

                    });

                }
            })
});
</script>
