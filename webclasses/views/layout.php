<!DOCTYPE html>
<html lang="<?php echo $lang ?>">
    <head>
        <meta charset="utf-8" />
        <title><?php echo $title; ?></title>
        <!--[if lt IE 9]>
        <script src="<?php echo site_url() ?>/js/html5/html5.js"></script>
        <script src="<?php echo site_url() ?>/js/html5/html5-print.js"></script>
        <![endif]-->
    </head>
    <body>
        <?php include (prepare_view($mainregion)); ?>
    </body>
</html>