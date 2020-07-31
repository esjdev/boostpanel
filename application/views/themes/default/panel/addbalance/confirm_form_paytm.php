<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="w-75 container-fluid padding_top">
    <div class="row justify-content-center">
        <div class="col-sm-6 mt-3">
            <div class="section_tittle text-center">
                <p>PayTM</p>
                <h2><?= sprintf(lang("starting_payment"), 'PayTM'); ?></h2>

                <h3 class="m-t-60 d-flex justify-content-center"><?= lang("not_refresh_page"); ?></h3>
            </div>

            <div class="loading text-center"></div>

            <?= form_open($action, ['name' => 'confirm_paytm']); ?>
            <?php
            foreach ($paramList as $name => $value) {
                echo '<input type="hidden" name="' . $name . '" value="' . $value . '">';
            }
            ?>
            <input type="hidden" name="CHECKSUMHASH" value="<?php echo $checkSum ?>">
            <?= form_close(); ?>
        </div>
    </div>
</div>

<script>
    document.confirm_paytm.submit();
</script>