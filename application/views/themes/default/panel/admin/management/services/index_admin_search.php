<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="table-search-services">
	<?php
	if (!empty($search_services)) :
		echo '<div class="tab-content table-responsive-lg"><table class="table table-hover text-center">
            <thead>
            <tr>
                <th class="text-center">' . lang("id") . '</th>
                <th>' . lang("input_name") . '</th>
                <th>' . lang("lang_type") . '</th>
                <th>' . lang("api_service_id") . '</th>
                <th>' . lang("rate") . ' (' . configs('currency_symbol', 'value') . ')</th>
                <th>' . lang("min") . ' / ' . lang("max") . '</th>
                <th>' . lang("dripfeed") . '</th>
                <th>' . lang("status") . '</th>
                <th class="text-right">' . lang("action") . '</th>
            </tr>
            </thead>';

		foreach ($search_services as $data) :
			$name_service = limit_str($data->name, 60, false);
			$value_rate = ($data->price <= 0.009 ? currency_format($data->price, 4) : currency_format($data->price));

			echo '<div class="description_service' . $data->id . ' d-none">' . $data->description . '</div>';

			$api_name = $this->model->get('*', TABLE_API_PROVIDERS, ['id' => $data->api_provider_id], '', '', true);
			$dripfeed = ($data->dripfeed == true ? '<div class="badge badge-success text-white">' . lang('status_on') . '</div>' : '<div class="badge badge-danger text-white">' . lang('status_off') . '</div>');

			$status = ($data->status == 1 ? '<div class="badge badge-success text-white">' . lang('status_on') . '</div>' : '<div class="badge badge-danger text-white">' . lang('status_off') . '</div>');
			$type = ($data->add_type === 'api' ? 'API <small class="font-weight-bold">(' . $api_name['name'] . ')</small>' : 'Manual');

			$textSexyAlert = str_replace("'", "\'", lang('confirm_close_message'));
			?>
			<tbody>
				<tr class="<?= ($data->status == '0' ? 'text-secondary bg-light' : 'text-dark'); ?>">
					<td class="text-center border" id="service_category_id<?= $data->category_id; ?>" data-category-id="<?= $data->category_id; ?>">
						<?= $data->id; ?><?= ($data->status == '0' ? ' <span class="fa fa-exclamation-circle fs-14 cursor-pointer text-danger" title="' . lang("service_disabled") . '"></span>' : ''); ?>
					</td>
					<td class="border" id="name_service<?= $data->id; ?>" data-name-service="<?= $data->name; ?>"><?= $name_service; ?></td>
					<td class="border" id="type_service<?= $data->id; ?>" data-api-or-manual="<?= $data->add_type; ?>" data-type="<?= $data->type; ?>" data-name-api="<?= $api_name['name']; ?>"><?= $type; ?></td>
					<td class="border"><?= $data->api_service_id; ?></td>
					<td class="border" id="price_service<?= $data->id; ?>" data-price="<?= $value_rate; ?>">
						<?= configs('currency_symbol', 'value') . $value_rate; ?></td>
					<td class="border" id="min_max_service<?= $data->id; ?>" data-min="<?= $data->min; ?>" data-max="<?= $data->max; ?>"><?= $data->min; ?>/<?= $data->max; ?></td>
					<td class="border" id="dripfeed_service<?= $data->id; ?>" data-dripfeed="<?= $data->dripfeed; ?>"><?= $dripfeed; ?></td>
					<td class="border" id="status_service<?= $data->id; ?>" data-status="<?= $data->status; ?>"><?= $status; ?></td>
					<td class="border text-right">
						<div class="btn-group-sm">
							<a href="javascript:void(0);" class="btn btn-icon bg-info border-info text-white" data-toggle="tooltip" data-placement="bottom" title="<?= lang("edit_service"); ?>"><i class="fa fa-edit" data-toggle="modal" data-target="#editService" data-backdrop="static" onclick="edit_service(<?= $data->id; ?>, <?= $data->category_id; ?>)"></i></a>
							<a href="javascript:void(0);" class="btn btn-icon bg-danger border-danger text-white" data-toggle="tooltip" data-placement="bottom" title="<?= lang("delete_service"); ?>" onclick="alert_confirm('<?= base_url('admin/services/destroy/' . $data->id); ?>', '<?= lang('alert_close'); ?>', '<?= $textSexyAlert; ?>', '<?= lang('close'); ?>', '<?= lang('success_deleted_success'); ?>')"><i class="fa fa-trash"></i></a>
						</div>
					</td>
				</tr>
			</tbody>
		<?php
		endforeach;
		echo '</table>';
	else:
		echo "<div class='bg-danger text-white p-2 rounded mb-4'>" . lang('error_nothing_found') . "</div>";
	endif;
	?>
</div>
