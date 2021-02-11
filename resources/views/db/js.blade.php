<script>
    $(".btnkosongkandulu").click(function (e) {
    e.preventDefault();
    Swal.fire({
                title: 'Kosongkan Database Sirinjani v2.0?',
                text: "seluruh data pada database SiRinjani v2.0 akan dikosongkan",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Kosongkan'
            }).then((result) => {
               if (result.value) {
                Swal.fire(
                        'Berhasil!',
                        'Menghapus data SiRinjani v2.0',
                        'success'
                    ).then(function() {
                        window.location.href = "{{route('db.kosongkan')}}";
                    });
               }
            })
});

$(".btnkosongkan").click(function (e) {
    e.preventDefault();
    Swal.fire({
        title: 'Kosongkan Database Sirinjani v2.0?',
                text: "seluruh data pada database SiRinjani v2.0 akan dikosongkan",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Kosongkan'
            }).then((result) => {
                if (result.value) {
                    //response ajax disini
                    $.ajax({
                        url : '{{route('db.kosongkan')}}',
                        method : 'get',
                        data: {
                           
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