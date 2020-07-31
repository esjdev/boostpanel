<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="w-75 container-fluid padding_top">
	<div class="row justify-content-between">
		<div class="col-sm-12 mt-3">
			<div class="section_tittle text-center" data-aos="fade-up">
				<p><?= lang("service_list"); ?></p>
				<h2><?= lang("menu_services"); ?></h2>

				<a href="javascript:void(0);" class="btn btn-green btn-lg border-0 text-white mt-5" data-toggle="modal" data-target="#addNewService" data-backdrop="static">
					<i class="fa fa-plus-circle text-white"></i> <?= lang("add_new"); ?>
				</a>
			</div>

			<div class="input-group bg-dark rounded mb-3">
				<?php
				if (!empty($rows_services)) :
					echo form_input([
						'name' => 'searchServices',
						'class' => 'form-control searchServicesAjax bg-dark text-white border-0',
						'type' => 'text',
						'data-url' => 'services/search',
						'placeholder' => lang("placeholder_search_service"),
					]);
				else :
					echo form_input([
						'name' => 'searchServices',
						'class' => 'form-control searchServicesAjax bg-dark text-white border-0',
						'type' => 'text',
						'data-url' => 'javascript:void(0);',
						'placeholder' => lang("placeholder_search_service"),
						'disabled' => 'disabled'
					]);
				endif;
				?>

				<div class="input-group-prepend">
					<i class="fa fa-search rounded-right text-white bg-default m-t-12 mr-3"></i>
				</div>
			</div>

			<?php if (!empty($rows_services)) : ?>
				<div class="table-search-services">
					<div class="table-responsive-lg">
						<table class="table">
							<thead>
								<tr class="fs-14">
									<th class="text-center"><?= lang("id"); ?></th>
									<th><?= lang("input_name"); ?></th>
									<th><?= lang("type"); ?></th>
									<th><?= lang("api_service_id"); ?></th>
									<th><?= lang("rate"); ?> (<?= configs('currency_symbol', 'value'); ?>)</th>
									<th><?= lang("min"); ?> / <?= lang("max"); ?></th>
									<th><?= lang("dripfeed"); ?></th>
									<th><?= lang("status"); ?></th>
									<th class="text-right"><?= lang("action"); ?></th>
								</tr>
							</thead>
							<?php
							foreach ($categories as $value) :
								$services = $this->model->fetch('*', TABLE_SERVICES, ['category_id' => $value->id], 'created_at', 'desc', '', '');

								if ($value->status == 0) :
									$status = "<div class='badge badge-danger cursor-pointer fs-12'>" . lang('badge_category_disabled') . "</div>";
									$border = 'class="border-inactive"';
								else :
									$status = "";
									$border = 'class="bg-linear text-white font-weight-bold"';
								endif;

								if (!empty($services)) :
							?>
									<tr <?= $border; ?>>
										<td colspan="9"><?= $value->name; ?> <?= $status; ?></td>
									</tr>
									<tbody>
										<?php
										foreach ($services as $data) :
											$name_service = limit_str($data->name, 75, false);
											$value_rate = ($data->price <= 0.009 ? currency_format($data->price, 4) : currency_format($data->price));

											echo '<div class="description_service' . $data->id . ' d-none">' . $data->description . '</div>';

											$api_name = $this->model->get('*', TABLE_API_PROVIDERS, ['id' => $data->api_provider_id], '', '', true);

											$dripfeed = ($data->dripfeed == true ? '<div class="badge badge-success text-white">' . lang('status_on') . '</div>' : '<div class="badge badge-danger text-white">' . lang('status_off') . '</div>');

											$status = ($data->status == 1 ? '<div class="badge badge-success text-white">' . lang('status_on') . '</div>' : '<div class="badge badge-danger text-white">' . lang('status_off') . '</div>');
											$type = ($data->add_type === 'api' ? 'API <small class="font-weight-bold">(' . $api_name['name'] . ')</small>' : 'Manual');

											$textSexyAlert = str_replace("'", "\'", lang('confirm_close_message'));
										?>
											<tr class="<?= ($data->status == '0' ? 'text-secondary bg-light' : 'text-dark'); ?>">
												<td class="border text-center" id="service_category_id<?= $value->id; ?>" data-category-id="<?= $value->id; ?>">
													<?= $data->id; ?><?= ($data->status == '0' ? ' <span class="fa fa-exclamation-circle fs-14 cursor-pointer text-danger" title="' . lang("service_disabled") . '"></span>' : ''); ?>
												</td>
												<td class="border" id="name_service<?= $data->id; ?>" data-name-service="<?= $data->name; ?>"><?= $name_service; ?></td>
												<td class="border" id="type_service<?= $data->id; ?>" data-id="<?= $data->id; ?>" data-api-or-manual="<?= $data->add_type; ?>" data-type="<?= $data->type; ?>" data-name-api="<?= $api_name['name']; ?>"><?= $type; ?></td>
												<td class="border"><?= $data->api_service_id; ?></tdclass="border">
												<td class="border" id="price_service<?= $data->id; ?>" data-price="<?= $value_rate; ?>">
													<?= configs('currency_symbol', 'value') . $value_rate; ?></td>
												<td class="border" id="min_max_service<?= $data->id; ?>" data-min="<?= $data->min; ?>" data-max="<?= $data->max; ?>"><?= $data->min; ?>/<?= $data->max; ?></td>
												<td class="border" id="dripfeed_service<?= $data->id; ?>" data-dripfeed="<?= $data->dripfeed; ?>"><?= $dripfeed; ?></td>
												<td class="border" id="status_service<?= $data->id; ?>" data-status="<?= $data->status; ?>"><?= $status; ?></td>
												<td class="border text-right">
													<div class="btn-group-sm">
														<a href="javascript:void(0);" class="btn btn-icon bg-info border-info text-white" data-toggle="tooltip" data-placement="bottom" title="<?= lang("edit_service"); ?>"><i class="fa fa-edit" data-toggle="modal" data-target="#editService" data-backdrop="static" onclick="serviceEdit(<?= $data->id; ?>, <?= $value->id; ?>)"></i></a>
														<a href="javascript:void(0);" class="btn btn-icon bg-danger border-danger text-white" data-toggle="tooltip" data-placement="bottom" title="<?= lang("delete_service"); ?>" onclick="alert_confirm('<?= base_url('admin/services/destroy/' . $data->id); ?>', '<?= lang('alert_close'); ?>', '<?= $textSexyAlert; ?>', '<?= lang('close'); ?>', '<?= lang('success_deleted_success'); ?>')"><i class="fa fa-trash"></i></a>
													</div>
												</td>
											</tr>
									</tbody>
						<?php endforeach; endif; endforeach; ?>
						</table>
					</div>
				</div>
			<?php else : ?>
				<div class='bg-danger text-white p-2 rounded'><?= lang('error_nothing_found'); ?></div>
			<?php endif; ?>
		</div>
	</div>
