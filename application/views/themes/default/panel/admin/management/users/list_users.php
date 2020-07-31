<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="w-75 container-fluid padding_top">
	<div class="row justify-content-center">
		<div class="col-sm-12 mt-3">
			<div class="section_tittle text-center" data-aos="fade-up">
				<p><?= lang("users_management"); ?></p>
				<h2><?= lang("menu_users"); ?></h2>

				<a href="javascript:void(0);" class="btn btn-green btn-lg border-0 text-white mt-5" data-toggle="modal" data-target="#addUser" data-backdrop="static" onclick="add_new_service();">
					<i class="fa fa-plus-circle text-white"></i> <?= lang("add_new"); ?>
				</a>
			</div>

			<?php if (flashdata('error')) : ?>
				<div class="alert alert-danger alert-dismissible rounded error mt-2" role="alert">
					<i class="fa fa-exclamation-triangle"></i> <span class="error-message"><?= flashdata('error'); ?></span>
					<a class="close cursor-pointer" aria-label="close">&times;</a>
				</div> <!-- Alert error -->
			<?php endif; ?>

			<div class="input-group bg-dark rounded">
				<?php
				echo form_input([
					'name' => 'searchUser',
					'class' => 'form-control searchUserAjax bg-dark text-white border-0',
					'type' => 'text',
					'data-url' => 'users/search',
					'placeholder' => lang("placeholder_search_user"),
				]);
				?>

				<div class="input-group-prepend">
					<i class="fa fa-search rounded-right text-white bg-default m-t-12 mr-3"></i>
				</div>
			</div>

			<div class="table-responsive-lg table-users mt-2">
				<table class="table border">
					<thead>
						<tr>
							<th class="text-center">*</th>
							<th><?= lang("input_name"); ?></th>
							<th><?= lang("input_username"); ?></th>
							<th><?= lang("input_email"); ?></th>
							<th><?= lang("total_spent"); ?></th>
							<th><?= lang("details"); ?></th>
							<th class="text-center"><?= lang("action"); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php $pos = $count;
						foreach ($list_users as $value) : $pos++;
							$textSexyAlert = str_replace("'", "\'", lang('confirm_close_message'));
							$sum_spendings = $this->model->sum_results(TABLE_ORDERS, ['user_id' => $value->id, 'status' => 'completed'], 'charge');

							if ($value->role == 'ADMIN') $role = '<div class="badge badge-danger text-white fs-12">' . lang("admin_role") . '</div>';
							if ($value->role == 'SUPPORT') $role = '<div class="badge badge-warning text-white fs-12">' . lang("support_role") . '</div>';
							if ($value->role == 'BANNED') $role = '<div class="badge badge-primary text-white fs-12">' . lang("banned_role") . '</div>';
							if ($value->role == 'USER') $role = '';
						?>
							<tr class="<?= ($value->role == 'USER' ? 'text-dark' : ''); ?> <?= ($value->role == 'BANNED' ? 'disabled' : ''); ?> <?= (in_array($value->role, ['ADMIN', 'SUPPORT']) ? 'text-dark font-weight-bold' : 'disabled'); ?>">
								<td class="border text-center"><?= $pos; ?></td>
								<td class="border"><?= $value->name; ?></td>
								<td class="border"><?= $value->username . " " . $role; ?><?= ($value->status == 'Inactive' ? '<div class="badge badge-secondary text-white">' . lang("status_inactive") . '</div>' : ''); ?></td>
								<td class="border"><?= $value->email; ?></td>
								<td class="border"><?= (($value->role == 'ADMIN' || $value->role == 'SUPPORT') ? '<span class="font-weight-bold">' . lang("undefined") . '</span>' : '' . configs('currency_symbol', 'value') . "" . currency_format($sum_spendings) . ''); ?>
								</td>
								<td class="border">
									<a href="<?= base_url("admin/users/show/" . $value->uuid); ?>">
										<i class="fa fa-eye fa-2x cursor-pointer"></i>
									</a>
								</td>
								<td class="border text-center">
									<?php if ($value->role != 'ADMIN') : ?>
										<a href="javascript:void(0);" onclick="alert_confirm('<?= base_url('admin/users/destroy/' . $value->uuid); ?>', '<?= lang('alert_close'); ?>', '<?= $textSexyAlert; ?>', '<?= lang('close'); ?>', '<?= lang('success_deleted_success'); ?>')"><i class="fa fa-trash fa-2x text-danger"></i>
										<?php else : ?>
											<i class="fa fa-minus cursor-pointer"></i>
										<?php endif; ?>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>

			<?= $pagination_links; ?>
		</div>
	</div>
</div>

<!-- Modal Add new User -->
<div class="modal fade" id="addUser" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content bg-white">
			<div class="modal-header">
				<h4 class="modal-title font-weight-bold text-dark"><?= lang("add_new"); ?></h4>
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

				<?= form_open('admin/users/create', ['id' => 'add-new-user']); ?>
				<div class="row">
					<div class="col-6">
						<div class="form-group">
							<?php
							echo form_label(lang("input_name"), 'name_user', [
								'class' => 'form-text font-weight-bold'
							]);

							echo form_input([
								'name' => 'name_user',
								'class' => 'form-control',
								'type' => 'text',
								'value' => set_value('name_user'),
								'placeholder' => lang("input_name")
							]);
							?>
						</div>
					</div>

					<div class="col-6">
						<div class="form-group">
							<?php
							echo form_label(lang("input_username"), 'username', [
								'class' => 'form-text font-weight-bold'
							]);

							echo form_input([
								'name' => 'username',
								'class' => 'form-control',
								'type' => 'text',
								'value' => set_value('username'),
								'placeholder' => lang("input_username")
							]);
							?>
						</div>
					</div>
				</div>
				<div class="form-group">
					<?php
					echo form_label(lang("input_email"), 'email_user', [
						'class' => 'form-text font-weight-bold'
					]);

					echo form_input([
						'name' => 'email_user',
						'class' => 'form-control',
						'type' => 'text',
						'value' => set_value('email_user'),
						'placeholder' => lang("input_email")
					]);
					?>
				</div>
				<div class="form-group">
					<?php
					echo form_label(lang("role"), 'role_user', [
						'class' => 'form-text font-weight-bold'
					]);

					echo '<select class="form-control" name="role_user">';

					echo '<option value="noselect" class="font-weight-bold">' . lang("select_role") . '</option>';
					echo '<option value="USER">' . lang("user_role") . '</option>';
					echo '<option value="ADMIN">' . lang("admin_role") . '</option>';
					echo '<option value="SUPPORT">' . lang("support_role") . '</option>';
					echo '</select>';
					?>
				</div>
				<div class="row">
					<div class="col-6">
						<div class="form-group">
							<?php
							echo form_label(lang("input_password"), 'password_user', [
								'class' => 'form-text font-weight-bold'
							]);

							echo form_input([
								'name' => 'password_user',
								'class' => 'form-control',
								'type' => 'password',
								'value' => set_value('password_user'),
								'placeholder' => "****"
							]);
							?>
						</div>
					</div>
					<div class="col-6">
						<div class="form-group">
							<?php
							echo form_label(lang("input_confirm_password"), 'cf_password_user', [
								'class' => 'form-text font-weight-bold'
							]);

							echo form_input([
								'name' => 'cf_password_user',
								'class' => 'form-control',
								'type' => 'password',
								'value' => set_value('input_confirm_password'),
								'placeholder' => "****"
							]);
							?>
						</div>
					</div>
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