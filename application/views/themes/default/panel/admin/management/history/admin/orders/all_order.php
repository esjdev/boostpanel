<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="w-75 container-fluid padding_top">
	<div class="row justify-content-center">
		<div class="col-sm-12 mt-3">
			<div class="section_tittle text-center" data-aos="fade-up">
				<p><?= lang("logs_orders"); ?></p>
				<h2><?= lang("menu_orders"); ?></h2>
			</div>

			<?php view("panel/admin/management/history/admin/orders/default_layout"); ?>

			<?php if (!empty($all_orders)) : ?>
				<div class="table-orders-search">
					<table class="table table-responsive-lg border">
						<thead class="text-center">
							<tr>
								<th><?= lang("id"); ?></th>
								<th><?= lang("input_username"); ?></th>
								<th><?= lang("order_basic_datails"); ?></th>
								<th><?= lang("status"); ?></th>
								<th><?= lang("date"); ?></th>
								<th><?= lang("action"); ?></th>
							</tr>
						</thead>
						<?php
						foreach ($all_orders as $order) :
							$services = $this->model->get("id, name", TABLE_SERVICES, ["id" => $order->service_id], "", "", true);
							$api = $this->model->get('*', TABLE_API_PROVIDERS, ['id' => $order->api_provider_id], '', '', true);

							$name_service = limit_str($services['name'], 90, false);
							$value_charge = ($order->charge <= 0.009 && $order->charge != "0" ? currency_format($order->charge, 4) : currency_format($order->charge));

							if ($order->status == 'completed') {
								$status = '<div class="badge badge-green cursor-pointer fs-12">' . lang("status_completed") . '</div>';
							} elseif ($order->status == 'processing') {
								$status = '<div class="badge badge-warning text-white cursor-pointer fs-12">' . lang("status_processing") . '</div>';
							} elseif ($order->status == 'inprogress') {
								$status = '<div class="badge badge-warning text-white cursor-pointer fs-12">' . lang("status_inprocess") . '</div>';
							} elseif ($order->status == 'pending') {
								$status = '<div class="badge badge-secondary cursor-pointer fs-12">' . lang("status_pending") . '</div>';
							} elseif ($order->status == 'partial') {
								$status = '<div class="badge badge-green cursor-pointer fs-12">' . lang("status_partial") . '</div>';
							} elseif ($api['type_parameter'] == 'api_token' && $order->status == 'refunded') {
								$status = '<div class="badge badge-danger cursor-pointer fs-12">' . lang("status_refunded") . '</div>';
							} elseif ($api['type_parameter'] == 'key' && $order->status == 'refunded') {
								$status = '<div class="badge badge-danger cursor-pointer fs-12">' . lang("status_canceled") . '</div>';
							} elseif ($order->api_provider_id == 0 || empty($order->api_provider_id) && $order->status == 'refunded') {
								$status = '<div class="badge badge-danger cursor-pointer fs-12">' . lang("status_canceled") . '</div>';
							} elseif ($order->status == 'canceled') {
								$status = '<div class="badge badge-danger cursor-pointer fs-12">' . lang("status_canceled") . '</div>';
							}

							$textSexyAlert = str_replace("'", "\'", lang('confirm_close_message'));
						?>
							<tbody>
								<tr class="text-center">
									<td class="border"><?= $order->id; ?></td>
									<td class="border"><?= dataUserId($order->user_id, "username"); ?></td>
									<td class="border">
										<div class="text-left font-weight-bold">
											<?= "<span class='badge badge-primary'>{$order->service_id}</span> - " . $name_service; ?>
										</div>
										<ul class="mt-3 float-left text-left ml-3">
											<li>- <strong><?= lang("type"); ?>:</strong> <?= ($order->type == "api" && !empty($order->api_provider_id) ? 'API' : 'Manual'); ?></li>
											<li>- <strong><?= lang("id_api_order"); ?></strong>: <?= $order->api_order_id; ?></li>
											<li>- <strong><?= lang("id_api_provider"); ?>:</strong> <?= $order->api_service_id; ?></li>
											<li>- <strong id="link-order<?= $order->id; ?>" data-link="<?= $order->link; ?>"><?= lang("link"); ?>:</strong> <?= $order->link; ?></li>
											<li>- <strong><?= lang("quantity"); ?>:</strong> <?= $order->quantity; ?></li>
											<li>- <strong><?= lang("amount"); ?>:</strong> <?= $value_charge; ?></li>
											<li>- <strong><?= lang("start_count"); ?>:</strong> <?= ($order->start_counter != 0 ? $order->start_counter : ''); ?></li>
											<li>- <strong><?= lang("remains"); ?>:</strong> <?= ($order->remains == 0 || $order->remains == '' ? 0 : $order->remains); ?></li>
										</ul>
									</td>
									<td class="border"><?= $status; ?></td>
									<td class="border"><?= $order->created_at; ?></td>
									<td class="border">
										<?php if (userLevel(logged(), 'admin')) : ?>
											<div class="btn-group dropleft">
												<?php
												if (in_array($order->status, ['completed', 'partial', 'refunded', 'canceled'])) :
													$alert = "onclick=\"alert_normal('warning', '" . lang('warning') . "', '" . lang('error_status_order_cannot_use_option') . "');\"";
													$disabled = "disabled";
												else :
													$alert = "";
													$disabled = "";
												endif;
												?>

												<button class="btn btn-sm btn-danger cursor-pointer dropdown-toggle <?= $disabled; ?>" <?= $alert; ?> data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-boundary="viewport"><?= lang("actions"); ?></button>
												<div class="dropdown-menu <?= (in_array($order->status, ['completed', 'partial', 'refunded', 'canceled']) ? 'd-none' : ''); ?>">
													<?php if (!in_array($order->status, ['completed', 'partial', 'refunded', 'canceled'])) : ?>
														<a href="javascript:void(0);" class="dropdown-item" data-toggle="modal" data-target="#editLink" data-backdrop="static" onclick="actionsOrdersHistory('edit_link', '<?= $order->id; ?>')"><?= lang("edit_link"); ?></a>
														<a href="javascript:void(0);" class="dropdown-item" data-toggle="modal" data-target="#setStartCount" data-backdrop="static" onclick="actionsOrdersHistory('set_start_count', '<?= $order->id; ?>')"><?= lang('set_start_count'); ?></a>
														<a href="javascript:void(0);" class="dropdown-item" data-toggle="modal" data-target="#setPartial" data-backdrop="static" onclick="actionsOrdersHistory('set_partial', '<?= $order->id; ?>')"><?= lang("set_partial"); ?></a>

														<a href="javascript:void(0);" class="dropdown-item dropdown-toggle" data-toggle="dropdown"><?= lang("change_status"); ?></a>

														<div class="dropdown-menu dropleft">
															<a href="javascript:void(0);" class="dropdown-item" onclick="alert_confirm('<?= base_url('admin/orders/actions/pending/' . $order->id); ?>', '<?= lang('alert_close'); ?>', '<?= $textSexyAlert; ?>', '<?= lang('close'); ?>', '<?= lang('success_status_updated'); ?>')"><?= lang("status_pending"); ?></a>
															<a href="javascript:void(0);" class="dropdown-item" onclick="alert_confirm('<?= base_url('admin/orders/actions/inprogress/' . $order->id); ?>', '<?= lang('alert_close'); ?>', '<?= $textSexyAlert; ?>', '<?= lang('close'); ?>', '<?= lang('success_status_updated'); ?>')"><?= lang("status_inprocess"); ?></a>
															<a href="javascript:void(0);" class="dropdown-item" onclick="alert_confirm('<?= base_url('admin/orders/actions/processing/' . $order->id); ?>', '<?= lang('alert_close'); ?>', '<?= $textSexyAlert; ?>', '<?= lang('close'); ?>', '<?= lang('success_status_updated'); ?>')"><?= lang("status_processing"); ?></a>
															<a href="javascript:void(0);" class="dropdown-item" onclick="alert_confirm('<?= base_url('admin/orders/actions/completed/' . $order->id); ?>', '<?= lang('alert_close'); ?>', '<?= $textSexyAlert; ?>', '<?= lang('close'); ?>', '<?= lang('success_status_updated'); ?>')"><?= lang("status_completed"); ?></a>
														</div>

														<a href="javascript:void(0);" class="dropdown-item" onclick="alert_confirm('<?= base_url('admin/orders/actions/cancel_order/' . $order->id); ?>', '<?= lang('alert_close'); ?>', '<?= $textSexyAlert; ?>', '<?= lang('close'); ?>', '<?= lang('success_canceled_success'); ?>')"><?= lang("cancel_and_refund"); ?></a>
													<?php endif; ?>
												</div>
											</div>
										<?php endif; ?>
									</td>
								</tr>
							</tbody>
						<?php endforeach; ?>
					</table>

					<?= $pagination_links; ?>
				</div>
			<?php else : ?>
				<div class='bg-danger text-white p-2 rounded'><?= lang('error_nothing_found'); ?></div>
			<?php endif; ?>
		</div>
	</div>