</div>

<!-- Modal Add new Service -->
<div class="modal fade" id="addNewService" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content bg-white">
			<div class="modal-header">
				<h4 class="modal-title font-weight-bold text-dark"><?= lang("add_new_service"); ?>
				</h4>
				<button type="button" class="close" data-dismiss="modal">
					<span>&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<?php if (!empty($rows_categories)) : ?>
					<div class="alert alert-danger alert-dismissible rounded error" style="display:none;" role="alert">
						<i class="fa fa-exclamation-triangle"></i> <span class="error-message"></span>
						<a class="close cursor-pointer" aria-label="close">&times;</a>
					</div> <!-- Alert error -->

					<div class="alert alert-success alert-dismissible rounded success" style="display:none;" role="alert">
						<i class="fa fa-thumbs-up"></i> <span class="success-message"></span>
						<a class="close cursor-pointer" aria-label="close">&times;</a>
					</div> <!-- Alert success -->

					<?= form_open('admin/services/create', ['class' => 'addNewService']); ?>
					<div class="form-group">
						<?php
						echo form_label(lang("package_name"), 'add_package_name', [
							'class' => 'form-text font-weight-bold'
						]);

						echo form_input([
							'name' => 'add_package_name',
							'class' => 'form-control',
							'type' => 'text',
							'value' => set_value('add_package_name')
						]);
						?>
					</div>
					<div class="form-group">
						<?php
						echo form_label(lang("choose_category"), 'choose_category', [
							'class' => 'form-text font-weight-bold'
						]);

						list_category("choose_category");
						?>
					</div>
					<div class="row">
						<div class="col-6">
							<div class="form-group">
								<?php
								echo form_label(lang("min_amount"), 'add_min_amount', [
									'class' => 'form-text font-weight-bold'
								]);

								echo form_input([
									'name' => 'add_min_amount',
									'class' => 'form-control',
									'type' => 'number',
									'value' => set_value('add_min_amount')
								]);
								?>
							</div>
						</div>
						<div class="col-6">
							<div class="form-group">
								<?php
								echo form_label(lang("max_amount"), 'add_max_amount', [
									'class' => 'form-text font-weight-bold'
								]);

								echo form_input([
									'name' => 'add_max_amount',
									'class' => 'form-control',
									'type' => 'number',
									'value' => set_value('add_max_amount')
								]);
								?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-6 dripfeed-default">
							<div class="form-group">
								<?php
								echo form_label(lang("dripfeed"), 'add_dripfeed_service', [
									'class' => 'form-text font-weight-bold'
								]);

								echo '<select name="add_dripfeed_service" class="form-control">';
								echo '<option value="0">' . lang("status_inactive") . '</option>';
								echo '<option value="1">' . lang("status_active") . '</option>';
								echo '</select>';
								?>
							</div>
						</div>
						<div class="type_service col-6">
							<div class="form-group">
								<?php
								echo form_label(lang("service_type"), 'add_service_type', [
									'class' => 'form-text font-weight-bold'
								]);

								$service_type_array = array(
									'default' => lang('default'),
									'subscriptions' => lang('subscriptions'),
									'custom_comments' => lang('custom_comments'),
									'custom_comments_package' => lang('comments_package'),
									'mentions_with_hashtags' => lang('mentions_hashtags'),
									'mentions_custom_list' => lang('mentions_custom_list'),
									'mentions_hashtag' => lang('metions_hashtag'),
									'mentions_user_followers' => lang('metions_user_followers'),
									'mentions_media_likers' => lang('metions_media_likers'),
									'package' => lang('package'),
									'comment_likes' => lang('comment_likes'),
									'custom_data' => lang('custom_data'),
									'poll' => lang('poll'),
									'seo' => lang('seo'),
								);

								echo '<select class="form-control" name="add_services_type" onChange="selectTypeService(\'add_services_type\');">';
								echo '<option value="noselect" class="font-weight-bold">' . lang("choose_type_service") . '</option>';
								foreach ($service_type_array as $type => $service_type) {
									echo '<option value="' . $type . '">' . $service_type . '</option>';
								}
								echo '</select>';
								?>
							</div>
						</div>
					</div>
					<div class="form-group">
						<?php
						echo form_label(lang("price"), 'add_price_amount', [
							'class' => 'form-text font-weight-bold'
						]);

						echo '<div class="input-group">';

						echo '<div class="input-group-append">
							<span class="input-group-text">' . configs('currency_symbol', 'value') . '</span>
						</div>';

						echo form_input([
							'name' => 'add_price_amount',
							'class' => 'form-control',
							'type' => 'text',
							'value' => set_value('add_price_amount'),
							'placeholder' => lang("price")
						]);

						echo '</div>';
						?>
					</div>
					<div class="form-group">
						<?php
						echo form_label(lang("description"), 'add_description_service', [
							'class' => 'form-text font-weight-bold'
						]);

						echo form_textarea([
							'name' => 'add_description_service',
							'class' => 'form-control',
							'id' => 'add_description_service',
							'value' => '',
							'rows' => '5',
							'placeholder' => lang("description")
						]);
						?>
					</div>
					<div class="form-group">
						<?php
						echo form_label(lang("status"), 'add_status_service', [
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
							'name' => 'add_status_service',
							'value' => '1',
							'checked' => 'checked',
						]);
						?>
					</div>
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<?php
								echo form_button([
									'class' => 'genric-btn info-green e-large btn-block radius fs-16',
									'type' => 'submit',
									'content' => '<i class="fa fa-save"></i> ' . lang("save")
								]);
								?>
							</div>
						</div>

						<div class="col-sm-6 mt-2 text-center">
							<div class="form-group">
								<a href="<?= base_url('admin/api/providers'); ?>" class="btn_6 fs-16"><?= lang("add_service_via_api"); ?></a>
							</div>
						</div>
					</div>
					<?= form_close(); ?>
				<?php else : ?>
					<div class="alert-not-found alert-danger-not-found text-danger p-2 rounded">
						<?= lang("error_not_found_category"); ?>
					</div>
				<?php endif; ?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal"><?= lang("close"); ?></button>
			</div>
		</div>
	</div>
