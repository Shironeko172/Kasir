<!DOCTYPE html>
<html lang="en">
<?php $this->getThemeElement("page/html/head", $__forward); ?>

<body class="bg-dark d-flex flex-column justify-content-between " style="height: 100vh;">
    <div>
        <?php $this->getThemeElement("page/html/navbar", $__forward); ?>
        <?= $this->getThemeContent(); ?>
        <?= $this->getJsFooter(); ?>
        <?= $this->getJsReady(); ?>
    </div>
    <div>
        <?php $this->getThemeElement("page/html/footer", $__forward); ?>
    </div>
</body>

</html>