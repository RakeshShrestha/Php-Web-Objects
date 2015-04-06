<!DOCTYPE html>
<html lang="<?php echo $lang ?>">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <meta name="HandheldFriendly" content="true" />
        <meta charset="utf-8" />
        <title><?php echo $title; ?></title>
    </head>
    <body>
        <?php include (prepare_view($mainregion)); ?>
    </body>
</html>