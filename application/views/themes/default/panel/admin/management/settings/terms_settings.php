<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<h3 class="card-title aqua-gradient rounded p-2 text-white"><i class="fa fa-edit"></i> <?= lang("menu_settings_terms_policy_settings"); ?></h3>

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

			<?= form_open('admin/settings/terms-policy-settings', ['class' => 'settingsForm']); ?>
			<div class="form-group">
				<?php
				echo form_label("<i class=\"fa fa-link\"></i> " . lang("content_of_terms") . "", 'terms_content_label', [
					'class' => 'form-text text-info font-weight-normal'
				]);

				echo form_textarea([
					'name' => 'terms_content',
					'class' => 'form-control',
					'id' => 'termsContent',
					'value' => configs("terms_content", "value"),
					'rows' => '3',
				]);

				echo form_textarea([
					'id' => 'text-area-input-settings-terms',
					'name' => 'text-area-input-settings-terms',
					'class' => 'd-none',
					'rows' => '3',
				]);
				?>
			</div>

			<div class="form-group">
				<?php
				echo form_label("<i class=\"fa fa-link\"></i> " . lang("content_of_policy") . "", 'policy_content_label', [
					'class' => 'form-text text-info font-weight-normal'
				]);

				echo form_textarea([
					'name' => 'policy_content',
					'class' => 'form-control',
					'id' => 'policyContent',
					'value' => configs("policy_content", "value"),
					'rows' => '3',
				]);

				echo form_textarea([
					'id' => 'text-area-input-settings-policy',
					'name' => 'text-area-input-settings-policy',
					'class' => 'd-none',
					'value' => '',
					'rows' => '3',
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

<script src="<?= set_js('settings.min.js'); ?>"></script>
<script src="<?= set_js('plugins/ckeditor/ckeditor.js'); ?>"></script>
<script>
	var editorTerms = CKEDITOR.replace('termsContent', {
		height: 350
	});

	var editorPolicy = CKEDITOR.replace('policyContent', {
		height: 350
	});

	timer = setInterval(updateDiv, 100);

	function updateDiv() {
		$('#text-area-input-settings-terms').html(editorTerms.getData());
		$('#text-area-input-settings-policy').html(editorPolicy.getData());
	}
</script>