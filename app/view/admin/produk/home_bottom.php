<script>
    let table;
    let base_url = "<?= base_url() ?>";
    let data;
    $(document).ready(function() {
        table = $(".table").DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                url: base_url + "admin/produk/getdata",
                type: "post"
            },
            "columns": [{
                    "data": null,
                    "render": function(data, type, row, meta) {
                        return meta.row + 1
                    }
                },
                {
                    "data": "kode"
                },
                {
                    "data": "namaproduk"
                },
                {
                    "data": "kategori"
                },
                {
                    "data": "stok",
                    "render": function(data, type, row) {
                        return data + "Pcs"
                    }
                },
                {
                    "data": "harga",
                    "render": function(data, type, row) {
                        return "Rp." + $.number(data, 2, ",", ".");
                    }
                },
                {
                    "data": "diskon",
                    "render": function(data, type, row) {
                        return data + "%"
                    }
                },
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
            $("#modalaction").find(".modal-title").text(data.namaproduk);
            let modalcontent = `
                            <div class="row">
                                <div class="col-lg-12 mb-3">
                                    <button onclick="tambahstok('${data.kode}')" class="btn btn-outline-primary w-100"><i class="bi bi-plus"></i> Tambah Stok</button>
                                </div>
                                <div class="col-lg-12 mb-3">
                                    <button onclick="kurangistok('${data.kode}')" class="btn btn-outline-primary w-100"><i class="bi bi-dash"></i> Kurangi Stok</button>
                                </div>
                                <div class="col-lg-12 mb-3">
                                    <button onclick="edit('${data.kode}')" class="btn btn-outline-warning w-100"><i class="bi bi-pencil-square"></i> Edit</button>
                                </div>
                                <div class="col-lg-12 mb-3">
                                    <button onclick="hapus('${data.kode}')" class="btn btn-outline-danger w-100"><i class="bi bi-trash"></i> Hapus</button>
                                </div>
                                <div class="col-lg-12">
                                    <button onclick="diskon('${data.kode}')" class="btn btn-outline-success w-100"><i class="bi bi-percent"></i> Diskon</button>
                                    <button onclick="tambahdata()" hidden class="btn btn-outline-success w-100"><i class="bi bi-percent"></i> Diskon</button>
                                </div>
                            </div>`;
            $("#modalaction").find(".modal-body").html(modalcontent);
        });
    });

    function diskon(kode) {
        $("#modalaction").modal("hide");
        $("#modalform").modal("show");
        $("#modalform").find(".modal-title").text("Menambah Diskon");
        let modalcontent = `
                            <div class="row">
                                <form id="tambahdiskon">
                                    <div class="col-lg-12 mb-3">
                                        <label for="diskon" class="form-label">Diskon</label>
                                        <input type="text" class="form-control" id="diskon" required>
                                        <span class="form-text">Jika Ingin Mengembalikan Ke 0% Maka Isi Saja 0</span>
                                    </div>
                                    <div class="col-lg-12">
                                        <button type="submit" class="btn btn-primary w-100">Tambahkan</button>
                                    </div>
                                </form>
                            </div>`;
        $("#modalform").find(".modal-body").html(modalcontent);
        var diskon = document.getElementById("diskon");
        diskon.value = data.diskon;
        $("#diskon").on("input", function() {
            var diskon = $(this).val();
            var sanitized = diskon.replace(/[^0-9]/g, '');
            $(this).val(sanitized);
            if (diskon.length > 3) {
                $(this).val(data.diskon);
                swal.fire("Di Haruskan 3 Angka");
            }
        });
        $("#tambahdiskon").submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: base_url + "admin/produk/diskon",
                data: {
                    diskon: $("#diskon").val(),
                    kode: kode,
                    namaproduk: data.namaproduk
                },
                type: "post",
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

    function edit(kode) {
        $("#modalaction").modal("hide");
        $("#modalform").modal("show");
        $("#modalform").find(".modal-title").text("Tambah Data Produk");
        let modalcontent = `
                            <div class="row">
                                <form id="edit">
                                    <div class="col-lg-12 mb-3">
                                        <label for="namaproduk" class="form-label">Nama Produk</label>
                                        <input type="text" class="form-control" id="namaproduk" required>
                                    </div>
                                    <div class="col-lg-12 mb-3">
                                        <label for="kategori" class="form-label">Kategori</label>
                                        <div class="input-group">
                                            <select class="form-select" id="kategori" required></select>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 mb-3">
                                        <label for="harga" class="form-label">Harga</label>
                                        <div class="input-group">
                                            <div class="input-group-text">
                                                <span>Rp</span>
                                            </div>
                                            <input type="text" class="form-control" id="harga" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <button type="submit" class="btn btn-primary w-100">Tambahkan</button>
                                    </div>
                                </form>
                            </div>`;
        $("#modalform").find(".modal-body").html(modalcontent);
        var namaproduk = document.getElementById("namaproduk");
        namaproduk.value = data.namaproduk;

        var harga = document.getElementById("harga");
        harga.value = data.harga;

        var kategori = data.kategori;
        $.ajax({
            url: base_url + "admin/produk/kategori",
            type: "get",
            dataType: "json",
            success: function(data) {
                $("#kategori").empty();
                data.forEach(function(item) {
                    var option = new Option(item.kategori);
                    $(option).data('kategori', item.kategori);
                    $(option).data('kode', item.kode);

                    if (item.kategori === kategori) {
                        $(option).attr("selected", "selected");
                    }

                    $("#kategori").append(option);
                });

                $("#kategori").select2({
                    theme: "bootstrap-5",
                    dropdownParent: $("#modalform .modal-body")
                })
            },
            error: function(xhr, status, error) {
                console.log(error);
            }
        });
        $("#harga").on("input", function() {
            var harga = $(this).val();
            var sanitized = harga.replace(/[^0-9]/g, '');
            $(this).val(sanitized);
        }).number(true);

        $("#edit").submit(function(e) {
            e.preventDefault();
            var formdata = new FormData();
            formdata.append("namaproduk", $("#namaproduk").val());
            formdata.append("namaprodukril", data.namaproduk);
            formdata.append("koderil", data.kode);
            formdata.append("kategoriril", data.kategori);
            formdata.append("kode", $("#kategori option:selected").data('kode'));
            formdata.append("kategori", $("#kategori option:selected").text());
            formdata.append("harga", $("#harga").val());

            $.ajax({
                url: base_url + "admin/produk/edit",
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
                    url: base_url + "admin/produk/hapus",
                    data: {
                        namaproduk: data.namaproduk,
                        kode: kode
                    },
                    type: "post",
                    success: function() {
                        swal.fire({
                            title: "Berhasil Di Dikurangi",
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

    function exportexcel() {
        $("#modalform").modal("show");
        $("#modalform").find(".modal-title").text("Export Ke Excel");
        let modalcontent = `
                            <div class="row">
                                <form id="excel">
                                    <div class="col-lg-12 mb-3">
                                        <label for="awal" class="form-label">Tanggal Awal</label>
                                        <input type="date" class="form-control" id="awal" required>
                                        <span class="form-text">Pilih Tanggal Awal</span>
                                    </div>
                                    <div class="col-lg-12 mb-3">
                                        <label for="akhir" class="form-label">Tanggal Akhir</label>
                                        <input type="date" class="form-control" id="akhir" required>
                                        <span class="form-text">Jika Ingin Mengambil Data Produk Hari Ini Ambil Tanggal Besok</span>
                                    </div>
                                    <div class="col-lg-12 w-100">
                                        <button type="submit" class="btn btn-primary w-100">Tambahkan</button>
                                    </div>
                                </form>
                            </div>`;
        $("#modalform").find(".modal-body").html(modalcontent);

        $("#excel").submit(function(e) {
            e.preventDefault();
            var awal = $("#awal").val();
            var akhir = $("#akhir").val();
            if (akhir < awal) {
                swal.fire("Tolong Pilih Tanggal Dengan Benar");
                return;
            }
            var formdata = new FormData();
            formdata.append("awal", awal);
            formdata.append("akhir", akhir);

            $.ajax({
                url: base_url + "admin/produk/export",
                data: formdata,
                type: "post",
                processData: false,
                contentType: false,
                success: function(response) {
                    var json = JSON.parse(response);
                    var wb = XLSX.utils.book_new();
                    var ws = XLSX.utils.json_to_sheet(json);
                    XLSX.utils.book_append_sheet(wb, ws, "Data Stok Produk");
                    XLSX.writeFile(wb, "Data Stok Produk.xlsx");
                    $("#modalform").modal("hide");
                },
                error: function(xhr, status, error) {
                    swal.fire({
                        title: error,
                        icon: "error"
                    })
                }

            })
        })
    }

    function kurangistok(kode) {
        $("#modalaction").modal("hide");
        $("#modalform").modal("show");
        $("#modalform").find(".modal-title").text("Menambah Diskon");
        let modalcontent = `
                            <div class="row">
                                <form id="kurangistok">
                                    <div class="col-lg-12 mb-3">
                                        <label for="stok" class="form-label">Kurangi Stok Berapa ?</label>
                                        <input type="text" class="form-control" id="stok" required>
                                    </div>
                                    <div class="col-lg-12">
                                        <button type="submit" class="btn btn-primary w-100">Tambahkan</button>
                                    </div>
                                </form>
                            </div>`;
        $("#modalform").find(".modal-body").html(modalcontent);
        $("#kurangistok").submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: base_url + "admin/produk/kurangistok",
                data: {
                    stok: $("#stok").val(),
                    kode: kode,
                    namaproduk: data.namaproduk
                },
                type: "post",
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

    function tambahstok(kode) {
        $("#modalaction").modal("hide");
        $("#modalform").modal("show");
        $("#modalform").find(".modal-title").text("Menambah Diskon");
        let modalcontent = `
                            <div class="row">
                                <form id="menambahstok">
                                    <div class="col-lg-12 mb-3">
                                        <label for="stok" class="form-label">Menambah Stok Berapa ?</label>
                                        <input type="text" class="form-control" id="stok" required>
                                    </div>
                                    <div class="col-lg-12">
                                        <button type="submit" class="btn btn-primary w-100">Tambahkan</button>
                                    </div>
                                </form>
                            </div>`;
        $("#modalform").find(".modal-body").html(modalcontent);
        $("#menambahstok").submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: base_url + "admin/produk/tambahstok",
                data: {
                    stok: $("#stok").val(),
                    kode: kode,
                    namaproduk: data.namaproduk
                },
                type: "post",
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
                    $("#modalform").modal("hide");
                    table.ajax.reload();
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

    function tambahdata() {
        $("#modalform").modal("show");
        $("#modalform").find(".modal-title").text("Tambah Data Produk");
        let modalcontent = `
                            <div class="row">
                                <form id="tambahdata">
                                    <div class="col-lg-12 mb-3">
                                        <label for="namaproduk" class="form-label">Nama Produk</label>
                                        <input type="text" class="form-control" id="namaproduk" required placeholder="Contoh: Playstation 5">
                                    </div>
                                    <div class="col-lg-12 mb-3">
                                        <label for="kategori" class="form-label">Kategori</label>
                                        <div class="input-group">
                                            <select class="form-select" id="kategori" required></select>
                                            <div class="input-group-text">
                                                <span onclick="tambahkt()" class="bi bi-plus"></span>
                                            </div>
                                        </div>
                                        <div class="form-text">Jika Ingin Menambah Kategori Klik Icon +</div>
                                    </div>
                                    <div class="col-lg-12 mb-3">
                                        <label for="stok" class="form-label">Stok</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="stok" required placeholder="Contoh: 200">
                                            <div class="input-group-text">
                                                <span>Pcs</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 mb-3">
                                        <label for="harga" class="form-label">Harga</label>
                                        <div class="input-group">
                                            <div class="input-group-text">
                                                <span>Rp</span>
                                            </div>
                                            <input type="text" class="form-control" id="harga" required placeholder="Contoh: 9.000.000">
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <button type="submit" class="btn btn-primary w-100">Tambahkan</button>
                                    </div>
                                </form>
                            </div>`;
        $("#modalform").find(".modal-body").html(modalcontent);
        $.ajax({
            url: base_url + "admin/produk/kategori",
            type: "get",
            dataType: "json",
            success: function(data) {
                $("#kategori").empty();
                $("#kategori").append('<option value="" selected>-- Pilih Di Sini--</option>');
                data.forEach(function(item) {
                    var option = new Option(item.kategori);
                    $(option).data('kategori', item.kategori);
                    $(option).data('kode', item.kode);

                    $("#kategori").append(option);
                });

                $("#kategori").select2({
                    theme: "bootstrap-5",
                    dropdownParent: $("#modalform .modal-body")
                })
            },
            error: function(xhr, status, error) {
                console.log(error);
            }
        });
        $("#stok").on("input", function() {
            var stok = $(this).val();
            var sanitized = stok.replace(/[^0-9]/g, '');
            $(this).val(sanitized);
            if (stok.length == 1 && stok[0] == 0) {
                $(this).val('');
            }
        });
        $("#harga").on("input", function() {
            var harga = $(this).val();
            var sanitized = harga.replace(/[^0-9]/g, '');
            $(this).val(sanitized);
        }).number(true);

        $("#tambahdata").submit(function(e) {
            e.preventDefault();
            var formdata = new FormData();
            formdata.append("namaproduk", $("#namaproduk").val());
            formdata.append("kode", $("#kategori option:selected").data('kode'));
            formdata.append("kategori", $("#kategori option:selected").text());
            formdata.append("stok", $("#stok").val());
            formdata.append("harga", $("#harga").val());

            $.ajax({
                url: base_url + "admin/produk/tambah",
                data: formdata,
                type: "post",
                processData: false,
                contentType: false,
                success: function(response) {
                    var json = JSON.parse(response);
                    if (json.message1) {
                        swal.fire({
                            title: json.message1,
                            icon: "error",
                            toast: true,
                            timer: 3000,
                            showConfirmButton: false,
                            position: "top-end",
                            timerProgressBar: true
                        });
                    } else {
                        swal.fire({
                            title: "Sudah Di Tambahkan",
                            icon: "success",
                            toast: true,
                            timer: 3000,
                            showConfirmButton: false,
                            position: "top-end",
                            timerProgressBar: true
                        });
                        table.ajax.reload();
                        $("#modalform").modal("hide");
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

    function tambahkt() {
        $("#modalform").modal("hide");
        $("#modalaction").modal("show");
        $("#modalaction").find(".modal-title").text("Tambah Kategori");
        let modalcontent = `
                            <div class="row">
                                <form id="tambahkt">
                                    <div class="col-lg-12 mb-3">
                                        <label for="kt" class="form-label">Kategori</label>
                                        <input type="text" class="form-control" id="kt" required placeholder="Contoh: Figma Figure">
                                    </div>
                                    <div class="col-lg-12 mb-3">
                                        <label for="kode" class="form-label">Kode</label>
                                        <input type="text" class="form-control" id="kode" maxlength="2" required placeholder="Contoh: FF">
                                        <span class="form-text">Kode Kategori Di Haruskan 2 Huruf</span>
                                    </div>
                                    <div class="col-lg-12 w-100">
                                        <button type="submit" class="btn btn-primary w-100">Tambahkan</button>
                                    </div>
                                </form>
                            </div>`;
        $("#modalaction").find(".modal-body").html(modalcontent);

        $("#tambahkt").submit(function(e) {
            e.preventDefault();
            var formdata = new FormData();
            formdata.append("kategori", $("#kt").val());
            formdata.append("kode", $("#kode").val().toUpperCase());
            swal.fire({
                title: "Apakah Anda Sudah Yakin ?",
                text: "Kategori Dan Kode Tidak Dapat Di Ganti Atau Di Hapus",
                icon: "warning",
                showCancelButton: true,
                cancelButtonText: "Saya Isi Kembali",
                preConfirm: (result) => {
                    $.ajax({
                        url: base_url + "admin/produk/tambahkt",
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
                            $("#modalaction").modal("hide");
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
        });
    }
</script>