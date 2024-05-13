<script>
    let data;
    let table;
    $(document).ready(function() {
        table = $("#tabel").DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                url: "<?= base_url("admin/transaksi/getdata") ?>",
                type: "post"
            },
            "columns": [{
                    "data": "id",
                    "render": function(data, type, row, meta) {
                        return meta.row + 1
                    }
                },
                {
                    "data": "nofaktur"
                },
                {
                    "data": "tanggaltransaksi"
                },
                {
                    "data": "petugas"
                },
                {
                    "data": "member"
                },
                {
                    "data": "barang",
                    "render": function(data, type, row) {
                        var joson = JSON.parse(data);
                        var items = joson.map(function(item) {
                            return item.item + " Jumlah: " + item.qty + " Pcs";
                        }).join(", ");
                        return items;
                    }
                },
                {
                    "data": "total",
                    "render": function(data, type, row) {
                        return "Rp." + $.number(data, 2, ",", ".");
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

        $(".table tbody").on("click", "tr", function() {
            data = table.row(this).data();
            $("#modalaction").modal("show");
            $("#modalaction").find(".modal-title").text(data.nofaktur);
            let modalcontent = `
                            <div class="row">
                                <div class="col-lg-12 mb-3">
                                    <button onclick="detail()" class="btn btn-outline-primary w-100"><i class="bi bi-info"></i> Detail</button>
                                </div>
                                <div class="col-lg-12">
                                    <button onclick="perint('${data.nofaktur}')" class="btn btn-outline-primary w-100"><i class="bi bi-receipt"></i> Print Struk</button>
                                </div>
                            </div>`;
            $("#modalaction").find(".modal-body").html(modalcontent);
        })
    });

    function perint(nofaktur) {
        $("#modalaction").modal("hide");
        $.ajax({
            url: "<?= base_url("admin/transaksi/print") ?>",
            data: {
                nofaktur: nofaktur
            },
            type: "post",
            processData: false,
            contentType: false,
            success: function(struk) {
                document.body.innerHTML = struk;
                window.print();
                location.reload();
            },
            error: function(xhr, status, error) {
                swal.fire({
                    title: error,
                    icon: "error"
                })
            }
        })
    }

    function detail() {
        var tbody = "";
        var barang = JSON.parse(data.barang);
        var total = "Rp." + $.number(data.total, 2, ",", ".");
        var diskon = "Rp." + $.number(data.diskon, 2, ",", ".");
        var uang = "Rp." + $.number(data.uang, 2, ",", ".");
        var kembalian = "Rp." + $.number(data.kembalian, 2, ",", ".");
        barang.forEach(function(item) {
            tbody += `<tr>
                        <td>${item.no}</td>
                        <td>${item.kode}</td>
                        <td>${item.item}</td>
                        <td>${item.qty}</td>
                        <td>Rp.${item.harga}</td>
                        <td>${item.diskon}</td>
                        <td>Rp.${item.subtotal}</td>
                    </tr>`;
        });
        var html = `<div class="container">
                        <div class="card card-body position-absolute start-50 top-50 translate-middle">
                            <div class="row">
                                <div class="col-lg-12 mb-3">
                                    <a class="h5" onclick="location.reload()"><- Kembali</a>
                                </div>
                                <div class="col-lg-12 mb-3">
                                    <h2>Detail Pembelian</h2>
                                </div>
                                <div class="col-lg-12">
                                    <h6>No Faktur : ${data.nofaktur}</h6>
                                </div>
                                <div class="col-lg-6">
                                    <h6>Di Layani Oleh : ${data.petugas}</h6>
                                </div>
                                <div class="col-lg-6">
                                    <h6>Di Layani Pada : ${data.tanggaltransaksi}</h6>
                                </div>
                                <div class="col-lg-12">
                                    <h5>Barang Yang Di Beli : </h5>
                                </div>
                                <div class="col-lg-12 mb-3">
                                    <div class="card card-body">
                                        <table class="table table-stripped">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Kode Produk</th>
                                                    <th>Produk</th>
                                                    <th>QTY</th>
                                                    <th>Harga</th>
                                                    <th>Diskon</th>
                                                    <th>Subtotal</th>
                                                </tr>
                                            </thead>
                                            <tbody>${tbody}</tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <h6>Total : ${total}</h6>
                                </div>
                                <div class="col-lg-6">
                                    <h6>Potongan Harga : ${diskon}</h6>
                                </div>
                                <div class="col-lg-6">
                                    <h6>Uang Masuk : ${uang}</h6>
                                </div>
                                <div class="col-lg-6">
                                    <h6>Kembalian : ${kembalian}</h6>
                                </div>
                            </div>
                        </div>
                    </div>`;
        document.body.innerHTML = html;
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
                                        <span class="form-text">Pilih Tanggal Awal Data Transaksi</span>
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
                url: "<?= base_url("admin/transaksi/export") ?>",
                data: formdata,
                type: "post",
                processData: false,
                contentType: false,
                success: function(response) {
                    var json = JSON.parse(response);
                    var wb = XLSX.utils.book_new();
                    var ws = XLSX.utils.json_to_sheet(json);
                    XLSX.utils.book_append_sheet(wb, ws, "Data Transaksi");
                    XLSX.writeFile(wb, "Data Transaksi.xlsx");
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
</script>