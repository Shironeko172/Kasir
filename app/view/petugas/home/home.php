<style>
    a {
        text-decoration: none;
        color: black;
    }
</style>
<div class="container">
    <div class="row">
        <div class="col-lg-12 mb-3">
            <div class="card card-body">
                <h1 class="text-center">Selamat Datang Petugas <?= $sess->petugas->nama ?></h1>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="card card-body">
                <div class="row">
                    <div class="col-lg-4 mb-2">
                        <a href="<?= base_url("petugas/transaksi") ?>">
                            <div class="card card-body border border-primary">
                                <h3 class="text-center"><i class="bi bi-cart"></i> Tangani Transaksi</h3>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-4 mb-2">
                        <a href="<?= base_url("petugas/produk") ?>">
                            <div class="card card-body border border-primary">
                                <h3 class="text-center"><i class="bi bi-box"></i> Kelola Produk</h3>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-4 mb-2">
                        <div onclick="tambahmember()" class="card card-body border border-primary">
                            <h3 class="text-center"><i class="bi bi-person-vcard"></i> Daftarkan Member</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="tm">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title">Daftarkan Member</h1>
            </div>
            <div class="modal-body">
                <div class="row">
                    <form id="tambah">
                        <div class="col-lg-12 mb-3">
                            <label for="nama" class="modal-form">Nama</label>
                            <input type="text" class="form-control" required id="nama">
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label for="email" class="modal-form">Email</label>
                            <input type="email" class="form-control" required id="email">
                        </div>
                        <div class="col-lg-12">
                            <button type="submit" class="btn btn-primary w-100">Tambahkan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>