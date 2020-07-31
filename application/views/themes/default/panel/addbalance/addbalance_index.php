<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="w-75 container-fluid padding_top">
    <div class="row justify-content-center">
        <div class="col-sm-12 mt-3">
            <div class="section_tittle text-center" data-aos="fade-up">
                <p><?= lang("add_more_balance"); ?></p>
                <h2><?= lang("menu_add_balance"); ?></h2>
            </div>

            <?php if (flashdata('error_transaction')) : ?>
                <div class="alert alert-danger alert-dismissible rounded error" role="alert">
                    <i class="fa fa-exclamation-triangle"></i> <span class="error-message"><?= flashdata('error_transaction'); ?></span>
                    <a class="close cursor-pointer" aria-label="close">&times;</a>
                </div> <!-- Alert error -->
            <?php endif; ?>

            <?php if (!verifyPayments()) : ?>
                <div class="tabs-list">
                    <ul class="nav nav-tabs fs-14">
                        <?php foreach (payments() as $key => $value) : ?>
                            <?php if (isset($value['PERMISSION']) && $value['PERMISSION']) : ?>
                                <li class='nav-item'>
                                    <a class="nav-link ml-1 border shadow-sm font-weight-bold" data-toggle="tab" href="#<?= $key; ?>"><i class="fa fa-credit-card"></i> <?= $value['NAME']; ?></a>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div class="tab-content">
                    <div class="tab-pane fade in active show mt-3">
                        <div class="bg-secondary p-2 text-white rounded text-center fs-15"><i class="fas fa-money-bill-wave fa-lg"></i> <?= lang('choose_form_payment'); ?> <i class="fas fa-money-bill-wave fa-lg"></i></div>
                    </div>

                    <?php foreach (paymentsForm() as $key => $value) : ?>
                        <?php if ($value['STATUS']) : $type = ($key == 'twocheckout' ? '2checkout' : $key); ?>
                            <div id="<?= $key; ?>" class="tab-pane fade mt-5">
                                <?= form_open($value['URL'], ['class' => 'balanceAddForm']); ?>
                                <input type="hidden" id="loading_balance" value="<?= lang('loading'); ?>">
                                <input type="hidden" id="pay_balance" value="<?= lang('pay'); ?>">
                                <div class="row">
                                    <div class="col-sm-7 mx-auto">
                                        <div class="for-group text-center">
                                            <img src="<?= set_image($key . '.png'); ?>" width="300">
                                            <p class="p-t-10"><small><?= sprintf(lang('deposit_submessage'), $value['NAME']); ?></small></p>

                                            <?php if (in_array($key, ['pagseguro', 'mercadopago']) && !in_array(configs('currency_code', 'value'), ['BRL'])) : ?>
                                                <div class="badge badge-pill badge-danger text-white fs-12 p-2 font-weight-bold mb-3 mt-2"><i class="fa fa-exclamation-triangle"></i> <?= sprintf(lang('converter_to_coin_add_balance'), configs('currency_code', 'value'), (isset($value['CURRENCY']) ? $value['CURRENCY'] : '')); ?></div>
                                            <?php endif; ?>
                                        </div>

                                        <div class="alert alert-danger alert-dismissible rounded error" style="display:none;" role="alert">
                                            <i class="fa fa-exclamation-triangle"></i> <span class="error-message"></span>
                                            <a class="close cursor-pointer" aria-label="close">&times;</a>
                                        </div> <!-- Alert error -->

                                        <div class="form-group">
                                            <label class='font-weight-bold text-dark'><?= lang('amount'); ?> (<?= configs('currency_code', 'value'); ?>)</label>
                                            <input class="form-control square" type="number" name="amount" placeholder="<?= configs('currency_symbol', 'value'); ?><?= config_payment($type . '_min_payment', 'value'); ?>">
                                            <input type="hidden" name="payment_method" value="<?= $key; ?>">
                                        </div>

                                        <?php if (!in_array($key, ['twocheckout', 'coinpayments', 'payumoney', 'paytm', 'instamojo', 'mollie', 'razorpay'])) : ?>
                                            <div class='mb-4 fs-12 text-danger font-weight-bold'><i class="fa fa-exclamation-circle"></i> <?= lang('transaction_fee_included'); ?></div>
                                        <?php endif; ?>

                                        <div class="form-group">
                                            <label class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" name="agree" value="1">
                                                <span class="custom-control-label"><?= lang('not_dispute_or_charge_back'); ?></span>
                                            </label>
                                        </div>

                                        <div class="form-actions left">
                                            <button type="submit" id="btnSubmitAddBalance" class="genric-btn info-green e-large btn-block radius fs-16"><?= lang('pay'); ?></button>
                                        </div>
                                    </div>
                                </div>
                                <?= form_close(); ?>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>

                    <div id="manual" class="tab-pane fade mt-3">
                        <div class="bg-info p-2 rounded text-white"><i class="fa fa-info"></i> <?= lang('manual_payment'); ?></div>
                    </div>
                </div>
            <?php else : ?>
                <div class='bg-danger text-white p-2 rounded'><?= lang("error_no_payment_method_available"); ?></div>
            <?php endif; ?>
        </div>
    </div>
</div>