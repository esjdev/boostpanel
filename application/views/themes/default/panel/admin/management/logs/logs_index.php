<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="w-75 container-fluid padding_top">
	<div class="row justify-content-center">
		<div class="col-sm-12 mt-3">
			<div class="section_tittle text-center" data-aos="fade-up">
				<p><?= lang("logs_management"); ?></p>
				<h2><?= lang("logs"); ?></h2>
			</div>

			<?php if (!empty($list_logs)) : ?>
				<div class="table-responsive-lg">
					<table class="table border">
						<thead>
							<tr>
								<th class="text-center">*</th>
								<th><?= lang("type"); ?></th>
								<th><?= lang("updated"); ?></th>
								<th class="text-center"><?= lang("view"); ?></th>
								<th class="text-center"><?= lang("action"); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php $pos = $count;
							foreach ($list_logs as $value) :
								$textSexyAlert = str_replace("'", "\'", lang('confirm_close_message'));
							?>
								<tr>
									<td class="border text-center"><?= $pos++; ?></td>
									<td class="border"><span class="badge badge-danger text-white fs-12"><?= $value->action; ?></span></td>
									<td class="border"><?= convert_time($value->updated_at, dataUser(logged(), 'timezone')); ?></td>
									<td class="border text-center"><i class="fa fa-eye fa-2x cursor-pointer" data-toggle="modal" data-target="#buttonContent<?= $value->id; ?>" data-backdrop="static"></i></td>
									<td class="border text-center">
										<a href="javascript:void(0);" class="btn bg-danger btn-sm border-danger text-white" data-toggle="tooltip" data-placement="bottom" title="<?= lang("delete"); ?>" onclick="alert_confirm('<?= base_url('admin/logs/destroy/' . $value->id); ?>', '<?= lang('alert_close'); ?>', '<?= $textSexyAlert; ?>', '<?= lang('close'); ?>', '<?= lang('success_deleted_success'); ?>')"><i class="fa fa-trash"></i></a>
									</td>
								</tr>
						</tbody>

						<div class="modal fade padding_top" id="buttonContent<?= $value->id; ?>" tabindex="-1">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header border-0">
										<p class="modal-title font-weight-bold text-dark"><?= $value->action; ?></p>
										<button type="button" class="close" data-dismiss="modal">
											<span>&times;</span>
										</button>
									</div>
									<div class="modal-body">
										<?= nl2br($value->value); ?>
										<div class="modal-footer border-0">
											<button type="button" class="btn btn-danger" data-dismiss="modal"><?= lang("close"); ?></button>
										</div>
									</div>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
					</table>
				</div>

				<?= $pagination_links; ?>
			<?php else : ?>
				<div class='bg-danger text-white p-2 rounded mb-3'><?= lang("error_nothing_found"); ?></div>
			<?php endif; ?>
		</div>
	</div>
</div>