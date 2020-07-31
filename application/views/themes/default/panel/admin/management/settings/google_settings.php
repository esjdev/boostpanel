<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<h3 class="card-title aqua-gradient rounded p-2 text-white"><i class="fa fa-cog"></i> Google Recaptcha</h3>

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

			<?= form_open('admin/settings/recaptcha-settings', ['class' => 'settingsForm']); ?>
			<div class="form-group">
				<?php
				echo form_label(lang("status"), 'status', [
					'class' => 'form-text font-weight-bold'
				]);
				?>
				<div class="onoffswitch">
					<input type="checkbox" name="recaptcha_on_off" class="onoffswitch-checkbox" id="onoffswitch" <?= (configs('google_recaptcha', 'value') == 'on' ? 'checked' : ''); ?>>
					<label class="onoffswitch-label" for="onoffswitch">
						<span class="onoffswitch-inner"></span>
						<span class="onoffswitch-switch"></span>
					</label>
				</div>
			</div>

			<div class="off-recaptcha <?= (configs('google_recaptcha', 'value') == 'on' ? '' : 'd-none'); ?>">
				<div class="form-group">
					<?php
					echo form_label(lang("key_public"), 'public_key', [
						'class' => 'form-text font-weight-bold'
					]);

					echo form_input([
						'name' => 'public_key',
						'class' => 'form-control',
						'type' => 'text',
					], configs('recaptcha_public_key', 'value'));
					?>
				</div>
				<div class="form-group">
					<?php
					echo form_label(lang("private_key"), 'private_key', [
						'class' => 'form-text font-weight-bold'
					]);

					echo form_input([
						'name' => 'private_key',
						'class' => 'form-control',
						'type' => 'text',
					], configs('recaptcha_private_key', 'value'));
					?>
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
