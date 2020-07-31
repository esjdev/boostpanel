<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="w-75 container-fluid padding_top">
	<div class="row justify-content-center">
		<div class="col-sm-12 mt-3">
			<div class="section_tittle text-center" data-aos="fade-up">
				<p><?= lang("user_details_management"); ?></p>
				<h2><?= lang("user_details"); ?></h2>
			</div>

			<?php if (flashdata('error')) : ?>
				<div class="alert alert-danger alert-dismissible rounded error" role="alert">
					<i class="fa fa-exclamation-triangle"></i> <span class="error-message"><?= flashdata('error'); ?></span>
					<a class="close cursor-pointer" aria-label="close">&times;</a>
				</div> <!-- Alert Flash Error -->
			<?php endif; ?>

			<div class="row">
				<div class="col-sm-4 mb-2">
					<div class="card border-0">
						<div class="card-body text-center blue-gradient pt-2 rounded shadow-sm">
							<i class="fa fa-user fa-4x mt-2 ml-1 float-left text-white"></i>
							<p class="mt-1 text-white fs-15"><?= lang('input_email'); ?>: <strong><?= $get_user['email']; ?></strong>
							</p>
							<p class="text-white fs-15"><?= lang('input_username'); ?>: <strong><?= $get_user['username']; ?></strong></p>
						</div>
					</div>
				</div>

				<div class="col-sm-4 mb-2">
					<div class="card border-0">
						<div class="card-body text-center aqua-gradient pt-2 rounded shadow-sm">
							<i class="fa fa-money fa-4x mt-2 ml-1 float-left text-white"></i>
							<p class="mt-1 text-white fs-15"><?= strtoupper(lang('balance')); ?></p>
							<p class="fs-15 text-white font-weight-bold"><?= ($get_user['role'] == 'USER' ? configs("currency_symbol", "value") . userBalance($get_user['email']) : lang("undefined")); ?></p>
						</div>
					</div>
				</div>

				<div class="col-sm-4 mb-2">
					<div class="card border-0">
						<div class="card-body text-center blue-gradient pt-2 rounded shadow-sm">
							<i class="fa fa-usd fa-4x mt-2 ml-1 float-left text-white"></i>
							<h4 class="mt-1 text-white fs-15"><?= strtoupper(lang("spent")); ?></h4>
							<p class="fs-15 text-white font-weight-bold"><?= ($get_user['role'] == 'USER' ? configs("currency_symbol", "value") . currency_format($sum_spendings) : lang("undefined")); ?></p>
						</div>
					</div>
				</div>
			</div>

			<?php $textSexyAlert = str_replace("'", "\'", lang('confirm_close_message')); ?>

			<div class="row align-items-center justify-content-center rounded p-3 text-center shadow mb-3 mt-2">
				<div class="col-sm-6 mt-2">
					<button type="button" class="btn btn-warning btn-block border-0" data-toggle="modal" data-target="#updateProfileUser" data-backdrop="static" <?= ((in_array($get_user['role'], ['ADMIN'])) ? 'disabled' : ''); ?>><?= lang("update_profile"); ?></button>
				</div>

				<div class="col-sm-6 mt-2">
					<button type="button" class="btn btn-info btn-block border-0" data-toggle="modal" data-target="#changepass" data-backdrop="static" <?= ((in_array($get_user['role'], ['ADMIN'])) ? 'disabled' : ''); ?>><?= lang("change_password"); ?></button>
				</div>

				<div class="col-sm-6 mt-2">
					<button type="button" class="btn btn-green btn-block border-0" data-toggle="modal" data-target="#balanceform" data-backdrop="static" <?= ((in_array($get_user['role'], ['BANNED', 'ADMIN', 'SUPPORT'])) ? 'disabled' : ''); ?>><?= lang("balance"); ?></button>
				</div>

				<div class="col-sm-6 mt-2">
					<?php if ($get_user['role'] != 'BANNED') : ?>
						<button type="button" <?= ($get_user['role'] == 'ADMIN' ? 'disabled' : ''); ?> class="btn btn-danger btn-block border-0" onclick="alert_swal('<?= base_url('admin/users/banned/' . $get_user['uuid']); ?>', '<?= lang('alert_close'); ?>', '<?= $textSexyAlert; ?>', '<?= lang('close'); ?>')"><?= lang("to_ban"); ?></button>
					<?php else : ?>
						<button type="button" <?= ($get_user['role'] == 'ADMIN' ? 'disabled' : ''); ?> class="btn btn-danger btn-block border-0 button-unban" onclick="alert_swal('<?= base_url('admin/users/banned/' . $get_user['uuid']); ?>', '<?= lang('alert_close'); ?>', '<?= $textSexyAlert; ?>', '<?= lang('close'); ?>')"><?= lang("cancel_ban"); ?></button>
					<?php endif; ?>
				</div>
			</div>

			<div class="text-center">
				<a href="<?= base_url('admin/users'); ?>" class="text-decoration-none"><?= lang("go_back"); ?></a>
			</div>
		</div>
	</div>
