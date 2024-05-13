<style>
    a {
        text-decoration: none;
        color: black;
    }
</style>
<nav class="navbar navbar-expand-lg bg-body-tertiary mb-3">
    <div class="container-fluid">
        <a href="<?= base_url("member/home") ?>" class="navbar-brand"><img src="<?= base_url("skin/media/Icon.png") ?>" alt="IconApk" width="70" height="70"> YaeStore</a>
        <h1 onclick="offCanvase()" class="bi bi-list"></span>
    </div>
</nav>
<div class="offcanvas offcanvas-start" id="ofucanvasu" style="width: 230px;">
    <div class="offcanvas-body">
        <a href="<?= base_url("member/profil") ?>">
            <h3><img src="<?= base_url() ?><?= $sess->member->foto ?>" alt="Fotomember" width="60" class="rounded-circle border border-dark"> <?= $sess->member->nama; ?></h3>
        </a>
        <hr>
        <ul class="list-group list-group-flush">
            <li class="list-group-item"><a href="<?= base_url("member/home/logout") ?>"><i class="bi bi-door-open"></i> Logout</a></li>
        </ul>
    </div>
</div>
<script>
    function offCanvase() {
        $("#ofucanvasu").offcanvas("show")
    }
</script>