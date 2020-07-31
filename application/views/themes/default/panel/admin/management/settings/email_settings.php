<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<h3 class="card-title aqua-gradient rounded p-2 text-white"><i class="fa fa-envelope"></i> <?= lang("menu_settings_email_settings"); ?></h3>

<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-sm-12">
			<div class="alert alert-danger alert-dismissible rounded error" style="display:none;" role="alert">
				<i class="fa fa-exclamation-triangle"></i> <span class="error-message"></span>
				<a class="close cursor-pointer" aria-label="close">&times;</a>
			</div> <!-- Alert error -->

			<div class="alert alert-success alert-dismissible rounded success" style="display:none;" role="alert">
				<i class="fa fa-thumbs-up"></i> <span class="success-message"></span>
				<a class="close cursor-pointer" aria-label="close">&times;</a>
			</div> <!-- Alert success -->

			<div class="loading text-center" style="display:none;">
				<svg viewBox="25 25 50 50">
					<circle cx="50" cy="50" r="20"></circle>
				</svg>
			</div> <!-- Loading -->

			<?= form_open('admin/settings/notifications-settings', ['id' => 'update-notifications']); ?>

			<div class="form-group bg-other-blue p-2 rounded">
				<div class="custom-control custom-checkbox">
					<input type="checkbox" class="custom-control-input" id="verification_news_account" name="verification_news_account" value="1" <?= (notification('email_verification_new_account', 'value') == 1 ? 'checked' : ''); ?> <?= DEMO_VERSION == true ? 'disabled' : '' ?>>
					<label class="custom-control-label text-white fs-14" for="verification_news_account"><?= lang("email_verification_new_account"); ?>
						<?php if (DEMO_VERSION == true) : ?>
							<span class="badge badge-danger text-white"><?= lang('disabled_demo_version'); ?></span>
						<?php else: ?>
							<span class="badge badge-success text-white notifications-active-1 <?= (notification('email_verification_new_account', 'value') == 1 ? '' : 'd-none'); ?>"><?= lang('status_active'); ?></span>
						<?php endif; ?>
					</label>
				</div>

				<div class="custom-control custom-checkbox">
					<input type="checkbox" class="custom-control-input" id="new_user_welcome" name="new_user_welcome" value="1" <?= (notification('new_user_welcome', 'value') == 1 ? 'checked' : ''); ?> <?= DEMO_VERSION == true ? 'disabled' : '' ?>>
					<label class="custom-control-label text-white fs-14" for="new_user_welcome"><?= lang("notification_welcome_user"); ?>
						<?php if (DEMO_VERSION == true) : ?>
							<span class="badge badge-danger text-white"><?= lang('disabled_demo_version'); ?></span>
						<?php else: ?>
							<span class="badge badge-success text-white notifications-active-2 <?= (notification('new_user_welcome', 'value') == 1 ? '' : 'd-none'); ?>"><?= lang('status_active'); ?></span>
						<?php endif; ?>
					</label>
				</div>

				<div class="custom-control custom-checkbox">
					<input type="checkbox" class="custom-control-input" id="new_user_notification" name="new_user_notification" value="1" <?= (notification('new_user_notification', 'value') == 1 ? 'checked' : ''); ?> <?= DEMO_VERSION == true ? 'disabled' : '' ?>>
					<label class="custom-control-label text-white fs-14" for="new_user_notification"><?= lang("notification_new_user"); ?>
						<?php if (DEMO_VERSION == true) : ?>
							<span class="badge badge-danger text-white"><?= lang('disabled_demo_version'); ?></span>
						<?php else: ?>
							<span class="badge badge-success text-white notifications-active-3 <?= (notification('new_user_notification', 'value') == 1 ? '' : 'd-none'); ?>"><?= lang('status_active'); ?></span>
						<?php endif; ?>
					</label>
				</div>

				<div class="custom-control custom-checkbox">
					<input type="checkbox" class="custom-control-input" id="notification_ticket" name="notification_ticket" value="1" <?= (notification('notification_ticket', 'value') == 1 ? 'checked' : ''); ?> <?= DEMO_VERSION == true ? 'disabled' : '' ?>>
					<label class="custom-control-label text-white fs-14" for="notification_ticket"><?= lang("notification_ticket_reply"); ?>
						<?php if (DEMO_VERSION == true) : ?>
							<span class="badge badge-danger text-white"><?= lang('disabled_demo_version'); ?></span>
						<?php else: ?>
							<span class="badge badge-success text-white notifications-active-5 <?= (notification('notification_ticket', 'value') == 1 ? '' : 'd-none'); ?>"><?= lang('status_active'); ?></span>
						<?php endif; ?>
					</label>
				</div>

				<div class="custom-control custom-checkbox">
					<input type="checkbox" class="custom-control-input" id="payment_notification" name="payment_notification" value="1" <?= (notification('payment_notification', 'value') == 1 ? 'checked' : ''); ?> <?= DEMO_VERSION == true ? 'disabled' : '' ?>>
					<label class="custom-control-label text-white fs-14" for="payment_notification"><?= lang("payments_notification"); ?>
						<?php if (DEMO_VERSION == true) : ?>
							<span class="badge badge-danger text-white"><?= lang('disabled_demo_version'); ?></span>
						<?php else: ?>
							<span class="badge badge-success text-white notifications-active-6 <?= (notification('payment_notification', 'value') == 1 ? '' : 'd-none'); ?>"><?= lang('status_active'); ?></span>
						<?php endif; ?>
					</label>
				</div>
			</div>
			<?= form_close(); ?>

			<?= form_open('admin/settings/email-settings', ['class' => 'settingsForm']); ?>
			<div class="form-group">
				<?php
				echo form_label(lang("from"), 'email_from', [
					'class' => 'form-text font-weight-bold'
				]);

				echo form_input([
					'name' => 'email_from',
					'class' => 'form-control',
					'type' => 'text',
					'value' => configs("email", "value"),
					'placeholder' => 'email@email.com',
				]);
				?>
			</div>

			<div class="form-group">
				<div class="form-label font-weight-bold"><?= lang("email_protocol"); ?></div>
				<div class="custom-switches-stacked">
					<label class="custom-switch">
						<?php
						echo form_radio([
							'name' => 'email_protocol',
							'class' => 'custom-switch-input',
						], 'mail', null, (configs('protocol', 'value') == 'mail') ? "checked" : '');
						?>
						<span class="custom-switch-indicator"></span>
						<span class="custom-switch-description">PHP Mail</span>
					</label>
					<label class="custom-switch">
						<?php
						echo form_radio([
							'name' => 'email_protocol',
							'class' => 'custom-switch-input',
						], 'smtp', null, (configs('protocol', 'value') == 'smtp') ? "checked" : '');
						?>
						<span class="custom-switch-indicator"></span>
						<span class="custom-switch-description">SMTP <small class="text-danger font-weight-bold">(<?= lang("recommended"); ?>)</small></span>
					</label>
				</div>
			</div>

			<div class="smtp-config <?= (configs('protocol', 'value') == 'smtp') ? "" : 'd-none' ?>">
				<h6 class="card-title bg-primary rounded p-2 text-white rounded shadow"><?= lang("smtp_configuration"); ?></h6>

				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<?php
							echo form_label(lang("smtp_server"), 'smtp_server', [
								'class' => 'form-text'
							]);

							echo form_input([
								'name' => 'smtp_server',
								'class' => 'form-control',
								'type' => 'text',
								'value' => (configs('smtp_host', 'value') != '' ? configs('smtp_host', 'value') : ''),
								'placeholder' => 'smtp.gmail.com',
							]);
							?>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<?php
							echo form_label(lang("smtp_port") . "<small>(25, 465, 587, 2525)</small>", 'smtp_port', [
								'class' => 'form-text'
							]);

							echo form_input([
								'name' => 'smtp_port',
								'class' => 'form-control',
								'type' => 'text',
								'value' => (configs('smtp_port', 'value') != '' ? configs('smtp_port', 'value') : ''),
								'placeholder' => '25, 465, 587, 2525',
							]);
							?>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<?php
							echo form_label(lang("smtp_encryption"), 'smtp_encryption', [
								'class' => 'form-text'
							]);

							echo form_dropdown('smtp_encryption', [
								'none' => lang("none"),
								'ssl' => 'SSL',
								'tls' => 'TLS',
							], (configs('smtp_encryption', 'value') != '' ? configs('smtp_encryption', 'value') : 'none'), 'class="form-control square"');
							?>
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-group">
							<?php
							echo form_label(lang("smtp_username"), 'smtp_username', [
								'class' => 'form-text'
							]);

							echo form_input([
								'name' => 'smtp_username',
								'class' => 'form-control',
								'type' => 'text',
								'value' => (configs('smtp_username', 'value') != '' ? configs('smtp_username', 'value') : ''),
								'placeholder' => lang("smtp_username"),
							]);
							?>
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-group">
							<?php
							echo form_label(lang("smtp_password"), 'smtp_password', [
								'class' => 'form-text'
							]);

							echo form_input([
								'name' => 'smtp_password',
								'class' => 'form-control',
								'type' => 'password',
								'value' => (configs('smtp_password', 'value') != '' ? '*****' : ''),
								'placeholder' => lang("smtp_password"),
							]);
							?>
						</div>
					</div>
				</div>
			</div>

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
	</div>
</div>

<script src="<?= set_js('settings.min.js'); ?>"></script>
