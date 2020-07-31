<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<?= link_tag('public/install/images/favicon.ico', 'shortcut icon', 'image/ico'); ?>
	<?= link_tag('public/install/css/bootstrap_install.min.css'); ?>
	<?= link_tag('public/install/css/install.min.css'); ?>
	<?= link_tag('public/install/css/sweetalert2.min.css'); ?>
	<link rel="stylesheet" href="https://designmodo.github.io/Flat-UI/dist/css/flat-ui.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css">
	<title>Website - Install Script</title>
</head>

<body>
<div id="page-overlay" class="visible incoming">
	<div class="loader-wrapper-outer">
		<div class="loader-wrapper-inner">
			<div class="loader">
				<div class="balls mx-auto">
					<div></div>
					<div></div>
					<div></div>
				</div>
				<br>
				Loading... Wait please. Be patient!
			</div>
		</div>
	</div>
</div>

<div class="container">
	<div class="row align-items-center justify-content-center">
		<div class="col-sm-12 d-flex justify-content-center mt-3">
			<img src="<?= base_url('public/install/images/logo-install.png'); ?>">
		</div>
		<div class="col-sm-12 d-flex justify-content-center mt-3">
			<!--- Installation --->
			<h4>Install Script</h4>
		</div>
	</div>
	<?php if (!$all_requirement_success): ?>
		<div class="row align-items-center justify-content-center">
			<button class="btn btn-green mb-2">REQUIREMENTS</button>
		</div>
		<table class="table border">
			<thead>
			<tr>
				<th class="text-center">*</th>
				<th>Current Settings</th>
				<th>Required Settings</th>
				<th class="text-center">Status</th>
			</tr>
			</thead>
			<tbody>
			<tr>
				<td>PHP Version</td>
				<td><?= PHP_VERSION; ?></td>
				<td><?= $required_php_version; ?>+</td>
				<td class="text-center">
					<?php if ($php_version_success) { ?>
						<i class="fas fa-check-circle text-success"></i>
					<?php } else { ?>
						<i class="fas fa-times-circle text-danger"></i>
					<?php } ?>
				</td>
			</tr>
			<tr>
				<td>MySQLi</td>
				<td> <?php if ($mysql_success) { ?>
						<span class="text-success font-weight-bold">On</span>
					<?php } else { ?>
						<span class="text-danger font-weight-bold">Off</span>
					<?php } ?>
				</td>
				<td><span class="text-success font-weight-bold">On</span></td>
				<td class="text-center">
					<?php if ($mysql_success) { ?>
						<i class="fas fa-check-circle text-success"></i>
					<?php } else { ?>
						<i class="fas fa-times-circle text-danger"></i>
					<?php } ?>
				</td>
			</tr>
			<tr>
				<td>cURL</td>
				<td> <?php if ($curl_success) { ?>
						<span class="text-success font-weight-bold">On</span>
					<?php } else { ?>
						<span class="text-danger font-weight-bold">Off</span>
					<?php } ?>
				</td>
				<td><span class="text-success font-weight-bold">On</span></td>
				<td class="text-center">
					<?php if ($curl_success) { ?>
						<i class="fas fa-check-circle text-success"></i>
					<?php } else { ?>
						<i class="fas fa-times-circle text-danger"></i>
					<?php } ?>
				</td>
			</tr>
			<tr>
				<td>allow_url_fopen</td>
				<td> <?php if ($allow_url_fopen_success) { ?>
						<span class="text-success font-weight-bold">On</span>
					<?php } else { ?>
						<span class="text-danger font-weight-bold">Off</span>
					<?php } ?>
				</td>
				<td><span class="text-success font-weight-bold">On</span></td>
				<td class="text-center">
					<?php if ($allow_url_fopen_success) { ?>
						<i class="fas fa-check-circle text-success"></i>
					<?php } else { ?>
						<i class="fas fa-times-circle text-danger"></i>
					<?php } ?>
				</td>
			</tr>
			<tr>
				<td>mbstring</td>
				<td> <?php if ($mbstring_success) { ?>
						<span class="text-success font-weight-bold">On</span>
					<?php } else { ?>
						<span class="text-danger font-weight-bold">Off</span>
					<?php } ?>
				</td>
				<td><span class="text-success font-weight-bold">On</span></td>
				<td class="text-center">
					<?php if ($mbstring_success) { ?>
						<i class="fas fa-check-circle text-success"></i>
					<?php } else { ?>
						<i class="fas fa-times-circle text-danger"></i>
					<?php } ?>
				</td>
			</tr>
			<tr>
				<td>Zip</td>
				<td> <?php if ($zip_success) { ?>
						<span class="text-success font-weight-bold">On</span>
					<?php } else { ?>
						<span class="text-danger font-weight-bold">Off</span>
					<?php } ?>
				</td>
				<td><span class="text-success font-weight-bold">On</span></td>
				<td class="text-center">
					<?php if ($zip_success) { ?>
						<i class="fas fa-check-circle text-success"></i>
					<?php } else { ?>
						<i class="fas fa-times-circle text-danger"></i>
					<?php } ?>
				</td>
			</tr>
			</tbody>
		</table>
	<?php endif; ?>
</div>

