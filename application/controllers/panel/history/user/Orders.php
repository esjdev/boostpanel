<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Orders extends MY_Controller
{
	protected $table;

	public function __construct()
	{
		parent::__construct();

		if (!userLevel(logged(), 'user')) return redirect(base_url());

		$this->table = TABLE_ORDERS;
	}

	public function all()
	{
		$this->load->library('pagination');

		$config = [
			'base_url' => base_url('orders'),
			'total_rows' => $this->model->counts($this->table, ['user_id' => dataUser(logged(), 'id'), 'service_type !=' => 'subscriptions']),
			'per_page' => 20,
			'uri_segment' => 2,
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
		$page = (uri(2)) ? uri(2) : 0;
		$offset = $page == 0 ? 0 : ($page - 1) * $config["per_page"];

		$data = [
			'title' => lang("title_orders"),
			'all_orders' => $this->model->fetch('*', $this->table, ['user_id' => dataUser(logged(), 'id'), 'service_type !=' => 'subscriptions'], "created_at", "desc", $offset, $config["per_page"]),
			'pagination_links' => $this->pagination->create_links(),
		];

		view('layouts/auth_header', $data);
		view("panel/admin/management/history/user/orders/all_order");
		view('layouts/auth_footer');
	}

	public function search()
	{
		if (isset($_POST) && !empty($_POST)) {
			$search = $this->input->post('searchOrders', true);

			$data = [
				'search_orders' => $this->model->search($this->table, 'created_at', 'desc', ['id' => $search], "", ['user_id' => dataUser(logged(), 'id'), 'service_type !=' => 'subscriptions']),
			];

			view('panel/admin/management/history/user/orders/search_orders', $data);
		}
	}

	public function orders_for_status($type)
	{
		$this->load->library('pagination');

		$config = [
			'base_url' => base_url("orders/" . $type),
			'total_rows' => $this->model->counts($this->table, ['user_id' => dataUser(logged(), 'id'), 'status' => $type, 'service_type !=' => 'subscriptions']),
			'per_page' => 20,
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

		if (!in_array($type, ['pending', 'processing', 'inprogress', 'completed', 'partial', 'canceled', 'refunded'])) return redirect(base_url('orders'));

		$this->pagination->initialize($config);
		$page = (uri(3)) ? uri(3) : 0;
		$offset = $page == 0 ? 0 : ($page - 1) * $config["per_page"];

		switch ($type) {
			case 'pending':
				$data = [
					'title' => lang('title_orders') . " - " . lang("status_pending"),
					'orders_for_status' => $this->model->fetch('*', $this->table, ['user_id' => dataUser(logged(), 'id'), 'status' => 'pending', 'service_type !=' => 'subscriptions'], "created_at", "desc", $offset, $config["per_page"]),
					'pagination_links' => $this->pagination->create_links(),
				];
				break;

			case 'processing':
				$data = [
					'title' => lang('title_orders') . " - " . lang("status_processing"),
					'orders_for_status' => $this->model->fetch('*', $this->table, ['user_id' => dataUser(logged(), 'id'), 'status' => 'processing', 'service_type !=' => 'subscriptions'], "created_at", "desc", $offset, $config["per_page"]),
					'pagination_links' => $this->pagination->create_links(),
				];
				break;

			case 'inprogress':
				$data = [
					'title' => lang('title_orders') . " - " . lang("status_inprocess"),
					'orders_for_status' => $this->model->fetch('*', $this->table, ['user_id' => dataUser(logged(), 'id'), 'status' => 'inprogress', 'service_type !=' => 'subscriptions'], "created_at", "desc", $offset, $config["per_page"]),
					'pagination_links' => $this->pagination->create_links(),
				];
				break;

			case 'completed':
				$data = [
					'title' => lang('title_orders') . " - " . lang("status_completed"),
					'orders_for_status' => $this->model->fetch('*', $this->table, ['user_id' => dataUser(logged(), 'id'), 'status' => 'completed', 'service_type !=' => 'subscriptions'], "created_at", "desc", $offset, $config["per_page"]),
					'pagination_links' => $this->pagination->create_links(),
				];
				break;

			case 'partial':
				$data = [
					'title' => lang('title_orders') . " - " . lang("status_partial"),
					'orders_for_status' => $this->model->fetch('*', $this->table, ['user_id' => dataUser(logged(), 'id'), 'status' => 'partial', 'service_type !=' => 'subscriptions'], "created_at", "desc", $offset, $config["per_page"]),
					'pagination_links' => $this->pagination->create_links(),
				];
				break;

			case 'canceled':
				$user = dataUser(logged(), 'id');
				$data = [
					'title' => lang('title_orders') . " - " . lang("status_canceled"),
					'orders_for_status' => $this->model->fetch('*', $this->table, "(user_id = {$user} AND service_type != 'subscriptions' AND (status = 'canceled' or status = 'refunded'))", "created_at", "desc", $offset, $config["per_page"]),
					'pagination_links' => $this->pagination->create_links(),
				];
				break;
		}

		view('layouts/auth_header', $data);
		view("panel/admin/management/history/user/orders/orders_for_status");
		view('layouts/auth_footer');
	}
}
