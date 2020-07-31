<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Users extends MY_Controller
{
	protected $table;
	protected $table_orders;
	protected $table_transaction;

	public function __construct()
	{
		parent::__construct();
		if (!userLevel(logged(), 'admin')) return redirect(base_url());

		$this->table = TABLE_USERS;
		$this->table_orders = TABLE_ORDERS;
		$this->table_transaction = TABLE_TRANSACTION_LOGS;

		$this->load->model("user_model");
	}

	public function index()
	{
		$this->load->library('pagination');

		$config = [
			'base_url' => base_url("admin/users"),
			'total_rows' => $this->model->counts($this->table),
			'per_page' => 30,
			'uri_segment' => 3,
			'use_page_numbers' => true,
			'reuse_query_string' => false,

			'first_link' => lang("pagination_first"),
			'last_link' => lang("pagination_last"),
			'full_tag_open' => '<div class="pagging text-center"><nav><ul class="pagination justify-content-center">',
			'full_tag_close' => '</ul></nav></div>',
			'num_tag_open' => '<li class="page-item">',
			'num_tag_close' => '</li>',
			'cur_tag_open' => '<li class="page-item active"><span class="page-link">',
			'cur_tag_close' => '<span class="sr-only">(current)</span></span></li>',
			'next_tag_open' => '<li class="page-item">',
			'next_tagl_close' => '<span aria-hidden="true">&raquo;</span></li>',
			'prev_tag_open' => '<li class="page-item">',
			'prev_tagl_close' => '</li>',
			'first_tag_open' => '<li class="page-item">',
			'first_tagl_close' => '</li>',
			'last_tag_open' => '<li class="page-item">',
			'last_tagl_close' => '</li>',
			'attributes' => ['class' => 'page-link'],
		];

		$this->pagination->initialize($config);
		$page = (uri(3)) ? uri(3) : 0;
		$offset = $page == 0 ? 0 : ($page - 1) * $config["per_page"];

		$data = [
			'title' => lang("title_users_management"),
			'list_users' => $this->model->fetch('*', $this->table, '', 'id', 'asc', $offset, $config["per_page"]),
			'pagination_links' => $this->pagination->create_links(),
			'count' => $offset,
		];

		view('layouts/auth_header', $data);
		view('panel/admin/management/users/list_users');
		view('layouts/auth_footer');
	}

	public function search()
	{
		if (isset($_POST) && !empty($_POST)) {
			$search = $this->input->post('searchUser', true);

			$data = [
				'search_user' => $this->model->search($this->table, 'id', 'asc', ['name' => $search], ['email' => $search, 'username' => $search]),
			];

			view('panel/admin/management/users/search_user', $data);
		}
	}

	public function show($uuid)
	{
		$user = $this->model->get('*', $this->table, ['uuid' => $uuid], '', '', true);
		$count_users = $this->model->counts($this->table, ['uuid' => $uuid]);

		if ($count_users > 0) {
			$data = [
				'title' => lang("title_viewing_user") . " - " . $user['username'],
				'get_user' => $user,
				'sum_spendings' => $this->model->sum_results($this->table_orders, ['user_id' => $user['id'], 'status' => 'completed'], 'charge'),
			];

			view('layouts/auth_header', $data);
			view('panel/admin/management/users/show_user');
			view('layouts/auth_footer');
		} else {
			redirect(base_url('admin/users'));
		}
	}

	public function create()
	{
		if (isset($_POST) && !empty($_POST)) {
			if (DEMO_VERSION != true) {
				$name_user = $this->input->post("name_user", true);
				$username = $this->input->post("username", true);
				$email_user = $this->input->post("email_user", true);
				$role_user = $this->input->post("role_user", true);
				$cf_password_user = $this->input->post("cf_password_user", true);

				$this->user_model->validate_form_create_user();

				if ($this->form_validation->run() == false) {
					foreach ($this->form_validation->error_array() as $key => $value) {
						json([
							'csrf' => $this->security->get_csrf_hash(),
							'type' => 'error',
							'message' => form_error($key, false, false),
						]);
					}
				}

				if ($role_user == 'noselect' || !in_array($role_user, ['USER', 'ADMIN', 'SUPPORT'])) {
					json([
						'csrf' => $this->security->get_csrf_hash(),
						'type' => 'error',
						'message' => lang("error_no_select_role"),
					]);
				}

				$timezone = config('timezone');

				$data = [
					'uuid' => uuid(),
					'name' => $name_user,
					'username' => $username,
					'email' => $email_user,
					'password' => password_hash($cf_password_user, PASSWORD_DEFAULT),
					'timezone' => $timezone,
					'balance' => 0,
					'role' => $role_user,
					'api_key' => create_random_api_key(""),
					'activation_token' => 0,
					'custom_rate' => 0,
					'status' => 'Active',
					'created_at' => NOW,
				];
				$this->model->insert($this->table, $data);

				json([
					'csrf' => $this->security->get_csrf_hash(),
					'type' => 'success',
					'message' => lang("success_account_created_for_admin"),
				]);
			}

			json([
				'csrf' => $this->security->get_csrf_hash(),
				'type' => 'error',
				'message' => lang("demo"),
			]);
		}
	}

	public function update_profile($uuid)
	{
		$user = $this->model->get('*', $this->table, ['uuid' => $uuid], '', '', true);

		if (isset($_POST) && !empty($_POST)) {
			if (DEMO_VERSION != true) {
				$name = $this->input->post("name", true);

				$this->user_model->validate_form_profile();

				if ($this->form_validation->run() == false && empty($name)) {
					foreach ($this->form_validation->error_array() as $key => $value) {
						json([
							'csrf' => $this->security->get_csrf_hash(),
							'type' => 'error',
							'message' => form_error($key, false, false),
						]);
					}
				}

				$data = ['name' => $name];
				$this->model->update($this->table, ['id' => $user['id']], $data);

				json([
					'csrf' => $this->security->get_csrf_hash(),
					'type' => 'success',
					'message' => lang("success_update_profile"),
				]);
			}

			json([
				'csrf' => $this->security->get_csrf_hash(),
				'type' => 'error',
				'message' => lang("demo"),
			]);
		}
	}

	public function change_custom_rate($uuid)
	{
		$user = $this->model->get('*', $this->table, ['uuid' => $uuid], '', '', true);

		if (isset($_POST) && !empty($_POST)) {
			if (DEMO_VERSION != true) {
				$custom_rate = $this->input->post("custom_rate", true);

				if ($custom_rate == 'noselect') {
					json([
						'csrf' => $this->security->get_csrf_hash(),
						'type' => 'error',
						'message' => lang("select_custom_rate"),
					]);
				}

				$data = ['custom_rate' => $custom_rate];
				$this->model->update($this->table, ['id' => $user['id'], 'role' => 'USER'], $data);

				json([
					'csrf' => $this->security->get_csrf_hash(),
					'type' => 'success',
					'message' => lang("success_custom_rate"),
				]);
			}

			json([
				'csrf' => $this->security->get_csrf_hash(),
				'type' => 'error',
				'message' => lang("demo"),
			]);
		}
	}

	public function change_role($uuid)
	{
		$user = $this->model->get('*', $this->table, ['uuid' => $uuid], '', '', true);

		if (isset($_POST) && !empty($_POST)) {
			if (DEMO_VERSION != true) {
				$role_user = $this->input->post("role_user", true);

				if ($role_user == 'noselect' || !in_array($role_user, ['USER', 'ADMIN', 'SUPPORT'])) {
					json([
						'csrf' => $this->security->get_csrf_hash(),
						'type' => 'error',
						'message' => lang("error_no_select_role"),
					]);
				}

				$data = ['role' => $role_user];
				$this->model->update($this->table, ['id' => $user['id']], $data);

				json([
					'csrf' => $this->security->get_csrf_hash(),
					'type' => 'success',
					'message' => lang("success_change_role_user"),
				]);
			}

			json([
				'csrf' => $this->security->get_csrf_hash(),
				'type' => 'error',
				'message' => lang("demo"),
			]);
		}
	}

	public function change_password($uuid)
	{
		$user = $this->model->get('*', $this->table, ['uuid' => $uuid], '', '', true);

		if (isset($_POST) && !empty($_POST)) {
			if (DEMO_VERSION != true) {
				$re_new_password = $this->input->post('re_pass_new', true);

				$this->user_model->validate_form();

				if ($this->form_validation->run() == false) {
					foreach ($this->form_validation->error_array() as $key => $value) {
						json([
							'csrf' => $this->security->get_csrf_hash(),
							'type' => 'error',
							'message' => form_error($key, false, false),
						]);
					}
				}

				$data = ['password' => password_hash($re_new_password, PASSWORD_DEFAULT)];
				$this->model->update($this->table, ['id' => $user['id'], 'role !=' => 'ADMIN'], $data);

				json([
					'csrf' => $this->security->get_csrf_hash(),
					'type' => 'success',
					'message' => lang("success_recover_pass"),
				]);
			}

			json([
				'csrf' => $this->security->get_csrf_hash(),
				'type' => 'error',
				'message' => lang("demo"),
			]);
		}
	}

	public function action_balance($uuid)
	{
		$user = $this->model->get('*', $this->table, ['uuid' => $uuid], '', '', true);

		if (isset($_POST) && !empty($_POST)) {
			if (DEMO_VERSION != true) {
				$operation = $this->input->post('operation', true);
				$amount = $this->input->post('amount', true);

				$this->user_model->validate_form(true);

				if ($this->form_validation->run() == false) {
					foreach ($this->form_validation->error_array() as $key => $value) {
						json([
							'csrf' => $this->security->get_csrf_hash(),
							'type' => 'error',
							'message' => form_error($key, false, false),
						]);
					}
				}

				if ($operation == 1) {
					$refer = 'MANUAL_' . create_random_api_key(16);

					$this->model->update($this->table, ['id' => $user['id']], '', true, false, $amount);
					$this->model->insert($this->table_transaction, [
						'user_id' => $user['id'],
						'transaction_id' => $refer,
						'payment_method' => 'manual',
						'amount' => $amount,
						'status' => 'paid',
						'created_at' => NOW,
						'updated_at' => NOW,
					]);

					json([
						'csrf' => $this->security->get_csrf_hash(),
						'type' => 'success',
						'message' => lang("success_add_balance"),
					]);
				}

				if ($operation == null) {
					if ($amount > $user['balance']) {
						json([
							'csrf' => $this->security->get_csrf_hash(),
							'type' => 'error',
							'message' => lang("error_incorrect_balance"),
						]);
					}

					$this->model->update($this->table, ['id' => $user['id'], 'role' => 'USER'], '', false, true, $amount);

					json([
						'csrf' => $this->security->get_csrf_hash(),
						'type' => 'success',
						'message' => sprintf(lang('success_withdraw_balance'), currency_format($amount)),
					]);
				}
			}

			json([
				'csrf' => $this->security->get_csrf_hash(),
				'type' => 'error',
				'message' => lang("demo"),
			]);
		}
	}

	public function action_ban($uuid)
	{
		$user = $this->model->get('*', $this->table, ['uuid' => $uuid], '', '', true);
		$count_users = $this->model->counts($this->table, ['role !=' => 'ADMIN', 'uuid' => $uuid]);

		if (DEMO_VERSION != true) {
			if ($count_users > 0) {
				$data = ($user['role'] != 'BANNED' ? ['role' => 'BANNED'] : ['role' => 'USER']);
				$this->model->update($this->table, ['id' => $user['id'], 'id !=' => dataUser(logged(), 'id'), 'role !=' => 'ADMIN'], $data);
			}
		} else {
			set_flashdata('error', lang("demo"));
		}
		redirect(base_url('admin/users/show/' . $uuid));
	}

	public function destroy($uuid)
	{
		$user = $this->model->get('*', $this->table, ['uuid' => $uuid], '', '', true);
		$count_users = $this->model->counts($this->table, ['role !=' => 'ADMIN', 'uuid' => $uuid]);

		if (DEMO_VERSION != true) {
			if ($count_users > 0) {
				$this->model->delete($this->table, ['id' => $user['id'], 'role !=' => 'ADMIN']);
				$this->model->delete($this->table_orders, ['user_id' => $user['id']]);
			}
		} else {
			set_flashdata('error', lang("demo"));
		}

		redirect(base_url('admin/users'));
	}

	/**
	 * Callbacks errors form validation
	 */
	public function select_validation($var, $message)
	{
		if ($var == 'noselect' || !in_array($var, ['USER', 'ADMIN', 'SUPPORT'])) {
			$this->form_validation->set_message('select_validation', $message);
			return false;
		} else {
			return true;
		}
	}
}
