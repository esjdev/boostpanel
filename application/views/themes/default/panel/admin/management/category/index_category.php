<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="w-75 container-fluid padding_top">
	<div class="row justify-content-center">
		<div class="col-sm-12 mt-3">
			<div class="section_tittle text-center" data-aos="fade-up">
				<p><?= lang("category_management"); ?></p>
				<h2><?= lang("menu_category"); ?></h2>

				<a href="javascript:void(0);" class="btn btn-green btn-lg border-0 text-white mt-5" data-toggle="modal" data-target="#addCategory" data-backdrop="static" onclick="add_new_service();">
					<i class="fa fa-plus-circle text-white"></i> <?= lang("add_new"); ?>
				</a>
			</div>

			<?php if (flashdata('error')) : ?>
				<div class="alert alert-danger alert-dismissible rounded error mt-3" role="alert">
					<i class="fa fa-exclamation-triangle"></i> <span class="error-message"><?= flashdata('error'); ?></span>
					<a class="close cursor-pointer" aria-label="close">&times;</a>
				</div> <!-- Alert error delete category -->
			<?php endif; ?>

			<?php if (!empty($list_category)) : ?>
				<div class="table-responsive-lg">
					<table class="table border">
						<thead>
							<tr>
								<th class="text-center">*</th>
								<th><?= lang("input_name"); ?></th>
								<th><?= lang("created"); ?></th>
								<th><?= lang("status"); ?></th>
								<th class="text-right"><?= lang("action"); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php
							$pos = $count;
							foreach ($list_category as $value) :
								if ($value->status == 1) :
									$status = "<div class='badge badge-pill badge-green cursor-pointer fs-12'>" . lang('status_active') . "</div>";
								else :
									$status = "<div class='badge badge-pill badge-danger cursor-pointer fs-12'>" . lang('status_inactive') . "</div>";
								endif;

								$textSexyAlert = str_replace("'", "\'", lang('confirm_close_message'));
							?>
								<tr>
									<td class="text-center font-weight-bold border"><?= $pos++; ?></td>
									<td class="border" id="category_name<?= $value->id; ?>"><?= html_escape($value->name); ?></td>
									<td class="border"><?= convert_time($value->created_at, dataUser(logged(), 'timezone')); ?></td>
									<td class="border" id="status_category<?= $value->id; ?>" data-status="<?= $value->status; ?>"><?= $status; ?></td>
									<td class="border text-right">
										<div class="btn-group">
											<a href="javascript:void(0);" class="btn round btn-primary btn-sm" onclick="editCategory('<?= $value->id; ?>')" data-toggle="modal" data-target="#editCategory" data-backdrop="static"><i class="fa fa-edit"></i></a>
											<a href="javascript:void(0);" class="btn round btn-danger btn-sm" onclick="alert_confirm_notice('<?= base_url('admin/category/destroy/' . $value->id); ?>', '<?= lang('alert_close'); ?>', '<?= $textSexyAlert; ?>', '<?= lang('close'); ?>', '<?= lang('error_delete_category'); ?>', '<?= lang('success_deleted_success'); ?>')"><i class="fa fa-trash"></i></a>
										</div>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>

				<?= $pagination_links; ?>
			<?php else : ?>
				<div class='bg-danger text-white p-2 rounded mb-3'><?= lang("error_nothing_found"); ?></div>
			<?php endif; ?>
		</div>
	</div>
</div>

<div class="modal fade" id="addCategory" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content bg-white">
			<div class="modal-header">
				<h4 class="modal-title font-weight-bold text-dark"><?= lang("add_new_category"); ?></h4>
				<button type="button" class="close" data-dismiss="modal">
					<span>&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="alert alert-danger alert-dismissible rounded error" style="display:none;" role="alert">
					<i class="fa fa-exclamation-triangle"></i> <span class="error-message"></span>
					<a class="close cursor-pointer" aria-label="close">&times;</a>
				</div> <!-- Alert error -->

				<div class="alert alert-success alert-dismissible rounded success" style="display:none;" role="alert">
					<i class="fa fa-thumbs-up"></i> <span class="success-message"></span>
					<a class="close cursor-pointer" aria-label="close">&times;</a>
				</div> <!-- Alert success -->

				<?= form_open('admin/category/create', ['id' => 'add-category-panel']); ?>
				<div class="form-group">
					<?php
					echo form_label(lang("input_name"), 'category_name', [
						'class' => 'form-text font-weight-bold'
					]);

					echo form_input([
						'name' => 'category_name',
						'class' => 'form-control',
						'type' => 'text',
						'value' => set_value("category_name"),
					]);
					?>
				</div>

				<div class="form-group">
					<?php
					echo form_label(lang("status"), 'status_category', [
						'class' => 'form-text font-weight-bold'
					]);

					echo form_input([
						'data-toggle' => 'toggle',
						'data-onstyle' => 'success cursor-pointer',
						'data-offstyle' => 'danger cursor-pointer',
						'data-on' => lang('status_active'),
						'data-off' => lang('status_inactive'),
						'data-width' => '100%',
						'data-height' => '20px',
						'type' => 'checkbox',
						'name' => 'status_category',
						'value' => '1',
						'checked' => 'checked',
					]);
					?>
				</div>
				<div class="form-group">
					<?php
					echo form_submit([
						'class' => 'genric-btn info-green e-large btn-block radius fs-16',
						'type' => 'submit',
						'value' => lang("button_submit")
					]);
					?>
				</div>
				<?= form_close(); ?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal"><?= lang("close"); ?></button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="editCategory" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content bg-white">
			<div class="modal-header">
				<h4 class="modal-title font-weight-bold text-dark"><?= lang("edit_category"); ?></h4>
				<button type="button" class="close" data-dismiss="modal">
					<span>&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="alert alert-danger alert-dismissible rounded error" style="display:none;" role="alert">
					<i class="fa fa-exclamation-triangle"></i> <span class="error-message"></span>
					<a class="close cursor-pointer" aria-label="close">&times;</a>
				</div> <!-- Alert error -->

				<div class="alert alert-success alert-dismissible rounded success" style="display:none;" role="alert">
					<i class="fa fa-thumbs-up"></i> <span class="success-message"></span>
					<a class="close cursor-pointer" aria-label="close">&times;</a>
				</div> <!-- Alert success -->

				<?= form_open('', ['id' => 'edit-category-panel']); ?>
				<div class="form-group">
					<?php
					echo form_label(lang("input_name"), 'edit_category_name', [
						'class' => 'form-text font-weight-bold'
					]);

					echo form_input([
						'name' => 'edit_category_name',
						'class' => 'form-control',
						'type' => 'text',
						'value' => set_value("edit_category_name"),
					]);
					?>
				</div>

				<div class="form-group">
					<?php
					echo form_label(lang("status"), 'edit_status_category', [
						'class' => 'form-text font-weight-bold'
					]);

					echo form_input([
						'data-toggle' => 'toggle',
						'data-onstyle' => 'success cursor-pointer',
						'data-offstyle' => 'danger cursor-pointer',
						'data-on' => lang('status_active'),
						'data-off' => lang('status_inactive'),
						'data-width' => '100%',
						'data-height' => '20px',
						'type' => 'checkbox',
						'name' => 'edit_status_category',
						'value' => '1',
						'checked' => 'checked',
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
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal"><?= lang("close"); ?></button>
			</div>
		</div>
	</div>
</div>