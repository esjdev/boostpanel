<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="w-75 container-fluid padding_top">
	<div class="row justify-content-center">
		<div class="col-sm-12 mt-3">
			<div class="section_tittle text-center" data-aos="fade-up">
				<p><?= lang("logs_orders"); ?></p>
				<h2><?= lang("menu_orders"); ?></h2>
			</div>

			<?php view("panel/admin/management/history/user/orders/default_layout"); ?>

			<?php if (!empty($all_orders)) : ?>
				<div class="table-orders-search">
					<table class="table-responsive-lg table border">
						<thead class="text-center">
							<tr>
								<th><?= lang("id"); ?></th>
								<th><?= lang("service"); ?></th>
								<th><?= lang("link"); ?></th>
								<th><?= lang("quantity"); ?></th>
								<th><?= lang("start_count"); ?></th>
								<th><?= lang("date"); ?></th>
								<th><?= lang("charge"); ?></th>
								<th><?= lang("status"); ?></th>
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
						?>
							<tbody>
								<tr class="text-center">
									<td class="border"><?= $order->id; ?></td>
									<td class="border font-weight-bold"><?= $name_service; ?></td>
									<td class="border"><a href="<?= $order->link; ?>" target="_blank" class="text-decoration-none text-blue"><i class="fa fa-link font-weight-bold"></i></a></td>
									<td class="border"><?= $order->quantity; ?></td>
									<td class="border"><?= ($order->start_counter != 0 ? $order->start_counter : ''); ?></td>
									<td class="border"><?= $order->created_at; ?></td>
									<td class="border text-primary font-weight-bold"><?= $value_charge; ?></td>
									<td class="border"><?= $status; ?></td>
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