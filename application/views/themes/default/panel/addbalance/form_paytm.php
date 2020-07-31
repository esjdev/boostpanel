<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="w-75 container-fluid padding_top">
    <div class="row justify-content-center">
        <div class="col-sm-6 mt-3">
            <div class="section_tittle text-center" data-aos="fade-up">
                <p>PayTM</p>
                <h2><?= sprintf(lang("starting_payment"), 'PayTM'); ?></h2>

                <h3 class="m-t-60"><?= lang("confirm_payment"); ?></h3>
            </div>

            <?= form_open('paytm/redirect', ['id' => 'payment-paytm']); ?>
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="tab-content">
                        <div class="alert alert-danger alert-dismissible rounded error" style="display:none;" role="alert">
                            <i class="fa fa-exclamation-triangle"></i> <span class="error-message"></span>
                            <a class="close cursor-pointer" aria-label="close">&times;</a>
                        </div> <!-- Alert error -->

                        <input type="hidden" tabindex="1" maxlength="20" size="20" name="ORDER_ID" autocomplete="off" value="<?= $order_id; ?>">
                        <input type="hidden" tabindex="2" maxlength="12" size="12" name="CUST_ID" autocomplete="off" value="<?= $cust_id; ?>">
                        <input type="hidden" tabindex="4" maxlength="12" size="12" name="INDUSTRY_TYPE_ID" autocomplete="off" value="<?= $industry_type_id; ?>">
                        <input type="hidden" tabindex="4" maxlength="12" size="12" name="CHANNEL_ID" autocomplete="off" value="<?= $channel_id; ?>">
                        <input type="hidden" tabindex="10" name="TXN_AMOUNT" value="<?= $amount; ?>">

                        <div class="form-group">
                            <label class="form-label text-dark font-weight-bold"><?= lang('user_information'); ?></label>
                            <input type="text" class="form-control" name="name" value="<?= dataUser(logged(), 'name'); ?>" readonly>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="email" value="<?= logged(); ?>" readonly>
                        </div>

                        <div class="form-group">
                            <div class="text-center fs-14"><?= lang("amount"); ?></div>
                            <div class="font-weight-bol fs-50 text-center">
                                <?= configs('currency_symbol', 'value') . currency_format(session('paytm_amount')); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <?php
                            echo form_submit([
                                'class' => 'genric-btn info-green e-large btn-block radius fs-16',
                                'id' => 'btnSubmit',
                                'type' => 'submit',
                                'value' => lang('pay')
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
    $('#payment-paytm').on('submit', function() {
        $('#btnSubmit').val('<?= lang('loading'); ?> ...').attr("disabled", "disabled");
    });
</script>