</div>

<?php if (!in_array($get_user['role'], ['ADMIN'])) : ?>
	<!-- Modal Update Profile -->
	<div class="modal fade" id="updateProfileUser" tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content bg-white">
				<div class="modal-header">
					<h4 class="modal-title font-weight-bold text-dark"><?= lang("update_profile"); ?></h4>
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

					<div class="bg-danger p-2 mb-3 text-white fs-12 rounded text-center cursor-pointer">
						<i class="fa fa-exclamation-circle"></i> <?= lang("created_account") . " " . convert_time($get_user['created_at'], dataUser(logged(), 'timezone')); ?>
					</div>

					<?= form_open('admin/users/profile/update/' . $get_user['uuid'], ['id' => 'update-profile-user']); ?>
					<div class="form-group">
						<?php
						echo form_label(lang("input_name"), 'name', [
							'class' => 'form-text font-weight-bold'
						]);

						echo form_input([
							'name' => 'name',
							'class' => 'form-control',
							'type' => 'text',
							'value' => $get_user['name'],
							'placeholder' => lang("input_name")
						]);
						?>
					</div>
					<div class="form-group">
						<?php
						echo form_label(lang("input_email"), 'email', [
							'class' => 'form-text font-weight-bold'
						]);

						echo form_input([
							'name' => 'email',
							'class' => 'form-control',
							'type' => 'email',
							'value' => $get_user['email'],
							'placeholder' => lang("input_email"),
							'disabled' => 'disabled'
						]);
						?>
					</div>
					<div class="form-group">
						<?php
						echo form_submit([
							'class' => 'genric-btn info-green e-large btn-block radius fs-16',
							'type' => 'submit',
							'value' => lang("button_update")
						]);
						?>
					</div>
					<?= form_close(); ?>

					<hr>

					<div class="row">
						<?php if ($get_user['role'] == 'USER') : ?>
							<div class="col-6">
								<?= form_open('admin/users/custom/rate/update/' . $get_user['uuid'], ['id' => 'update-custom-rate-user']); ?>
								<div class="form-group">
									<?php
									echo form_label(lang("custom_rate"), 'custom_rate', [
										'class' => 'form-text font-weight-bold'
									]);

									echo '<select class="form-control" name="custom_rate">';

									echo '<option value="noselect" class="font-weight-bold">' . lang("select_custom_rate") . '</option>';
									for ($i = 0; $i <= 100; $i++) {
										echo '<option value="' . $i . '" ' . ($get_user['custom_rate'] == $i ? 'selected' : '') . '>' . $i . '%</option>';
									}
									echo '</select>';
									?>
								</div>
								<div class="form-group">
									<?php
									echo form_submit([
										'class' => 'genric-btn info-green e-large btn-block radius fs-16',
										'type' => 'submit',
										'value' => lang("button_update")
									]);
									?>
								</div>
								<?= form_close(); ?>
							</div>
						<?php endif; ?>
						<div class="<?= ($get_user['role'] == 'USER' ? "col-6" : "col-12"); ?>">
							<?= form_open('admin/users/role/update/' . $get_user['uuid'], ['id' => 'update-role-user']); ?>
							<div class="form-group">
								<?php
								echo form_label(lang("role"), 'role_user', [
									'class' => 'form-text font-weight-bold'
								]);

								echo '<select class="form-control" name="role_user">';

								echo '<option value="noselect" class="font-weight-bold">' . lang("select_role") . '</option>';
								echo '<option value="USER" ' . ($get_user['role'] == 'USER' ? 'selected' : '') . '>' . lang("user_role") . '</option>';
								echo '<option value="ADMIN" ' . ($get_user['role'] == 'ADMIN' ? 'selected' : '') . '>' . lang("admin_role") . '</option>';
								echo '<option value="SUPPORT" ' . ($get_user['role'] == 'SUPPORT' ? 'selected' : '') . '>' . lang("support_role") . '</option>';
								echo '</select>';
								?>
							</div>
							<div class="form-group">
								<?php
								echo form_submit([
									'class' => 'genric-btn info-green e-large btn-block radius fs-16',
									'type' => 'submit',
									'value' => lang("button_change")
								]);
								?>
							</div>
							<?= form_close(); ?>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-danger" data-dismiss="modal"><?= lang("close"); ?></button>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal Change Password -->
	<div class="modal fade" id="changepass" tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content bg-white">
				<div class="modal-header">
					<h4 class="modal-title font-weight-bold text-dark"><?= lang("change_password"); ?></h4>
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

					<?= form_open('admin/users/change/password/' . $get_user['uuid'], ['id' => 'change-password']); ?>
					<div class="form-group">
						<?php
						echo form_label(lang("input_new_password"), 'new_pass', [
							'class' => 'form-text font-weight-bold'
						]);

						echo form_input([
							'name' => 'new_pass',
							'class' => 'form-control',
							'type' => 'password',
							'value' => set_value("new_pass"),
						]);
						?>
					</div>
					<div class="form-group">
						<?php
						echo form_label(lang("input_confirm_password"), 're_pass_new', [
							'class' => 'form-text font-weight-bold'
						]);

						echo form_input([
							'name' => 're_pass_new',
							'class' => 'form-control',
							'type' => 'password',
							'value' => set_value("re_pass_new"),
						]);
						?>
					</div>
					<div class="form-group">
						<?php
						echo form_submit([
							'class' => 'genric-btn info-green e-large btn-block radius fs-16',
							'type' => 'submit',
							'value' => lang("change_password")
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

	<?php if ($get_user['role'] != 'BANNED') : ?>
		<!-- Modal Balance -->
		<div class="modal fade" id="balanceform" tabindex="-1">
			<div class="modal-dialog">
				<div class="modal-content bg-white">
					<div class="modal-header">
						<h4 class="modal-title font-weight-bold text-dark"><?= lang("add_withdraw_balance"); ?></h4>
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

						<?= form_open('admin/users/balance/' . $get_user['uuid'], ['id' => 'balance-action']); ?>
						<div class="form-group">
							<?php
							echo form_label(lang("operation"), 'operation', [
								'class' => 'form-text font-weight-bold'
							]);

							echo form_input([
								'data-toggle' => 'toggle',
								'data-onstyle' => 'success cursor-pointer',
								'data-offstyle' => 'danger cursor-pointer',
								'data-on' => lang('add'),
								'data-off' => lang('to_remove'),
								'data-width' => '100%',
								'data-height' => '20px',
								'type' => 'checkbox',
								'name' => 'operation',
								'value' => '1',
								'checked' => 'checked',
							]);
							?>
						</div>
						<div class="form-group">
							<?php
							echo form_label(lang("amount"), 'amount', [
								'class' => 'form-text font-weight-bold'
							]);

							echo form_input([
								'name' => 'amount',
								'class' => 'form-control',
								'type' => 'number',
								'value' => set_value("amount"),
							]);
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
	<?php endif; ?>
<?php endif; ?>