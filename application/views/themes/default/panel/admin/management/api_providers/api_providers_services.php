<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="w-75 container-fluid padding_top">
	<div class="row justify-content-center">
		<div class="col-sm-12 mt-3">
			<div class="section_tittle text-center" data-aos="fade-up">
				<p><?= lang("api_providers_management"); ?></p>
				<h2><?= lang("list_api_services"); ?></h2>
			</div>

			<?php if ($get_status_api_providers['status'] == 0) : ?>
				<div class='bg-other-blue p-2 text-white mb-2 rounded'><strong><?= lang("services_api"); ?></strong>
					<a href='<?= base_url("admin/api/providers"); ?>' class='float-right text-white text-decoration-none'><?= lang("go_back"); ?></a>
				</div>
			<?php endif; ?>

			<?php if ($get_status_api_providers['status'] == 1) : ?>
				<div class="table-responsive-lg">
					<table class="table border table-hover">
						<thead>
							<tr class="fs-14">
								<th class="text-center"><?= lang("service_id"); ?></th>
								<th><?= lang("input_name"); ?></th>
								<th><?= lang("menu_category"); ?></th>
								<th><?= lang("price"); ?></th>
								<th><?= lang("min"); ?> / <?= lang("max"); ?></th>
								<th class="text-center"><?= lang("action"); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php
							foreach ($api_providers_services as $providers) :
								$type_services = ($providers->type_parameter == 'api_token' ? 'packages' : 'services');
								$api_connect = api_connect($providers->url, [$providers->type_parameter => $providers->key, 'action' => $type_services], true);

								$balance = api_connect($providers->url, [$providers->type_parameter => $providers->key, 'action' => 'balance'], true);

								$id_provider = $providers->id;
								$nameAPIProvider = $providers->name;

								echo "<div class='bg-other-blue p-2 text-white mb-2 rounded'><strong>" . lang("services_api") . "</strong>: $providers->name <a href='" . base_url("admin/api/providers") . "' class='float-right text-white text-decoration-none'>" . lang("go_back") . "</a></div>";

								if (isset($api_connect['error']) || isset($api_connect['errors'])) :
									echo "<div class='alert alert-danger rounded p-2'><i class=\"fa fa-exclamation-triangle\"></i> " . lang('error_api_connecting') . "</div>";
								else :
									if (!empty($api_connect) && !isset($api_connect['data'])) :
										foreach ($api_connect as $key => $value) :
											$dripfeed = (isset($value['dripfeed']) ? ($value['dripfeed'] == true ? '<div class="badge badge-success text-white fs-12">' . lang("dripfeed") . ': ' . lang("status_on") . '</div>' : '') : '');
											$value_rate = ($value['rate'] <= 0.009 ? currency_format($value['rate'], 4) : currency_format($value['rate']));
							?>
											<tr id="data-<?= ($providers->type_parameter == 'key' ? $value['service'] : $value['id']); ?>" data-dripfeed="<?= (isset($value['dripfeed']) && $value['dripfeed'] == true ? $value['dripfeed'] : false); ?>" data-provider-id="<?= $id_provider; ?>" data-type="<?= (isset($value['type']) && !empty($value['type']) ? $value['type'] : 'Default'); ?>" <?= ($providers->type_parameter == 'key' ? "" : "data-description='" . (isset($value['desc']) ? $value['desc'] : '') . "'"); ?>>
												<td class="border text-center" id="data-service-<?= ($providers->type_parameter == 'key' ? $value['service'] : $value['id']); ?>" data-service-id="<?= ($providers->type_parameter == 'key' ? $value['service'] : $value['id']); ?>">
													<?= ($providers->type_parameter == 'key' ? $value['service'] : $value['id']); ?>
												</td>
												<td class="border" id="data-service-name-<?= ($providers->type_parameter == 'key' ? $value['service'] : $value['id']); ?>" data-title="<?= $value['name']; ?>">
													<?= limit_str($value['name'], (isset($value['dripfeed']) && $value['dripfeed'] == true ? '40' : '60')) . " " . $dripfeed; ?>
												</td>
												<td class="border" id="data-service-category-<?= ($providers->type_parameter == 'key' ? $value['service'] : $value['id']); ?>" data-category="<?= ($providers->type_parameter == 'key' ? $value['category'] : $value['service']); ?>">
													<?= limit_str(($providers->type_parameter == 'key' ? $value['category'] : $value['service']), 50); ?>
												</td>
												<td class="border" id="data-price-<?= ($providers->type_parameter == 'key' ? $value['service'] : $value['id']); ?>" data-price="<?= $value['rate']; ?>" data-currency="<?= (isset($balance['currency']) ? $balance['currency'] : ''); ?>">
													<?= $value_rate . " " . (isset($balance['currency']) ? $balance['currency'] : ''); ?>
												</td>
												<td class="border">
													<span id="data-amount-min-<?= ($providers->type_parameter == 'key' ? $value['service'] : $value['id']); ?>" data-min="<?= $value['min']; ?>">
														<?= $value['min']; ?></span>/<span id="data-amount-max-<?= ($providers->type_parameter == 'key' ? $value['service'] : $value['id']); ?>" data-max="<?= $value['max']; ?>"><?= $value['max']; ?>
													</span>
												</td>
												<td class="border text-center">
													<a href="javascript:void(0);" onclick="getDataViaApi('<?= ($providers->type_parameter == 'key' ? $value['service'] : $value['id']); ?>');" class="border-0" data-toggle="modal" data-target="#addServiceViaApi" data-backdrop="static" data-toggle="tooltip" data-placement="bottom" data-original-title="<?= lang("add_new_service_via_api"); ?>"><i class="fa fa-plus-square fa-2x"></i></a>
												</td>
											</tr>
							<?php endforeach;
									else : echo "<div class='alert alert-danger rounded p-2'><i class=\"fa fa-exclamation-triangle\"></i> " . sprintf(lang("error_service_lists_sync_services"), $providers->name) . "</div>";
									endif;
								endif;
							endforeach; ?>
						</tbody>
					</table>
				</div>

				<!-- Modal Add service via API -->
				<div class="modal fade" id="addServiceViaApi" tabindex="-1">
					<div class="modal-dialog">
						<div class="modal-content bg-white">
							<div class="modal-header">
								<h4 class="modal-title font-weight-bold text-dark"><?= lang("add_new_service_via_api"); ?></h4>
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

								<?php if ($count_category > 0) : ?>
									<?= form_open('admin/api/service/create', ['id' => 'add-service-via-api']); ?>
									<div class="form-group">
										<?php
										echo form_input([
											'name' => 'currency_price',
											'class' => 'form-control',
											'type' => 'hidden',
											'value' => set_value('currency_price')
										]);

										echo form_input([
											'name' => 'dripfeed',
											'class' => 'form-control',
											'type' => 'hidden',
											'value' => set_value('dripfeed')
										]);

										echo form_input([
											'name' => 'api_service_id',
											'class' => 'form-control',
											'type' => 'hidden',
											'value' => set_value('api_service_id')
										]);

										echo form_input([
											'name' => 'api_provider_id',
											'class' => 'form-control',
											'type' => 'hidden',
											'value' => set_value('api_provider_id')
										]);

										echo form_input([
											'name' => 'type',
											'class' => 'form-control',
											'type' => 'hidden',
											'value' => set_value('type')
										]);

										echo form_input([
											'name' => 'min_amount_service',
											'class' => 'form-control',
											'type' => 'hidden',
											'value' => set_value('min_amount_service')
										]);

										echo form_input([
											'name' => 'max_amount_service',
											'class' => 'form-control',
											'type' => 'hidden',
											'value' => set_value('max_amount_service')
										]);

										echo form_label(lang("input_name"), 'name_service', [
											'class' => 'form-text font-weight-bold'
										]);

										echo form_input([
											'name' => 'name_service',
											'class' => 'form-control',
											'type' => 'text',
											'value' => set_value('name_service')
										]);
										?>
									</div>
									<div class="form-group">
										<?php
										echo form_label(lang("menu_category"), 'category_service', [
											'class' => 'form-text font-weight-bold'
										]);

										list_category();
										?>
									</div>
									<div class="form-group">
										<?php
										echo form_label(lang("price_per_1k"), 'price_service', [
											'class' => 'form-text font-weight-bold'
										]);

										echo '<div class="input-group">';

										echo '<div class="input-group-append">
											<span class="input-group-text">' . configs('currency_symbol', 'value') . '</span>
										</div>';

										echo form_input([
											'name' => 'price_service',
											'class' => 'form-control',
											'type' => 'text',
											'value' => set_value('price_service')
										]);

										echo '</div>';
										?>
									</div>
									<div class="form-group">
										<?php
										echo form_label(lang("price_percentage_increase"), 'price_percentage_increase', [
											'class' => 'form-text font-weight-bold'
										]);


										echo '<select class="form-control" name="price_percentage_increase">';

										for ($i = 0; $i <= 1000; $i++) {
											echo '<option value="' . $i . '" class="font-weight-bold">' . $i . '%</option>';
										}
										echo '</select>';
										?>
									</div>
									<?php if (configs('auto_currency_converter', 'value') == 'on') : ?>
										<div class="form-group bg-primary p-2 rounded">
											<div class="custom-control custom-checkbox">
												<input type="checkbox" class="custom-control-input" id="auto_convert_currency_service" name="auto_convert_currency_service" value="1">
												<label class="custom-control-label fs-16 text-white" for="auto_convert_currency_service"><?= lang("auto_convert_currency_service"); ?>
												</label>
											</div>
										</div>
									<?php endif; ?>
									<div class="form-group">
										<?php
										echo form_label(lang("description"), 'description_service', [
											'class' => 'form-text font-weight-bold'
										]);

										echo form_textarea([
											'name' => 'description_service',
											'class' => 'form-control',
											'id' => 'description_service',
											'value' => '',
											'rows' => '5',
											'placeholder' => lang("description")
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
						<?php else : ?>
							<div class="bg-danger text-white p-2 rounded">
								<?= lang("error_not_found_category"); ?>
							</div>
						<?php endif; ?>
						<div class="modal-footer">
							<button type="button" class="btn btn-danger" data-dismiss="modal"><?= lang("close"); ?></button>
						</div>
						</div>
					</div>
				</div>

			<?php
			else :
				echo "<div class='bg-danger text-white p-2 rounded'>" . lang('error_api_provider_disabled') . "</div>";
			endif;

			if (empty($api_providers_services)) return redirect(base_url('admin/api/providers'));
			?>
		</div>
	</div>
</div>