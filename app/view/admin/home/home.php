<div class="container">
    <div class="row">
        <div class="col-lg-4 mb-3">
            <div class="card card-body">
                <table>
                    <tr>
                        <th rowspan="2">
                            <h1 class="bi bi-box"></h1>
                        </th>
                        <th class="text-center h3">Produk Di Gudang</th>
                    </tr>
                    <tr>
                        <?php if ($opm) { ?>
                            <th class="text-center h3"><?= $opm->jumlah ?></th>
                        <?php } ?>
                    </tr>
                </table>
            </div>
        </div>
        <div class="col-lg-4 mb-3">
            <div class="card card-body">
                <table>
                    <tr>
                        <th rowspan="2">
                            <h1 class="bi bi-box"></h1>
                        </th>
                        <th class="text-center h3">Petugas yang Aktif</th>
                    </tr>
                    <tr>
                        <?php if ($pm) { ?>
                            <th class="text-center h3"><?= $pm->jumlah ?></th>
                        <?php } ?>
                    </tr>
                </table>
            </div>
        </div>
        <div class="col-lg-4 mb-3">
            <div class="card card-body">
                <table>
                    <tr>
                        <th rowspan="2">
                            <h1 class="bi bi-box"></h1>
                        </th>
                        <th class="text-center h3">Member Terdaftar</th>
                    </tr>
                    <tr>
                        <?php if ($mm) { ?>
                            <th class="text-center h3"><?= $mm->jumlah ?></th>
                        <?php } ?>
                    </tr>
                </table>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="card card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <h2 class="card-title"><i class="bi bi-clock"></i> History Log</h2>
                        <hr>
                    </div>
                    <div class="col-lg-12 table-responsive">
                        <table class="table table-stripped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Waktu</th>
                                    <th>Pelaku</th>
                                    <th>Kejadian</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>