<div class="container">
	<?php

	if ($all_requirement_success):
		?>
		<?= form_open('install', ['id' => 'install-form']); ?>
		<div class="row">
			<div class="col-md-8 round align-items-center justify-content-center mx-auto">
				<div class="card border-0">
					<div class="card-body">
						<h6 class="bg-dark text-white p-2 rounded font-weight-bold">MySQL Settings</h5>
						<div class="form-group">
							<?php

							echo form_label("Host <span class='text-danger font-weight-normal'>*</span>", 'host', [
								'class' => 'form-text font-weight-bold'
							]);

							echo form_input([
								'name' => 'host',
								'class' => 'form-control',
								'type' => 'text',
								'value' => set_value("host"),
								'placeholder' => 'localhost'
							]);
							?>
						</div>
						<div class="form-group">
							<?php
							echo form_label("Database user <span class='text-danger font-weight-normal'>*</span>", 'db_user', [
								'class' => 'form-text font-weight-bold'
							]);

							echo form_input([
								'name' => 'db_user',
								'class' => 'form-control',
								'type' => 'text',
								'value' => set_value("db_user"),
								'placeholder' => 'Database user'
							]);
							?>
						</div>
						<div class="form-group">
							<?php
							echo form_label("Database password <span class='text-danger font-weight-normal'>*</span>", 'db_password', [
								'class' => 'form-text font-weight-bold'
							]);

							echo form_input([
								'name' => 'db_password',
								'class' => 'form-control',
								'type' => 'text',
								'value' => set_value("db_password"),
								'placeholder' => 'Database password'
							]);
							?>
						</div>
						<div class="form-group">
							<?php
							echo form_label("Database name <span class='text-danger font-weight-normal'>*</span>", 'db_name', [
								'class' => 'form-text font-weight-bold'
							]);

							echo form_input([
								'name' => 'db_name',
								'class' => 'form-control',
								'type' => 'text',
								'value' => set_value("db_name"),
								'placeholder' => 'Database name'
							]);
							?>
						</div>

						<h6 class="bg-dark text-white p-2 rounded font-weight-bold">Control Panel Admin Access</h5>

						<div class="mt-3">
							<div class="form-group">
								<?php
								echo form_label("Name <span class='text-danger font-weight-normal'>*</span>", 'username', [
									'class' => 'form-text font-weight-bold'
								]);

								echo '<div class="input-group">
									<span class="input-group-prepend">
										<span class="input-group-text">
											<i class="fas fa-user"></i>
										</span>
									</span>';

								echo form_input([
									'name' => 'name',
									'class' => 'form-control',
									'type' => 'text',
									'value' => set_value("name"),
									'placeholder' => "Your name",
								]);

								echo '</div>';
								?>
							</div>

							<div class="row">
								<div class="col-6">
									<div class="form-group">
										<?php
										echo form_label("Username <span class='text-danger font-weight-normal'>*</span>", 'username', [
											'class' => 'form-text font-weight-bold'
										]);

										echo '<div class="input-group">
											<span class="input-group-prepend">
												<span class="input-group-text">
													<i class="fas fa-user"></i>
												</span>
											</span>';

										echo form_input([
											'name' => 'username',
											'class' => 'form-control',
											'type' => 'text',
											'value' => set_value("username"),
											'placeholder' => "Username",
											'maxlength' => 15
										]);

										echo '</div>';
										?>
									</div>
								</div>
								<div class="col-6">
									<div class="form-group">
										<?php
										echo form_label("Email <span class='text-danger font-weight-normal'>*</span>", 'email', [
											'class' => 'form-text font-weight-bold'
										]);

										echo '<div class="input-group">
											<span class="input-group-prepend">
												<span class="input-group-text">
													<i class="fas fa-envelope"></i>
												</span>
											</span>';

										echo form_input([
											'name' => 'email',
											'class' => 'form-control',
											'type' => 'email',
											'value' => set_value("email"),
											'placeholder' => "Email"
										]);

										echo '</div>';
										?>
									</div>
								</div>
							</div>
							<div class="form-group">
								<?php
								echo form_label("Password <span class='text-danger font-weight-normal'>*</span>", 'password', [
									'class' => 'form-text font-weight-bold'
								]);

								echo '<div class="input-group">
									<span class="input-group-prepend">
										<span class="input-group-text">
											<i class="fas fa-lock"></i>
										</span>
									</span>';

								echo form_input([
									'name' => 'password',
									'class' => 'form-control',
									'type' => 'password',
									'value' => set_value("password"),
									'placeholder' => "Password"
								]);

								echo '</div>';
								?>
							</div>
							<div class="form-group">
								<?php
								echo form_label("Timezone <span class='text-danger font-weight-normal'>*</span>", 'timezone', [
									'class' => 'form-text font-weight-bold'
								]);

								echo '<select class="form-control" name="timezone">';

								echo '<option value="noselect" class="font-weight-bold">Choose a timezone</option>';
								foreach (timezone_list() as $key => $value) :
									echo '<option value="' . $value['zone'] . '">' . $value['time'] . '</option>';
								endforeach;

								echo '</select>';
								?>
							</div>
							<div class="form-group">
								<?php
								echo form_submit([
									'name' => 'install',
									'class' => 'btn btn-lg btn-primary w-100',
									'id' => 'button-install',
									'type' => 'submit',
									'value' => "Install"
								]);
								?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?= form_close(); ?>
	<?php else: ?>
		<div class="alert alert-danger text-danger font-weight-bold p-2 rounded mt-3">
			You do not have the requirements for script installation.
		</div>
	<?php endif; ?>
</div>

<script src="<?= base_url('public/install/js/jquery-3.4.1.min.js'); ?>"></script>
<script src="<?= base_url('public/install/js/sweetalert2.all.min.js'); ?>"></script>
<script src="<?= base_url('public/install/js/install.min.js'); ?>"></script>
</body>

</html>
