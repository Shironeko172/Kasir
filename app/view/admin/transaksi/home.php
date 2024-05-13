<div class="container">
    <div class="card card-body">
        <div class="row">
            <div class="col-lg-12">
                <h2 class="card-title"><i class="bi bi-receipt-cutoff"></i> Mengelola Transaksi</h2>
                <hr>
            </div>
            <div class="col-lg-12">
                <button onclick="exportexcel()" class="btn btn-success"><i class="bi bi-filetype-xlsx"></i> Export Data Ke Excel</button>
                <hr>
            </div>
            <div class="col-lg-12 table-responsive">
                <table class="table table-stripped table-hover" id="tabel">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>No Faktur</th>
                            <th>Tanggal Transaksi</th>
                            <th>Petugas</th>
                            <th>Member</th>
                            <th>Produk Di Beli</th>
                            <th>Total</th>
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