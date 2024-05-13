<div class="container">
    <div class="card card-body">
        <div class="row">
            <div class="col-lg-12">
                <h2 class="card-title"><i class="bi bi-table"></i> Daftar Member Dan Diskon Khusus Member</h2>
                <hr>
            </div>
            <div class="col-lg-9">
                <button onclick="tambahdata()" class="btn btn-primary"><i class="bi bi-plus"></i> Tambah Member</button>
            </div>
            <div class="col-lg-3">
                <button onclick="diskonmember()" class="btn btn-primary w-100"><i class="bi bi-percent"></i> Tambah Diskon Khusus Member</button>
            </div>
            <div class="col-lg-12">
                <hr>
                <div class="input-group d-flex justify-content-center">
                    <button id="membwer" class="btn btn-light border border-dark"><i class="bi bi-file-person"></i> Member</button>
                    <button id="diskonn" class="btn btn-light border border-dark"><i class="bi bi-percent"></i> Diskon</button>
                </div>
                <hr>
            </div>
            <div class="col-lg-12 table-responsive" id="tablemember">
                <table class="table table-stripped table-hover" id="tabelmember">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Foto</th>
                            <th>Nama</th>
                            <th>Alamat</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <div class="col-lg-12 table-responsive d-none" id="tablediskon">
                <table class="table table-stripped table-hover w-100" id="tabeldiskon">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Diskon</th>
                            <th>Barang Minimal</th>
                            <th>Barang Max</th>
                            <th>Harga Minimal</th>
                            <th>Harga Maksimal</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalform">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title"></h3>
            </div>
            <div class="modal-body"></div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalaction">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title"></h3>
            </div>
            <div class="modal-body"></div>
        </div>
    </div>
</div>