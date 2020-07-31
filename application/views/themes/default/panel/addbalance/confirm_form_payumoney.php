<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="w-75 container-fluid padding_top">
    <div class="row justify-content-center">
        <div class="col-sm-6 mt-3">
            <div class="section_tittle text-center" data-aos="fade-up">
                <p>PayUmoney</p>
                <h2><?= sprintf(lang("starting_payment"), 'PayUmoney'); ?></h2>

                <h3 class="m-t-60"><?= lang("confirm_payment"); ?></h3>
            </div>

            <?= form_open($action . "/_payment", ['id' => 'payment-confirm-payumoney']); ?>
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="tab-content">
                        <div class="alert alert-danger alert-dismissible rounded error" style="display:none;" role="alert">
                            <i class="fa fa-exclamation-triangle"></i> <span class="error-message"></span>
                            <a class="close cursor-pointer" aria-label="close">&times;</a>
                        </div> <!-- Alert error -->

                        <input type="hidden" name="key" value="<?= $key; ?>">
                        <input type="hidden" name="txnid" value="<?= $tid; ?>">
                        <input type="hidden" name="amount" value="<?= $amount ?>">
                        <input type="hidden" name="productinfo" value="<?= $productinfo; ?>">

                        <div class="form-group">
                            <label class="form-label text-dark font-weight-bold"><?= lang('user_information'); ?></label>
                            <input type="text" class="form-control" name="firstname" value="<?= $name; ?>" readonly>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="email" value="<?= $email; ?>" readonly>
                        </div>

                        <div class="form-group">
                            <label class="form-label text-dark font-weight-bold"><?= lang('phone'); ?></label>
                            <input type="number" name="phone" class="form-control" value="<?= $phone; ?>" readonly>
                        </div>

                        <div class="form-group">
                            <input type="hidden" name="surl" value="<?= $success; ?>">
                            <input type="hidden" name="furl" value="<?= $failure; ?>">
                            <input type="hidden" name="cancel" value="<?= $cancel; ?>">
                            <input type="hidden" name="hash" value="<?= $hash; ?>">
                            <input type="hidden" name="service_provider" value="">

                            <?php
                            echo form_submit([
                                'class' => 'genric-btn info-green e-large btn-block radius fs-16',
                                'id' => 'btnSubmit',
                                'type' => 'submit',
                                'value' => lang('pay') . ' ' . configs('currency_symbol', 'value') . currency_format(session('payumoney_amount'))
                            ]);
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<script>
    $('#payment-confirm-payumoney').on('submit', function() {
        $('#btnSubmit').val('<?= lang('loading'); ?> ...').attr("disabled", "disabled");
    });
</script>