<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Install extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$database = config('database');

		if ($database != '__DATABASE__') return redirect(base_url());
	}

	public function index()
	{
		$required_version_php = '5.6.0';
		$php_version_success = (version_compare(PHP_VERSION, $required_version_php) >= 0 ? true : false);
		$mysql_success = (function_exists("mysqli_connect") ? true : false);
		$curl_success = (function_exists("curl_version") ? true : false);
		$allow_url_fopen_success = (ini_get('allow_url_fopen') ? true : false);
		$mbstring_success = (extension_loaded('mbstring') ? true : false);
		$zip_success = (extension_loaded('zip') ? true : false);
		$all_requirement_success = ($php_version_success && $mysql_success && $curl_success && $allow_url_fopen_success && $mbstring_success && $zip_success ? true : false);

		$data = [
			'required_php_version' => $required_version_php,
			'php_version_success' => $php_version_success,
			'mysql_success' => $mysql_success,
			'curl_success' => $curl_success,
			'allow_url_fopen_success' => $allow_url_fopen_success,
			'mbstring_success' => $mbstring_success,
			'zip_success' => $zip_success,
			'all_requirement_success' => $all_requirement_success,
		];

		$this->load->view('install/installation', $data);
		$this->store();
	}

	public function store()
	{
		if (isset($_POST) && !empty($_POST)) {
			// MYSQL Settings
			$host = $this->input->post('host', true);
			$db_user = $this->input->post('db_user', true);
			$db_password = $this->input->post('db_password', true);
			$db_name = $this->input->post('db_name', true);

			// Account Admin
			$name = $this->input->post("name", true);
			$username = $this->input->post('username', true);
			$email = $this->input->post('email', true);
			$password = $this->input->post('password', true);

			// Others
			$timezone = $this->input->post("timezone", true);

			$this->load->model('setup_model');
			$this->setup_model->validate_form();

			if ($this->form_validation->run() == false) {
				foreach ($this->form_validation->error_array() as $key => $value) {
					json([
						'csrf' => $this->security->get_csrf_hash(),
						'type' => 'error',
						'message' => form_error($key, false, false),
					]);
				}
			}

			if ($timezone == 'noselect') {
				json([
					'csrf' => $this->security->get_csrf_hash(),
					'type' => 'error',
					'message' => 'Choose a timezone',
				]);
			}

			setup_install($host, $db_user, $db_password, $db_name, $name, $username, $email, $password, $timezone);
		}
	}
}
