<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends MY_Controller
{
	protected $table_user;
	protected $table_orders;
	protected $table_tickets;
	protected $table_news;

	private $user;

	public function __construct()
	{
		parent::__construct();

		if (!logged()) return redirect(base_url());

		$this->table_user = TABLE_USERS;
		$this->table_orders = TABLE_ORDERS;
		$this->table_tickets = TABLE_TICKETS;
		$this->table_news = TABLE_NEWS;


		$this->user = $this->model->get('*', $this->table_user, ['email' => logged()], '', '', true);
	}

	public function index()
	{
		$this->load->model('chartjs_model');

		$data = [
			'title' => lang("title_dashboard"),
			'user_role' => $this->user['role'],
			'list_news' => $this->model->fetch('*', $this->table_news, ['type' => 'general'], 'created_at', 'desc', 0, 3),
			'list_news_updated_disable' => $this->model->fetch('*', $this->table_news, ['type !=' => 'general'], 'created_at', 'desc', 0, 10),

			'total_users' => $this->model->counts($this->table_user, ['role !=' => 'ADMIN']),

			'user_sum_spendings' => $this->model->sum_results($this->table_orders, ['user_id' => $this->user['id'], 'status' => 'completed', 'service_type !=' => 'subscriptions'], 'charge', 'charge'),
			'admin_sum_spendings' => $this->model->sum_results($this->table_orders, ['status' => 'completed', 'service_type !=' => 'subscriptions'], 'charge', 'charge'),

			'count_orders' => $this->model->counts($this->table_orders, ['service_type !=' => 'subscriptions']),
			'count_orders_user' => $this->model->counts($this->table_orders, ['user_id' => $this->user['id'], 'service_type !=' => 'subscriptions']),

			'total_tickets' => $this->model->counts($this->table_tickets),
			'total_tickets_user' => $this->model->counts($this->table_tickets, ['user_id' => $this->user['id']]),

			'list_chartjs_order' => $this->chartjs_model->chartjs(),

			// TOP BEST SELLER
			'list_top_bestseller' => $this->model->select_join('count(orders.service_id) as total, services.add_type, services.category_id, services.id as id_service, services.name as name_service, services.api_service_id, services.api_provider_id, services.price, services.description', $this->table_orders, "orders.service_id", 'services', 'services.id = orders.service_id', 'services.status = "1" AND orders.status = "completed" OR orders.status_sub = "Completed"', 'total', 'desc', 0, 7)
		];

		view('layouts/auth_header', $data);
		view('panel/dashboard/dashboard');
		view('layouts/auth_footer');
	}
}
