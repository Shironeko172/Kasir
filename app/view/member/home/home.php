<div class="container">
    <div class="row">
        <div class="col-lg-12 mb-3">
            <div class="card card-body">
                <h2>Selamat Datang Member <?= $sess->member->nama ?> </h2>
            </div>
        </div>
        <div class="col-lg-12 table-responsive">
            <div class="card card-body">
                <h3>Barang Yang Sedang Diskon</h3>
                <hr>
                <table class="table table-stripped">
                    <thead>
                        <tr>
                            <th>Nama Produk</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Diskon</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pm as $data) { ?>
                            <tr>
                                <td><?= $data->namaproduk ?></td>
                                <td><?= $data->kategori ?></td>
                                <td>Rp.<?= number_format($data->harga, 2, ",", ".") ?></td>
                                <td><?= $data->diskon ?>%</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>