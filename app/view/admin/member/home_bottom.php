<script>
    let table;
    let table2;
    let base_url = "<?= base_url() ?>";
    let data;
    let data2;
    $(document).ready(function() {
        var membwer = document.getElementById("membwer");
        var diskonn = document.getElementById("diskonn");
        var tablemember = document.getElementById("tablemember");
        var tablediskon = document.getElementById("tablediskon");
        membwer.addEventListener("click", function() {
            tablediskon.classList.add("d-none");
            tablemember.classList.remove("d-none");
        });
        diskonn.addEventListener("click", function() {
            tablemember.classList.add("d-none");
            tablediskon.classList.remove("d-none");
        });

        table = $("#tabelmember").DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                url: base_url + "admin/member/getdata",
                type: "post"
            },
            "columns": [{
                    "data": null,
                    "render": function(data, type, row, meta) {
                        return meta.row + 1
                    }
                },
                {
                    "data": "kode",
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
                    "data": "alamat",
                    "render": function(data, type, row) {
                        if (data === null || data === "") {
                            return "Belum Di Isi";
                        } else {
                            return data;
                        }
                    }
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
        table2 = $("#tabeldiskon").DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                url: base_url + "admin/member/getdatadiskon",
                type: "post"
            },
            "columns": [{
                    "data": "id",
                    "render": function(data, type, row, meta) {
                        return meta.row + 1
                    }
                },
                {
                    "data": "diskon",
                    "render": function(data, type, row) {
                        return data + "%"
                    }
                },
                {
                    "data": "barangmin",
                    "render": function(data, type, row) {
                        return data + " Pcs"
                    }
                },
                {
                    "data": "barangmax",
                    "render": function(data, type, row) {
                        return data + " Pcs"
                    }
                },
                {
                    "data": "hargamin",
                    "render": function(data, type, row) {
                        return "Rp." + $.number(data, 2, ",", ".")
                    }
                },
                {
                    "data": "hargamax",
                    "render": function(data, type, row) {
                        return "Rp." + $.number(data, 2, ",", ".")
                    }
                }
            ],
            "order": [
                [0, "desc"]
            ],
            "searching": true,
            "lengthMenu": [10, 25, 50, 100],
            "pageLength": 10,
        });
        $("#tabelmember tbody").on("click", "tr", function() {
            data = table.row(this).data();
            $("#modalaction").modal("show");
            $("#modalaction").find(".modal-title").text(data.nama);
            let modalcontent = `
                            <div class="row">
                                <div class="col-lg-12 mb-3">
                                    <button onclick="edit('${data.kode}')" class="btn btn-outline-warning w-100"><i class="bi bi-pencil-square"></i> Edit</button>
                                </div>
                                <div class="col-lg-12 mb-3">
                                    <button onclick="hapus('${data.kode}')" class="btn btn-outline-danger w-100"><i class="bi bi-trash"></i> Hapus</button>
                                </div>
                            </div>`;
            $("#modalaction").find(".modal-body").html(modalcontent);
        });
        $("#tabeldiskon tbody").on("click", "tr", function() {
            data2 = table2.row(this).data();
            $("#modalaction").modal("show");
            $("#modalaction").find(".modal-title").text("Diskon Member");
            let modalcontent = `
                            <div class="row">
                                <div class="col-lg-12 mb-3">
                                    <button onclick="editdiskon('${data2.id}')" class="btn btn-outline-warning w-100"><i class="bi bi-pencil-square"></i> Edit</button>
                                </div>
                                <div class="col-lg-12 mb-3">
                                    <button onclick="hapusdiskon('${data2.id}')" class="btn btn-outline-danger w-100"><i class="bi bi-trash"></i> Hapus</button>
                                </div>
                            </div>`;
            $("#modalaction").find(".modal-body").html(modalcontent);
        });
    });

    function editdiskon(id) {
        $("#modalaction").modal("hide");
        $("#modalform").modal("show");
        $("#modalform").find(".modal-title").text("Edit Diskon Khusus Member");
        let modalcontent = `
                            <div class="row">
                                <form id="editdata">
                                    <div class="col-lg-12 mb-3">
                                        <label for="diskon" class="form-label">Diskon</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="diskon" maxlength="3" required placeholder="Contoh: 40">
                                            <div class="input-group-text">%</div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 mb-3">
                                        <label for="barangmin" class="form-label">Barang Minimal</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="barangmin" required placeholder="Contoh: 2">
                                            <div class="input-group-text">Pcs</div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 mb-3">
                                        <label for="barangmax" class="form-label">Barang Maksimal</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="barangmax" required placeholder="Contoh: 5">
                                            <div class="input-group-text">Pcs</div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 mb-3">
                                        <label for="hargamin" class="form-label">Harga Minimal</label>
                                        <div class="input-group">
                                            <div class="input-group-text">Rp.</div>
                                            <input type="text" class="form-control" id="hargamin" required placeholder="Contoh: 1.200.000">
                                        </div>
                                    </div>
                                    <div class="col-lg-12 mb-3">
                                        <label for="hargamax" class="form-label">Harga Maksimal</label>
                                        <div class="input-group">
                                            <div class="input-group-text">Rp.</div>
                                            <input type="text" class="form-control" id="hargamax" required placeholder="Contoh: 5.200.000">
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <button type="submit" class="btn btn-primary w-100">Tambahkan</button>
                                    </div>
                                </form>
                            </div>`;
        $("#modalform").find(".modal-body").html(modalcontent);
        var diskon = document.getElementById("diskon");
        diskon.value = data2.diskon;
        var barangmin = document.getElementById("barangmin");
        barangmin.value = data2.barangmin;
        var barangmax = document.getElementById("barangmax");
        barangmax.value = data2.barangmax;
        var hargamin = document.getElementById("hargamin");
        hargamin.value = data2.hargamin;
        var hargamax = document.getElementById("hargamax");
        hargamax.value = data2.hargamax;
        $("#diskon").on("input", function() {
            var diskon = $(this).val();
            var sanitized = diskon.replace(/[^0-9]/g, '');
            $(this).val(sanitized);
            if (diskon.length == 1 && diskon[0] == 0) {
                $(this).val('');
            }
        });

        $("#barangmin").on("input", function() {
            var barangmin = $(this).val();
            var sanitized = barangmin.replace(/[^0-9]/g, '');
            $(this).val(sanitized);
            if (barangmin.length == 1 && barangmin[0] == 0) {
                $(this).val('');
            }
        });

        $("#barangmax").on("input", function() {
            var barangmax = $(this).val();
            var sanitized = barangmax.replace(/[^0-9]/g, '');
            $(this).val(sanitized);
            if (barangmax.length == 1 && barangmax[0] == 0) {
                $(this).val('');
            }
        });

        $("#hargamin").number(true);
        $("#hargamax").number(true);

        $("#editdata").submit(function(e) {
            e.preventDefault();
            var diskon = parseInt($("#diskon").val());
            var barangmin = parseInt($("#barangmin").val());
            var barangmax = parseInt($("#barangmax").val());
            var hargamin = $("#hargamin").val();
            var hargamax = $("#hargamax").val();

            if (barangmin > barangmax || barangmax < barangmin) {
                swal.fire("Mohon Mengisi Barang Minimal Dan Maksimal Dengan Benar");
                return;
            } else if (hargamin > hargamax || hargamax < hargamin) {
                swal.fire("Mohon Mengisi Harga Minimal Dan Maksimal Dengan Benar");
                return;
            }

            var formdata = new FormData();
            formdata.append("id", id);
            formdata.append("rildiskon", data2.diskon);
            formdata.append("diskon", diskon);
            formdata.append("barangmin", barangmin);
            formdata.append("barangmax", barangmax);
            formdata.append("hargamin", hargamin);
            formdata.append("hargamax", hargamax);

            $.ajax({
                url: base_url + "admin/member/editdiskon",
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
                    table2.ajax.reload();
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

    function hapusdiskon(id) {
        $("#modalaction").modal("hide");
        swal.fire({
            title: "Apakah Anda Yakin Ingin Menghapus Diskon Ini?",
            icon: "warning",
            showCancelButton: true,
            preConfirm: (result) => {
                $.ajax({
                    url: base_url + "admin/member/hapusdiskon",
                    data: {
                        diskon: data2.diskon,
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
                        table2.ajax.reload();
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

    function edit(kode) {
        $("#modalaction").modal("hide");
        $("#modalform").modal("show");
        $("#modalform").find(".modal-title").text("Edit Data Member");
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
        nama.value = data.nama;

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
                formdata.append("foto", data.foto);
            }

            $.ajax({
                url: base_url + "admin/member/edit",
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

    function hapus(kode) {
        $("#modalaction").modal("hide");
        swal.fire({
            title: "Apakah Anda Yakin Ingin Menghapus Data Ini?",
            icon: "warning",
            showCancelButton: true,
            preConfirm: (result) => {
                $.ajax({
                    url: base_url + "admin/member/hapus",
                    data: {
                        nama: data.nama,
                        kode: kode
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
        $("#modalform").find(".modal-title").text("Tambah Data Member");
        let modalcontent = `
                            <div class="row">
                                <form id="tambahdata" enctype="multipart/form-data">
                                    <div class="col-lg-12 mb-2 d-flex justify-content-center">
                                        <img id="output" class="d-none" width="100"/>
                                    </div>
                                    <div class="col-lg-12 mb-3">
                                        <label for="foto" class="form-label">Foto member</label>
                                        <input type="file" class="form-control" id="foto" required>
                                        <span class="form-text">Ukuran Foto Maksimal 4 Mb</span>
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
                                        <label for="email" class="form-label">Email</label>
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
            var maxsize = 5 * 1024 * 1024;
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
            formdata.append("password", $("#password").val());


            $.ajax({
                url: base_url + "admin/member/tambah",
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

    function diskonmember() {
        $("#modalform").modal("show");
        $("#modalform").find(".modal-title").text("Tambah Diskon Member");
        let modalcontent = `
                            <div class="row">
                                <form id="tambahdata" enctype="multipart/form-data">
                                    <div class="col-lg-12 mb-3">
                                        <label for="diskon" class="form-label">Diskon</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="diskon" maxlength="3" required placeholder="Contoh: 40">
                                            <div class="input-group-text">%</div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 mb-3">
                                        <label for="barangmin" class="form-label">Barang Minimal</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="barangmin" required placeholder="Contoh: 2">
                                            <div class="input-group-text">Pcs</div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 mb-3">
                                        <label for="barangmax" class="form-label">Barang Maksimal</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="barangmax" required placeholder="Contoh: 5">
                                            <div class="input-group-text">Pcs</div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 mb-3">
                                        <label for="hargamin" class="form-label">Harga Minimal</label>
                                        <div class="input-group">
                                            <div class="input-group-text">Rp.</div>
                                            <input type="text" class="form-control" id="hargamin" required placeholder="Contoh: 1.200.000">
                                        </div>
                                    </div>
                                    <div class="col-lg-12 mb-3">
                                        <label for="hargamax" class="form-label">Harga Maksimal</label>
                                        <div class="input-group">
                                            <div class="input-group-text">Rp.</div>
                                            <input type="text" class="form-control" id="hargamax" required placeholder="Contoh: 5.200.000">
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <button type="submit" class="btn btn-primary w-100">Tambahkan</button>
                                    </div>
                                </form>
                            </div>`;
        $("#modalform").find(".modal-body").html(modalcontent);
        $("#diskon").on("input", function() {
            var diskon = $(this).val();
            var sanitized = diskon.replace(/[^0-9]/g, '');
            $(this).val(sanitized);
            if (diskon.length == 1 && diskon[0] == 0) {
                $(this).val('');
            }
        });

        $("#barangmin").on("input", function() {
            var barangmin = $(this).val();
            var sanitized = barangmin.replace(/[^0-9]/g, '');
            $(this).val(sanitized);
            if (barangmin.length == 1 && barangmin[0] == 0) {
                $(this).val('');
            }
        });

        $("#barangmax").on("input", function() {
            var barangmax = $(this).val();
            var sanitized = barangmax.replace(/[^0-9]/g, '');
            $(this).val(sanitized);
            if (barangmax.length == 1 && barangmax[0] == 0) {
                $(this).val('');
            }
        });

        $("#hargamin").number(true);
        $("#hargamax").number(true);

        $("#tambahdata").submit(function(e) {
            e.preventDefault();
            var diskon = parseInt($("#diskon").val());
            var barangmin = parseInt($("#barangmin").val());
            var barangmax = parseInt($("#barangmax").val());
            var hargamin = $("#hargamin").val();
            var hargamax = $("#hargamax").val();

            if (barangmin > barangmax || barangmax < barangmin) {
                swal.fire("Mohon Mengisi Barang Minimal Dan Maksimal Dengan Benar");
                return;
            } else if (hargamin > hargamax || hargamax < hargamin) {
                swal.fire("Mohon Mengisi Harga Minimal Dan Maksimal Dengan Benar");
                return;
            }

            var formdata = new FormData();
            formdata.append("diskon", diskon);
            formdata.append("barangmin", barangmin);
            formdata.append("barangmax", barangmax);
            formdata.append("hargamin", hargamin);
            formdata.append("hargamax", hargamax);

            $.ajax({
                url: base_url + "admin/member/tambahdiskon",
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
                    table2.ajax.reload();
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