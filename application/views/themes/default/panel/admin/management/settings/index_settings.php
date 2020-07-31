<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="w-75 container-fluid padding_top">
	<div class="row justify-content-center">
		<div class="col-sm-12 mt-3">
			<div class="section_tittle text-center" data-aos="fade-up">
				<p><?= lang("settings_management"); ?></p>
				<h2><?= lang("menu_settings"); ?></h2>
			</div>
		</div>

		<div class="col-sm-12 col-lg-3 mb-3">
			<div class="nav flex-column fs-18 bg-other-blue rounded">
				<a class="getContents menu-settings nav-item nav-link text-blue-light" href="javascript:void(0);" data-url="<?= base_url('admin/settings/get'); ?>" data-content="website_setting"><i class="fa fa-globe"></i> <?= lang("menu_website_settings"); ?></a>
				<a class="getContents menu-settings nav-item nav-link text-blue-light" href="javascript:void(0);" data-url="<?= base_url('admin/settings/get'); ?>" data-content="currency_setting"><i class="fa fa-dollar"></i> <?= lang("menu_settings_currency"); ?></a>
				<a class="getContents menu-settings nav-item nav-link text-blue-light" href="javascript:void(0);" data-url="<?= base_url('admin/settings/get'); ?>" data-content="google_recaptcha"><i class="fa fa-cog"></i> Google Recaptcha</a>
				<a class="getContents menu-settings nav-item nav-link text-blue-light" href="javascript:void(0);" data-url="<?= base_url('admin/settings/get'); ?>" data-content="terms"><i class="fa fa-edit"></i> <?= lang("menu_settings_terms_policy_settings"); ?></a>
				<a href="javascript:void(0);" class="menu-settings nav-item nav-link email-settings text-blue-light">
					<i class="fa fa-at"></i> <?= lang("menu_settings_email_settings"); ?>
				</a>
				<div class="emails-settings-dropdown mb-1 border-0 d-none">
					<a class="getContents bg-blue-light nav-item nav-link text-blue-light" href="javascript:void(0);" data-url="<?= base_url('admin/settings/get'); ?>" data-content="email_settings"><?= lang("menu_settings_email_settings"); ?></a>
					<a class="getContents bg-blue-light nav-item nav-link text-blue-light" href="javascript:void(0);" data-url="<?= base_url('admin/settings/get'); ?>" data-content="email_templates"><?= lang("menu_settings_email_templates_settings"); ?></a>
				</div>
				<a class="getContents menu-settings nav-item nav-link text-blue-light" href="javascript:void(0);" data-url="<?= base_url('admin/settings/get'); ?>" data-content="others_settings"><i class="fa fa-asterisk"></i> <?= lang("menu_settings_others_settings"); ?></a>
			</div>

			<div class="aqua-gradient rounded shadow p-2 mt-3 text-center fs-13">
				<?php
				echo form_label("<strong class='text-white'>" . lang("cron_job_token") . "</strong>", 'token_cron_job', [
					'class' => 'form-text font-weight-bold fs-18 text-white'
				]);

				if (DEMO_VERSION != true) {
					echo '<span class="fs-15 text-white">' . config('security_token') . '</span>';

					echo ' <i onclick="javascript:void(0);" data-toggle="tooltip" data-placement="bottom" title="' . lang("generate_new_token") . '" class="fa fa-refresh cursor-pointer fs-16" id="generate_new_token"></i> <div class="spinner-border spinner-border-sm spinner d-none" role="status"></div>
					';
				} else {
					echo '<span class="fs-14 text-white">' . lang("demo_hidden") . '</span>';
				}
				?>
			</div>
		</div>

		<div class="col-lg-9">
			<div class="ajax_content mb-3">
				<?php view("panel/admin/management/settings/web_settings"); ?>
			</div>
		</div>
	</div>
</div>

<script>
	// Email Settings
	$('.email-settings').on('click', function () {
		if ($(".emails-settings-dropdown").hasClass("d-none")) {
			$('.emails-settings-dropdown').removeClass('d-none');
		} else {
			$('.emails-settings-dropdown').addClass('d-none');
		}
	});
</script>
