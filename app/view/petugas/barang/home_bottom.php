<script>
    let table;
    let data;
    $(document).ready(function() {
        table = $("#DataProduk").DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                url: "<?= base_url("petugas/barang/getdata") ?>",
                type: "post"
            },
            "columns": [{
                    "data": null,
                    "render": function(data, type, row, meta) {
                        return "<div class='d-flex justify-content-center'>" + (meta.row + 1) + "</div>";
                    }
                },
                {
                    "data": "kode"
                },
                {
                    "data": "tanggalmasuk"
                },
                {
                    "data": "namabarang"
                },
                {
                    "data": "kategori"
                },
                {
                    "data": "stok",
                    "render": function(data, type, row) {
                        return data + " Pcs";
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
                        return data + "%";
                    }
                }
            ],
            "searching": true,
            "order": [
                [2, "desc"]
            ],
            "lengthMenu": [10, 25, 50, 100],
            "pageLength": 10,
        });
        $("#DataProduk tbody").on("click", "tr", function() {
            data = table.row(this).data();
            let modalbodycontent = `<div class="row">
                                    <div class="col-lg-12"><button class="btn btn-outline-info mb-3" onclick="Stok('${data.kode}')" style="width: 100%;"><i class="bi bi-box"></i> Stok</button></div>
                                    <div class="col-lg-12"><button class="btn btn-outline-warning mb-3" onclick="Edit('${data.kode}')" style="width: 100%;"><i class="bi bi-pencil-square"></i> Edit</button></div>
                                    <div class="col-lg-12"><button class="btn btn-outline-danger mb-3" onclick="Hapus('${data.kode}')" style="width: 100%;"><i class="bi bi-trash"></i> Hapus</button></div>
                                    </div>`;
            $("#ModalAction").find(".modal-title").text(data.namabarang);
            $("#ModalAction").find(".modal-body").html(modalbodycontent);
            $("#ModalAction").modal("show");
        })
    });

    function Stok(kode) {
        $("#ModalAction").modal("hide");
        swal.fire({
            title: "Isi Stok Yang Ingin Di Tambahkan ?",
            input: "number",
            showCancelButton: true,
            showConfirmButton: true,
            confirmButtonText: "Tambahkan",
            preConfirm: (stok) => {
                return $.ajax({
                    type: "post",
                    url: '<?= base_url("petugas/barang/stok") ?>',
                    data: {
                        kode: kode,
                        stok: stok,
                        namabarang: data.namabarang
                    },
                    success: function(response) {
                        swal.fire({
                            title: "Sukses Di Tambah",
                            icon: "success",
                            showConfirmButton: false,
                            timer: 1500
                        });
                        table.ajax.reload();
                    },
                    error: function(xhr, status, error) {
                        swal.fire({
                            title: "Terjadi Kesalahan " + error,
                            icon: "error",
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                });
            }
        });
    }

    function Hapus(kode) {
        $("#ModalAction").modal("hide");
        swal.fire({
            title: "Anda Yakin Ingin Menghapus Data Ini ?",
            icon: "question",
            showCancelButton: true,
            cancelButtonText: "Tidak",
            confirmButtonText: "Ya, Saya Yakin",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "<?= base_url("petugas/barang/hapus") ?>",
                    type: "post",
                    data: {
                        kode: kode,
                        namabarang: data.namabarang
                    },
                    success: function() {
                        swal.fire({
                            title: "Sukses Mengubah Data Produk",
                            icon: "success",
                            toast: true,
                            position: "top-end",
                            timer: 3000,
                            timerProgressBar: true,
                            showConfirmButton: false
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
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                swal.fire({
                    title: "Cancel",
                    icon: "success",
                    toast: true,
                    position: "top-end",
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false
                });
            }
        });
    }

    function Edit(kode) {
        $("#ModalEdit").modal("show");
        $("#ModalAction").modal("hide");

        var namabarang1 = document.getElementById("namabarang1");
        namabarang1.value = data.namabarang;

        var kategori = document.getElementById("kategori1");
        for (var i = 0; i < kategori.options.length; i++) {
            if (kategori.options[i].value == data.kategori) {
                kategori.options[i].selected = true;
                break;
            }
        }

        var stok1 = document.getElementById("stok1");
        stok1.value = data.stok;
        $("#stok1").on("input", function() {
            var input = $(this).val();
            var sanitized = input.replace(/[^0-9]/g, '');
            $(this).val(sanitized);
        });

        var harga1 = document.getElementById("harga1");
        harga1.value = data.harga;
        $("#harga1").on("input", function() {
            var input = $(this).val();
            var sanitized = input.replace(/[^0-9]/g, '');
            $(this).val(sanitized);
        }).number(true);
        $("#EditData").submit(function(e) {
            e.preventDefault();

            var dataform = new FormData();
            dataform.append("namabarangril", data.namabarang);
            dataform.append("namabarang", $("#namabarang1").val());
            dataform.append("kategori", $("#kategori1").val());
            dataform.append("stok", $("#stok1").val());
            dataform.append("harga", $("#harga1").val());
            dataform.append("kode", data.kode);

            $.ajax({
                url: "<?= base_url("petugas/barang/edit") ?>",
                data: dataform,
                type: "post",
                processData: false,
                contentType: false,
                success: function() {
                    swal.fire({
                        title: "Sukses Mengubah Data Produk",
                        icon: "success",
                        toast: true,
                        position: "top-end",
                        timer: 3000,
                        timerProgressBar: true,
                        showConfirmButton: false
                    });
                    table.ajax.reload();
                    $("#ModalEdit").modal("hide");
                },
                error: function(xhr, status, error) {
                    swal.fire({
                        title: error,
                        icon: "error"
                    })
                }
            });
        });
    }

    function TambahProduk() {
        $("#ModalTambah").modal("show");
        $("#stok").on("input", function() {
            var input = $(this).val();
            var sanitized = input.replace(/[^0-9]/g, '');
            $(this).val(sanitized);
        });
        $("#harga").on("input", function() {
            var input = $(this).val();
            var sanitized = input.replace(/[^0-9]/g, '');
            $(this).val(sanitized);
        }).number(true);
        $("#TambahData").submit(function(e) {
            e.preventDefault();
            var formdata = new FormData();
            formdata.append("namabarang", $("#namabarang").val());
            formdata.append("kategori", $("#kategori").val());
            formdata.append("stok", $("#stok").val());
            formdata.append("harga", $("#harga").val());

            $.ajax({
                url: "<?= base_url("petugas/barang/tambah") ?>",
                type: "post",
                data: formdata,
                processData: false,
                contentType: false,
                success: function() {
                    swal.fire({
                        title: "Sukses Menambah Data Produk",
                        icon: "success",
                        timer: 3000,
                        showConfirmButton: false,
                        timerProgressBar: true,
                        position: "top-end",
                        toast: true
                    });
                    $("#ModalTambah").modal("hide");
                    table.ajax.reload();
                },
                error: function(xhr, status, error) {
                    swal.fire({
                        title: error,
                        icon: "error"
                    });
                }
            });
        });
    }
</script>