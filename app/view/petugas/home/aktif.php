<?php foreach ($mm as $data) {
    date_default_timezone_set("Asia/Jakarta");
    $expire = $data->expire_token;
    $tanggal = date("Y-m-d h:i:s");
    if ($tanggal > $expire) { ?>
        <div class="container">
            <div class="card card-body position-absolute start-50 top-50 translate-middle w-25">
                <h1>Token Sudah Expire</h1>
            </div>
        </div>
    <?php } else if ($data->is_active === 1) {
        redir(base_url("member/login"));
    } else {
    ?>
        <div class="container">
            <div class="card card-body position-absolute start-50 top-50 translate-middle w-25">
                <div class="row">
                    <form id="tambah">
                        <div class="col-lg-12 mb-3 d-flex justify-content-center">
                            <img src="<?= base_url("skin/media/Icon.png") ?>" alt="IconApk" width="100" height="100">
                            <input type="text" value="<?= $data->kode ?>" id="kode" hidden>
                        </div>
                        <div class="col-lg-12 mb-3 input-group">
                            <div class="form-floating">
                                <input type="password" class="form-control" id="newpassword" required placeholder="newpassword">
                                <label for="newpassword">New Password</label>
                            </div>
                            <div class="input-group-text">
                                <span class="bi bi-eye" id="showpass1"></span>
                            </div>
                        </div>
                        <div class="col-lg-12 mb-3 input-group">
                            <div class="form-floating">
                                <input type="password" class="form-control" id="repeatpassword" required placeholder="repeatpassword">
                                <label for="repeatpassword">Repeat Password</label>
                            </div>
                            <div class="input-group-text">
                                <span class="bi bi-eye" id="showpass2"></span>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <button type="submit" class="btn btn-primary w-100">Aktikan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
<?php  }
} ?>