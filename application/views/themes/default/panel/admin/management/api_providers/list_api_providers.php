<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="w-75 container-fluid padding_top">
	<div class="row justify-content-center">
		<div class="col-sm-12 mt-3">
			<div class="section_tittle text-center" data-aos="fade-up">
				<p><?= lang("api_providers_management"); ?></p>
				<h2><?= lang("menu_api_providers"); ?></h2>

				<a href="javascript:void(0);" class="btn btn-green btn-lg border-0 text-white mt-5" data-toggle="modal" data-target="#addApiProviders" data-backdrop="static">
					<i class="fa fa-plus-circle text-white"></i> <?= lang("add_new"); ?>
				</a>
			</div>

			<?php if (flashdata('error')) : ?>
				<div class="alert alert-danger alert-dismissible rounded error" role="alert">
					<i class="fa fa-exclamation-triangle"></i> <span class="error-message"><?= flashdata('error'); ?></span>
					<a class="close cursor-pointer" aria-label="close">&times;</a>
				</div> <!-- Alert error -->
			<?php endif; ?>

			<div class="table-responsive-lg">
				<table class="table border">
					<thead>
						<tr class="fs-14">
							<th class="text-center">*</th>
							<th><?= lang("input_name"); ?></th>
							<th><?= lang("balance"); ?></th>
							<th><?= lang("status"); ?></th>
							<th class="text-right"><?= lang("action"); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php
						$pos = 0;
						foreach ($list_api_providers as $value) :
							$status = ($value->status == 1 ? "<div class='badge badge-pill badge-green cursor-pointer fs-12'>" . lang('status_active') . "</div>" : "<div class='badge badge-pill badge-danger cursor-pointer fs-12'>" . lang('status_inactive') . "</div>");

							$textSexyAlert = str_replace("'", "\'", lang('confirm_close_message'));
							$only_url = str_replace(['api/v1', 'api/v2', 'api/v3', 'api/v4', 'api/v5'], '', $value->url);
							$pos++;
						?>
							<tr>
								<span id="api_key<?= $value->id; ?>" class="d-none"><?= $value->key; ?></span>
								<span id="url_api<?= $value->id; ?>" class="d-none"><?= $value->url; ?></span>
								<span id="type_parameter<?= $value->id; ?>" class="d-none"><?= $value->type_parameter; ?></span>
								<td class="border text-center"><?= $pos; ?></td>
								<td class="border"><a href="<?= $only_url; ?>" target="_blank" class="text-decoration-none" id="name_api<?= $value->id; ?>"><?= (strlen($value->name) >= 17) ? substr(html_escape($value->name), 0, 17) . '...' : html_escape($value->name); ?></a>
								</td>
								<td class="border"><?= currency_format($value->balance) . " " . $value->currency; ?>
									<i onclick="updateBalanceApi(<?= $value->id; ?>);" data-toggle="tooltip" data-placement="bottom" title="<?= lang("update_balance_api"); ?>" class="fa fa-refresh cursor-pointer" id="update_balance_api<?= $value->id; ?>"></i>
									<div class="spinner-border spinner-border-sm spinner<?= $value->id; ?> d-none" role="status"></div>
								</td>
								<td class="border" id="api_status<?= $value->id; ?>" data-status="<?= $value->status; ?>"><?= $status; ?></td>
								<td class="border text-right">
									<div class="btn-group-sm">
										<a href="javascript:void(0);" class="btn btn-icon bg-info border-info text-white" data-toggle="tooltip" data-placement="bottom" title="<?= lang("edit_api"); ?>" onclick="editApi(<?= $value->id; ?>);"><i class="fa fa-edit" data-toggle="modal" data-target="#editApiProviders" data-backdrop="static"></i></a>
										<a href="javascript:void(0);" class="btn btn-icon bg-warning border-warning text-white" data-toggle="tooltip" data-placement="bottom" title="<?= lang("sync_services_api"); ?>" onclick="syncServicesApi(<?= $value->id; ?>);"><i class="fa fa-refresh" data-toggle="modal" data-target="#SyncServices" data-backdrop="static"></i></a>
										<a href="<?= base_url("admin/api/providers/services/" . $value->uuid); ?>" class="btn btn-icon bg-dark border-dark text-white loadingOverlay" data-toggle="tooltip" data-placement="bottom" title="<?= lang("services_list_api"); ?>"><i class="fa fa-list"></i></a>
										<a href="javascript:void(0);" class="btn btn-icon bg-danger border-danger text-white" data-toggle="tooltip" data-placement="bottom" title="<?= lang("delete"); ?>" onclick="alert_confirm_notice('<?= base_url('admin/api/providers/destroy/' . $value->id); ?>', '<?= lang('alert_close'); ?>', '<?= $textSexyAlert; ?>', '<?= lang('close'); ?>', '<?= lang('error_delete_api_providers'); ?>', '<?= lang('success_deleted_success'); ?>')"><i class="fa fa-trash"></i></a>
									</div>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>

			<?= (empty($list_api_providers) ? "<div class='bg-danger text-white p-2 rounded'>" . lang("error_nothing_found") . "</div>" : ''); ?>

			<!-- Modal Add new API -->
			<div class="modal fade" id="addApiProviders" tabindex="-1">
				<div class="modal-dialog">
					<div class="modal-content bg-white">
						<div class="modal-header">
							<h4 class="modal-title font-weight-bold text-dark"><?= lang("add_new"); ?> API</h4>
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

							<?= form_open('admin/api/providers/create', ['id' => 'add-api-providers']); ?>
							<div class="form-group">
								<?php
								echo form_label(lang("input_name"), 'name_api', [
									'class' => 'form-text font-weight-bold'
								]);

								echo form_input([
									'name' => 'name_api',
									'class' => 'form-control',
									'type' => 'text',
									'value' => set_value('name_api'),
								]);
								?>
							</div>
							<div class="form-group">
								<?php
								echo form_label(lang("api_url"), 'url_api', [
									'class' => 'form-text font-weight-bold'
								]);

								echo form_input([
									'name' => 'url_api',
									'class' => 'form-control',
									'type' => 'text',
									'value' => set_value('url_api'),
								]);
								?>
							</div>
							<div class="form-group">
								<?php
								echo form_label(lang("api_key"), 'key_api', [
									'class' => 'form-text font-weight-bold'
								]);
								?>
							</div>
							<div class="row">
								<div class="col-5">
									<div class="form-group">
										<?php
										echo '<select class="form-control" name="type_parameter">';

										echo '<option value="noselect" class="font-weight-bold">' . lang("parameter_type") . '</option>';
										echo '<option value="key">key</option>';
										echo '<option value="api_token">api_token</option>';
										echo '</select>';
										?>
									</div>
								</div>
								<div class="col-7">
									<div class="form-group">
										<?php
										echo form_input([
											'name' => 'key_api',
											'class' => 'form-control',
											'type' => 'text',
											'value' => set_value('key_api'),
										]);
										?>
									</div>
								</div>
							</div>
							<div class="form-group">
								<?php
								echo form_label(lang("status"), 'api_status', [
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
									'name' => 'api_status',
									'value' => '1',
									'checked' => 'checked',
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

			<!-- Modal Edit API -->
			<div class="modal fade" id="editApiProviders" tabindex="-1">
				<div class="modal-dialog">
					<div class="modal-content bg-white">
						<div class="modal-header">
							<h4 class="modal-title font-weight-bold text-dark"><?= lang("edit_api"); ?></h4>
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

							<?= form_open('', ['id' => 'edit-api-providers']); ?>
							<div class="form-group">
								<?php
								echo form_label(lang("input_name"), 'edit_name_api', [
									'class' => 'form-text font-weight-bold'
								]);

								echo form_input([
									'name' => 'edit_name_api',
									'class' => 'form-control',
									'type' => 'text',
									'value' => set_value('edit_name_api')
								]);
								?>
							</div>
							<div class="form-group">
								<?php
								echo form_label(lang("api_url"), 'edit_url_api', [
									'class' => 'form-text font-weight-bold'
								]);

								echo form_input([
									'name' => 'edit_url_api',
									'class' => 'form-control',
									'type' => 'text',
									'value' => set_value('edit_url_api')
								]);
								?>
							</div>
							<div class="form-group">
								<?php
								echo form_label(lang("api_key"), 'edit_key_api', [
									'class' => 'form-text font-weight-bold'
								]);
								?>
							</div>
							<div class="row">
								<div class="col-5">
									<div class="form-group">
										<?php
										echo '<select class="form-control" name="edit_type_parameter">';

										echo '<option value="noselect" class="font-weight-bold">' . lang("parameter_type") . '</option>';
										echo '<option value="key">key</option>';
										echo '<option value="api_token">api_token</option>';
										echo '</select>';
										?>
									</div>
								</div>
								<div class="col-7">
									<div class="form-group">
										<?php
										echo form_input([
											'name' => 'edit_key_api',
											'class' => 'form-control',
											'type' => 'text',
											'value' => set_value('edit_key_api'),
										]);
										?>
									</div>
								</div>
							</div>
							<div class="form-group">
								<?php
								echo form_label(lang("status"), 'edit_api_status', [
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
									'name' => 'edit_api_status',
									'value' => '1',
									'checked' => 'checked',
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

			<!-- Modal Sync Services API -->
			<div class="modal fade" id="SyncServices" tabindex="-1">
				<div class="modal-dialog">
					<div class="modal-content bg-white">
						<div class="modal-header">
							<h4 class="modal-title font-weight-bold text-dark"><?= lang("sync_services_api"); ?></h4>
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

							<?= form_open('', ['id' => 'sync-services-api']); ?>
							<div class="form-group">
								<?php
								echo form_label(lang("input_name"), 'sync_name_api', [
									'class' => 'form-text font-weight-bold'
								]);

								echo form_input([
									'name' => 'sync_name_api',
									'class' => 'form-control',
									'type' => 'text',
									'disabled' => 'disabled',
									'value' => set_value('sync_name_api')
								]);
								?>
							</div>
							<div class="form-group">
								<?php
								echo form_label(lang("api_url"), 'sync_url_api', [
									'class' => 'form-text font-weight-bold'
								]);

								echo form_input([
									'name' => 'sync_url_api',
									'class' => 'form-control',
									'type' => 'text',
									'disabled' => 'disabled',
									'value' => set_value('sync_url_api')
								]);
								?>
							</div>
							<div class="form-group">
								<?php
								echo form_label(lang("api_key"), 'sync_key_api', [
									'class' => 'form-text font-weight-bold'
								]);

								echo form_input([
									'name' => 'sync_key_api',
									'class' => 'form-control',
									'type' => 'text',
									'disabled' => 'disabled',
									'value' => set_value('sync_key_api'),
								]);
								?>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<?php
										echo form_label(lang("synchronous_request"), 'synchronous_request', [
											'class' => 'form-text font-weight-bold'
										]);

										echo '<select class="form-control" name="synchronous_request">';

										echo '<option value="noselect" class="font-weight-bold">' . lang("type_synchronous") . '</option>';
										echo '<option value="current">' . lang("sync_current_services") . '</option>';
										echo '<option value="all">' . lang("sync_all_services") . '</option>';
										echo '</select>';
										?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<?php
										echo form_label(lang("price_percentage_increase"), 'price_percentage_increase', [
											'class' => 'form-text font-weight-bold'
										]);

										echo '<select class="form-control" name="price_percentage_increase">';
										for ($i = 0; $i <= 1000; $i++) {
											echo '<option value="' . $i . '" class="font-weight-bold" ' . ($i == 100 ? "selected" : '') . '>' . $i . '%</option>';
										}
										echo '</select>';
										?>
									</div>
								</div>
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
								echo '<div class="w-100 bg-light p-2">';
								echo '<b class="fs-14 text-danger">' . lang("sync_current_services") . ':</b> ' . lang("sync_current_services_note");
								echo '<br>';
								echo '<b class="fs-14 text-danger">' . lang("sync_all_services") . ':</b> ' . lang("sync_new_services_all_note");
								echo '</div>';
								?>
							</div>
							<div class="form-group">
								<?php
								echo form_submit([
									'class' => 'genric-btn info-green e-large btn-block radius fs-16',
									'type' => 'submit',
									'value' => lang("button_submit")
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
		</div>
	</div>
</div>
