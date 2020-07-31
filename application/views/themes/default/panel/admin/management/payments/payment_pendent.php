<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="w-75 container-fluid padding_top">
	<div class="row justify-content-center">
		<div class="col-sm-6">
			<div class="bg-white d-flex justify-content-center mx-auto shadow p-5 mt-3">
				<div class="d-flex flex-column">
					<?php if ($type == 'mollie') : ?>
                        <img src="<?= set_image('mollie.png'); ?>" class="mx-auto mb-4" width="300">
					<?php endif; ?>

					<?php if ($type == 'paytm') : ?>
                        <img src="<?= set_image('paytm.png'); ?>" class="mx-auto mb-4" width="300">
					<?php endif; ?>

					<?php if ($type == 'instamojo') : ?>
                        <img src="<?= set_image('instamojo.png'); ?>" class="mx-auto mb-4" width="300">
					<?php endif; ?>

					<h5 class="text-center text-secondary font-weight-bold"><?= lang('success_order_placed'); ?></h5>
					<h5 class="text-center text-secondary font-weight-normal"><?= lang('success_your_order_pending'); ?></h5>
				</div>
			</div>
		</div>
	</div>
</div>