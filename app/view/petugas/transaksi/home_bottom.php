<script>
    let base_url = "<?= base_url() ?>";
    var members;
    $(document).ready(function() {
        var daftarbelanja = [];
        $("#qty").number(true);
        $("#uang").number(true);

        $(".tambahkan").click(function() {
            var namaproduk = $("#produk option:selected").text();
            var qty = $("#qty").val();
            var jumlah = parseInt(qty);
            var kode = $("#produk option:selected").data('kode');
            var stok = $("#produk option:selected").data('stok');
            var harga = $("#produk option:selected").data('harga');
            var diskon = $("#produk option:selected").data('diskon');

            if (namaproduk === "-- Pilih Produk Di Sini --") {
                swal.fire("Pilih Produk Terlebih Dahulu");
                return;
            } else if (qty === "" || qty === "0") {
                swal.fire("Isi Jumlah Produk Yang Di Beli");
                return;
            } else if (jumlah > stok) {
                swal.fire("Stok Yang Tersedia Di Hanya " + stok);
                return;
            }

            tambahkedaftarbelanja(kode, namaproduk, jumlah, harga, diskon, stok);
            $("#qty").val('');
        });

        function tambahkedaftarbelanja(kode, namaproduk, jumlah, harga, diskon, stok) {
            var sudahada = daftarbelanja.find(item => item.kode === kode);
            if (sudahada) {
                sudahada.jumlah = sudahada.jumlah + jumlah;
                sudahada.totalharga = sudahada.harga * sudahada.jumlah;
                sudahada.subtotal = (sudahada.harga - (sudahada.harga * sudahada.diskon / 100)) * sudahada.jumlah;
            } else {
                var totalharga = harga * jumlah;
                var subtotal = (harga - (harga * diskon / 100)) * jumlah;
                daftarbelanja.push({
                    kode: kode,
                    namaproduk: namaproduk,
                    jumlah: jumlah,
                    harga: harga,
                    diskon: diskon,
                    totalharga: totalharga,
                    subtotal: subtotal
                });
            }

            var newstok = stok - jumlah;
            $("#produk option:selected").data('stok', newstok);

            render();
            hitungdiskon();
        }

        function render() {
            $("#tabelbelanja").empty();
            daftarbelanja.forEach(function(item, index) {
                var hargasatuan = $.number(item.harga, 2, ",", ".");
                var subtotal = $.number(item.subtotal, 2, ",", ".");
                var rowdata = "<tr><td>" + (index + 1) + "</td><td>" + item.kode + "</td><td>" + item.namaproduk + "</td><td>" + item.jumlah + "</td><td>" + hargasatuan + "</td><td>" + item.diskon + "%</td><td>" + subtotal + "</td></tr>";
                $("#tabelbelanja").append(rowdata);
            });
        }

        function hitungdiskon() {
            var jumlah = daftarbelanja.reduce((acc, item) => acc + item.jumlah, 0);
            var subtotal = daftarbelanja.reduce((acc, item) => acc + item.subtotal, 0);
            var diskon = 0;
            var total = subtotal - diskon;
            var selectedmember = $("#member").find("option:selected");
            <?php foreach ($mm as $diskon) { ?>
                if (subtotal >= <?= $diskon->hargamin ?> && subtotal <= <?= $diskon->hargamax ?> && jumlah >= <?= $diskon->barangmin ?> && jumlah <= <?= $diskon->barangmax ?> && selectedmember.val()) {
                    diskon = <?= $diskon->diskon ?>
                }
            <?php } ?>

            var discountamount = subtotal * (diskon / 100);
            total = subtotal - discountamount;

            $("#subtotal").text(subtotal).number(true, 2, ",", ".");
            $("#diskon").text(diskon + "%");
            $("#total").text(total).number(true, 2, ",", ".");

            return {
                subtotal: subtotal,
                diskon: discountamount,
                total: total,
            };
        }

        $("#tabelbelanja").on("click", "tr", function() {
            var rowindex = $(this).index();
            var selected = daftarbelanja[rowindex];
            var stok = $("#produk option:selected").data("stok");
            if (selected) {
                if (selected.jumlah > 1) {
                    selected.jumlah -= 1;
                    selected.totalharga = selected.harga * selected.jumlah;
                    selected.subtotal = (selected.harga - (selected.harga * selected.diskon / 100)) * selected.jumlah;
                } else {
                    daftarbelanja.splice(rowindex, 1);
                }
                var newstok = stok + 1;
                $("#produk option:selected").data('stok', newstok);
                render();
                hitungdiskon();
            }
        });

        function joson() {
            var data = [];
            $("#tabelbelanja tr").each(function() {
                var row = {};
                $(this).find("td").each(function(index, cell) {
                    var columnname = $("thead th").eq(index).text().toLowerCase();
                    row[columnname] = $(cell).text();
                });
                data.push(row);
            });
            return data;
        }

        $(".membayar").click(function(e) {
            e.preventDefault();
            if (daftarbelanja.length === 0) {
                swal.fire("Harap Isi Terlebih Dahulu Produk Yang akan Di Beli");
                return;
            }
            var petugas = "<?= $sess->petugas->nama ?>";
            var member = $("#member option:selected").text();
            if (member === "-- Pilih Member Di Sini --") {
                member = '-';
            }
            var barang = joson();
            var hasilhitung = hitungdiskon();
            var subtotal = hasilhitung.subtotal;
            var diskon = hasilhitung.diskon;
            var total = hasilhitung.total;
            var uang = $("#uang").val();
            var kembalian = uang - total;
            if (uang < total) {
                swal.fire("Uang Pembayaran Tidak Cukup");
                return;
            }


            daftarbelanja = [];
            $.ajax({
                url: base_url + "petugas/transaksi/proses",
                data: {
                    petugas: petugas,
                    member: member,
                    barang: JSON.stringify(barang),
                    subtotal: subtotal,
                    diskon: diskon,
                    total: total,
                    uang: uang,
                    kembalian: kembalian
                },
                type: "post",
                success: function(struk) {
                    swal.fire({
                        html: struk,
                        allowOutsideClick: false,
                        confirmButtonText: "Print"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.body.innerHTML = struk;
                            window.print();
                            location.reload();
                        }
                    })
                },
                error: function(xhr, status, error) {
                    swal.fire({
                        title: error,
                        icon: "error"
                    })
                }
            })
        });

        $.ajax({
            url: base_url + "petugas/transaksi/produk",
            type: "get",
            dataType: "json",
            success: function(data) {
                $("#produk").empty();
                $("#produk").append("<option value=''>-- Pilih Produk Di Sini --</option>");

                data.forEach(function(item) {
                    var option = new Option(item.namaproduk);
                    $(option).data('namaproduk', item.namaproduk);
                    $(option).data('kode', item.kode);
                    $(option).data('harga', item.harga);
                    $(option).data('stok', item.stok);
                    $(option).data('diskon', item.diskon);

                    $("#produk").append(option);
                });

                $("#produk option").each(function() {
                    var option = $(this);
                    var stok = parseInt(option.data('stok'));
                    if (stok === 0) {
                        option.append(" (Stok Telah Habis)");
                        option.prop('disabled', true);
                    }
                });

                $("#produk").on("change", function() {
                    var option = $(this).find("option:selected");
                    var hargaproduk = option.data('harga');
                    var harga = $.number(hargaproduk, 2, ",", ".");
                    $("#hargaproduk").text(harga);
                });

                $("#produk").select2({
                    theme: "bootstrap-5"
                });
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
        $.ajax({
            url: base_url + "petugas/transaksi/member",
            type: "get",
            dataType: "json",
            success: function(data) {
                $("#member").empty();
                $("#member").append("<option value='' selected>-- Pilih Member Di Sini --</option>");

                data.forEach(function(item) {
                    var option = new Option(item.nama);
                    $(option).data('nama');

                    $("#member").append(option);
                });

                $("#member").on("change", function() {
                    hitungdiskon();
                });

                $("#member").select2({
                    theme: "bootstrap-5"
                });
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });

        window.addEventListener("beforeunload", function(e) {
            if (daftarbelanja.length > 0) {
                var message = "message";
                (e || window.event).returnValue = message;
                return message;
            }
        });
    });
</script>