<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php if (!empty($search_orders)) : ?>
	<div class="table-orders-search">
		<div class="tab-content table-responsive-lg">
			<table class="table table-hover text-center">
				<thead>
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
				?>
					<tbody>
						<tr class="text-center">
							<td><?= $order->id; ?></td>
							<td class="font-weight-bold"><?= $name_service; ?></td>
							<td><a href="<?= $order->link; ?>" target="_blank" class="text-decoration-none text-blue"><i class="fa fa-link font-weight-bold"></i></a></td>
							<td><?= $order->quantity; ?></td>
							<td><?= ($order->start_counter != 0 ? $order->start_counter : ''); ?></td>
							<td><?= $order->created_at; ?></td>
							<td class="text-primary font-weight-bold"><?= $value_charge; ?></td>
							<td><?= $status; ?></td>
						</tr>
					</tbody>
				<?php endforeach; ?>
			</table>
		</div>
	</div>
<?php else : ?>
	<div class='bg-danger text-white p-2 rounded mt-2'><?= lang('error_nothing_found'); ?></div>
<?php endif; ?>
