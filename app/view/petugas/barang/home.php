<div class="container">
    <div class="card card-body">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="card-title"><i class="bi bi-table"></i> Daftar Produk</h1>
                <hr>
            </div>
            <div class="col-lg-10">
                <button onclick="TambahProduk()" class="btn btn-primary"><i class="bi bi-plus"></i> Tambah Produk</button>
            </div>
            <div class="col-lg-12">
                <hr>
            </div>
            <div class="col-lg-12">
                <table class="table table-stripped table-hover" id="DataProduk">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Produk</th>
                            <th>Tanggal Masuk</th>
                            <th>Nama Produk</th>
                            <th>Kategori</th>
                            <th>Stok</th>
                            <th>Harga</th>
                            <th>Diskon</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ModalTambah">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <h3 class="modal-title">Tambah Data Produk</h3>
                        <hr>
                    </div>
                    <form id="TambahData">
                        <div class="col-lg-12 mb-3">
                            <label for="namabarang" class="form-label">Nama Produk</label>
                            <input required type="text" class="form-control" id="namabarang" placeholder="Contoh: Figure Sakura Miko">
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label for="kategori" class="form-label">Kategori</label>
                            <div class="input-group">
                                <select type="text" class="form-select" id="kategori">
                                    <option value="">-- Pilih Di Sini --</option>
                                    <?php foreach ($bm as $data) { ?>
                                        <option value="<?= $data->kategori ?>"><?= $data->kategori ?></option>
                                    <?php } ?>
                                </select>
                                <div class="input-group-text">
                                    <span onclick="TambahKategori()"><i class="bi bi-plus"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label for="stok" class="form-label">Stok</label>
                            <div class="input-group">
                                <input required type="text" class="form-control" id="stok" placeholder="Contoh: 90">
                                <div class="input-group-text">
                                    <span>Pcs</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label for="harga" class="form-label">Harga</label>
                            <div class="input-group">
                                <div class="input-group-text">
                                    <span>Rp.</span>
                                </div>
                                <input required type="text" class="form-control" id="harga" placeholder="Contoh: 1,000,000">
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <button type="submit" class="btn btn-primary" style="width: 100%;">Tambahkan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ModalAction">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title"></h3>
            </div>
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ModalEdit">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <h3 class="modal-title"><i class="bi bi-pencil"></i> Edit Data</h3>
                        <hr>
                    </div>
                    <form id="EditData">
                        <div class="col-lg-12 mb-3">
                            <label for="namabarang1" class="form-label">Nama Produk</label>
                            <input required type="text" class="form-control" id="namabarang1" placeholder="Contoh: Figure Sakura Miko">
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label for="kategori1" class="form-label">Kategori</label>
                            <select type="text" class="form-select" id="kategori1">
                                <option value="">-- Pilih Di Sini --</option>
                                <?php foreach ($bm as $data) { ?>
                                    <option value="<?= $data->kategori ?>"><?= $data->kategori ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label for="stok1" class="form-label">Stok</label>
                            <div class="input-group">
                                <input required type="text" class="form-control" id="stok1">
                                <div class="input-group-text">
                                    <span>Pcs</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label for="harga1" class="form-label">Harga</label>
                            <div class="input-group">
                                <div class="input-group-text">
                                    <span>Rp.</span>
                                </div>
                                <input required type="text" class="form-control" id="harga1">
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <button type="submit" class="btn btn-primary" style="width: 100%;">Tambahkan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>