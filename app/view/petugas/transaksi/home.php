<div class="container">
    <div class="row">
        <div class="col-lg-12 mb-3">
            <div class="card card-body">
                <div class="row">
                    <div class="col-lg-6 mb-3">
                        <div class="card card-body">
                            <div class="row">
                                <div class="col-lg-12 mb-3">
                                    <label for="produk" class="form-label">Produk</label>
                                    <select class="form-select" id="produk"></select>
                                </div>
                                <div class="col-lg-8 mb-3">
                                    <label for="qty" class="form-label">QTY</label>
                                    <input type="text" class="form-control" id="qty">
                                </div>
                                <div class="col-lg-4">
                                    <label for="Label" class="form-label text-white">Label</label>
                                    <button class="btn btn-outline-primary w-100 tambahkan" type="button">Tambahkan</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card card-body">
                            <div class="row">
                                <div class="col-lg-12 mb-5">
                                    <h1>Harga Produk Satuan</h1>
                                </div>
                                <div class="col-lg-12">
                                    <h1 class="text-end">Rp.<span id="hargaproduk"></span></h1>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 mb-3">
                        <div class="card card-body table-responsive">
                            <table class="table table-stripped">
                                <thead>
                                    <tr>
                                        <th>NO</th>
                                        <th>KODE</th>
                                        <th>ITEM</th>
                                        <th>QTY</th>
                                        <th>HARGA</th>
                                        <th>DISKON</th>
                                        <th>SUBTOTAL</th>
                                    </tr>
                                </thead>
                                <tbody id="tabelbelanja"></tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="card card-body">
                            <div class="row">
                                <div class="col-lg-6">Subtotal : <span id="subtotal"></span></div>
                                <div class="col-lg-6">Total : <span id="total"></span></div>
                                <div class="col-lg-6">Diskon : <span id="diskon"></span></div>
                                <div class="col-lg-12">
                                    <hr>
                                </div>
                                <div class="col-lg-5">
                                    <label for="member" class="form-label">Member (Opsional)</label>
                                    <select class="form-select" id="member"></select>
                                    <span class="form-text">Abaikan Jika Pembeli Tidak Mempunyai Member</span>
                                </div>
                                <div class="col-lg-5">
                                    <label for="uang" class="form-label">Uang Bayar</label>
                                    <input type="text" class="form-control" id="uang">
                                </div>
                                <div class="col-lg-2">
                                    <label for="label" class="form-label text-white">Label</label>
                                    <button class="btn btn-outline-primary w-100 membayar" type="button">Bayar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>