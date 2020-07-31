<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<h3 class="card-title aqua-gradient rounded p-2 text-white"><i class="fa fa-envelope"></i> <?= lang("menu_settings_email_templates_settings"); ?></h3>

<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-sm-12">
			<div class="alert alert-danger fs-11">
				<span class="fs-15"><strong><?= lang("note"); ?>:</strong> <?= lang("tags_meanings"); ?>:</span><br><br>

				<div class="row">
					<div class="col-6">
						<strong>{{app_name}}</strong> - <?= lang("display_tag_your_site_name"); ?><br>
						<strong>{{username}}</strong> - <?= lang("display_tag_username"); ?><br>
						<strong>{{name}}</strong> - <?= lang("display_tag_user_name"); ?><br>
						<strong>{{user_email}}</strong> - <?= lang("display_tag_user_email"); ?><br>
						<strong>{{user_timezone}}</strong> - <?= lang("display_tag_user_timezone"); ?>
					</div>
					<div class="col-6">
						<strong>{{activation_link}}</strong> - <?= lang("display_tag_activation_account_link"); ?><br>
						<strong>{{link_ticket}}</strong> - <?= lang("display_tag_link_ticket"); ?><br>
						<strong>{{transaction_id}}</strong> - <?= lang("display_payment_transaction_id"); ?><br>
						<strong>{{method_payment}}</strong> - <?= lang("display_method_payment"); ?><br>
					</div>
				</div>
			</div>

			<div id="collapse-group">
				<div class="font-weight-bold bg-blue-light cursor-pointer p-2 mt-2 text-white rounded" data-toggle="collapse" data-target="#email_recover_password_collapse"><i class="fa fa-link"></i> <?= lang("email_to_recover_password"); ?></div>

				<div id="email_recover_password_collapse" class="collapse bg-light p-3" data-parent="#collapse-group">
					<?= form_open('admin/settings/email-templates/recover_password_link', ['class' => 'templateEmail']); ?>
					<div class="form-group">
						<?php
						echo form_label(lang("input_subject"), 'recover_password_link_subject', [
							'class' => 'form-text font-weight-bold'
						]);

						echo form_input([
							'name' => 'recover_password_link_subject',
							'class' => 'form-control',
							'type' => 'text',
							'value' => (email_tpl('link_recover_password_subject', 'value') == '') ? email_template('recover_password_link')->subject : email_tpl('link_recover_password_subject', 'value'),
						]);
						?>
					</div>

					<div class="form-group">
						<?php
						echo form_label(lang("content"), 'recover_password_link_content', [
							'class' => 'form-text font-weight-bold'
						]);

						echo form_textarea([
							'name' => 'recover_password_link_content',
							'class' => 'form-control',
							'id' => 'recover_password_link',
							'value' => (email_tpl('link_recover_password_content', 'value') == '') ? email_template('recover_password_link')->content : email_tpl('link_recover_password_content', 'value'),
						]);

						echo form_textarea([
							'id' => 'text-area-input-recover-password-link',
							'name' => 'text-area-input-recover-password-link',
							'class' => 'd-none',
							'rows' => '4',
						]);
						?>
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

				<div class="font-weight-bold bg-blue-light cursor-pointer p-2 mt-2 text-white rounded" data-toggle="collapse" data-target="#verification_account_collapse"><i class="fa fa-link"></i> <?= lang("email_verification_new_account"); ?></div>

				<div id="verification_account_collapse" class="collapse bg-light p-3" data-parent="#collapse-group">
					<?= form_open('admin/settings/email-templates/email_verification_account', ['class' => 'templateEmail']); ?>
					<div class="form-group">
						<?php
						echo form_label(lang("input_subject"), 'email_subject_verification_account', [
							'class' => 'form-text font-weight-bold'
						]);

						echo form_input([
							'name' => 'email_subject_verification_account',
							'class' => 'form-control',
							'type' => 'text',
							'value' => (email_tpl('verification_account_subject', 'value') == '') ? email_template('verification_account')->subject : email_tpl('verification_account_subject', 'value'),
						]);
						?>
					</div>

					<div class="form-group">
						<?php
						echo form_label(lang("content"), 'email_content_verification_account', [
							'class' => 'form-text font-weight-bold'
						]);

						echo form_textarea([
							'name' => 'email_content_verification_account',
							'class' => 'form-control',
							'id' => 'verification_account',
							'value' => (email_tpl('verification_account_content', 'value') == '') ? email_template('verification_account')->content : email_tpl('verification_account_content', 'value'),
						]);

						echo form_textarea([
							'id' => 'text-area-input-verification-account',
							'name' => 'text-area-input-verification-account',
							'class' => 'd-none',
							'rows' => '4',
						]);
						?>
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

				<div class="font-weight-bold bg-blue-light cursor-pointer p-2 mt-2 text-white rounded" data-toggle="collapse" data-target="#notification_welcome_user_collapse"><i class="fa fa-link"></i> <?= lang("notification_welcome_user"); ?></div>

				<div id="notification_welcome_user_collapse" class="collapse bg-light p-3" data-parent="#collapse-group">
					<?= form_open('admin/settings/email-templates/welcome_user', ['class' => 'templateEmail']); ?>
					<div class="form-group">
						<?php
						echo form_label(lang("input_subject"), 'notification_welcome_user_subject', [
							'class' => 'form-text font-weight-bold'
						]);

						echo form_input([
							'name' => 'notification_welcome_user_subject',
							'class' => 'form-control',
							'type' => 'text',
							'value' => (email_tpl('welcome_user_subject', 'value') == '') ? email_template('welcome_user')->subject : email_tpl('welcome_user_subject', 'value'),
						]);
						?>
					</div>

					<div class="form-group">
						<?php
						echo form_label(lang("content"), 'notification_welcome_user_content', [
							'class' => 'form-text font-weight-bold'
						]);

						echo form_textarea([
							'name' => 'notification_welcome_user_content',
							'class' => 'form-control',
							'id' => 'notification_welcome_user',
							'value' => (email_tpl('welcome_user_content', 'value') == '') ? email_template('welcome_user')->content : email_tpl('welcome_user_content', 'value'),
						]);

						echo form_textarea([
							'id' => 'text-area-input-notification-welcome-user',
							'name' => 'text-area-input-notification-welcome-user',
							'class' => 'd-none',
							'rows' => '4',
						]);
						?>
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

				<div class="font-weight-bold bg-blue-light cursor-pointer p-2 mt-2 text-white rounded" data-toggle="collapse" data-target="#new_user_to_admin_collapse"><i class="fa fa-link"></i> <?= lang("notification_new_user"); ?></div>

				<div id="new_user_to_admin_collapse" class="collapse bg-light p-3" data-parent="#collapse-group">
					<?= form_open('admin/settings/email-templates/new_user_to_admin', ['class' => 'templateEmail']); ?>
					<div class="form-group">
						<?php
						echo form_label(lang("input_subject"), 'new_user_to_admin_subject', [
							'class' => 'form-text font-weight-bold'
						]);

						echo form_input([
							'name' => 'new_user_to_admin_subject',
							'class' => 'form-control',
							'type' => 'text',
							'value' => (email_tpl('new_user_to_admin_subject', 'value') == '') ? email_template('new_user_to_admin')->subject : email_tpl('new_user_to_admin_subject', 'value'),
						]);
						?>
					</div>

					<div class="form-group">
						<?php
						echo form_label(lang("content"), 'new_user_to_admin_content', [
							'class' => 'form-text font-weight-bold'
						]);

						echo form_textarea([
							'name' => 'new_user_to_admin_content',
							'class' => 'form-control',
							'id' => 'new_user_to_admin',
							'value' => (email_tpl('new_user_to_admin_content', 'value') == '') ? email_template('new_user_to_admin')->content : email_tpl('new_user_to_admin_content', 'value'),
						]);

						echo form_textarea([
							'id' => 'text-area-input-new-user-to-admin-content',
							'name' => 'text-area-input-new-user-to-admin-content',
							'class' => 'd-none',
							'rows' => '4',
						]);
						?>
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

				<div class="font-weight-bold bg-blue-light cursor-pointer p-2 mt-2 text-white rounded" data-toggle="collapse" data-target="#notification_ticket_reply_collapse"><i class="fa fa-link"></i> <?= lang("notification_ticket_reply"); ?></div>

				<div id="notification_ticket_reply_collapse" class="collapse bg-light p-3" data-parent="#collapse-group">
					<?= form_open('admin/settings/email-templates/notification_ticket_reply', ['class' => 'templateEmail']); ?>
					<div class="form-group">
						<?php
						echo form_label(lang("input_subject"), 'notification_ticket_reply_subject', [
							'class' => 'form-text font-weight-bold'
						]);

						echo form_input([
							'name' => 'notification_ticket_reply_subject',
							'class' => 'form-control',
							'type' => 'text',
							'value' => (email_tpl('notification_ticket_reply_subject', 'value') == '') ? email_template('notification_ticket_reply')->subject : email_tpl('notification_ticket_reply_subject', 'value'),
						]);
						?>
					</div>

					<div class="form-group">
						<?php
						echo form_label(lang("content"), 'notification_ticket_reply_content', [
							'class' => 'form-text font-weight-bold'
						]);

						echo form_textarea([
							'name' => 'notification_ticket_reply_content',
							'class' => 'form-control',
							'id' => 'notification_ticket_reply',
							'value' => (email_tpl('notification_ticket_reply_content', 'value') == '') ? email_template('notification_ticket_reply')->content : email_tpl('notification_ticket_reply_content', 'value'),
						]);

						echo form_textarea([
							'id' => 'text-area-input-notification-ticket-reply-content',
							'name' => 'text-area-input-notification-ticket-reply-content',
							'class' => 'd-none',
							'rows' => '4',
						]);
						?>
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

				<div class="font-weight-bold bg-blue-light cursor-pointer p-2 mt-2 text-white rounded" data-toggle="collapse" data-target="#payments_notification_collapse"><i class="fa fa-link"></i> <?= lang("payments_notification"); ?></div>

				<div id="payments_notification_collapse" class="collapse bg-light p-3" data-parent="#collapse-group">
					<?= form_open('admin/settings/email-templates/payments_notification', ['class' => 'templateEmail']); ?>
					<div class="form-group">
						<?php
						echo form_label(lang("input_subject"), 'payments_notification_subject', [
							'class' => 'form-text font-weight-bold'
						]);

						echo form_input([
							'name' => 'payments_notification_subject',
							'class' => 'form-control',
							'type' => 'text',
							'value' => (email_tpl('payments_notification_subject', 'value') == '') ? email_template('payments_notification')->subject : email_tpl('payments_notification_subject', 'value'),
						]);
						?>
					</div>

					<div class="form-group">
						<?php
						echo form_label(lang("content"), 'payments_notification_content', [
							'class' => 'form-text font-weight-bold'
						]);

						echo form_textarea([
							'name' => 'payments_notification_content',
							'class' => 'form-control',
							'id' => 'payments_notification',
							'value' => (email_tpl('payments_notification_content', 'value') == '') ? email_template('payments_notification')->content : email_tpl('payments_notification_content', 'value'),
						]);

						echo form_textarea([
							'id' => 'text-area-input-payments-notification-content',
							'name' => 'text-area-input-payments-notification-content',
							'class' => 'd-none',
							'rows' => '4',
						]);
						?>
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
	</div>
