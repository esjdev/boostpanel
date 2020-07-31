<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="w-75 container-fluid padding_top">
	<div class="row justify-content-center">
		<div class="col-sm-12 mt-3">
			<div class="section_tittle text-center" data-aos="fade-up">
				<p><?= lang("language_management"); ?></p>
				<h2><?= lang("language"); ?></h2>
			</div>

			<div class="alert alert-danger alert-dismissible rounded error" style="display:none;" role="alert">
				<i class="fa fa-exclamation-triangle"></i> <span class="error-message"></span>
				<a class="close cursor-pointer" aria-label="close">&times;</a>
			</div> <!-- Alert error -->

			<div class="alert alert-success alert-dismissible rounded success" style="display:none;" role="alert">
				<i class="fa fa-thumbs-up"></i> <span class="success-message"></span>
				<a class="close cursor-pointer" aria-label="close">&times;</a>
			</div> <!-- Alert success -->

			<div class="table-responsive-lg">
				<table class="table border">
					<thead>
						<tr>
							<th class="table-plus datatable-nosort"><?= lang("key"); ?></th>
							<th class="datatable-nosort"><?= lang("value"); ?></th>
						</tr>
					</thead>
					<div class="row">
						<div class="col-md-3">
							<div class="form-group">
								<?= form_open('admin/language/action/add', ['id' => 'add-new-language']); ?>
								<select name="language_code" class="form-control">
									<option value="not" class="font-weight-bold"><?= lang("select_language"); ?>
									</option>
									<?php
									$languageCodes = language_codes();
									if (is_array($languageCodes)) {
										foreach ($languageCodes as $key => $value) {
									?>
											<option value="<?= $key; ?>"><?= $key; ?> - <?= $value; ?></option>
									<?php }
									} ?>
								</select>
							</div>
						</div>

						<div class="col-md-3">
							<div class="form-group">
								<select name="country_code" class="form-control">
									<option value="not" class="font-weight-bold"><?= lang("select_country"); ?></option>
									<?php
									$countryCodes = country_codes();
									if (is_array($countryCodes)) {
										foreach ($countryCodes as $key => $value) {
									?>
											<option value="<?= $key; ?>"><?= $value; ?></option>
									<?php }
									} ?>
								</select>
							</div>
						</div>

						<div class="col-md-3">
							<div class="form-group">
								<?php
								echo form_dropdown('status_lang', [
									'not' => lang("select_status"),
									'1' => lang("status_active"),
									'0' => lang("status_inactive"),
								], null, [
									'class' => 'form-control',
								]);
								?>
							</div>
						</div>

						<div class="col-md-3">
							<div class="form-group">
								<?php
								echo form_dropdown('lang_default', [
									'not' => lang("select_default_language"),
									'0' => lang("status_no"),
									'1' => lang("status_yes"),
								], null, [
									'class' => 'form-control',
								]);
								?>
							</div>
						</div>
					</div>

					<div class="float-right mb-3">
						<?php
						echo form_submit([
							'class' => 'genric-btn primary e-large btn-block radius fs-16',
							'type' => 'submit',
							'value' => lang("add"),
						]);
						?>
					</div>

					<div class="float-right">
						<a href="<?= base_url("admin/language"); ?>"><i class="fa fa-arrow-circle-left text-secondary fa-2x mr-2 mt-3"></i></a>
					</div>

					<h4 class="font-weight-bold my-2 text-dark"><?= lang('translation_editor'); ?></h4>
					<?php foreach (all_language_keys() as $key => $value) : ?>
						<tbody>
							<tr>
								<td class="font-weight-bold bg-other-blue text-white">
									<?= $key; ?></td>
								<td width="65%">
									<?php
									echo form_input([
										'name' => 'lang[' . $key . ']',
										'class' => 'form-control',
										'type' => 'text',
										'value' => (isset($langs[$key])) ? $langs[$key] : $value,
									]);
									?>
								</td>
							</tr>
						</tbody>
					<?php endforeach; ?>
					<?= form_close(); ?>
				</table>
			</div>
		</div>
	</div>
</div>