<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<h3 class="card-title aqua-gradient rounded p-2 text-white"><i class="fa fa-asterisk"></i> <?= lang("menu_others_settings"); ?></h3>

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

			<?= form_open('/admin/settings/update-register-page', ['id' => 'update-register-settings']); ?>
			<div class="form-group">
				<?php
				echo form_label("<i class=\"fa fa-link\"></i> " . lang("registration_page") . ' <span class="fa fa-info-circle cursor-pointer" data-toggle="tooltip" data-placement="top" title="" data-title="' . lang("allow_user_signup") . '"></span>', 'auto_currency_converter', [
					'class' => 'form-text text-info font-weight-bold'
				]);
				?>
				<div class="onoffswitch">
					<input type="checkbox" name="registration_page" class="onoffswitch-checkbox" id="onoffswitch" <?= (configs('registration_page', 'value') == 'on' ? 'checked' : ''); ?>>
					<label class="onoffswitch-label" for="onoffswitch">
						<span class="onoffswitch-inner"></span>
						<span class="onoffswitch-switch"></span>
					</label>
				</div>

				<span id="msg-update-settings" class='badge badge-green d-none'><i class="fa fa-thumbs-up"></i> <?= lang('success_edited'); ?></span>
			</div>
			<?= form_close(); ?>

			<div class="bg-blue-light rounded p-2 text-white mb-3">
				<?= form_open('admin/settings/embed-settings', ['class' => 'settingsForm']); ?>

				<h5 class="font-weight-bold text-white mb-4"><i class="fa fa-link"></i> <?= lang("social_media_links"); ?></h5>

				<div class="row">
					<div class="col-sm-6">
						<div class="form-group">
							<?php
							echo form_label("<strong>Facebook</strong>", 'facebook_link', [
								'class' => 'form-text text-info font-weight-normal'
							]);

							echo form_input([
								'name' => 'facebook_link',
								'class' => 'form-control',
								'value' => configs('facebook_link', 'value'),
								'placeholder' => "https://facebook.com",
							]);
							?>
						</div>
					</div>

					<div class="col-sm-6">
						<div class="form-group">
							<?php
							echo form_label("<strong>Twitter</strong>", 'twitter_link', [
								'class' => 'form-text text-info font-weight-normal'
							]);

							echo form_input([
								'name' => 'twitter_link',
								'class' => 'form-control',
								'value' => configs('twitter_link', 'value'),
								'placeholder' => "https://www.twitter.com",
							]);
							?>
						</div>
					</div>

					<div class="col-sm-6">
						<div class="form-group">
							<?php
							echo form_label("<strong>Instagram</strong>", 'instagram_link', [
								'class' => 'form-text text-info font-weight-normal'
							]);

							echo form_input([
								'name' => 'instagram_link',
								'class' => 'form-control',
								'value' => configs('instagram_link', 'value'),
								'placeholder' => "https://instagram.com",
							]);
							?>
						</div>
					</div>

					<div class="col-sm-6">
						<div class="form-group">
							<?php
							echo form_label("<strong>Youtube</strong>", 'youtube_link', [
								'class' => 'form-text text-info font-weight-normal'
							]);

							echo form_input([
								'name' => 'youtube_link',
								'class' => 'form-control',
								'value' => configs('youtube_link', 'value'),
								'placeholder' => "http://youtube.com/",
							]);
							?>
						</div>
					</div>
				</div>

				<div class="form-group">
					<?php
					echo form_label("<i class=\"fa fa-link\"></i> " . lang("custom_header_code") . "", '', [
						'class' => 'form-text text-info font-weight-normal'
					]);

					echo form_textarea([
						'name' => 'embed_code_header',
						'class' => 'form-control',
						'id' => 'embed_code_header',
						'value' => configs('javascript_embed_header', 'value'),
						'rows' => '10',
						'placeholder' => lang("code_embed"),
					]);
					?>
				</div>
				<div class="text-danger cursor-pointer fs-10 rounded mb-3"><i class='fa fa-question'></i> <?= lang("only_javasript"); ?></div>
				<div class="form-group">
					<?php
					echo form_label("<i class=\"fa fa-link\"></i> " . lang("custom_footer_code") . "", '', [
						'class' => 'form-text text-info font-weight-normal'
					]);

					echo form_textarea([
						'name' => 'embed_code_footer',
						'class' => 'form-control',
						'id' => 'embed_code_footer',
						'value' => configs('javascript_embed_footer', 'value'),
						'rows' => '10',
						'placeholder' => lang("code_embed"),
					]);
					?>
				</div>
				<div class="text-danger cursor-pointer fs-10 rounded mb-3"><i class='fa fa-question'></i> <?= lang("only_javasript"); ?></div>
				<div class="form-group">
					<?php
					echo form_submit([
						'class' => 'genric-btn info-green e-large btn-block radius fs-16',
						'type' => 'submit',
						'value' => lang("save")
					]);
					?>
				</div>
			</div>
			<?= form_close(); ?>
		</div>
	</div>
</div>

<script src="<?= set_js('settings.min.js'); ?>"></script>
<link rel="stylesheet" href="<?= set_js('plugins/codemirror/codemirror.min.css'); ?>">
<link rel="stylesheet" href="<?= set_js('plugins/codemirror/dracula.min.css'); ?>">
<script src="<?= set_js('plugins/codemirror/codemirror.min.js'); ?>"></script>
<script src="<?= set_js('plugins/codemirror/css.min.js'); ?>"></script>
<script>
	setTimeout(function() {
		var editor_header = CodeMirror.fromTextArea(document.getElementById("embed_code_header"), {
			lineNumbers: true,
			theme: "dracula"
		});

		var editor_footer = CodeMirror.fromTextArea(document.getElementById("embed_code_footer"), {
			lineNumbers: true,
			theme: "dracula"
		});

		editor_header.on("blur", function() {
			editor_header.save();
		});
		editor_footer.on("blur", function() {
			editor_footer.save();
		});
	}, 100);
</script>