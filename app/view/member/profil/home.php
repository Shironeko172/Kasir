<div class="container">
    <div class="row">
        <div class="col-lg-12 mb-3">
            <div class="card card-body">
                <div class="row">
                    <div class="col-lg-8"></div>
                    <div class="col-lg-2"><button onclick="gantipassword()" class="btn btn-warning w-100"><i class="bi bi-key"></i> Ganti Password</button></div>
                    <div class="col-lg-2"><button onclick="edit()" class="btn btn-warning w-100"><i class="bi bi-pencil-square"></i> Edit</button></div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 table-responsive">
            <div class="card card-body">
                <div class="row">
                    <div class="col-lg-2">
                        <img src="<?= base_url() ?><?= $sess->member->foto ?>" alt="FotoMember" class="border border-dark" width="150">
                    </div>
                    <div class="col-lg-10">
                        <div class="row">
                            <div class="col-lg-12">
                                <h2>Nama : <?= $sess->member->nama ?></h2>
                            </div>
                            <div class="col-lg-12">
                                <h2>Email : <?= $sess->member->email ?></h2>
                            </div>
                            <div class="col-lg-12">
                                <h2>Alamat : <?= $sess->member->alamat ?></h2>
                            </div>
                        </div>
                    </div>
                </div>
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