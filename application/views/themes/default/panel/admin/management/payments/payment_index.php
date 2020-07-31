<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="w-75 container-fluid padding_top">
	<div class="row justify-content-center">
		<div class="col-sm-12 mt-3">
			<div class="section_tittle text-center" data-aos="fade-up">
				<p><?= lang("managing_payment_methods"); ?></p>
				<h2><?= lang("menu_payment_integrations"); ?></h2>
			</div>

			<table class="table-responsive-lg table border">
				<thead>
					<tr class="fs-14">
						<th><?= lang("method"); ?></th>
						<th><?= lang("input_name"); ?></th>
						<th><?= lang("min"); ?></th>
						<th><?= lang("max"); ?></th>
						<th><?= lang("status"); ?></th>
						<th class="text-center"><?= lang("action"); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach (payments() as $key => $value) : ?>
						<tr class="<?= (isset($value['CLASS_TABLE']) ? $value['CLASS_TABLE'] : ''); ?>">
							<td class="border"><?= $value['METHOD']; ?></td>
							<td class="border"><?= $value['NAME']; ?></td>
							<td class="border"><?= $value['MIN']; ?></td>
							<td class="border"><?= $value['MAX']; ?></td>
							<td class="border">
								<?= form_open('/admin/payments/update-payment-status/' . $key, ['id' => 'update-payment-status-' . $key]); ?>
								<label class="switch">
									<input type="checkbox" onclick="updateStatusPayment('<?= $key; ?>');" name='payment_status_<?= $key; ?>' <?= $value['STATUS']; ?>>
									<span class="slider round"></span>
								</label>
								<?= form_close(); ?>
							</td>
							<td class="border text-center">
								<?php if ($key != 'manual') : ?>
									<a href="javascript:void();" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#editPayment-<?= $key; ?>" data-backdrop="static"><?= lang('edit'); ?></a>
								<?php endif; ?>
								</th>
						</tr>

						<!-- Modal Edit Payment -->
						<div class="modal fade" id="editPayment-<?= $key; ?>" tabindex="-1">
							<div class="modal-dialog">
								<div class="modal-content bg-white">
									<div class="modal-header">
										<h4 class="modal-title font-weight-bold text-dark"><?= lang("edit_payment"); ?> <?= $value['NAME']; ?></h4>
										<button type="button" class="close" data-dismiss="modal">
											<span>&times;</span>
										</button>
									</div>
									<div class="modal-body">
										<div class="alert alert-danger alert-dismissible rounded error" style="display:none;" role="alert">
											<i class="fa fa-exclamation-triangle"></i> <span class="error-message"></span>
											<a class="close cursor-pointer" aria-label="close">&times;</a>
										</div> <!-- Alert error -->

										<div class="alert alert-success alert-dismissible rounded success" style="display:none;" role="alert">
											<i class="fa fa-thumbs-up"></i> <span class="success-message"></span>
											<a class="close cursor-pointer" aria-label="close">&times;</a>
										</div> <!-- Alert success -->

										<?= form_open('/admin/payment/edit/' . $key, ['class' => 'editPayment']); ?>
										<?php $type = ($key == 'twocheckout' ? '2checkout' : $key);
										if (!in_array($key, ['stripe', 'skrill', 'mollie', 'razorpay'])) : ?>
											<div class="form-group">
												<?php
												echo form_label(lang('environment'), 'edit_environment_payment', [
													'class' => 'text-muted font-weight-bold'
												]);

												echo '<select class="form-control" name="edit_environment_payment">';
												echo '<option value="Sandbox" ' . (config_payment('' . $type . '_environment', 'value') == 'Sandbox' ? 'selected' : '') . '>Sandbox (test)</option>';
												echo '<option value="Live" ' . (config_payment('' . $type . '_environment', 'value') == 'Live' ? 'selected' : '') . '>Live</option>';
												echo '</select>';
												?>
											</div>
										<?php endif; ?>
										<div class="form-group">
											<?php
											echo form_label(lang("input_name"), 'edit_name_payment', [
												'class' => 'text-muted font-weight-bold'
											]);

											echo form_input([
												'name' => 'edit_name_payment',
												'class' => 'form-control',
												'type' => 'text',
												'value' => config_payment('' . $type . '_name', 'value')
											]);
											?>
										</div>
										<div class="form-group">
											<?php
											echo form_label(lang('minimal_payment'), '' . $type . '_minimal_payment', [
												'class' => 'text-muted font-weight-bold'
											]);

											echo form_input([
												'name' => 'edit_minimal_payment',
												'class' => 'form-control',
												'type' => 'text',
												'value' => number_format(config_payment('' . $type . '_min_payment', 'value'), 2, '.', '')
											]);
											?>
										</div>
										<div class="form-group">
											<?php
											echo form_label(lang('maximal_payment'), '' . $type . '_maximal_payment', [
												'class' => 'text-muted font-weight-bold'
											]);

											echo form_input([
												'name' => 'edit_maximal_payment',
												'class' => 'form-control',
												'type' => 'text',
												'value' => number_format(config_payment('' . $type . '_max_payment', 'value'), 2, '.', '')
											]);
											?>
											<div class="badge badge-danger mt-3"><i class="fa fa-exclamation-triangle"></i> <?= lang("warning_maximal_payment_unlimited"); ?></div>
										</div>
										<hr>
										<?php if ($key == 'paypal') : ?>
											<div class="form-group">
												<?php
												echo form_label("Client ID", 'edit_client_id_payment', [
													'class' => 'text-muted font-weight-bold'
												]);

												echo form_input([
													'name' => 'edit_client_id_payment',
													'class' => 'form-control',
													'type' => 'text',
													'value' => (DEMO_VERSION != true ? config_payment('paypal_client_id', 'value') : lang('demo_hidden'))
												]);
												?>
											</div>
											<div class="form-group">
												<?php
												echo form_label("Client Secret", 'edit_client_secret_payment', [
													'class' => 'text-muted font-weight-bold'
												]);

												echo form_input([
													'name' => 'edit_client_secret_payment',
													'class' => 'form-control',
													'type' => 'text',
													'value' => (DEMO_VERSION != true ? config_payment('paypal_client_secret', 'value') : lang('demo_hidden'))
												]);
												?>
											</div>
										<?php endif; ?>

										<?php if ($key == 'pagseguro') : ?>
											<div class="form-group">
												<?php
												echo form_label(lang('input_email'), 'edit_email_payment', [
													'class' => 'text-muted font-weight-bold'
												]);

												echo form_input([
													'name' => 'edit_email_payment',
													'class' => 'form-control',
													'type' => 'text',
													'value' => (DEMO_VERSION != true ? config_payment('pagseguro_email', 'value') : lang('demo_hidden'))
												]);
												?>
											</div>
											<div class="form-group">
												<?php
												echo form_label("Token", 'edit_token_payment', [
													'class' => 'text-muted font-weight-bold'
												]);

												echo form_input([
													'name' => 'edit_token_payment',
													'class' => 'form-control',
													'type' => 'text',
													'value' => (DEMO_VERSION != true ? config_payment('pagseguro_token', 'value') : lang('demo_hidden'))
												]);
												?>
											</div>
										<?php endif; ?>

										<?php if ($key == 'mercadopago') : ?>
											<div class="form-group">
												<?php
												echo form_label("Access Token", 'edit_accesstoken_payment', [
													'class' => 'text-muted font-weight-bold'
												]);

												echo form_input([
													'name' => 'edit_accesstoken_payment',
													'class' => 'form-control',
													'type' => 'text',
													'value' => (DEMO_VERSION != true ? config_payment('mercadopago_access_token', 'value') : lang('demo_hidden'))
												]);
												?>
											</div>
										<?php endif; ?>

										<?php if ($key == 'stripe') : ?>
											<div class="form-group">
												<?php
												echo form_label("Publishable Key", 'edit_publishable_key_payment', [
													'class' => 'text-muted font-weight-bold'
												]);

												echo form_input([
													'name' => 'edit_publishable_key_payment',
													'class' => 'form-control',
													'type' => 'text',
													'value' => (DEMO_VERSION != true ? config_payment('stripe_publishable_key', 'value') : lang('demo_hidden'))
												]);
												?>
											</div>

											<div class="form-group">
												<?php
												echo form_label("Secret Key", 'edit_secret_key_payment', [
													'class' => 'text-muted font-weight-bold'
												]);

												echo form_input([
													'name' => 'edit_secret_key_payment',
													'class' => 'form-control',
													'type' => 'text',
													'value' => (DEMO_VERSION != true ? config_payment('stripe_secret_key', 'value') : lang('demo_hidden'))
												]);
												?>
											</div>
										<?php endif; ?>

										<?php if ($key == 'twocheckout') : ?>
											<div class="form-group">
												<?php
												echo form_label("Publishable Key", 'edit_publishable_key_payment', [
													'class' => 'text-muted font-weight-bold'
												]);

												echo form_input([
													'name' => 'edit_publishable_key_payment',
													'class' => 'form-control',
													'type' => 'text',
													'value' => (DEMO_VERSION != true ? config_payment('2checkout_publishable_key', 'value') : lang('demo_hidden'))
												]);
												?>
											</div>

											<div class="form-group">
												<?php
												echo form_label("Private Key", 'edit_private_key_payment', [
													'class' => 'text-muted font-weight-bold'
												]);

												echo form_input([
													'name' => 'edit_private_key_payment',
													'class' => 'form-control',
													'type' => 'text',
													'value' => (DEMO_VERSION != true ? config_payment('2checkout_private_key', 'value') : lang('demo_hidden'))
												]);
												?>
											</div>

											<div class="form-group">
												<?php
												echo form_label("Seller ID", 'edit_seller_id_payment', [
													'class' => 'text-muted font-weight-bold'
												]);

												echo form_input([
													'name' => 'edit_seller_id_payment',
													'class' => 'form-control',
													'type' => 'text',
													'value' => (DEMO_VERSION != true ? config_payment('2checkout_seller_id', 'value') : lang('demo_hidden'))
												]);
												?>
											</div>
										<?php endif; ?>

										<?php if ($key == 'coinpayments') : ?>
											<div class="form-group">
												<?php
												echo form_label("Public Key", 'edit_public_key_payment', [
													'class' => 'text-muted font-weight-bold'
												]);

												echo form_input([
													'name' => 'edit_public_key_payment',
													'class' => 'form-control',
													'type' => 'text',
													'value' => (DEMO_VERSION != true ? config_payment('coinpayments_public_key', 'value') : lang('demo_hidden'))
												]);
												?>
											</div>
											<div class="form-group">
												<?php
												echo form_label("Private Key", 'edit_private_key_payment', [
													'class' => 'text-muted font-weight-bold'
												]);

												echo form_input([
													'name' => 'edit_private_key_payment',
													'class' => 'form-control',
													'type' => 'text',
													'value' => (DEMO_VERSION != true ? config_payment('coinpayments_private_key', 'value') : lang('demo_hidden'))
												]);
												?>
											</div>
										<?php endif; ?>

										<?php if ($key == 'skrill') : ?>
											<div class="form-group">
												<?php
												echo form_label("Skrill Merchant Email", 'edit_email_skrill_payment', [
													'class' => 'text-muted font-weight-bold'
												]);

												echo form_input([
													'name' => 'edit_email_skrill_payment',
													'class' => 'form-control',
													'type' => 'text',
													'value' => (DEMO_VERSION != true ? config_payment('skrill_email', 'value') : lang('demo_hidden'))
												]);
												?>
											</div>
										<?php endif; ?>
										<?php if ($key == 'payumoney') : ?>
											<div class="form-group">
												<?php
												echo form_label("Merchant Key:", 'edit_merchant_key_payment', [
													'class' => 'text-muted font-weight-bold'
												]);

												echo form_input([
													'name' => 'edit_merchant_key_payment',
													'class' => 'form-control',
													'type' => 'text',
													'value' => (DEMO_VERSION != true ? config_payment('payumoney_merchant_key', 'value') : lang('demo_hidden'))
												]);
												?>
											</div>

											<div class="form-group">
												<?php
												echo form_label("Merchant Salt", 'edit_merchant_salt_payment', [
													'class' => 'text-muted font-weight-bold'
												]);

												echo form_input([
													'name' => 'edit_merchant_salt_payment',
													'class' => 'form-control',
													'type' => 'text',
													'value' => (DEMO_VERSION != true ? config_payment('payumoney_merchant_salt', 'value') : lang('demo_hidden'))
												]);
												?>
											</div>
										<?php endif; ?>
										<?php if ($key == 'paytm') : ?>
											<div class="form-group">
												<?php
												echo form_label("Merchant Key:", 'edit_paytm_merchant_key_payment', [
													'class' => 'text-muted font-weight-bold'
												]);

												echo form_input([
													'name' => 'edit_paytm_merchant_key_payment',
													'class' => 'form-control',
													'type' => 'text',
													'value' => (DEMO_VERSION != true ? config_payment('paytm_merchant_key', 'value') : lang('demo_hidden'))
												]);
												?>
											</div>

											<div class="form-group">
												<?php
												echo form_label("Merchant ID", 'edit_merchant_id_payment', [
													'class' => 'text-muted font-weight-bold'
												]);

												echo form_input([
													'name' => 'edit_merchant_id_payment',
													'class' => 'form-control',
													'type' => 'text',
													'value' => (DEMO_VERSION != true ? config_payment('paytm_merchant_mid', 'value') : lang('demo_hidden'))
												]);
												?>
											</div>

											<div class="form-group">
												<?php
												echo form_label("Merchant Website", 'edit_merchant_website_payment', [
													'class' => 'text-muted font-weight-bold'
												]);

												echo form_input([
													'name' => 'edit_merchant_website_payment',
													'class' => 'form-control',
													'type' => 'text',
													'value' => (DEMO_VERSION != true ? config_payment('paytm_merchant_website', 'value') : lang('demo_hidden'))
												]);
												?>
											</div>
										<?php endif; ?>
										<?php if ($key == 'instamojo') : ?>
											<div class="form-group">
												<?php
												echo form_label("API Key:", 'edit_api_key_payment', [
													'class' => 'text-muted font-weight-bold'
												]);

												echo form_input([
													'name' => 'edit_api_key_payment',
													'class' => 'form-control',
													'type' => 'text',
													'value' => (DEMO_VERSION != true ? config_payment('instamojo_api_key', 'value') : lang('demo_hidden'))
												]);
												?>
											</div>

											<div class="form-group">
												<?php
												echo form_label("Auth Token", 'edit_auth_token_payment', [
													'class' => 'text-muted font-weight-bold'
												]);

												echo form_input([
													'name' => 'edit_auth_token_payment',
													'class' => 'form-control',
													'type' => 'text',
													'value' => (DEMO_VERSION != true ? config_payment('instamojo_auth_token', 'value') : lang('demo_hidden'))
												]);
												?>
											</div>
										<?php endif; ?>
										<?php if ($key == 'mollie') : ?>
											<div class="form-group">
												<?php
												echo form_label("API Key:", 'edit_mollie_api_key_payment', [
													'class' => 'text-muted font-weight-bold'
												]);

												echo form_input([
													'name' => 'edit_mollie_api_key_payment',
													'class' => 'form-control',
													'type' => 'text',
													'value' => (DEMO_VERSION != true ? config_payment('mollie_api_key', 'value') : lang('demo_hidden'))
												]);
												?>
											</div>
										<?php endif; ?>
										<?php if ($key == 'razorpay') : ?>
											<div class="form-group">
												<?php
												echo form_label("Key Id:", 'edit_key_id_payment', [
													'class' => 'text-muted font-weight-bold'
												]);

												echo form_input([
													'name' => 'edit_key_id_payment',
													'class' => 'form-control',
													'type' => 'text',
													'value' => (DEMO_VERSION != true ? config_payment('razorpay_key_id', 'value') : lang('demo_hidden'))
												]);
												?>
											</div>

											<div class="form-group">
												<?php
												echo form_label("Key Secret:", 'edit_key_secret_payment', [
													'class' => 'text-muted font-weight-bold'
												]);

												echo form_input([
													'name' => 'edit_key_secret_payment',
													'class' => 'form-control',
													'type' => 'text',
													'value' => (DEMO_VERSION != true ? config_payment('razorpay_key_secret', 'value') : lang('demo_hidden'))
												]);
												?>
											</div>
										<?php endif; ?>
										<hr>
										<div class="form-group">
											<?php
											echo form_submit([
												'class' => 'genric-btn info-green e-large btn-block radius fs-16',
												'type' => 'submit',
												'value' => lang("save")
											]);
											?>
										</div>
										<?= form_close(); ?>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-danger" data-dismiss="modal"><?= lang("close"); ?></button>
									</div>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>