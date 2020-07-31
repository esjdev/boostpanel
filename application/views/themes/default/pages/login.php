<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="description" content="<?= html_escape(configs('meta_description', 'value')); ?>">
    <meta name="keywords" content="<?= html_escape(configs('meta_keywords', 'value')); ?>">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="HandheldFriendly" content="True">
    <meta name="MobileOptimized" content="320">
    <title><?= html_escape($title); ?></title>
    <?= link_tag('public/themes/' . config('theme') . '/images/' . configs('website_favicon', 'value'), 'shortcut icon', 'image/ico'); ?>
	<!-- Google fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat">
    <!-- Bootstrap CSS -->
    <?= set_css('bootstrap.min.css'); ?>
    <?= set_css('bootstrap-toggle.min.css'); ?>
    <!-- animate CSS -->
    <?= set_css('animate.min.css'); ?>
    <!-- fontawesome CSS -->
    <?= set_css('font-awesome.min.css'); ?>
    <!-- style CSS -->
    <?= set_css('style.min.css'); ?>
    <!-- fonts size CSS -->
    <?= set_css('fontsize.min.css'); ?>
    <!-- style css required (do not remove) -->
	<style>.loading:after {content: '<?= lang("loading") . "... " . lang("loading_patient_wait") . ""; ?>';}</style>
	<script src="<?= set_js('jquery-3.4.1.min.js'); ?>"></script>
	<?= configs('javascript_embed_header', 'value'); ?>
</head>

<body>

<div class="preloader">
	<div class="loader">
		<div class="ytp-spinner">
			<div class="ytp-spinner-container">
				<div class="ytp-spinner-rotator">
					<div class="ytp-spinner-left">
						<div class="ytp-spinner-circle"></div>
					</div>
					<div class="ytp-spinner-right">
						<div class="ytp-spinner-circle"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

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
				<div class="loading"></div>
			</div>
		</div>
	</div>
</div>

<div class="container mt-5">
	<div class="row justify-content-center">
		<div class="col-sm-5">
			<a class="navbar-brand mb-5 d-flex justify-content-center" href="<?= base_url(); ?>"><img src="<?= set_image(configs('website_logo', 'value')); ?>" alt="logo-boostpanel"></a>

			<div class="section_tittle text-center">
				<p><?= lang("sign_to_control_panel"); ?></p>
				<h2><?= lang("login"); ?></h2>
			</div>

			<div class="alert alert-danger alert-dismissible rounded error" style="display:none;" role="alert">
				<i class="fa fa-exclamation-triangle"></i> <span class="error-message"></span>
				<a class="close cursor-pointer" aria-label="close">&times;</a>
			</div> <!-- Alert error -->

			<div class="bg-white p-3 rounded shadow">
				<?= form_open('login', ['class' => 'actionForm', 'id' => 'login-form']); ?>
				<div class="form-group">
					<?php
					echo form_label(lang("input_email"), 'email', [
						'class' => 'font-weight-bold form-text'
					]);

					echo '<div class="input-group mb-2">';

					echo '<div class="input-group-prepend">
							<div class="input-group-text"><i class="fa fa-user"></i></div>
						</div>';

					echo form_input([
						'name' => 'email',
						'class' => 'form-control',
						'type' => 'email',
						'value' => set_value("email"),
						'placeholder' => lang("input_email"),
						'onfocus' => "this.placeholder = ''",
						'onblur' => "this.placeholder = '" . lang("input_email") . "'",
						'required' => 'required'
					]);

					echo '</div>';
					?>
				</div>

				<div class="form-group">
					<?php
					echo form_label(lang("input_password"), 'password', [
						'class' => 'font-weight-bold form-text'
					]);

					echo '<div class="input-group mb-2">';

					echo '<div class="input-group-prepend">
							<div class="input-group-text"><i class="fa fa-lock"></i></div>
						</div>';

					echo form_input([
						'name' => 'password',
						'class' => 'form-control',
						'type' => 'password',
						'value' => set_value("password"),
						'placeholder' => lang("input_password"),
						'onfocus' => "this.placeholder = ''",
						'onblur' => "this.placeholder = '" . lang("input_password") . "'",
						'required' => 'required'
					]);

					echo '</div>';
					?>
				</div>

				<?php if (configs('google_recaptcha', 'value') == 'on') : ?>
					<div class="form-group">
						<div class="g-recaptcha" data-sitekey="<?= configs('recaptcha_public_key', 'value'); ?>"></div>
					</div>
				<?php endif; ?>

				<div class="row">
					<div class="col-sm-6">
						<div class="form-group">
						<?php
						echo form_submit([
							'class' => 'genric-btn info-purple e-large btn-block radius fs-16',
							'type' => 'submit',
							'value' => lang("button_login")
						]);
						?>
						</div>
					</div>

					<div class="mt-3 col-sm-6 text-center">
						<a href="<?= base_url('recover'); ?>" class="btn_6 e-large radius text-decoration-none fs-14"><?= lang("forgot_your_password"); ?></a>
					</div>
				</div>

				<?= form_close(); ?>
			</div>

			<div class="mt-4">
				<a href="<?= base_url('register'); ?>" class="btn btn-green btn-lg btn-block radius text-decoration-none mb-3"><i class="fa fa-user-plus"></i> <?= lang("button_register"); ?></a>
			</div>
		</div>
	</div>
</div>

<!-- popper js -->
<script src="<?= set_js('popper.min.js'); ?>"></script>
<!-- bootstrap js -->
<script src="<?= set_js('bootstrap.bundle.min.js'); ?>"></script>
<script src="<?= set_js('bootstrap-datepicker.min.js'); ?>"></script>
<script src="<?= set_js('bootstrap-toggle.min.js'); ?>"></script>
<!-- counterup js -->
<script src="<?= set_js('waypoints.min.js'); ?>"></script>
<script src="<?= set_js('jquery.counterup.min.js'); ?>"></script>
<!-- custom js -->
<script src="<?= set_js('core.min.js'); ?>"></script>
<script src="<?= set_js('scripts.min.js'); ?>"></script>
<!-- tagsinput js -->
<script src="<?= set_js('jquery.tagsinput-revisited.min.js'); ?>"></script>
<?php if (configs('google_recaptcha', 'value') == 'on') : ?>
	<!-- Google Recaptcha v2 -->
	<script src='https://www.google.com/recaptcha/api.js' async defer></script>
<?php endif; ?>

<?= configs('javascript_embed', 'value'); ?>
</body>

</html>