<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="w-75 container-fluid padding_top">
    <div class="row justify-content-center">
        <div class="col-sm-6 mt-3">
            <div class="bg-white d-flex justify-content-center shadow p-5 mt-3">
                <div class="d-flex flex-column">
                    <img src="<?= set_image('coinpayments.png'); ?>" class="mx-auto mb-4" width="300">
                    <h5 class="text-center text-secondary font-weight-bold"><?= lang('hello'); ?>, <?= $payee_name; ?>.</h5>
                    <h5 class="text-center text-secondary font-weight-normal"><?= lang('success_your_order_success'); ?></h5><br>

                    <p>
                        <li><b><?= lang('input_transaction_id'); ?>:</b> <?= $payee_transaction; ?></li>
                        <li><b><?= lang('amount_paid'); ?>:</b> <?= currency_format($payee_amount); ?> <?= configs('currency_code', 'value'); ?></li><br>

                        <div class="text-center">
                            <h5 class="text-white bg-primary rounded p-2"><?= lang('total_amount_to_send'); ?></h5><br>

                            <div class="fs-17">
                                <b class="text-danger"><?= lang('amount'); ?></b><br>
                                <?= $payee_amount_in_coin; ?> (<?= $payee_type_coin; ?>)<br><br>

                                <b class="text-danger"><?= lang('send_to_address'); ?></b><br>
                                <?= $payee_address_coin; ?><br><br>

                                <b class="text-dark"><?= strtoupper(lang('or')); ?> QRCODE</b><br>
                                <img src="<?= $payee_qrcode; ?>">
                            </div>
                        </div>
                    </p>

                    <b class="bg-danger p-2 text-white text-center"><?= sprintf(lang('you_have_time_send_payment'), gmdate("H:i:s", $payee_timeout)); ?></b>

                    <div class="alert-not-found alert-info text-dark p-2 rounded text-left" role="alert">
                        <i class="fa fa-exclamation-triangle"></i> <span class="error-message"> <b><?= lang("note"); ?></b></span><br><br>
                        * <?= lang('copy_order_sent_email'); ?><br>
                        * <?= sprintf(lang('track_statuses'), '<a href="' . base_url("transactions/user") . '" target="_blank" class="font-weight-bold">' . lang("clicking_here") . '</a>'); ?><br>
                        * <?= lang('soon_send_payment'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>