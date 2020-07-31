<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="w-75 container-fluid padding_top">
	<div class="row justify-content-center" data-aos="fade-up">
		<div class="col-sm-12 mt-3">
			<div class="section_tittle text-center">
				<p><?= lang("view_account_info"); ?></p>
				<h2><?= lang("profile"); ?></h2>
			</div>
		</div>

		<div class="col-sm-6">
			<div class="alert alert-danger alert-dismissible rounded error" style="display:none;"
				 role="alert">
				<i class="fa fa-exclamation-triangle"></i> <span class="error-message"></span>
				<a class="close cursor-pointer" aria-label="close">&times;</a>
			</div> <!-- Alert error -->

			<div class="alert alert-success alert-dismissible rounded success" style="display:none;"
				 role="alert">
				<i class="fa fa-thumbs-up"></i> <span class="success-message"></span>
				<a class="close cursor-pointer" aria-label="close">&times;</a>
			</div> <!-- Alert success -->

			<?= form_open('profile/update', ['id' => 'change-profile']); ?>
			<div class="form-group">
				<?php
				echo form_label(lang("input_username"), 'username', [
					'class' => 'form-text font-weight-bold'
				]);

				echo form_input([
					'name' => 'username',
					'class' => 'form-control',
					'type' => 'text',
					'value' => dataUser(logged(), 'username'),
					'disabled' => 'disabled'
				]);
				?>
			</div>
			<div class="form-group">
				<?php
				echo form_label(lang("input_email"), 'email', [
					'class' => 'form-text font-weight-bold'
				]);

				echo form_input([
					'name' => 'email',
					'class' => 'form-control',
					'type' => 'text',
					'value' => dataUser(logged(), 'email'),
					'disabled' => 'disabled'
				]);
				?>
			</div>
			<div class="form-group">
				<?php
				echo form_label(lang("input_current_password"), 'password_current', [
					'class' => 'form-text font-weight-bold'
				]);

				echo form_input([
					'name' => 'password_current',
					'class' => 'form-control',
					'type' => 'password',
					'value' => set_value("password_current")
				]);
				?>
			</div>
			<div class="form-group">
				<?php
				echo form_label(lang("input_new_password"), 'new_password', [
					'class' => 'form-text font-weight-bold'
				]);

				echo form_input([
					'name' => 'new_password',
					'class' => 'form-control',
					'type' => 'password',
					'value' => set_value("new_password")
				]);
				?>
			</div>
			<div class="form-group">
				<?php
				echo form_label(lang("input_confirm_password"), 'cf_new_password', [
					'class' => 'form-text font-weight-bold'
				]);

				echo form_input([
					'name' => 'cf_new_password',
					'class' => 'form-control',
					'type' => 'password',
					'value' => set_value("cf_new_password")
				]);
				?>
			</div>
			<div class="form-group">
				<?php
				echo form_submit([
					'class' => 'genric-btn primary e-large radius fs-14',
					'type' => 'submit',
					'value' => lang("button_change")
				]);
				?>
			</div>
			<?= form_close(); ?>

			<div class="row">
				<div class="col-sm-6 bg-light pt-2 mb-3 roudend">
					<?= form_open('profile/change/timezone', ['id' => 'change-timezone-profile']) ?>
					<div class="form-group">
					<?php
						echo form_label(lang("input_timezone"), 'timezone', [
							'class' => 'form-text font-weight-bold'
						]);

						echo '<select class="form-control" name="timezone">';

						echo '<option value="noselect" class="font-weight-bold">' . lang("input_select_timezone") . '</option>';
						foreach (timezone_list() as $key => $value) :
							echo '<option value="' . $value['zone'] . '" ' . (dataUser(logged(), 'timezone') == $value['zone'] ? 'selected' : '') . '>' . $value['time'] . '</option>';
						endforeach;

						echo '</select>';
					?>
					</div>

					<div class="form-group">
					<?php
						echo form_submit([
							'class' => 'genric-btn success-border small radius fs-14',
							'type' => 'submit',
							'value' => lang("save")
						]);
					?>
					</div>
					<?= form_close(); ?>
				</div>

				<div class="col-sm-6 bg-light pt-2 mb-3 roudend">
					<div class="form-group">
					<?php
						echo form_label(lang("api_key"), 'profile_api_key', [
							'class' => 'form-text font-weight-bold'
						]);

						echo form_input([
							'name' => 'profile_api_key',
							'class' => 'form-control',
							'type' => 'text',
							'value' => (userLevel(logged(), 'user') ? dataUser(logged(), 'api_key') : lang('hidden')),
							'disabled' => 'disabled'
						]);
					?>
					</div>
					<div class="form-group">
					<?php
						if (userLevel(logged(), 'user')) {
							echo form_submit([
								'class' => 'genric-btn success-border small radius fs-14',
								'id' => 'generate_new_token_api',
								'type' => 'submit',
								'value' => lang("generate_new_token")
							]);
						} else {
							echo form_submit([
								'class' => 'genric-btn success-border small radius fs-14',
								'type' => 'submit',
								'disabled' => 'disabled',
								'value' => lang("generate_new_token")
							]);
						}

						echo '<div class="spinner-border spinner-border-sm spinner d-none text-success mt-2" role="status"></div>';
					?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
