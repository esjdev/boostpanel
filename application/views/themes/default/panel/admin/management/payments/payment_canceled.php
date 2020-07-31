<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="w-75 container-fluid padding_top">
	<div class="row justify-content-center">
		<div class="col-sm-6">
			<div class="bg-white d-flex justify-content-center mx-auto shadow p-5 mt-3">
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

					<h5 class="text-center text-secondary font-weight-bold"><?= lang('error_payment_without_success'); ?></h5>
					<h5 class="text-center text-secondary font-weight-normal"><?= lang('error_payment_failed'); ?></h5>
				</div>
			</div>
		</div>
	</div>
</div>