<?php
$cont = getContentByPageName('dashboard_page_text');

if ($cont) {
    ?>
    <div class="information_section">
        <?php
        echo $cont;
        ?>
    </div>
    <?php
}
?>

<div class="inner-box-main sign-in-page">
    This is dashboard
</div>