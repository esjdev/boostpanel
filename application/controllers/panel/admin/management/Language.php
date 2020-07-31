<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Language extends MY_Controller
{
	protected $table_lang_list;
	protected $table_lang;

	public function __construct()
	{
		parent::__construct();

		if (!userLevel(logged(), 'admin')) return redirect(base_url());

		$this->table_lang_list = TABLE_LANG_LIST;
		$this->table_lang = TABLE_LANG;

		$this->load->model("panel/language/language_model");
	}

	public function index()
	{
		$data = [
			'title' => lang("title_language_management"),
			'lang_list' => $this->model->fetch('*', $this->table_lang_list),
		];

		view('layouts/auth_header', $data);
		view('panel/admin/management/languages/language');
		view('layouts/auth_footer');
	}

	public function create()
	{
		$data = [
			'title' => lang("title_add_language"),
			'langs' => [],
		];

		view('layouts/auth_header', $data);
		view('panel/admin/management/languages/add_language');
		view('layouts/auth_footer');

		$this->store(); //  Create new Language
	}

	public function store()
	{
		if (isset($_POST) && !empty($_POST)) {
			if (DEMO_VERSION != true) {
				ini_set('max_execution_time', 300000);

				$language_code = $this->input->post('language_code', true);
				$country_code = $this->input->post('country_code', true);
				$status = $this->input->post('status_lang', true);
				$default = $this->input->post('lang_default', true);
				$langs = $this->input->post('lang', true);

				$this->language_model->validate_form();

				if ($this->form_validation->run() == false) {
					foreach ($this->form_validation->error_array() as $key => $value) {
						json([
							'csrf' => $this->security->get_csrf_hash(),
							'type' => 'error',
							'message' => form_error($key, false, false),
						]);
					}
				}

				$checkLangList = $this->model->fetch('*', $this->table_lang_list, ['code' => $language_code]);
				if (!empty($checkLangList)) {
					json([
						'csrf' => $this->security->get_csrf_hash(),
						'type' => 'error',
						'message' => lang("error_language_exists"),
					]);
				}
				$ids = token_rand();

				$exists_language_default = $this->model->counts($this->table_lang_list, ['is_default' => '1']);
				if ($default == 1 && $exists_language_default > 0) {
					$this->model->update($this->table_lang_list, ['is_default' => '1'], ['is_default' => '0']);
				}

				$insert = [
					"ids" => $ids,
					"code" => $language_code,
					"country_code" => $country_code,
					"status" => $status,
					"is_default" => $default,
					"created" => date('Y-m-d H:i:s'),
				];
				$this->model->insert($this->table_lang_list, $insert);

				if ($default == '1') {
					set_session('langCurrent', $this->model->fetch('*', $this->table_lang_list, ['ids' => $ids]));
				}

				if (is_array($langs) && !empty($langs)) {
					foreach ($langs as $slug => $value) {
						$checklang = $this->model->fetch('*', $this->table_lang, ['slug' => $slug, 'lang_code' => $language_code]);

						if (empty($checklang)) {
							$insert = [
								"ids" => $ids,
								"lang_code" => $language_code,
								"slug" => $slug,
								"value" => $value,
							];
							$this->model->insert($this->table_lang, $insert);
						}
					}
				}

				json([
					'csrf' => $this->security->get_csrf_hash(),
					'type' => 'success',
					'message' => lang("success_language_add"),
				]);
			}

			json([
				'csrf' => $this->security->get_csrf_hash(),
				'type' => 'error',
				'message' => lang("demo"),
			]);
		}
	}

	public function edit($ids)
	{
		$count_ids = $this->model->counts($this->table_lang, ['ids' => $ids]);

		if ($count_ids > 0) {
			$data = [
				'title' => lang("title_edit_language"),
				'list_language_edit' => $this->model->fetch('*', $this->table_lang, ['ids' => $ids]),
				'language_edit' => $this->model->get('*', $this->table_lang_list, ['ids' => $ids], "", "", true),
			];

			view('layouts/auth_header', $data);
			view("panel/admin/management/languages/edit_language");
			view('layouts/auth_footer');
		} else {
			redirect(base_url('admin/language'));
		}
	}

	public function update($ids)
	{
		if (isset($_POST) && !empty($_POST)) {
			if (DEMO_VERSION != true) {
				ini_set('max_execution_time', 300000);

				$status = $this->input->post('status_lang', true);
				$default = $this->input->post('lang_default', true);
				$langs = $this->input->post('lang', true);

				$this->language_model->validate_form(false);

				if ($this->form_validation->run() == false) {
					foreach ($this->form_validation->error_array() as $key => $value) {
						json([
							'csrf' => $this->security->get_csrf_hash(),
							'type' => 'error',
							'message' => form_error($key, false, false),
						]);
					}
				}

				foreach ($langs as $key => $value) {
					$this->model->update($this->table_lang, ['slug' => $key], ['value' => $value]);
				}

				$this->model->update($this->table_lang_list, ['ids' => $ids], [
					'is_default' => $default,
					'status' => $status,
				]);

				if ($default == 0) {
					if (session('langCurrent')) return unset_session('langCurrent');
				}

				if ($default == 1) {
					$langDefault = $this->model->fetch('*', $this->table_lang_list, ['is_default' => '1', 'status' => '1', 'ids' => $ids]);
					set_session('langCurrent', $langDefault);
				}

				json([
					'csrf' => $this->security->get_csrf_hash(),
					'type' => 'success',
					'message' => lang("success_language_update"),
				]);
			}

			json([
				'csrf' => $this->security->get_csrf_hash(),
				'type' => 'error',
				'message' => lang("demo"),
			]);
		}
	}

	public function destroy($ids)
	{
		if (DEMO_VERSION != true) {
			$countLanguages = $this->model->counts($this->table_lang_list, ['ids' => $ids, 'is_default' => '0']);
			if ($countLanguages != 0) {
				$checklangList = $this->model->fetch('*', $this->table_lang_list, ['ids' => $ids]);

				$this->model->delete($this->table_lang_list, ['is_default' => '0', 'ids' => $ids]);
				$this->model->delete($this->table_lang, ['lang_code' => $checklangList[0]->code]);

				if (session('langCurrent')) return unset_session('langCurrent');
			}
		} else {
			set_flashdata('error', lang("demo"));
		}

		redirect(base_url('admin/language'));
	}

	/**
	 * Callbacks errors form validation
	 */
	public function select_validation($var, $message)
	{
		if ($var == 'not') {
			$this->form_validation->set_message('select_validation', $message);
			return false;
		} else {
			return true;
		}
	}

	public function validation_language($var)
	{
		if (!language_codes($var)) {
			$this->form_validation->set_message('validation_language', lang("error_language_code_not_exists"));
			return false;
		} else {
			return true;
		}
	}

	public function validation_country_code($var)
	{
		if (!country_codes($var)) {
			$this->form_validation->set_message('validation_country_code', lang("error_language_country_not_exists"));
			return false;
		} else {
			return true;
		}
	}

	public function validate_number_select($var)
	{
		if ($var > 1 || !is_numeric($var)) {
			$this->form_validation->set_message('validate_number_select', lang("error_select_invalid"));
			return false;
		} else {
			return true;
		}
	}
}
