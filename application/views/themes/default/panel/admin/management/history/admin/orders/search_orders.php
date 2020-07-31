<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php if (!empty($search_orders)) : ?>
	<div class="table-orders-search">
		<div class="tab-content table-responsive-lg">
			<table class="table table-striped text-center">
				<thead>
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
				foreach ($search_orders as $order) :
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
							<td><?= $order->id; ?></td>
							<td><?= dataUserId($order->user_id, "username"); ?></td>
							<td>
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
							<td><?= $status; ?></td>
							<td><?= $order->created_at; ?></td>
							<td>
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
		</div>
	</div>
<?php else : ?>
	<div class='bg-danger text-white p-2 rounded mt-2'><?= lang('error_nothing_found'); ?></div>
<?php endif; ?>
