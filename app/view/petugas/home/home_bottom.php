<script>
    function tambahmember() {
        $("#tm").modal("show");
        $("#tambah").submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: "<?= base_url("petugas/home/tambah") ?>",
                data: {
                    nama: $("#nama").val(),
                    email: $("#email").val(),
                },
                type: "post",
                success: function() {
                    swal.fire({
                        title: "Email Telah Di Kirimkan Silahkan Aktifkan Akun",
                        icon: "success",
                        toast: true,
                        showConfirmButton: false,
                        position: "top-end",
                        timer: 3000,
                        timerProgressBar: true
                    });
                    $("#tm").modal("hidden");
                },
                error: function(xhr, status, error) {
                    swal.fire({
                        title: "error",
                        icon: "error"
                    })
                }
            });
        });
    }
</script>