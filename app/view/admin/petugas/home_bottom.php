<script>
    let table;
    let base_url = "<?= base_url() ?>";
    let data;
    $(document).ready(function() {
        table = $(".table").DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                url: base_url + "admin/petugas/getdata",
                type: "post"
            },
            "columns": [{
                    "data": null,
                    "render": function(data, type, row, meta) {
                        return meta.row + 1
                    }
                },
                {
                    "data": "foto",
                    "render": function(data, type, row) {
                        return "<img class='border border-dark' src='" + base_url + data + "' alt='fotoprofil' width='100' />";
                    }
                },
                {
                    "data": "nama"
                },
                {
                    "data": "tanggallahir"
                },
                {
                    "data": "alamat"
                },
                {
                    "data": "email"
                }
            ],
            "order": [
                [0, "desc"]
            ],
            "searching": true,
            "lengthMenu": [10, 25, 50, 100],
            "pageLength": 10,
        });
        $(".table tbody").on("click", "tr", function() {
            data = table.row(this).data();
            $("#modalaction").modal("show");
            $("#modalaction").find(".modal-title").text(data.nama);
            let modalcontent = `
                            <div class="row">
                                <div class="col-lg-12 mb-3">
                                    <button onclick="gantipassword('${data.id}')" class="btn btn-outline-primary w-100"><i class="bi bi-key"></i> Ganti Password</button>
                                </div>
                                <div class="col-lg-12 mb-3">
                                    <button onclick="edit('${data.id}')" class="btn btn-outline-warning w-100"><i class="bi bi-pencil-square"></i> Edit</button>
                                </div>
                                <div class="col-lg-12 mb-3">
                                    <button onclick="hapus('${data.id}')" class="btn btn-outline-danger w-100"><i class="bi bi-trash"></i> Hapus</button>
                                </div>
                            </div>`;
            $("#modalaction").find(".modal-body").html(modalcontent);
        });
    });

    function gantipassword(id) {
        $("#modalaction").modal("hide");
        $("#modalform").modal("show");
        $("#modalform").find(".modal-title").text("Ganti Password " + data.nama);
        let modalcontent = `
                            <div class="row">
                                <form id="editpassword" enctype="multipart/form-data">
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

        $("#editpassword").submit(function(e) {
            e.preventDefault();
            var newpassword = $("#newpassword").val();
            var repeatpassword = $("#repeatpassword").val();
            if (newpassword !== repeatpassword) {
                swal.fire("Password Tidak Sama");
                return;
            }
            var formdata = new FormData();
            formdata.append("nama", data.nama);
            formdata.append("password", newpassword);
            formdata.append("id", id);


            $.ajax({
                url: base_url + "admin/petugas/gantipassword",
                data: formdata,
                type: "post",
                processData: false,
                contentType: false,
                success: function() {
                    swal.fire({
                        title: "Berhasil Di Ganti",
                        icon: "success",
                        toast: true,
                        timer: 3000,
                        showConfirmButton: false,
                        position: "top-end",
                        timerProgressBar: true
                    });
                    table.ajax.reload();
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

    function edit(id) {
        $("#modalaction").modal("hide");
        $("#modalform").modal("show");
        $("#modalform").find(".modal-title").text("Edit Data " + data.nama);
        let modalcontent = `
        <div class="row">
                                <form id="edit" enctype="multipart/form-data">
                                    <div class="col-lg-12 mb-2 d-flex justify-content-center">
                                        <img class="border border-dark" id="output" width="100"/>
                                    </div>
                                    <div class="col-lg-12 mb-3">
                                        <label for="foto" class="form-label">Foto Petugas</label>
                                        <input type="file" class="form-control" id="foto">
                                        <span class="form-text">Jika Tidak Akan Mengganti Abaikan Saja</span>
                                    </div>
                                    <div class="col-lg-12 mb-3">
                                        <label for="nama" class="form-label">nama</label>
                                        <input type="text" class="form-control" id="nama" required placeholder="Contoh: Adam Marwadi">
                                    </div>
                                    <div class="col-lg-12 mb-3">
                                        <label for="ttl" class="form-label">Tempat Tanggal Lahir</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="tempatlahir" required placeholder="Contoh: Tasik Malaya">
                                            <input type="date" class="form-control" id="tanggallahir" required>
                                        </div>
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
        nama.value = data.nama;

        var tanggallahir = document.getElementById("tanggallahir");
        tanggallahir.value = data.tanggallahir;

        var tempatlahir = document.getElementById("tempatlahir");
        tempatlahir.value = data.tempatlahir;

        var email = document.getElementById("email");
        email.value = data.email;

        var alamat = document.getElementById("alamat");
        alamat.value = data.alamat;

        var defaultfoto = base_url + data.foto;
        var output = $("#output");

        if (data.foto) {
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
            formdata.append("namaril", data.nama);
            formdata.append("id", data.id);
            formdata.append("alamat", $("#alamat").val());
            formdata.append("tempatlahir", $("#tempatlahir").val());
            formdata.append("tanggallahir", $("#tanggallahir").val());
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
                formdata.append("foto", data.foto);
            }

            $.ajax({
                url: base_url + "admin/petugas/edit",
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
                    });
                    table.ajax.reload();
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

    function hapus(id) {
        $("#modalaction").modal("hide");
        swal.fire({
            title: "Apakah Anda Yakin Ingin Menghapus Data Ini?",
            icon: "warning",
            showCancelButton: true,
            preConfirm: (result) => {
                $.ajax({
                    url: base_url + "admin/petugas/hapus",
                    data: {
                        nama: data.nama,
                        id: id
                    },
                    type: "post",
                    success: function() {
                        swal.fire({
                            title: "Berhasil Di Hapus",
                            icon: "success",
                            toast: true,
                            timer: 3000,
                            showConfirmButton: false,
                            position: "top-end",
                            timerProgressBar: true
                        });
                        table.ajax.reload();
                    },
                    error: function(xhr, status, error) {
                        swal.fire({
                            title: error,
                            icon: "error"
                        })
                    }
                });
            }
        });
    }

    function tambahdata() {
        $("#modalform").modal("show");
        $("#modalform").find(".modal-title").text("Tambah Data Petugas");
        let modalcontent = `
                            <div class="row">
                                <form id="tambahdata" enctype="multipart/form-data">
                                    <div class="col-lg-12 mb-2 d-flex justify-content-center">
                                        <img id="output" class="d-none" width="100"/>
                                    </div>
                                    <div class="col-lg-12 mb-3">
                                        <label for="foto" class="form-label">Foto Petugas</label>
                                        <input type="file" class="form-control" id="foto" required>
                                        <span class="form-text">Ukuran Foto Maksimal 4 Mb</span>
                                    </div>
                                    <div class="col-lg-12 mb-3">
                                        <label for="nama" class="form-label">nama</label>
                                        <input type="text" class="form-control" id="nama" required placeholder="Contoh: Adam Marwadi">
                                    </div>
                                    <div class="col-lg-12 mb-3">
                                        <label for="ttl" class="form-label">Tempat Tanggal Lahir</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="tempatlahir" required placeholder="Contoh: Tasik Malaya">
                                            <input type="date" class="form-control" id="tanggallahir" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 mb-3">
                                        <label for="alamat" class="form-label">Alamat</label>
                                        <textarea class="form-control" id="alamat" cols="5"></textarea>
                                    </div>
                                    <div class="col-lg-12 mb-3">
                                        <label for="email" class="form-label">email</label>
                                        <input type="email" class="form-control" id="email" required>
                                    </div>
                                    <div class="col-lg-12 mb-3">
                                        <label for="password" class="form-label">password</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="password" required>
                                            <div class="input-group-text">
                                                <span id="showpass" class="bi bi-eye"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <button type="submit" class="btn btn-primary w-100">Tambahkan</button>
                                    </div>
                                </form>
                            </div>`;
        $("#modalform").find(".modal-body").html(modalcontent);
        $("#foto").on("change", function(event) {
            var output = $("#output");
            output.removeClass();
            output.attr("src", URL.createObjectURL(event.target.files[0]));
            output.on("load", function() {
                URL.revokeObjectURL(output.attr("src"));
            });
        });
        var password_field = document.getElementById("password");
        var toggle = document.getElementById("showpass");
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
        })

        $("#tambahdata").submit(function(e) {
            e.preventDefault();
            var foto = document.getElementById("foto");
            var file = foto.files[0];
            var allowedtypes = ["image/jpg", "image/png", "image/jpeg", "image/gif"];
            var maxsize = 4 * 1024 * 1024;
            if (!allowedtypes.includes(file.type)) {
                swal.fire("Yang Di Izinkan Hanya jpg, jpeg, png, gif");
                return;
            } else if (file.size > maxsize) {
                swal.fire("Foto Melebihi Dari 4 Mb");
                return;
            }
            var formdata = new FormData();
            formdata.append("foto", file);
            formdata.append("nama", $("#nama").val());
            formdata.append("tempatlahir", $("#tempatlahir").val());
            formdata.append("alamat", $("#alamat").val());
            formdata.append("email", $("#email").val());
            formdata.append("tanggallahir", $("#tanggallahir").val());
            formdata.append("tempatlahir", $("#tempatlahir").val());
            formdata.append("password", $("#password").val());


            $.ajax({
                url: base_url + "admin/petugas/tambah",
                data: formdata,
                type: "post",
                processData: false,
                contentType: false,
                success: function() {
                    swal.fire({
                        title: "Berhasil Di Tambahkan",
                        icon: "success",
                        toast: true,
                        timer: 3000,
                        showConfirmButton: false,
                        position: "top-end",
                        timerProgressBar: true
                    });
                    table.ajax.reload();
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
</script>