</div>

<!-- Modal Edit Link -->
<div class="modal fade" id="editLink" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content bg-white">
			<div class="modal-header">
				<h4 class="modal-title font-weight-bold text-dark"><?= lang("edit"); ?></h4>
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

				<?= form_open('', ['id' => 'edit-link-order']); ?>
				<div class="form-group">
					<?php
					echo form_label(lang("link"), 'edit_link', [
						'class' => 'form-text font-weight-bold'
					]);

					echo form_input([
						'name' => 'edit_link',
						'class' => 'form-control',
						'type' => 'text',
						'value' => set_value('edit_link'),
						'placeholder' => lang("link")
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

<!-- Modal Set Start Count -->
<div class="modal fade" id="setStartCount" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content bg-white">
			<div class="modal-header">
				<h4 class="modal-title font-weight-bold text-dark"><?= lang("edit"); ?></h4>
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

				<?= form_open('', ['id' => 'form_set_start_count']); ?>
				<div class="form-group">
					<?php
					echo form_label(lang("start_count"), 'set_start_count', [
						'class' => 'form-text font-weight-bold'
					]);

					echo form_input([
						'name' => 'set_start_count',
						'class' => 'form-control',
						'type' => 'text',
						'value' => set_value('set_start_count'),
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

<!-- Modal Set Partial -->
<div class="modal fade" id="setPartial" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content bg-white">
			<div class="modal-header">
				<h4 class="modal-title font-weight-bold text-dark"><?= lang("edit"); ?></h4>
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

				<?= form_open('', ['id' => 'form_set_partial']); ?>
				<div class="form-group">
					<?php
					echo form_label(lang("remains"), 'set_partial', [
						'class' => 'form-text font-weight-bold'
					]);

					echo form_input([
						'name' => 'set_partial',
						'class' => 'form-control',
						'type' => 'text',
						'value' => set_value('set_partial'),
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