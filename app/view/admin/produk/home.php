<div class="container">
    <div class="card card-body">
        <div class="row">
            <div class="col-lg-12">
                <h2 class="card-title"><i class="bi bi-table"></i> Daftar Produk</h2>
                <hr>
            </div>
            <div class="col-lg-10">
                <button onclick="tambahdata()" class="btn btn-primary"><i class="bi bi-plus"></i> Tambah Produk</button>
            </div>
            <div class="col-lg-2">
                <button onclick="exportexcel()" class="btn btn-success"><i class="bi bi-filetype-xlsx"></i> Export Data Ke Excel</button>
            </div>
            <div class="col-lg-12">
                <hr>
            </div>
            <div class="col-lg-12 table-responsive">
                <table class="table table-stripped table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
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