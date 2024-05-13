<script>
    $(document).ready(function() {
        var password = document.getElementById("password");
        var toggle = document.getElementById("showpass");
        toggle.addEventListener('click', function() {
            if (password.type === "password") {
                password.type = "text";
                toggle.classList.remove("bi-eye");
                toggle.classList.add("bi-eye-slash");
            } else {
                password.type = "password";
                toggle.classList.remove("bi-eye-slash");
                toggle.classList.add("bi-eye");
            }
        });
        $("#tambahmember").submit(function(e) {
            e.preventDefault();
            var formdata = new FormData();
            formdata.append("nama", $("#nama").val());
            formdata.append("email", $("#email").val());
            formdata.append("password", $("#password").val());

            $.ajax({
                url: "<?= base_url("petugas/member/tambah") ?>",
                data: formdata,
                type: "post",
                processData: false,
                contentType: false,
                success: function() {
                    swal.fire({
                        title: "Member Telah Di Daftarkan",
                        icon: "success",
                        toast: true,
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        position: "top-end"
                    });
                },
                error: function(xhr, status, error) {
                    swal.fire({
                        title: error,
                        icon: "error"
                    });
                }
            })
        })
    });
</script>