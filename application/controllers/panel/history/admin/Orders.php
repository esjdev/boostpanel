<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Orders extends MY_Controller
{
	protected $table;
	protected $table_users;
	protected $table_services;

	public function __construct()
	{
		parent::__construct();

		if (userLevel(logged(), 'user') || userLevel(logged(), 'banned')) return redirect(base_url());

		$this->table = TABLE_ORDERS;
		$this->table_users = TABLE_USERS;
		$this->table_services = TABLE_SERVICES;
	}

	public function all()
	{
		$this->load->library('pagination');

		$config = [
			'base_url' => base_url("admin/orders"),
			'total_rows' => $this->model->counts($this->table, ['service_type !=' => 'subscriptions']),
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
			'title' => lang("title_orders"),
			'all_orders' => $this->model->fetch('*', $this->table, ['service_type !=' => 'subscriptions'], "created_at", "desc", $offset, $config["per_page"]),
			'pagination_links' => $this->pagination->create_links(),
		];

		view('layouts/auth_header', $data);
		view("panel/admin/management/history/admin/orders/all_order");
		view('layouts/auth_footer');
	}

	public function actions($action, $id)
	{
		if (!userLevel(logged(), 'admin')) return redirect(base_url('admin/subscriptions'));

		$order = $this->model->get('*', $this->table, "id = $id AND service_type != 'subscriptions' AND (status = 'pending' or status = 'processing' or status = 'inprogress')", '', '', true);

		switch ($action) {
			case 'edit_link':
				if (isset($_POST) && !empty($_POST)) {
					$link = $this->input->post("edit_link", true);

					if (empty($link)) {
						json([
							'csrf' => $this->security->get_csrf_hash(),
							'type' => 'error',
							'message' => sprintf(lang("error_empty_field"), lang("link")),
						]);
					}

					if (!empty($order)) {
						$this->model->update($this->table, ['id' => $id, 'service_type !=' => 'subscriptions'], ['link' => $link]);

						json([
							'csrf' => $this->security->get_csrf_hash(),
							'type' => 'success',
							'message' => lang("success_edited"),
						]);
					}
				}
				break;

			case 'set_start_count':
				if (isset($_POST) && !empty($_POST)) {
					$start_count = $this->input->post("set_start_count", true);

					if ($start_count != 0 && empty($start_count)) {
						json([
							'csrf' => $this->security->get_csrf_hash(),
							'type' => 'error',
							'message' => sprintf(lang("error_empty_field"), lang("start_count")),
						]);
					}

					if (!is_numeric($start_count)) {
						json([
							'csrf' => $this->security->get_csrf_hash(),
							'type' => 'error',
							'message' => sprintf(lang("error_only_numbers"), lang("start_count")),
						]);
					}

					if (!empty($order)) {
						$this->model->update($this->table, ['id' => $id, 'service_type !=' => 'subscriptions'], ['start_counter' => $start_count]);

						json([
							'csrf' => $this->security->get_csrf_hash(),
							'type' => 'success',
							'message' => lang("success_edited"),
						]);
					}
				}
				break;

			case 'set_partial':
				if (isset($_POST) && !empty($_POST)) {
					$set_partial = $this->input->post("set_partial", true);

					if ($set_partial != 0 && empty($set_partial)) {
						json([
							'csrf' => $this->security->get_csrf_hash(),
							'type' => 'error',
							'message' => sprintf(lang("error_empty_field"), lang("remains")),
						]);
					}

					if (!is_numeric($set_partial)) {
						json([
							'csrf' => $this->security->get_csrf_hash(),
							'type' => 'error',
							'message' => sprintf(lang("error_only_numbers"), lang("remains")),
						]);
					}

					if (!empty($order)) {
						$services_list = $this->model->get('*', $this->table_services, ['id' => $order['service_id']], '', '', true);

						$real_charge = ($services_list['price'] * $set_partial) / 1000;
						$refundAmount = $order['charge'] - $real_charge;

						$this->model->update($this->table, ['id' => $id, 'service_type !=' => 'subscriptions'], [
							'remains' => $set_partial,
							'type' => 'manual',
							'api_provider_id' => 0,
							'api_service_id' => 0,
							'api_order_id' => 0,
							'status' => 'partial',
						]);
						$this->model->update($this->table_users, ['id' => $order['user_id']], '', true, false, $refundAmount);

						json([
							'csrf' => $this->security->get_csrf_hash(),
							'type' => 'success',
							'message' => lang("success_edited"),
						]);
					}
				}
				break;

			case 'pending':
				if (!empty($order)) {
					$this->model->update($this->table, "id = $id AND service_type != 'subscriptions' AND (status = 'pending' or status = 'processing' or status = 'inprogress')", [
						'type' => 'manual',
						'api_provider_id' => 0,
						'api_service_id' => 0,
						'api_order_id' => 0,
						'status' => 'pending',
					]);

					redirect(base_url('admin/orders'));
				}
				break;

			case 'inprogress':
				if (!empty($order)) {
					$this->model->update($this->table, "id = $id AND service_type != 'subscriptions' AND (status = 'pending' or status = 'processing' or status = 'inprogress')", [
						'type' => 'manual',
						'api_provider_id' => 0,
						'api_service_id' => 0,
						'api_order_id' => 0,
						'status' => 'inprogress',
					]);

					redirect(base_url('admin/orders'));
				}
				break;

			case 'processing':
				if (!empty($order)) {
					$this->model->update($this->table, ['id' => $id, 'service_type !=' => 'subscriptions'], [
						'type' => 'manual',
						'api_provider_id' => 0,
						'api_service_id' => 0,
						'api_order_id' => 0,
						'status' => 'processing',
					]);

					redirect(base_url('admin/orders'));
				}
				break;

			case 'completed':
				if (!empty($order)) {
					$this->model->update($this->table, ['id' => $id, 'service_type !=' => 'subscriptions'], [
						'type' => 'manual',
						'api_provider_id' => 0,
						'api_service_id' => 0,
						'api_order_id' => 0,
						'status' => 'completed',
					]);

					redirect(base_url('admin/orders'));
				}
				break;

			case 'cancel_order':
				if (!empty($order)) {
					$this->model->update($this->table, ['id' => $id, 'service_type !=' => 'subscriptions'], [
						'type' => 'manual',
						'api_provider_id' => 0,
						'api_service_id' => 0,
						'api_order_id' => 0,
						'status' => 'canceled',
					]);
					$this->model->update($this->table_users, ['id' => $order['user_id']], '', true, false, $order['charge']);

					redirect(base_url('admin/orders'));
				}
				break;
		}
	}

	public function search()
	{
		if (isset($_POST) && !empty($_POST)) {
			$search = $this->input->post('searchAdminOrders', true);

			$data = [
				'search_orders' => $this->model->search($this->table, 'created_at', 'desc', ['id' => $search], ['link' => $search, 'username' => $search, 'service_id' => $search], ['service_type !=' => 'subscriptions']),
			];

			view('panel/admin/management/history/admin/orders/search_orders', $data);
		}
	}

	public function orders_for_status($type)
	{
		$this->load->library('pagination');

		$config = [
			'base_url' => base_url("admin/orders/" . $type),
			'total_rows' => $this->model->counts($this->table, ['status' => $type, 'service_type !=' => 'subscriptions']),
			'per_page' => 30,
			'uri_segment' => 4,
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

		if (!in_array($type, ['pending', 'processing', 'inprogress', 'completed', 'partial', 'canceled', 'refunded'])) return redirect(base_url('admin/orders'));

		$this->pagination->initialize($config);
		$page = (uri(4)) ? uri(4) : 0;
		$offset = $page == 0 ? 0 : ($page - 1) * $config["per_page"];

		switch ($type) {
			case 'pending':
				$data = [
					'title' => lang('title_orders') . " - " . lang("status_pending"),
					'orders_for_status' => $this->model->fetch('*', $this->table, ['status' => 'pending', 'service_type !=' => 'subscriptions'], "created_at", "desc", $offset, $config["per_page"]),
					'pagination_links' => $this->pagination->create_links(),
				];
				break;

			case 'processing':
				$data = [
					'title' => lang('title_orders') . " - " . lang("status_processing"),
					'orders_for_status' => $this->model->fetch('*', $this->table, ['status' => 'processing', 'service_type !=' => 'subscriptions'], "created_at", "desc", $offset, $config["per_page"]),
					'pagination_links' => $this->pagination->create_links(),
				];
				break;

			case 'inprogress':
				$data = [
					'title' => lang('title_orders') . " - " . lang("status_inprocess"),
					'orders_for_status' => $this->model->fetch('*', $this->table, ['status' => 'inprogress', 'service_type !=' => 'subscriptions'], "created_at", "desc", $offset, $config["per_page"]),
					'pagination_links' => $this->pagination->create_links(),
				];
				break;

			case 'completed':
				$data = [
					'title' => lang('title_orders') . " - " . lang("status_completed"),
					'orders_for_status' => $this->model->fetch('*', $this->table, ['status' => 'completed', 'service_type !=' => 'subscriptions'], "created_at", "desc", $offset, $config["per_page"]),
					'pagination_links' => $this->pagination->create_links(),
				];
				break;

			case 'partial':
				$data = [
					'title' => lang('title_orders') . " - " . lang("status_partial"),
					'orders_for_status' => $this->model->fetch('*', $this->table, ['status' => 'partial', 'service_type !=' => 'subscriptions'], "created_at", "desc", $offset, $config["per_page"]),
					'pagination_links' => $this->pagination->create_links(),
				];
				break;

			case 'canceled':
				$data = [
					'title' => lang('title_orders') . " - " . lang("status_canceled"),
					'orders_for_status' => $this->model->fetch('*', $this->table, "service_type != 'subscriptions' AND (status = 'canceled' or status = 'refunded')", "created_at", "desc", $offset, $config["per_page"]),
					'pagination_links' => $this->pagination->create_links(),
				];
				break;
		}

		view('layouts/auth_header', $data);
		view("panel/admin/management/history/admin/orders/orders_for_status");
		view('layouts/auth_footer');
	}
}
