<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="w-75 container-fluid padding_top">
	<div class="row justify-content-between">
		<div class="col-sm-12 mt-3">
			<div class="section_tittle text-center" data-aos="fade-up">
				<p><?= lang("have_any_questions"); ?></p>
				<h2><?= lang("menu_support"); ?></h2>
			</div>

			<div class="input-group bg-dark rounded mb-3">
				<?php
				if (!empty($get_all_ticket)) :
					echo form_input([
						'name' => 'searchTickets',
						'class' => 'form-control searchTicketsAjax bg-dark text-white border-0',
						'type' => 'text',
						'data-url' => '/ticket/search',
						'data-error' => lang("error_nothing_found"),
						'placeholder' => lang("placeholder_search_ticket"),
					]);
				else :
					echo form_input([
						'name' => 'searchTickets',
						'class' => 'form-control searchTicketsAjax bg-dark text-white border-0',
						'type' => 'text',
						'data-url' => 'javascript:void(0);',
						'data-error' => lang("error_nothing_found"),
						'placeholder' => lang("placeholder_search_ticket"),
						'disabled' => 'disabled'
					]);
				endif;
				?>

				<div class="input-group-prepend">
					<i class="fa fa-search rounded-right text-white bg-default mr-3 m-t-12"></i>
				</div>
			</div>

			<?php if (!empty($get_all_ticket)) : ?>
				<div class="table-search-tickets">
					<div class="table-responsive-lg">
						<table class="table border">
							<thead>
								<tr>
									<th class="text-center"><?= lang("ticket_id"); ?></th>
									<th><?= lang("input_subject"); ?></th>
									<th><?= lang("status"); ?></th>
									<th><?= lang("last_update"); ?></th>
									<th class="text-center"><?= lang("action"); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($get_all_ticket as $value) : ?>
									<?php
									if ($value->status == 'pending') :
										$status = '<span class="text-warning font-weight-bold">' . lang("status_pending") . '</span>';
									elseif ($value->status == 'answered') :
										$status = '<span class="text-success font-weight-bold">' . lang("status_answered") . '</span>';
									elseif ($value->status == 'closed') :
										$status = '<span class="text-danger font-weight-bold">' . lang("status_closed") . '</span>';
									endif;

									if ($value->subject == 'order') :
										$subject = lang("select_option_order");
									elseif ($value->subject == 'payment') :
										$subject = lang("select_option_payment");
									elseif ($value->subject == 'service') :
										$subject = lang("select_option_service");
									elseif ($value->subject == 'other') :
										$subject = lang("select_option_other");
									endif;

									$textSexyAlert = str_replace("'", "\'", lang('confirm_close_message'));
									?>
									<tr class="cursor-pointer">
										<td class="border text-center" onclick="location.href='<?= base_url('ticket/show/' . $value->uuid); ?>';" class="border font-weight-bold">#<?= $value->id; ?></td>
										<td onclick="location.href='<?= base_url('ticket/show/' . $value->uuid); ?>';" class="border"><?= $subject; ?> <?= ($value->status == 'pending' ? '<div class="badge badge-warning text-white p-1"><i class="fa fa-bell"></i></div>' : ''); ?>
										</td>
										<td onclick="location.href='<?= base_url('ticket/show/' . $value->uuid); ?>';" class="border"><?= $status; ?></td>
										<td onclick="location.href='<?= base_url('ticket/show/' . $value->uuid); ?>';" class="border"><?= convert_time($value->updated_at, dataUser(logged(), 'timezone')); ?></td>
										<?php if (userLevel(logged(), 'admin')) : ?>
											<td href="javascript:void(0);" class="border text-center" onclick="alert_confirm('<?= base_url('ticket/destroy/' . $value->uuid); ?>', '<?= lang('alert_close'); ?>', '<?= $textSexyAlert; ?>', '<?= lang('close'); ?>', '<?= lang('success_deleted_success'); ?>')">
												<i class="fa fa-trash fa-2x text-danger"></i>
											</td>
										<?php else: ?>
											<td class="border text-center"><i class="fa fa-minus"></i></td>
										<?php endif; ?>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</div>

				<?= $pagination_links; ?>
			<?php else : ?>
				<div class='bg-danger text-white p-2 rounded'><?= lang('error_nothing_found'); ?></div>
			<?php endif; ?>
		</div>
	</div>
</div>
