<script>
    $('#generate').on('click', function(e) {
        e.preventDefault();
        $('#form_generate').submit();
        Swal.fire({
            title: 'Generate Nilai...',
            html: 'Mohon menunggu... <br />sampai pesan ini otomatis tertutup',
            allowEscapeKey: false,
            allowOutsideClick: false,
            onOpen: () => {
            swal.showLoading();
            }
        });
    });
</script>