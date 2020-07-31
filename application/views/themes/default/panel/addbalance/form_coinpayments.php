<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="w-75 container-fluid padding_top">
	<div class="row justify-content-center">
		<div class="col-sm-6 mt-3">
			<div class="section_tittle text-center" data-aos="fade-up">
				<p>CoinPayments</p>
				<h2><?= sprintf(lang("starting_payment"), 'CoinPayments'); ?></h2>
			</div>

			<?= form_open('coinpayments/success', ['id' => 'payment-coinpayments']); ?>
			<div class="card shadow-sm">
				<div class="card-body">
					<div class="tab-content">
						<div class="alert alert-danger alert-dismissible rounded error" style="display:none;" role="alert">
							<i class="fa fa-exclamation-triangle"></i> <span class="error-message"></span>
							<a class="close cursor-pointer" aria-label="close">&times;</a>
						</div> <!-- Alert error -->

						<div class="loading text-center" style="display:none;">
							<svg viewBox="25 25 50 50">
								<circle cx="50" cy="50" r="20"></circle>
							</svg>
						</div> <!-- Loading -->

						<div class="form-group">
							<label class="form-label text-dark font-weight-bold"><?= lang("choose_coin"); ?></label>
							<select name="type_coin" class="form-control">
								<option value="no_select" class="font-weight-bold"><?= lang("choose_coin"); ?></option>
								<?php foreach (listCoin() as $key => $value) : ?>
									<option value="<?= $value; ?>"><?= $value; ?> - <?= $key; ?></option>
								<?php endforeach; ?>
							</select>

							<?php if (config_payment('coinpayments_environment', 'value') == 'Live') : ?>
								<div class='mt-3 mb-4 fs-12 text-danger font-weight-bold'><i class="fa fa-exclamation-circle"></i> <?= lang('transaction_fee_included'); ?></div>
							<?php endif; ?>
						</div>

						<div class="form-group">
							<button class="genric-btn info-green e-large btn-block radius fs-16" id="btnSubmit"><?= lang('pay'); ?>
								<?= configs('currency_symbol', 'value'); ?><?= currency_format(session('coinpayments_amount')); ?>
							</button>
						</div>

						<?= form_close(); ?>
					</div>

					<?php if (config_payment('coinpayments_environment', 'value') == 'Sandbox') : ?>
						<div class="bg-danger text-white p-2 rounded" role="alert">
							<i class="fa fa-exclamation-triangle"></i> <span class="error-message-sandbox"> <?= lang('sandbox_enable'); ?></span>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	$("#payment-coinpayments").bind('submit', function(e) {
		e.preventDefault();
		$('.loading').attr('style', 'display:block');
		$('#btnSubmit').html('<?= lang('loading'); ?> ...').attr("disabled", "disabled");
		let req = axios.post(this.action, $(this).serialize()).then(function(response) {
			if (response.data.type == 'error') {
				$("input[name=csrf_boostpanel]").val(response.data.csrf);
				$('.loading').attr('style', 'display:none;');
				$('.error').attr('style', 'display:block;');
				$('.error-message').html(response.data.message);
				$('#btnSubmit').html('<?= lang('pay'); ?> <?= configs('currency_symbol', 'value'); ?><?= currency_format(session('coinpayments_amount')); ?>').removeAttr('disabled');
			} else if (response.data.type == 'success') {
				$("input[name=csrf_boostpanel]").val(response.data.csrf);
				$('.loading').attr('style', 'display:none;');
				$('.error').attr('style', 'display:none;');
				window.location.href = response.data.link;
			}
		});
		req.abort();
		return false;
	});
</script>