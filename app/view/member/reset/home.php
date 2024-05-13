<div class="container">
    <div class="card card-body position-absolute start-50 top-50 translate-middle w-25">
        <div class="row">
            <form id="login">
                <div class="col-lg-12 mb-3 d-flex justify-content-center">
                    <img src="<?= base_url("skin/media/Icon.png") ?>" alt="IconApk" width="100" height="100">
                </div>
                <div class="col-lg-12 mb-3">
                    <div class="form-floating">
                        <input type="email" class="form-control" id="email" placeholder="Email" required>
                        <label for="email">Email</label>
                    </div>
                </div>
                <div class="col-lg-12 mb-3">
                    <h6 class="text-end"><a href="<?= base_url("member/login") ?>">Sudah Tahu Password</a></h6>
                </div>
                <div class="col-lg-12">
                    <button type="submit" class="btn btn-primary w-100">Kirimkan</button>
                </div>
            </form>
        </div>
    </div>
</div>