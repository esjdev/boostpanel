<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<h3 class="card-title aqua-gradient rounded p-2 text-white"><i class="fa fa-dollar"></i> <?= lang("menu_settings_currency"); ?></h3>

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

			<?= form_open('admin/settings/currency-settings', ['class' => 'settingsForm']); ?>

			<div class="form-group">
				<?php
				echo form_label(lang("currency_code"), 'currency_code', [
					'class' => 'form-text font-weight-bold'
				]);

				$list_currency = currency_codes();

				echo '<select name="currency_code" class="form-control">';
				foreach ($list_currency as $key => $value) {
					$selected = (configs('currency_code', 'value') == $key ? 'selected class="font-weight-bold"' : '');
					echo '<option value="' . $key . '" ' . $selected . '>' . $key . ' - ' . $value . '</option>';
				}
				echo '</select>';
				?>
			</div>
			<div class="row">
				<div class="col-6">
					<div class="form-group">
						<?php
						echo form_label(lang("currency_symbol"), 'symbol_currency', [
							'class' => 'form-text font-weight-bold'
						]);

						echo form_input([
							'name' => 'symbol_currency',
							'class' => 'form-control',
							'type' => 'text',
							'value' => configs('currency_symbol', 'value')
						]);
						?>
					</div>
				</div>
				<div class="col-6">
					<div class="form-group">
						<?php
						echo form_label(lang("currency_decimal_places"), 'currency_places', [
							'class' => 'form-text font-weight-bold'
						]);

						echo '<select name="currency_places" class="form-control">';
						echo '<option value="0" ' . (configs('currency_decimal', 'value') == '0' ? 'selected class="font-weight-bold"' : '') . '>0</option>';
						echo '<option value="1" ' . (configs('currency_decimal', 'value') == '1' ? 'selected class="font-weight-bold"' : '') . '>0.0</option>';
						echo '<option value="2" ' . (configs('currency_decimal', 'value') == '2' ? 'selected class="font-weight-bold"' : '') . '>0.00</option>';
						echo '<option value="3" ' . (configs('currency_decimal', 'value') == '3' ? 'selected class="font-weight-bold"' : '') . '>0.000</option>';
						echo '<option value="4" ' . (configs('currency_decimal', 'value') == '4' ? 'selected class="font-weight-bold"' : '') . '>0.0000</option>';
						echo '</select>';
						?>
					</div>
				</div>
				<div class="col-6">
					<div class="form-group">
						<?php
						echo form_label(lang("decimal_separator"), 'decimal_separator', [
							'class' => 'form-text font-weight-bold'
						]);

						echo '<select name="decimal_separator" class="form-control">';
						echo '<option value="." ' . (configs('currency_decimal_separator', 'value') == '.' ? 'selected class="font-weight-bold"' : '') . '>' . lang("dot") . '</option>';
						echo '<option value="," ' . (configs('currency_decimal_separator', 'value') == ',' ? 'selected class="font-weight-bold"' : '') . '>' . lang("comma") . '</option>';
						echo '</select>';
						?>
					</div>
				</div>
				<div class="col-6">
					<div class="form-group">
						<?php

						echo form_label(lang("thousand_separator"), 'thousand_separator', [
							'class' => 'form-text font-weight-bold'
						]);

						echo '<select name="thousand_separator" class="form-control">';
						echo '<option value="." ' . (configs('currency_thousand_separator', 'value') == '.' ? 'selected class="font-weight-bold"' : '') . '>' . lang("dot") . '</option>';
						echo '<option value="," ' . (configs('currency_thousand_separator', 'value') == ',' ? 'selected class="font-weight-bold"' : '') . '>' . lang("comma") . '</option>';
						echo '<option value=" " ' . (configs('currency_thousand_separator', 'value') == ' ' ? 'selected class="font-weight-bold"' : '') . '>' . lang("space") . '</option>';
						echo '</select>';
						?>
					</div>
				</div>
			</div>

			<div class="form-group">
				<?php
				echo form_label("<i class=\"fa fa-link\"></i> " . lang("auto_currency_converter"), 'auto_currency_converter', [
					'class' => 'form-text font-weight-bold'
				]);
				?>
				<div class="onoffswitch">
					<input type="checkbox" name="auto_currency_converter" class="onoffswitch-checkbox" id="onoffswitch" <?= (configs('auto_currency_converter', 'value') == 'on' ? 'checked' : ''); ?>>
					<label class="onoffswitch-label" for="onoffswitch">
						<span class="onoffswitch-inner"></span>
						<span class="onoffswitch-switch"></span>
					</label>
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
