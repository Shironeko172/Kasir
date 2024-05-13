<script>
    $(document).ready(function() {
        var password_field1 = document.getElementById("newpassword");
        var toggle1 = document.getElementById("showpass1");
        var password_field2 = document.getElementById("repeatpassword");
        var toggle2 = document.getElementById("showpass2");
        toggle1.addEventListener("click", function() {
            if (password_field1.type === "password") {
                password_field1.type = "text";
                toggle1.classList.remove("bi-eye");
                toggle1.classList.add("bi-eye-slash");
            } else {
                password_field1.type = "password";
                toggle1.classList.remove("bi-eye-slash");
                toggle1.classList.add("bi-eye");
            }
        });
        toggle2.addEventListener("click", function() {
            if (password_field2.type === "password") {
                password_field2.type = "text";
                toggle2.classList.remove("bi-eye");
                toggle2.classList.add("bi-eye-slash");
            } else {
                password_field2.type = "password";
                toggle2.classList.remove("bi-eye-slash");
                toggle2.classList.add("bi-eye");
            }
        });
        $("#tambah").submit(function(e) {
            e.preventDefault();
            var newpassword = $("#newpassword").val();
            var repeatpassword = $("#repeatpassword").val();
            var kode = $("#kode").val();
            if (newpassword !== repeatpassword) {
                swal.fire("Password Tidak Sama");
                return;
            }
            $.ajax({
                url: "<?= base_url("member/reset/resetpassword") ?>",
                data: {
                    password: newpassword,
                    kode: kode
                },
                type: "post",
                success: function() {
                    var html = `<div class="container">
                                    <div class="card card-body position-absolute start-50 top-50 translate-middle">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <h1 class="text-center">Akun Sudah Di Daftarkan</h1>
                                            </div>
                                            <div class="col-lg-12">
                                                <h3 class="text-center">Anda Bisa Menutup Page Ini Atau Anda Bisa Masuk Ke Halaman</h3>
                                            </div>
                                            <div class="col-lg-12 d-flex justify-content-center"><a class="h3" href="<?= base_url("member/login") ?>">Login</a></div>
                                        </div>
                                    </div>
                                </div>`;
                    document.body.innerHTML = html;
                },
                error: function(xhr, status, error) {
                    swal.fire({
                        title: "error",
                        icon: "error"
                    })
                }
            });
        });
    });
</script>