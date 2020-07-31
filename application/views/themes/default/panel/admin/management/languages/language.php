<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="w-75 container-fluid padding_top">
	<div class="row justify-content-center">
		<div class="col-sm-12 mt-3">
			<div class="section_tittle text-center" data-aos="fade-up">
				<p><?= lang("language_management"); ?></p>
				<h2><?= lang("language"); ?></h2>

				<a href="<?= base_url('admin/language/action/add'); ?>" class="btn btn-green btn-lg border-0 text-white mt-5">
					<i class="fa fa-plus-circle text-white"></i> <?= lang("add_new"); ?>
				</a>
			</div>

			<?php if (flashdata('error')) : ?>
				<div class="alert alert-danger alert-dismissible rounded error mt-2" role="alert">
					<i class="fa fa-exclamation-triangle"></i> <span class="error-message"><?= flashdata('error'); ?></span>
					<a class="close cursor-pointer" aria-label="close">&times;</a>
				</div> <!-- Alert error -->
			<?php endif; ?>

			<?php if (!empty($lang_list)) : ?>
				<div class="table-responsive-lg">
					<table class="table border">
						<thead>
							<tr>
								<th class="text-center">*</th>
								<th><?= lang("input_name"); ?></th>
								<th><?= lang("code"); ?></th>
								<th><?= lang("default"); ?></th>
								<th><?= lang("created"); ?></th>
								<th><?= lang("status"); ?></th>
								<th class="text-right"><?= lang("action"); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php $pos = 0;
							foreach ($lang_list as $value) :
								$is_default = ($value->is_default == 1 ? '<i class="fa fa-check-circle fa-2x text-success"></i>' : '-');
								$status = ($value->status == 1 ? "<div class='badge badge-green cursor-pointer fs-12'>" . lang('status_active') . "</div>" : "<div class='badge badge-danger cursor-pointer fs-12'>" . lang('status_inactive') . "</div>");
								$textSexyAlert = str_replace("'", "\'", lang('confirm_close_message'));

								$pos++;

								$countLanguages = $this->model->counts(TABLE_LANG_LIST, ['ids' => $value->ids, 'is_default' => '0']);
							?>
								<tr>
									<td class="border text-center"><?= $pos; ?></td>
									<td class="border"><?= country_codes($value->country_code); ?></td>
									<td class="border"><?= $value->code; ?></td>
									<td class="border font-weight-bold"><?= $is_default; ?></td>
									<td class="border"><?= convert_time($value->created, dataUser(logged(), 'timezone')); ?></td>
									<td class="border">
										<h5><?= $status; ?></h5>
									</td>
									<td class="border text-right">
										<div class="btn-group">
											<a href="<?= base_url("admin/language/edit/" . $value->ids); ?>" class="btn round btn-primary btn-sm"><i class="fa fa-edit"></i></a>
											<a href="javascript:void(0);" onclick="alert_confirm('<?= base_url('admin/language/destroy/' . $value->ids); ?>', '<?= lang('alert_close'); ?>', '<?= $textSexyAlert; ?>', '<?= lang('close'); ?>', '<?= lang('success_deleted_success'); ?>')" class="btn round btn-danger btn-sm <?php if ($countLanguages == 0) : ?> disabled <?php endif; ?>"><i class="fa fa-trash"></i></a>
										</div>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			<?php else : ?>
				<div class='bg-danger text-white rounded p-2'><?= lang('error_nothing_found'); ?></div>
			<?php endif; ?>
		</div>
	</div>
</div>