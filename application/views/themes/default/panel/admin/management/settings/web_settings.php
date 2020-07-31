<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<h3 class="card-title aqua-gradient rounded p-2 text-white"><i class="fa fa-globe"></i> <?= lang("menu_website_settings"); ?></h3>

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

			<?= form_open_multipart('admin/settings/website-settings', ['class' => 'webSettings']); ?>
			<div class="form-group">
				<?php
				echo form_label(lang("app_title"), 'app_title', [
					'class' => 'form-text font-weight-bold'
				]);

				echo form_input([
					'name' => 'app_title',
					'class' => 'form-control',
					'type' => 'text',
					'value' => configs('app_title', 'value')
				]);
				?>
			</div>
			<div class="form-group">
				<?php
				echo form_label(lang("meta_description"), 'meta_description', [
					'class' => 'form-text font-weight-bold'
				]);

				echo form_input([
					'name' => 'meta_description',
					'class' => 'form-control',
					'type' => 'text',
					'value' => configs('meta_description', 'value')
				]);
				?>
			</div>
			<div class="form-group">
				<?php
				echo form_label(lang("meta_keywords"), 'meta_keywords', [
					'class' => 'form-text font-weight-bold'
				]);

				echo form_input([
					'name' => 'meta_keywords',
					'class' => 'form-control',
					'type' => 'text',
					'value' => configs('meta_keywords', 'value')
				]);
				?>
			</div>
			<div class="row bg-blue-light rounded p-2 text-white">
				<div class="col-sm-6">
					<div class="form-group">
						<?php
						echo form_label(lang("website_logo"), 'website_logo', [
							'class' => 'form-text font-weight-bold'
						]);

						echo "<img src='" . set_image('logo.png') . "' class='logo bg-white mb-2 ml-auto' alt='logo-boostpanel'>";

						echo form_upload([
							'name' => 'website_logo',
							'class' => 'form-control'
						]);
						echo "<small class='text-danger font-weight-normal'>" . lang("only_png_logo_settings") . "</small>";
						?>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<?php
						echo form_label(lang("website_logo_white"), 'website_logo_white', [
							'class' => 'form-text font-weight-bold'
						]);

						echo "<img src='" . set_image('logo_white.png') . "' class='logo mb-2 ml-auto' alt='logo-boostpanel-white'>";

						echo form_upload([
							'name' => 'website_logo_white',
							'class' => 'form-control'
						]);
						echo "<small class='text-danger font-weight-normal'>" . lang("only_png_logo_settings") . "</small>";
						?>
					</div>
				</div>

				<div class="col-sm-12">
					<div class="form-group">
						<?php
						echo form_label(lang("website_favicon"), 'website_favicon', [
							'class' => 'form-text font-weight-bold'
						]);

						echo form_upload([
							'name' => 'website_favicon',
							'class' => 'form-control'
						]);
						echo "<small class='text-danger font-weight-normal'>" . lang("only_ico_or_png") . "</small>";
						?>
					</div>
				</div>
			</div>
			<div class="form-group">
				<?php
				echo form_submit([
					'class' => 'genric-btn info-green e-large btn-block radius fs-16 mt-3',
					'type' => 'submit',
					'value' => lang("save")
				]);
				?>
			</div>
			<?= form_close(); ?>

			<hr>

			<div class="row">
				<div class="col-sm-6">
					<?= form_open('admin/settings/theme-setting', ['class' => 'settingsForm']); ?>
					<div class="form-group">
						<?php
						echo form_label(lang("theme"), 'theme_website', [
							'class' => 'form-text font-weight-bold'
						]);

						echo themes();
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

				<div class="col-sm-6">
					<?= form_open('admin/settings/timezone-setting', ['class' => 'settingsForm']); ?>
					<div class="form-group">
						<?php
						echo form_label(lang("input_timezone"), 'timezone', [
							'class' => 'form-text font-weight-bold'
						]);

						echo '<select class="form-control" name="timezone">';
						echo '<option value="noselect" class="font-weight-bold">' . lang("input_select_timezone") . '</option>';

						foreach (timezone_list() as $key => $value) :
							$timezone = config('timezone');
							echo '<option value="' . $value['zone'] . '" ' . ($timezone == $value['zone'] ? 'selected' : '') . '>' . $value['time'] . '</option>';
						endforeach;

						echo '</select>';
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
