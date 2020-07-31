<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="w-75 container-fluid padding_top">
    <div class="row justify-content-center">
        <div class="col-sm-6 mt-3">
            <div class="bg-white d-flex justify-content-center shadow p-5 mt-3">
                <div class="d-flex flex-column">
                    <?php if ($type == 'paypal') : ?>
                        <img src="<?= set_image('paypal.png'); ?>" class="mx-auto mb-4" width="300">
                    <?php endif; ?>

                    <?php if ($type == 'stripe') : ?>
                        <img src="<?= set_image('stripe.png'); ?>" class="mx-auto mb-4" width="300">
                    <?php endif; ?>

                    <?php if ($type == 'twocheckout') : ?>
                        <img src="<?= set_image('twocheckout.png'); ?>" class="mx-auto mb-4" width="300">
                    <?php endif; ?>

                    <?php if ($type == 'skrill') : ?>
                        <img src="<?= set_image('skrill.png'); ?>" class="mx-auto mb-4" width="300">
                    <?php endif; ?>

                    <?php if ($type == 'payumoney') : ?>
                        <img src="<?= set_image('payumoney.png'); ?>" class="mx-auto mb-4" width="300">
                    <?php endif; ?>

                    <?php if ($type == 'paytm') : ?>
                        <img src="<?= set_image('paytm.png'); ?>" class="mx-auto mb-4" width="300">
                    <?php endif; ?>

                    <?php if ($type == 'instamojo') : ?>
                        <img src="<?= set_image('instamojo.png'); ?>" class="mx-auto mb-4" width="300">
                    <?php endif; ?>

                    <?php if ($type == 'mollie') : ?>
                        <img src="<?= set_image('mollie.png'); ?>" class="mx-auto mb-4" width="300">
                    <?php endif; ?>

                    <?php if ($type == 'razorpay') : ?>
                        <img src="<?= set_image('razorpay.png'); ?>" class="mx-auto mb-4" width="300">
                    <?php endif; ?>

                    <h5 class="text-center text-secondary font-weight-bold"><?= lang('hello'); ?>, <?= $payee_name; ?>.</h5>
                    <h5 class="text-center text-secondary font-weight-normal"><?= lang('success_payment_purchase'); ?></h5><br>
                    <p>
                        <li class="fs-16"><b><?= lang('input_transaction_id'); ?>:</b> <?= $payee_transaction; ?></li>
                        <li class="fs-16"><b><?= lang('amount_paid'); ?>:</b> <?= currency_format($payee_amount_total); ?> <?= configs('currency_code', 'value'); ?></li>
                        <?php if (!in_array($type, ['twocheckout', 'skrill', 'payumoney', 'paytm', 'instamojo', 'mollie', 'razorpay'])) : ?>
                            <li class="fs-16"><b><?= lang('balance_received'); ?> (<?= lang('includes_fee'); ?>):</b> <?= currency_format($payee_amount); ?> <?= configs('currency_code', 'value'); ?></li>
                        <?php endif; ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>