</div>

<!-- Modal Edit Service -->
<div class="modal fade" id="editService" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content bg-white">
			<div class="modal-header">
				<h4 class="modal-title font-weight-bold text-dark"><?= lang("edit_service"); ?> <span class='api_name_form text-danger d-none'></span></h4>
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

				<?= form_open('', ['class' => 'editService']); ?>
				<div class="form-group">
					<?php
					echo form_label(lang("package_name"), 'edit_package_name', [
						'class' => 'form-text font-weight-bold'
					]);

					echo form_input([
						'name' => 'edit_package_name',
						'class' => 'form-control',
						'type' => 'text',
						'value' => set_value('edit_package_name'),
						'placeholder' => lang("package_name")
					]);
					?>
				</div>
				<div class="form-group">
					<?php
					echo form_label(lang("menu_category"), 'edit_choose_category', [
						'class' => 'form-text font-weight-bold'
					]);

					list_category();
					?>
				</div>
				<div class="row">
					<div class="col-6 edit_min_amount d-none">
						<div class="form-group">
							<?php
							echo form_label(lang("min_amount"), 'edit_min_amount', [
								'class' => 'form-text font-weight-bold'
							]);

							echo form_input([
								'name' => 'edit_min_amount',
								'class' => 'form-control',
								'type' => 'number',
								'value' => set_value('edit_min_amount'),
								'placeholder' => lang("min_amount")
							]);
							?>
						</div>
					</div>
					<div class="col-6 edit_max_amount d-none">
						<div class="form-group">
							<?php
							echo form_label(lang("max_amount"), 'edit_max_amount', [
								'class' => 'form-text font-weight-bold'
							]);

							echo form_input([
								'name' => 'edit_max_amount',
								'class' => 'form-control',
								'type' => 'number',
								'value' => set_value('edit_max_amount'),
								'placeholder' => lang("max_amount")
							]);
							?>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-6 dripfeed-default-edit">
						<div class="form-group">
							<?php
							echo form_label(lang("dripfeed"), 'edit_dripfeed_service', [
								'class' => 'form-text font-weight-bold'
							]);

							echo '<select name="edit_dripfeed_service" class="form-control">';
							echo '<option value="0">' . lang("status_inactive") . '</option>';
							echo '<option value="1">' . lang("status_active") . '</option>';
							echo '</select>';
							?>
						</div>
					</div>
					<div class="type_service_edit col-6">
						<div class="form-group">
							<?php
							echo form_label(lang("service_type"), 'edit_service_type', [
								'class' => 'form-text font-weight-bold'
							]);

							$service_type_array = array(
								'default' => lang('default'),
								'subscriptions' => lang('subscriptions'),
								'custom_comments' => lang('custom_comments'),
								'custom_comments_package' => lang('comments_package'),
								'mentions_with_hashtags' => lang('mentions_hashtags'),
								'mentions_custom_list' => lang('mentions_custom_list'),
								'mentions_hashtag' => lang('metions_hashtag'),
								'mentions_user_followers' => lang('metions_user_followers'),
								'mentions_media_likers' => lang('metions_media_likers'),
								'package' => lang('package'),
								'comment_likes' => lang('comment_likes'),
								'custom_data' => lang('custom_data'),
								'poll' => lang('poll'),
								'seo' => lang('seo'),
							);

							echo '<select class="form-control" name="edit_services_type" onChange="selectTypeService(\'edit_services_type\');">';
							echo '<option value="noselect" class="font-weight-bold">' . lang("choose_type_service") . '</option>';
							foreach ($service_type_array as $type => $service_type) {
								echo '<option value="' . $type . '">' . $service_type . '</option>';
							}
							echo '</select>';
							?>
						</div>
					</div>
				</div>
				<div class="form-group">
					<?php
					echo form_label(lang("price"), 'edit_price_amount', [
						'class' => 'form-text font-weight-bold'
					]);

					echo '<div class="input-group">';

					echo '<div class="input-group-append">
						<span class="input-group-text">' . configs('currency_symbol', 'value') . '</span>
					</div>';

					echo form_input([
						'name' => 'edit_price_amount',
						'class' => 'form-control',
						'type' => 'text',
						'value' => set_value('edit_price_amount'),
						'placeholder' => lang("price")
					]);

					echo '</div>';
					?>
				</div>
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
					echo form_label(lang("status"), 'status_service', [
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
						'name' => 'status_service',
						'value' => '1',
						'checked' => 'checked',
					]);
					?>
				</div>
				<div class="form-group">
					<?php
					echo form_button([
						'class' => 'genric-btn info-green e-large btn-block radius fs-16',
						'type' => 'submit',
						'content' => '<i class="fa fa-save"></i> ' . lang("save")
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

<script src="<?= set_js('services.min.js'); ?>"></script>