<style>
    a {
        text-decoration: none;
        color: black;
    }
</style>
<nav class="navbar navbar-expand-lg bg-body-tertiary mb-3">
    <div class="container-fluid">
        <a href="<?= base_url("admin/home") ?>" class="navbar-brand"><img src="<?= base_url("skin/media/Icon.png") ?>" alt="IconApk" width="70" height="70"> YaeStore</a>
        <h1 onclick="offCanvase()" class="bi bi-list"></span>
    </div>
</nav>
<div class="offcanvas offcanvas-start" id="ofucanvasu" style="width: 230px;">
    <div class="offcanvas-header">
        <h3><?= $sess->admin->nama; ?></h3>
    </div>
    <div class="offcanvas-body">
        <ul class="list-group list-group-flush">
            <li class="list-group-item"><a href="<?= base_url("admin/produk") ?>"><i class="bi bi-box"></i> Produk</a></li>
            <li class="list-group-item"><a href="<?= base_url("admin/petugas") ?>"><i class="bi bi-person-vcard"></i> Petugas</a></li>
            <li class="list-group-item"><a href="<?= base_url("admin/member") ?>"><i class="bi bi-file-person"></i> Member</a></li>
            <li class="list-group-item"><a href="<?= base_url("admin/transaksi") ?>"><i class="bi bi-receipt-cutoff"></i> Transaksi</a></li>
            <li class="list-group-item"><a href="<?= base_url("admin/home/logout") ?>"><i class="bi bi-door-open"></i> Logout</a></li>
        </ul>
    </div>
</div>
<script>
    function offCanvase() {
        $("#ofucanvasu").offcanvas("show")
    }
</script>