</div>

<script src="<?= set_js('settings.min.js'); ?>"></script>
<script src="<?= set_js('plugins/ckeditor/ckeditor.js'); ?>"></script>
<script>
	var RecoverPasswordLink = CKEDITOR.replace('recover_password_link', {
		height: 250,
	});

	var EmailVerificationAccount = CKEDITOR.replace('verification_account', {
		height: 250
	});

	var NotificationWelcomeUser = CKEDITOR.replace('notification_welcome_user', {
		height: 250
	});

	var NewUserToAdmin = CKEDITOR.replace('new_user_to_admin', {
		height: 250
	});

	var NotificationTicketReply = CKEDITOR.replace('notification_ticket_reply', {
		height: 250
	});

	var PaymentsNotification = CKEDITOR.replace('payments_notification', {
		height: 300
	});

	timer = setInterval(updateDiv, 100);

	function updateDiv() {
		$('#text-area-input-recover-password-link').html(RecoverPasswordLink.getData());
		$('#text-area-input-verification-account').html(EmailVerificationAccount.getData());
		$('#text-area-input-notification-welcome-user').html(NotificationWelcomeUser.getData());
		$('#text-area-input-new-user-to-admin-content').html(NewUserToAdmin.getData());
		$('#text-area-input-notification-ticket-reply-content').html(NotificationTicketReply.getData());
		$('#text-area-input-payments-notification-content').html(PaymentsNotification.getData());
	}
</script>