<script>
    let base_url = "<?= base_url() ?>";

    function edit(kode) {
        $("#modalaction").modal("hide");
        $("#modalform").modal("show");
        $("#modalform").find(".modal-title").text("Tambah Data Produk");
        let modalcontent = `
        <div class="row">
                                <form id="edit" enctype="multipart/form-data">
                                    <div class="col-lg-12 mb-2 d-flex justify-content-center">
                                        <img class="border border-dark" id="output" width="100"/>
                                    </div>
                                    <div class="col-lg-12 mb-3">
                                        <label for="foto" class="form-label">Foto member</label>
                                        <input type="file" class="form-control" id="foto">
                                        <span class="form-text">Jika Tidak Akan Mengganti Abaikan Saja</span>
                                    </div>
                                    <div class="col-lg-12 mb-3">
                                        <label for="nama" class="form-label">nama</label>
                                        <input type="text" class="form-control" id="nama" required placeholder="Contoh: Adam Marwadi">
                                    </div>
                                    <div class="col-lg-12 mb-3">
                                        <label for="alamat" class="form-label">Alamat</label>
                                        <textarea class="form-control" id="alamat" cols="5"></textarea>
                                    </div>
                                    <div class="col-lg-12 mb-3">
                                        <label for="email" class="form-label">email</label>
                                        <input type="email" class="form-control" id="email" required>
                                    </div>
                                    <div class="col-lg-12">
                                        <button type="submit" class="btn btn-primary w-100">Tambahkan</button>
                                    </div>
                                </form>
                            </div>`;
        $("#modalform").find(".modal-body").html(modalcontent);
        var nama = document.getElementById("nama");
        nama.value = "<?= $sess->member->nama ?>";

        var email = document.getElementById("email");
        email.value = "<?= $sess->member->email ?>";

        var alamat = document.getElementById("alamat");
        alamat.value = "<?= $sess->member->alamat ?>";

        var fotto = "<?= $sess->member->foto ?>";
        var defaultfoto = base_url + "<?= $sess->member->foto ?>";
        var output = $("#output");
        var kode = "<?= $sess->member->kode ?>";

        if (fotto) {
            output.attr("src", defaultfoto);
        }

        $("#foto").on("change", function() {
            output.attr("src", URL.createObjectURL(event.target.files[0]));
            output.on("load", function() {
                URL.revokeObjectURL(output.attr("src"));
            });
        });

        $("#edit").submit(function(e) {
            e.preventDefault();
            var foto = document.getElementById("foto");
            var file = foto.files[0];
            var formdata = new FormData();
            formdata.append("nama", $("#nama").val());
            formdata.append("kode", kode);
            formdata.append("alamat", $("#alamat").val());
            formdata.append("email", $("#email").val());
            if (file) {
                var allowedtypes = ["image/jpg", "image/png", "image/jpeg", "image/gif"];
                var maxsize = 4 * 1024 * 1024;
                if (!allowedtypes.includes(file.type)) {
                    swal.fire("Yang Di Izinkan Hanya jpg, jpeg, png, gif");
                    return;
                } else if (file.size > maxsize) {
                    swal.fire("Foto Melebihi Dari 4 Mb");
                    return;
                }
                formdata.append("foto", file);
            } else {
                formdata.append("foto", fotto);
            }

            $.ajax({
                url: base_url + "member/profil/edit",
                data: formdata,
                type: "post",
                processData: false,
                contentType: false,
                success: function() {
                    swal.fire({
                        title: "Berhasil Di Edit",
                        icon: "success",
                        toast: true,
                        timer: 3000,
                        showConfirmButton: false,
                        position: "top-end",
                        timerProgressBar: true
                    }).then((result) => {
                        window.location.reload();
                    });
                    $("#modalform").modal("hide");
                },
                error: function(xhr, status, error) {
                    swal.fire({
                        title: error,
                        icon: "error"
                    })
                }
            });
        })
    }

    function gantipassword(id) {
        $("#modalaction").modal("hide");
        $("#modalform").modal("show");
        $("#modalform").find(".modal-title").text("Ganti Password");
        let modalcontent = `
                            <div class="row">
                                <form id="editpassword" enctype="multipart/form-data">
                                <div class="col-lg-12 mb-3">
                                        <label for="oldpassword" class="form-label">Password Lama</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="oldpassword" required>
                                            <input type="text" class="form-control" id="old" value="<?= $sess->member->password ?>" hidden>
                                            <div class="input-group-text">
                                                <span id="showpass2" class="bi bi-eye"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 mb-3">
                                        <label for="newpassword" class="form-label">Password Baru</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="newpassword" required>
                                            <div class="input-group-text">
                                                <span id="showpass" class="bi bi-eye"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 mb-3">
                                        <label for="repeatpassword" class="form-label">Ulangi Password</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="repeatpassword" required>
                                            <div class="input-group-text">
                                                <span id="showpass1" class="bi bi-eye"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <button type="submit" class="btn btn-primary w-100">Tambahkan</button>
                                    </div>
                                </form>
                            </div>`;
        $("#modalform").find(".modal-body").html(modalcontent);
        var password_field = document.getElementById("newpassword");
        var toggle = document.getElementById("showpass");
        var password_field1 = document.getElementById("repeatpassword");
        var toggle1 = document.getElementById("showpass1");
        var password_field2 = document.getElementById("oldpassword");
        var toggle2 = document.getElementById("showpass2");
        toggle.addEventListener("click", function() {
            if (password_field.type === "password") {
                password_field.type = "text";
                toggle.classList.remove("bi-eye");
                toggle.classList.add("bi-eye-slash");
            } else {
                password_field.type = "password";
                toggle.classList.remove("bi-eye-slash");
                toggle.classList.add("bi-eye");
            }
        });
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

        $("#editpassword").submit(function(e) {
            e.preventDefault();
            var newpassword = $("#newpassword").val();
            var oldpassword = $("#oldpassword").val();
            var old = $("#old").val();
            var repeatpassword = $("#repeatpassword").val();
            var formdata = new FormData();
            formdata.append("old", old);
            formdata.append("oldpassword", oldpassword)
            formdata.append("newpassword", newpassword);
            formdata.append("repeatpassword", repeatpassword);
            formdata.append("id", id);


            $.ajax({
                url: base_url + "member/profil/gantipassword",
                data: formdata,
                type: "post",
                processData: false,
                contentType: false,
                success: function(response) {
                    var json = JSON.parse(response);
                    if (json.message1) {
                        swal.fire({
                            title: json.message1,
                            icon: "success",
                            toast: true,
                            timer: 3000,
                            showConfirmButton: false,
                            position: "top-end",
                            timerProgressBar: true
                        }).then((result) => {
                            window.location.reload();
                        });;
                        $("#modalform").modal("hide");
                    } else {
                        swal.fire({
                            title: json.message,
                            icon: "error",
                        });
                    }

                },
                error: function(xhr, status, error) {
                    swal.fire({
                        title: error,
                        icon: "error"
                    })
                }
            });
        })
    }
</script>