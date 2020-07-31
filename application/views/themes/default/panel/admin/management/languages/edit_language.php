<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="w-75 container-fluid padding_top">
	<div class="row justify-content-center">
		<div class="col-sm-12 mt-3">
			<div class="section_tittle text-center" data-aos="fade-up">
				<p><?= lang("edit_language"); ?></p>
				<h2><?= lang("edit"); ?></h2>
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
						<?= form_open('admin/language/update/' . $language_edit['ids'], ['id' => 'edit-language']); ?>
						<div class="col-md-6">
							<div class="form-group">
								<select name="status_lang" class="form-control">
									<option value="not" class="font-weight-bold"><?= lang("select_status"); ?></option>
									<option value="1" <?= (($language_edit['status'] == '1') ? 'selected' : ''); ?>><?= lang("status_active"); ?></option>
									<option value="0" <?= (($language_edit['status'] == '0') ? 'selected' : ''); ?>><?= lang("status_inactive"); ?></option>
								</select>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<select name="lang_default" class="form-control">
									<option value="not" class="font-weight-bold"><?= lang("select_default_language"); ?></option>
									<option value="0" <?= (($language_edit['is_default'] == '0') ? 'selected' : ''); ?>><?= lang("status_no"); ?></option>
									<option value="1" <?= (($language_edit['is_default'] == '1') ? 'selected' : ''); ?>><?= lang("status_yes"); ?></option>
								</select>
							</div>
						</div>
					</div>

					<div class="float-right mb-3">
						<?php
						echo form_submit([
							'class' => 'genric-btn primary e-large btn-block radius fs-16',
							'type' => 'submit',
							'value' => lang("edit")
						]);
						?>
					</div>

					<div class="float-right">
						<a href="<?= base_url("admin/language"); ?>"><i class="fa fa-arrow-circle-left text-secondary fa-2x mr-2 mt-3"></i></a>
					</div>

					<h4 class="font-weight-bold my-2 text-dark"><?= lang('translation_editor'); ?></h4>

					<?php foreach ($list_language_edit as $data) : ?>
						<tbody>
							<tr>
								<td class="font-weight-bold bg-dark text-white"><?= limit_str($data->slug, 20, true); ?></td>
								<td width="65%">
									<?php
									echo form_input([
										'name' => 'lang[' . $data->slug . ']',
										'class' => 'form-control',
										'type' => 'text',
										'value' => (isset($langs[$data->slug])) ? $langs[$value->slug] : $data->value,
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