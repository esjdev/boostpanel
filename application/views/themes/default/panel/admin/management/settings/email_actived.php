<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container padding_top">
	<div class="d-flex justify-content-center p-4">
		<div class="d-flex flex-column">
			<div class="text-center"><i class="fa fa-check-circle fa-5x text-green mt-5 mb-3"></i></div>
			<h5 class="text-center text-secondary font-weight-bold"><?= lang("welcome_to_our_service"); ?></h5>
			<h5 class="text-center text-secondary font-weight-normal"><?= lang("congratulations_actived_account"); ?></h5>
			<a href="<?= base_url('login'); ?>" class="text-center btn btn-green w-50 border-0 mt-4 mx-auto"><?= lang("click_connect_your_account"); ?></a>
		</div>
	</div>
</div>
