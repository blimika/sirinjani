<script type="text/javascript">
$('#TambahModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget) // Button that triggered the modal

    //kosongan isian
    $('#TambahModal .modal-footer #reset_unitkerja').on('click', function(e) {
        e.preventDefault();
        $('#TambahModal .modal-body #unit_kode').val("");
        $('#TambahModal .modal-body #unit_nama').val("");
    });
});
    //tombol submit di cek
    $('#TambahModal .modal-footer #simpan_unitkerja').on('click', function(e) {
    e.preventDefault();
    var unit_kode = $('#TambahModal .modal-body #unit_kode').val();
    var unit_nama = $('#TambahModal .modal-body #unit_nama').val();
    //var chek_kode = /^[0-9]+$/;
    if (unit_kode == "")
    {
        $('#TambahModal .modal-body #unit_error').text('Unit kode tidak boleh kosong');
        return false;
    }
    else if (!unit_kode.match(/^[0-9]+$/))
    {
        $('#TambahModal .modal-body #unit_error').text('Unit kode hanya angka/numeric');
        return false;
    }
    else if (unit_kode.length < 5 || unit_kode.length > 5 )
    {
        $('#TambahModal .modal-body #unit_error').text('Unit kode harus 5 digit');
        return false;
    }
    else if (unit_nama == "")
    {
        $('#TambahModal .modal-body #unit_error').text('Nama harus terisi');
        return false;
    }
    else
    {

        //$('#TambahMember .modal-body #formEditMasterPengunjung').submit();
        //ajax responsen
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url : '{{route('unitprov.simpan')}}',
            method : 'post',
            data: {
                unit_kode: unit_kode,
                unit_nama: unit_nama,
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
                        $('#prov').DataTable().ajax.reload(null,false);
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
        //batas
    }
    });

//edit modal
$('#EditModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget) // Button that triggered the modal
    var id = button.data('id')

		$.ajax({
        url : '{{route("unitkerja.cari","")}}/'+id,
        method : 'get',
        cache: false,
        dataType: 'json',
        success: function(data){
            if (data.status == true)
            {
                $('#EditModal .modal-body #edit_teks_id').text("#"+id);
                $('#EditModal .modal-body #edit_unitkode').val(data.hasil.unit_kode);
                $('#EditModal .modal-body #edit_unitnama').val(data.hasil.unit_nama);
                $('#EditModal .modal-body #edit_unitflag').val(data.hasil.unit_flag);
                $('#EditModal .modal-body #edit_id').val(data.hasil.unit_id);
            }
            else
            {
                alert(data.hasil);
            }
        },
        error: function(){
            alert("error load transaksi");
        }
        });

        //klik update
        $('#EditModal .modal-footer #update_unitprov').on('click', function(e) {
        e.preventDefault();
        var edit_id = $('#EditModal .modal-body #edit_id').val();
        var unit_kode = $('#EditModal .modal-body #edit_unitkode').val();
        var unit_nama = $('#EditModal .modal-body #edit_unitnama').val()
        var unit_flag = $('#EditModal .modal-body #edit_unitflag').val()
        if (unit_kode == "")
        {
            $('#EditModal .modal-body #edit_uniterror').text('Unitkode tidak boleh kosong');
            return false;
        }
        else if (!unit_kode.match(/^[0-9]+$/))
        {
            $('#EditModal .modal-body #edit_uniterror').text('Unit kode hanya angka/numeric');
            return false;
        }
        else if (unit_nama == "")
        {
            $('#EditModal .modal-body #edit_uniterror').text('Unit Nama tidak boleh kosong');
            return false;
        }
        else if (unit_flag == "")
        {
            $('#EditModal .modal-body #edit_uniterror').text('Silakan pilih salah satu flag');
            return false;
        }
        else
        {
            //ajax update isian
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url : '{{route('unitprov.updatedata')}}',
                method : 'post',
                data: {
                    id: id,
                    unit_nama: unit_nama,
                    unit_kode: unit_kode,
                    unit_flag: unit_flag,
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
                            $('#prov').DataTable().ajax.reload(null,false);
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
            //batas ajax
    }
});
        //batas klik update
    });
//edit modal
</script>
