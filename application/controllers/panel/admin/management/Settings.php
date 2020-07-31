<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Settings extends MY_Controller
{
	protected $table_configs;
	protected $table_notifications;
	protected $table_email_templates;
	protected $table_payments;

	public function __construct()
	{
		parent::__construct();
		if (!userLevel(logged(), 'admin')) return redirect(base_url());

		$this->table_configs = TABLE_CONFIG;
		$this->table_notifications = TABLE_NOTIFICATIONS;
		$this->table_email_templates = TABLE_EMAIL_TEMPLATES;
		$this->table_payments = TABLE_PAYMENTS_CONFIG;

		$this->load->model("config_model");
	}

	public function index()
	{
		$data = [
			'title' => lang("title_manage_system"),
		];

		view('layouts/auth_header', $data);
		view('panel/admin/management/settings/index_settings');
		view('layouts/auth_footer');
	}

	public function get()
	{
		$type = $this->input->post("type", true);

		if (isset($_POST) && !empty($_POST)) {
			switch ($type) {
				case 'currency_setting':
					view('panel/admin/management/settings/currency_settings');
					break;

				case 'google_recaptcha':
					view('panel/admin/management/settings/google_settings');
					break;

				case 'terms':
					view('panel/admin/management/settings/terms_settings');
					break;

				case 'other':
					view('panel/admin/management/settings/other_settings');
					break;

				case 'email_settings':
					view('panel/admin/management/settings/email_settings');
					break;

				case 'email_templates':
					view('panel/admin/management/settings/email_templates');
					break;

				case 'others_settings':
					view('panel/admin/management/settings/others_settings');
					break;

				default:
					view('panel/admin/management/settings/web_settings');
					break;
			}
		}
	}

	public function generate_new_token()
	{
		if (DEMO_VERSION != true) {
			$security_token = config('security_token');
			$hash = create_random_api_key(16);
			change_file(APPPATH . "config/config.php", [$security_token => $hash]);
		}

		redirect(base_url('admin/settings'));
	}

	public function website_settings()
	{
		if (isset($_POST) && !empty($_POST)) {
			if (DEMO_VERSION != true) {
				$app_title = $this->input->post("app_title", true);
				$meta_description = $this->input->post("meta_description", true);
				$meta_keywords = $this->input->post("meta_keywords", true);

				$this->config_model->validate_form();

				if ($this->form_validation->run() == false) {
					foreach ($this->form_validation->error_array() as $key => $value) {
						json([
							'csrf' => $this->security->get_csrf_hash(),
							'type' => 'error',
							'message' => form_error($key, false, false)
						]);
					}
				}

				if (!empty($_FILES)) {
					$path = './public/themes/' . config('theme') . '/images/';

					$website_logo = $_FILES['website_logo']['size'];
					$website_logo_white = $_FILES['website_logo_white']['size'];
					$website_favicon = $_FILES['website_favicon']['size'];

					if ($website_logo > 0) {
						$config = [
							'upload_path' => $path,
							'allowed_types' => 'png',
							'file_name' => 'logo.png',
							'overwrite' => true,
						];

						$this->load->library('upload', $config);

						if (!$this->upload->do_upload('website_logo')) {
							json([
								'csrf' => $this->security->get_csrf_hash(),
								'type' => 'error',
								'message' => $this->upload->display_errors('', ''),
							]);
						}
					}

					if ($website_logo_white > 0) {
						$config = [
							'upload_path' => $path,
							'allowed_types' => 'png',
							'file_name' => 'logo_white.png',
							'overwrite' => true,
						];

						$this->load->library('upload', $config);

						if (!$this->upload->do_upload('website_logo_white')) {
							json([
								'csrf' => $this->security->get_csrf_hash(),
								'type' => 'error',
								'message' => $this->upload->display_errors('', ''),
							]);
						}
					}

					if ($website_favicon > 0) {
						$config = [
							'upload_path' => $path,
							'allowed_types' => 'ico|png',
							'file_name' => 'favicon.ico',
							'overwrite' => true,
						];

						$this->load->library('upload', $config);

						if (!$this->upload->do_upload('website_favicon')) {
							json([
								'csrf' => $this->security->get_csrf_hash(),
								'type' => 'error',
								'message' => $this->upload->display_errors('', ''),
							]);
						}
					}
				}

				$this->model->one_update($this->table_configs, 'name', 'app_title', ['value' => $app_title, 'updated_at' => NOW]);
				$this->model->one_update($this->table_configs, 'name', 'meta_description', ['value' => $meta_description, 'updated_at' => NOW]);
				$this->model->one_update($this->table_configs, 'name', 'meta_keywords', ['value' => $meta_keywords, 'updated_at' => NOW]);

				json([
					'csrf' => $this->security->get_csrf_hash(),
					'type' => 'success',
					'message' => lang("success_settings_save"),
				]);
			}

			json([
				'csrf' => $this->security->get_csrf_hash(),
				'type' => 'error',
				'message' => lang("demo"),
			]);
		}
	}

	public function notifications_settings()
	{
		if (isset($_POST) && !empty($_POST)) {
			if (DEMO_VERSION != true) {
				$verification_news_account = $this->input->post('verification_news_account');
				$new_user_welcome = $this->input->post('new_user_welcome');
				$new_user_notification = $this->input->post('new_user_notification');
				$notification_ticket = $this->input->post('notification_ticket');
				$payment_notification = $this->input->post('payment_notification');

				if (isset($verification_news_account)) {
					$this->model->one_update($this->table_notifications, 'name', 'email_verification_new_account', ['value' => $verification_news_account, 'updated_at' => NOW]);
				} else {
					$this->model->one_update($this->table_notifications, 'name', 'email_verification_new_account', ['value' => 0, 'updated_at' => NOW]);
				}

				if (isset($new_user_welcome)) {
					$this->model->one_update($this->table_notifications, 'name', 'new_user_welcome', ['value' => $new_user_welcome, 'updated_at' => NOW]);
				} else {
					$this->model->one_update($this->table_notifications, 'name', 'new_user_welcome', ['value' => 0, 'updated_at' => NOW]);
				}

				if (isset($new_user_notification)) {
					$this->model->one_update($this->table_notifications, 'name', 'new_user_notification', ['value' => $new_user_notification, 'updated_at' => NOW]);
				} else {
					$this->model->one_update($this->table_notifications, 'name', 'new_user_notification', ['value' => 0, 'updated_at' => NOW]);
				}

				if (isset($notification_ticket)) {
					$this->model->one_update($this->table_notifications, 'name', 'notification_ticket', ['value' => $notification_ticket, 'updated_at' => NOW]);
				} else {
					$this->model->one_update($this->table_notifications, 'name', 'notification_ticket', ['value' => 0, 'updated_at' => NOW]);
				}

				if (isset($payment_notification)) {
					$this->model->one_update($this->table_notifications, 'name', 'payment_notification', ['value' => $payment_notification, 'updated_at' => NOW]);
				} else {
					$this->model->one_update($this->table_notifications, 'name', 'payment_notification', ['value' => 0, 'updated_at' => NOW]);
				}
			}
		}
	}

	public function change_theme()
	{
		if (isset($_POST) && !empty($_POST)) {
			if (DEMO_VERSION != true) {
				$theme_website = $this->input->post('theme_website', true);

				$theme = config('theme');
				change_file(APPPATH . "config/config.php", [$theme => $theme_website]);

				json([
					'csrf' => $this->security->get_csrf_hash(),
					'type' => 'success',
					'message' => lang("success_change_theme"),
				]);
			}

			json([
				'csrf' => $this->security->get_csrf_hash(),
				'type' => 'error',
				'message' => lang("demo"),
			]);
		}
	}


	public function timezone_setting()
	{
		if (isset($_POST) && !empty($_POST)) {
			if (DEMO_VERSION != true) {
				$timezone = $this->input->post('timezone', true);

				if ($timezone == 'noselect') {
					json([
						'csrf' => $this->security->get_csrf_hash(),
						'type' => 'error',
						'message' => lang("error_select_timezone"),
					]);
				}

				$current_timezone = config('timezone');
				change_file(APPPATH . "config/config.php", [$current_timezone => $timezone]);

				json([
					'csrf' => $this->security->get_csrf_hash(),
					'type' => 'success',
					'message' => lang("success_change_timezone"),
				]);
			}

			json([
				'csrf' => $this->security->get_csrf_hash(),
				'type' => 'error',
				'message' => lang("demo"),
			]);
		}
	}

	public function currency_settings()
	{
		if (isset($_POST) && !empty($_POST)) {
			if (DEMO_VERSION != true) {
				$currency_code = $this->input->post("currency_code", true);
				$symbol_currency = $this->input->post("symbol_currency", true);
				$currency_places = $this->input->post("currency_places", true);
				$decimal_separator = $this->input->post("decimal_separator", true);
				$thousand_separator = $this->input->post("thousand_separator", true);
				$auto_currency_converter = $this->input->post("auto_currency_converter", true);

				$this->config_model->validate_form_currency();

				if ($this->form_validation->run() == false) {
					foreach ($this->form_validation->error_array() as $key => $value) {
						json([
							'csrf' => $this->security->get_csrf_hash(),
							'type' => 'error',
							'message' => form_error($key, false, false),
						]);
					}
				}

				if (!in_array($currency_code, ['AUD', 'BRL', 'CAD', 'CZK', 'DKK', 'EUR', 'HKD', 'HUF', 'INR', 'ILS', 'JPY', 'CNY', 'RON', 'TRY', 'ZAR', 'EGP', 'PEN', 'MYR', 'MXN', 'TWD', 'NZD', 'NOK', 'PHP', 'PLN', 'GBP', 'RUB', 'SGD', 'SEK', 'CHF', 'THB', 'USD'])) {
					json([
						'csrf' => $this->security->get_csrf_hash(),
						'type' => 'error',
						'message' => lang('error_code_currency_incorrect'),
					]);
				}

				$auto_currency_converter = ($auto_currency_converter == 'on' ? $currency_converter = 'on' : $currency_converter = 'off');

				$places = (in_array($currency_places, [0, 1, 2, 3, 4]) ? $currency_places : 2);
				$decimal = (in_array($decimal_separator, [',', '.']) ? $decimal_separator : '.');
				$thousand = (in_array($thousand_separator, [',', '.', ' ']) ? $thousand_separator : ',');

				$this->model->one_update($this->table_configs, 'name', 'currency_code', ['value' => $currency_code, 'updated_at' => NOW]);
				$this->model->one_update($this->table_configs, 'name', 'currency_decimal', ['value' => $places, 'updated_at' => NOW]);
				$this->model->one_update($this->table_configs, 'name', 'currency_symbol', ['value' => $symbol_currency, 'updated_at' => NOW]);
				$this->model->one_update($this->table_configs, 'name', 'currency_decimal_separator', ['value' => $decimal, 'updated_at' => NOW]);
				$this->model->one_update($this->table_configs, 'name', 'currency_thousand_separator', ['value' => $thousand, 'updated_at' => NOW]);
				$this->model->one_update($this->table_configs, 'name', 'auto_currency_converter', ['value' => $currency_converter, 'updated_at' => NOW]);

				if ($currency_code != 'BRL' || !in_array($symbol_currency, ['r$', 'R$'])) {
					$this->model->one_update($this->table_payments, 'name', 'mercadopago_access_token', ['value' => '', 'updated_at' => NOW]);
					$this->model->one_update($this->table_payments, 'name', 'mercadopago_status', ['value' => 'off', 'updated_at' => NOW]);

					$this->model->one_update($this->table_payments, 'name', 'pagseguro_token', ['value' => '', 'updated_at' => NOW]);
					$this->model->one_update($this->table_payments, 'name', 'pagseguro_email', ['value' => '', 'updated_at' => NOW]);
					$this->model->one_update($this->table_payments, 'name', 'pagseguro_status', ['value' => 'off', 'updated_at' => NOW]);
				}

				json([
					'csrf' => $this->security->get_csrf_hash(),
					'type' => 'success',
					'message' => lang("success_settings_save"),
				]);
			}

			json([
				'csrf' => $this->security->get_csrf_hash(),
				'type' => 'error',
				'message' => lang("demo"),
			]);
		}
	}

	public function google_recaptcha()
	{
		if (isset($_POST) && !empty($_POST)) {
			if (DEMO_VERSION != true) {
				$recaptcha_on_off = $this->input->post("recaptcha_on_off", true);
				$public_key = $this->input->post("public_key", true);
				$private_key = $this->input->post("private_key", true);

				$this->config_model->validate_form_recaptcha();

				if ($this->form_validation->run() == false && $recaptcha_on_off == 'on') {
					foreach ($this->form_validation->error_array() as $key => $value) {
						json(
							[
								'csrf' => $this->security->get_csrf_hash(),
								'type' => 'error',
								'message' => form_error($key, false, false),
							]
						);
					}
				}

				$status_recaptcha = ($recaptcha_on_off == 'on' ? 'on' : 'off');

				$this->model->one_update($this->table_configs, 'name', 'google_recaptcha', ['value' => $status_recaptcha, 'updated_at' => NOW]);
				$this->model->one_update($this->table_configs, 'name', 'recaptcha_public_key', [
					'value' => ($status_recaptcha == 'on') ? $public_key : '',
					'updated_at' => NOW
				]);
				$this->model->one_update($this->table_configs, 'name', 'recaptcha_private_key', [
					'value' => ($status_recaptcha == 'on') ? $private_key : '',
					'updated_at' => NOW
				]);

				json([
					'csrf' => $this->security->get_csrf_hash(),
					'type' => 'success',
					'message' => lang("success_settings_save"),
				]);
			}

			json([
				'csrf' => $this->security->get_csrf_hash(),
				'type' => 'error',
				'message' => lang("demo"),
			]);
		}
	}

	public function terms_policy_settings()
	{
		if (isset($_POST) && !empty($_POST)) {
			if (DEMO_VERSION != true) {
				$terms_content = $this->input->post("text-area-input-settings-terms", true);
				$policy_content = $this->input->post("text-area-input-settings-policy", true);

				$this->model->one_update($this->table_configs, 'name', 'terms_content', ['value' => $terms_content, 'updated_at' => NOW]);
				$this->model->one_update($this->table_configs, 'name', 'policy_content', ['value' => $policy_content, 'updated_at' => NOW]);

				json([
					'csrf' => $this->security->get_csrf_hash(),
					'type' => 'success',
					'message' => lang("success_settings_save"),
				]);
			}

			json([
				'csrf' => $this->security->get_csrf_hash(),
				'type' => 'error',
				'message' => lang("demo"),
			]);
		}
	}

	public function email_settings()
	{
		if (isset($_POST) && !empty($_POST)) {
			if (DEMO_VERSION != true) {
				$email_from = $this->input->post("email_from", true);
				$protocol = $this->input->post("email_protocol", true);
				$smtp_server = $this->input->post("smtp_server", true);
				$smtp_port = $this->input->post("smtp_port", true);
				$smtp_encryption = $this->input->post("smtp_encryption", true);
				$smtp_username = $this->input->post("smtp_username", true);
				$smtp_password = $this->input->post("smtp_password", true);

				if ($protocol == 'mail') {
					$this->config_model->validate_form_email_settings();
				} elseif ($protocol == 'smtp') {
					$this->config_model->validate_form_email_settings(false);
				} else {
					$this->config_model->validate_form_email_settings();
				}

				if ($this->form_validation->run() == false) {
					foreach ($this->form_validation->error_array() as $key => $value) {
						json([
							'csrf' => $this->security->get_csrf_hash(),
							'type' => 'error',
							'message' => form_error($key, false, false),
						]);
					}
				}

				if (isset($protocol)) {
					$return_encryption = ($smtp_encryption == 'tls' ? 'tls' : ($smtp_encryption == 'ssl' ? 'ssl' : ''));
					$this->model->one_update($this->table_configs, 'name', 'email', ['value' => $email_from, 'updated_at' => NOW]);

					$password_smtp = ($smtp_password == '*****' ? configs('smtp_password', 'value') : $smtp_password);

					if ($protocol == 'smtp') {
						$this->model->one_update($this->table_configs, 'name', 'protocol', ['value' => $protocol, 'updated_at' => NOW]);
						$this->model->one_update($this->table_configs, 'name', 'smtp_host', ['value' => $smtp_server, 'updated_at' => NOW]);
						$this->model->one_update($this->table_configs, 'name', 'smtp_port', ['value' => $smtp_port, 'updated_at' => NOW]);
						$this->model->one_update($this->table_configs, 'name', 'smtp_encryption', ['value' => $return_encryption, 'updated_at' => NOW]);
						$this->model->one_update($this->table_configs, 'name', 'smtp_username', ['value' => $smtp_username, 'updated_at' => NOW]);
						$this->model->one_update($this->table_configs, 'name', 'smtp_password', ['value' => $password_smtp, 'updated_at' => NOW]);
					}

					if ($protocol == 'mail') {
						$this->model->one_update($this->table_configs, 'name', 'protocol', ['value' => $protocol, 'updated_at' => NOW]);
						$this->model->one_update($this->table_configs, 'name', 'smtp_host', ['value' => '', 'updated_at' => NOW]);
						$this->model->one_update($this->table_configs, 'name', 'smtp_port', ['value' => '', 'updated_at' => NOW]);
						$this->model->one_update($this->table_configs, 'name', 'smtp_encryption', ['value' => '', 'updated_at' => NOW]);
						$this->model->one_update($this->table_configs, 'name', 'smtp_username', ['value' => '', 'updated_at' => NOW]);
						$this->model->one_update($this->table_configs, 'name', 'smtp_password', ['value' => '', 'updated_at' => NOW]);
					}

					json([
						'csrf' => $this->security->get_csrf_hash(),
						'type' => 'success',
						'message' => lang("success_settings_save"),
					]);
				}

				json([
					'csrf' => $this->security->get_csrf_hash(),
					'type' => 'error',
					'message' => lang("error_protocol_mail_smtp"),
				]);
			}

			json([
				'csrf' => $this->security->get_csrf_hash(),
				'type' => 'error',
				'message' => lang("demo"),
			]);
		}
	}

	public function email_templates($templates)
	{
		if (isset($_POST) && !empty($_POST)) {
			if (DEMO_VERSION != true) {
				switch ($templates) {
					case 'recover_password_link':
						$subject = $this->input->post("recover_password_link_subject", true);
						$content = $this->input->post("text-area-input-recover-password-link", true);

						$this->model->one_update($this->table_email_templates, 'name', 'link_recover_password_subject', ['value' => $subject, 'updated_at' => NOW]);
						$this->model->one_update($this->table_email_templates, 'name', 'link_recover_password_content', ['value' => $content, 'updated_at' => NOW]);
						break;

					case 'email_verification_account':
						$subject = $this->input->post("email_subject_verification_account", true);
						$content = $this->input->post("text-area-input-verification-account", true);

						$this->model->one_update($this->table_email_templates, 'name', 'verification_account_subject', ['value' => $subject, 'updated_at' => NOW]);
						$this->model->one_update($this->table_email_templates, 'name', 'verification_account_content', ['value' => $content, 'updated_at' => NOW]);
						break;

					case 'welcome_user':
						$subject = $this->input->post("notification_welcome_user_subject", true);
						$content = $this->input->post("text-area-input-notification-welcome-user", true);

						$this->model->one_update($this->table_email_templates, 'name', 'welcome_user_subject', ['value' => $subject, 'updated_at' => NOW]);
						$this->model->one_update($this->table_email_templates, 'name', 'welcome_user_content', ['value' => $content, 'updated_at' => NOW]);
						break;

					case 'new_user_to_admin':
						$subject = $this->input->post("new_user_to_admin_subject", true);
						$content = $this->input->post("text-area-input-new-user-to-admin-content", true);

						$this->model->one_update($this->table_email_templates, 'name', 'new_user_to_admin_subject', ['value' => $subject, 'updated_at' => NOW]);
						$this->model->one_update($this->table_email_templates, 'name', 'new_user_to_admin_content', ['value' => $content, 'updated_at' => NOW]);
						break;

					case 'notification_ticket_reply':
						$subject = $this->input->post("notification_ticket_reply_subject", true);
						$content = $this->input->post("text-area-input-notification-ticket-reply-content", true);

						$this->model->one_update($this->table_email_templates, 'name', 'notification_ticket_reply_subject', ['value' => $subject, 'updated_at' => NOW]);
						$this->model->one_update($this->table_email_templates, 'name', 'notification_ticket_reply_content', ['value' => $content, 'updated_at' => NOW]);
						break;

					case 'payments_notification':
						$subject = $this->input->post("payments_notification_subject", true);
						$content = $this->input->post("text-area-input-payments-notification-content", true);

						$this->model->one_update($this->table_email_templates, 'name', 'payments_notification_subject', ['value' => $subject, 'updated_at' => NOW]);
						$this->model->one_update($this->table_email_templates, 'name', 'payments_notification_content', ['value' => $content, 'updated_at' => NOW]);
						break;
				}

				json([
					'csrf' => $this->security->get_csrf_hash(),
					'type' => 'success',
					'message' => lang("success_email_template"),
				]);
			}

			json([
				'csrf' => $this->security->get_csrf_hash(),
				'type' => 'error',
				'message' => lang("demo"),
			]);
		}
	}

	public function embed_code_settings()
	{
		if (isset($_POST) && !empty($_POST)) {
			if (DEMO_VERSION != true) {
				$facebook_link = $this->input->post('facebook_link', true);
				$twitter_link = $this->input->post('twitter_link', true);
				$instagram_link = $this->input->post('instagram_link', true);
				$youtube_link = $this->input->post('youtube_link', true);
				$embed_code_header = $this->input->post('embed_code_header');
				$embed_code_footer = $this->input->post('embed_code_footer');

				$this->model->one_update($this->table_configs, 'name', 'facebook_link', ['value' => $facebook_link, 'updated_at' => NOW]);
				$this->model->one_update($this->table_configs, 'name', 'twitter_link', ['value' => $twitter_link, 'updated_at' => NOW]);
				$this->model->one_update($this->table_configs, 'name', 'instagram_link', ['value' => $instagram_link, 'updated_at' => NOW]);
				$this->model->one_update($this->table_configs, 'name', 'youtube_link', ['value' => $youtube_link, 'updated_at' => NOW]);
				$this->model->one_update($this->table_configs, 'name', 'javascript_embed_header', ['value' => $embed_code_header, 'updated_at' => NOW]);
				$this->model->one_update($this->table_configs, 'name', 'javascript_embed_footer', ['value' => $embed_code_footer, 'updated_at' => NOW]);

				json([
					'csrf' => $this->security->get_csrf_hash(),
					'type' => 'success',
					'message' => lang("success_settings_save"),
				]);
			}

			json([
				'csrf' => $this->security->get_csrf_hash(),
				'type' => 'error',
				'message' => lang("demo"),
			]);
		}
	}

	public function updateRegisterPage()
	{
		if (isset($_POST) && !empty($_POST)) {
			if (DEMO_VERSION != true) {
				$registration_page = $this->input->post('registration_page', true);

				if (empty($registration_page) || $registration_page == '') {
					$registration_page = 'off';
				}

				$this->model->one_update($this->table_configs, 'name', 'registration_page', ['value' => $registration_page, 'updated_at' => NOW]);
			}
		}
	}

	/**
	 * Callbacks errors form validation (SMTP ENCRYPTION)
	 */
	public function select_validation($var, $message)
	{
		if ($var == 'none') {
			$this->form_validation->set_message('select_validation', $message);
			return false;
		} else {
			return true;
		}
	}
}
