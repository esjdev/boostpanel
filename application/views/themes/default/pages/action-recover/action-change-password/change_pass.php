<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container padding_top">
	<div class="row justify-content-center" data-aos="fade-up">
		<div class="col-sm-6 mt-3">
			<div class="section_tittle text-center">
				<p><?= lang("changing_your_password"); ?></p>
				<h2><?= lang("password_reset"); ?></h2>
			</div>

			<div class="alert alert-danger alert-dismissible rounded error" style="display:none;" role="alert">
				<i class="fa fa-exclamation-triangle"></i> <span class="error-message"></span>
				<a class="close cursor-pointer" aria-label="close">&times;</a>
			</div> <!-- Alert error -->

			<div class="alert alert-success alert-dismissible rounded success" style="display:none;" role="alert">
				<i class="fa fa-thumbs-up"></i> <span class="success-message"></span>
				<a class="close cursor-pointer" aria-label="close">&times;</a>
			</div> <!-- Alert success -->

			<?= form_open('recover/token/' . $hash, ['id' => 'token-recover-form']); ?>
			<div class="form-group">
				<?php
				echo form_label(lang("input_new_password"), 'new_pass', [
					'class' => 'form-text font-weight-bold'
				]);

				echo form_input([
					'name' => 'new_pass',
					'class' => 'form-control',
					'type' => 'password',
					'value' => set_value("new_pass"),
					'placeholder' => lang("input_new_password"),
					'onfocus' => "this.placeholder = ''",
					'onblur' => "this.placeholder = '" . lang("input_new_password") . "'"
				]);
				?>
			</div>
			<div class="form-group">
				<?php
				echo form_label(lang("input_confirm_password"), 're_pass_new', [
					'class' => 'form-text font-weight-bold'
				]);

				echo form_input([
					'name' => 're_pass_new',
					'class' => 'form-control',
					'type' => 'password',
					'value' => set_value("re_pass_new"),
					'placeholder' => lang("input_confirm_password"),
					'onfocus' => "this.placeholder = ''",
					'onblur' => "this.placeholder = '" . lang("input_confirm_password") . "'"
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
		</div>
	</div>
</div>
