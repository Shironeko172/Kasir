<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->getTitle(); ?></title>
    <link rel="icon" href="<?= base_url("skin/media/Icon.png") ?>">

    <?php $this->getAdditionalBefore(); ?>
    <?php $this->getAdditional(); ?>
    <?php $this->getAdditionalAfter(); ?>
</head>