<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Services extends MY_Controller
{
	protected $table;
	protected $table_category;
	protected $table_api_providers;

	public function __construct()
	{
		parent::__construct();

		$this->table = TABLE_SERVICES;
		$this->table_category = TABLE_CATEGORIES;
		$this->table_api_providers = TABLE_API_PROVIDERS;

		$this->load->model("panel/services/services_model");
	}

	public function index_user()
	{
		$data = [
			'title' => lang("title_services"),
			'rows_services' => $this->model->counts($this->table, ['status' => '1']),
			'categories' => $this->model->fetch('*', $this->table_category, ['status' => '1'], 'id', 'desc', '', ''),
		];

		if (!logged()) {
			view('layouts/header', $data);
		} else {
			view('layouts/auth_header', $data);
		}

		view("panel/admin/management/services/index_user");

		if (!logged()) {
			view('layouts/footer');
		} else {
			view('layouts/auth_footer');
		}
	}

	public function index_admin()
	{
		if (!userLevel(logged(), 'admin')) return redirect(base_url());

		$data = [
			'title' => lang("title_services_management"),
			'rows_services' => $this->model->counts($this->table),
			'categories' => $this->model->fetch('*', $this->table_category, '', 'id', 'asc', '', ''),
			'rows_categories' => $this->model->counts($this->table_category)
		];

		view('layouts/auth_header', $data);
		view("panel/admin/management/services/index_admin");
		view('layouts/auth_footer');
	}

	public function search()
	{
		if (!userLevel(logged(), 'admin')) return redirect(base_url());

		if (isset($_POST) && !empty($_POST)) {
			$search = $this->input->post('searchServices', true);

			$data = [
				'search_services' => $this->model->search($this->table, 'created_at', 'desc', ['id' => $search], ['name' => $search, 'add_type' => $search, 'api_service_id' => $search]),
			];

			view('panel/admin/management/services/index_admin_search', $data);
		}
	}

	public function store()
	{
		if (!userLevel(logged(), 'admin')) return redirect(base_url());

		if (isset($_POST) && !empty($_POST)) {
			$package_name = $this->input->post("add_package_name", true);
			$category_service = $this->input->post("choose_category", true);
			$min_amount = $this->input->post("add_min_amount", true);
			$max_amount = $this->input->post("add_max_amount", true);
			$dripfeed_service = $this->input->post("add_dripfeed_service", true);
			$services_type = $this->input->post("add_services_type", true);
			$price_amount = $this->input->post("add_price_amount", true);
			$description_service = $this->input->post("add_description_service", true);
			$status_service = $this->input->post("add_status_service", true);

			$this->services_model->validation_form_service([
				'add_package_name',
				'choose_category',
				'add_min_amount',
				'add_max_amount',
				'add_price_amount'
			]);

			if ($this->form_validation->run() == false) {
				foreach ($this->form_validation->error_array() as $key => $value) {
					json([
						'csrf' => $this->security->get_csrf_hash(),
						'type' => 'error',
						'message' => form_error($key, false, false),
					]);
				}
			}

			if ($services_type == 'noselect' || !in_array($services_type, ['default', 'subscriptions', 'custom_comments', 'custom_comments_package', 'mentions_with_hashtags', 'mentions_custom_list', 'mentions_hashtag', 'mentions_user_followers', 'mentions_media_likers', 'package', 'comment_likes', 'custom_data', 'poll', 'seo'])) {
				json([
					'csrf' => $this->security->get_csrf_hash(),
					'type' => 'error',
					'message' => lang("choose_type_service"),
				]);
			}

			$dripfeed = ($dripfeed_service == true && $services_type == 'default' ? '1' : '0');
			$status = ($status_service == '1' ? '1' : '0');

			$data = [
				'category_id' => $category_service,
				'name' => $package_name,
				'description' => $description_service,
				'price' => $price_amount,
				'min' => $min_amount,
				'max' => $max_amount,
				'add_type' => 'manual',
				'type' => $services_type,
				'api_service_id' => '0',
				'api_provider_id' => '0',
				'dripfeed' => $dripfeed,
				'status' => $status,
				'created_at' => NOW,
			];
			$this->model->insert($this->table, $data);

			json([
				'csrf' => $this->security->get_csrf_hash(),
				'type' => 'success',
				'message' => lang("success_service_add"),
			]);
		}
	}

	public function edit($id)
	{
		if (!userLevel(logged(), 'admin')) return redirect(base_url());

		if (isset($_POST) && !empty($_POST)) {
			$services = $this->model->get('*', $this->table, ['id' => $id], '', '', true);

			$edit_package_name = $this->input->post("edit_package_name", true);
			$edit_category_service = $this->input->post("category_service", true);
			$edit_min_amount = $this->input->post("edit_min_amount", true);
			$edit_max_amount = $this->input->post("edit_max_amount", true);
			$edit_dripfeed_service = $this->input->post("edit_dripfeed_service", true);
			$edit_services_type = $this->input->post("edit_services_type", true);
			$edit_price_amount = $this->input->post("edit_price_amount", true);
			$edit_description_service = $this->input->post("description_service", true);
			$status_service = $this->input->post("status_service", true);

			if ($services['add_type'] == 'api') {
				$this->services_model->validation_form_service_api();
			} else {
				$this->services_model->validation_form_service([
					'edit_package_name',
					'category_service',
					'edit_min_amount',
					'edit_max_amount',
					'edit_price_amount'
				]);
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

			if ($edit_services_type == 'noselect' || !in_array($edit_services_type, ['default', 'subscriptions', 'custom_comments', 'custom_comments_package', 'mentions_with_hashtags', 'mentions_custom_list', 'mentions_hashtag', 'mentions_user_followers', 'mentions_media_likers', 'package', 'comment_likes', 'custom_data', 'poll', 'seo']) && $services['add_type'] != 'api') {
				json([
					'csrf' => $this->security->get_csrf_hash(),
					'type' => 'error',
					'message' => lang("choose_type_service"),
				]);
			}

			$dripfeed = ($edit_dripfeed_service == true && $edit_services_type == 'default' ? '1' : '0');
			$status = ($status_service == '1' ? '1' : '0');

			$data = [
				'category_id' => $edit_category_service,
				'name' => $edit_package_name,
				'description' => $edit_description_service,
				'price' => $edit_price_amount,
				'min' => ($services['add_type'] == 'api' ? $services['min'] : $edit_min_amount),
				'max' => ($services['add_type'] == 'api' ? $services['max'] : $edit_max_amount),
				'add_type' => ($services['add_type'] == 'api' ? 'api' : 'manual'),
				'type' => ($services['add_type'] == 'api' ? $services['type'] : $edit_services_type),
				'api_service_id' => ($services['add_type'] == 'api' ? $services['api_service_id'] : 0),
				'api_provider_id' => ($services['add_type'] == 'api' ? $services['api_provider_id'] : 0),
				'dripfeed' => $dripfeed,
				'status' => $status,
				'created_at' => NOW,
			];
			$this->model->update($this->table, ['id' => $id], $data);

			json([
				'csrf' => $this->security->get_csrf_hash(),
				'type' => 'success',
				'message' => lang("success_service_edited"),
			]);
		}
	}

	public function destroy($id)
	{
		if (userLevel(logged(), 'admin')) {
			$delete_service = $this->model->counts($this->table, ['id' => $id]);

			if ($delete_service > 0) {
				$this->model->delete($this->table, ['id' => $id]);
			}
		}

		redirect(base_url('admin/services'));
	}

	/**
	 * Callbacks errors form validation
	 */
	public function select_validation($var, $message)
	{
		if ($var == 'noselect') {
			$this->form_validation->set_message('select_validation', $message);
			return false;
		} else {
			return true;
		}
	}

	public function validation_radio_type($var, $message)
	{
		if (!in_array($var, ['manual', 'api'])) {
			$this->form_validation->set_message('validation_radio_type', $message);
			return false;
		} else {
			return true;
		}
	}
}
