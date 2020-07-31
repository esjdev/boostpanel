<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Subscriptions extends MY_Controller
{
	protected $table_users;
	protected $table_orders;
	protected $table_services;

	public function __construct()
	{
		parent::__construct();

		if (userLevel(logged(), 'user') || userLevel(logged(), 'banned')) return redirect(base_url());

		$this->table_users = TABLE_USERS;
		$this->table_orders = TABLE_ORDERS;
		$this->table_services = TABLE_SERVICES;
	}

	public function all()
	{
		$this->load->library('pagination');

		$config = [
			'base_url' => base_url("admin/subscriptions"),
			'total_rows' => $this->model->counts($this->table_orders, ['service_type' => 'subscriptions']),
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
			'title' => lang("title_subscriptions"),
			'all_subscriptions' => $this->model->fetch('*', $this->table_orders, ['service_type' => 'subscriptions'], "updated_at", "desc", $offset, $config["per_page"]),
			'pagination_links' => $this->pagination->create_links(),
		];

		view('layouts/auth_header', $data);
		view("panel/admin/management/history/admin/subscriptions/all_subscription");
		view('layouts/auth_footer');
	}

	public function status_subscription($type, $id)
	{
		if (!userLevel(logged(), 'admin')) return redirect(base_url('admin/subscriptions'));

		$subscription = $this->model->get('*', $this->table_orders, ['id' => $id, 'service_type' => 'subscriptions'], "", "", true);

		if (!in_array($subscription['status_sub'], ['Completed', 'Expired', 'Canceled'])) {
			switch ($type) {
				case 'pause':
					$this->model->update($this->table_orders, ['id' => $id, 'status_sub' => 'Active'], ['status_sub' => 'Paused', 'updated_at' => NOW]);
					redirect(base_url('admin/subscriptions'));
					break;

				case 'completed':
					if (isset($_POST) && !empty($_POST)) {
						$posts = (int) $this->input->post('posts_sub', true);
						$remains = (int) $this->input->post('remains_sub', true);
						$start_count = (int) $this->input->post('start_count_sub', true);

						$subscription = $this->model->get('*', $this->table_orders, ['id' => $id], '', '', true);
						$posts_sub = $subscription['posts_sub'] - $subscription['order_response_posts_sub'];

						$services_list = $this->model->get('*', $this->table_services, ['id' => $subscription['service_id']], '', '', true);
						$real_charge = ($remains * $posts * $services_list['price']) / 1000;

						if (empty($posts) || empty($remains) || empty($start_count)) {
							json([
								'csrf' => $this->security->get_csrf_hash(),
								'type' => 'error',
								'message' => sprintf(lang("error_empty_field"), "" . lang("posts") . ", " . lang("remains") . ", " . lang("start_count") . ""),
							]);
						}

						if (!is_numeric($posts) || !is_numeric($remains) || !is_numeric($start_count)) {
							json([
								'csrf' => $this->security->get_csrf_hash(),
								'type' => 'error',
								'message' => sprintf(lang("error_alpha_numeric"), "" . lang("posts") . ", " . lang("remains") . ", " . lang("start_count") . ""),
							]);
						}

						if ($posts > $posts_sub) {
							json([
								'csrf' => $this->security->get_csrf_hash(),
								'type' => 'error',
								'message' => sprintf(lang("error_post_limit_is_maximum"), $posts_sub),
							]);
						}

						if (empty($subscription)) {
							json([
								'csrf' => $this->security->get_csrf_hash(),
								'type' => 'error',
								'message' => lang("error_not_exists_id"),
							]);
						}

						if (dataUserId($subscription['user_id'], 'balance') < $real_charge) {
							json([
								'csrf' => $this->security->get_csrf_hash(),
								'type' => 'error',
								'message' => sprintf(lang("error_not_balance_for_user"), "<b>" . dataUserId($subscription['user_id'], 'username') . "</b>"),
							]);
						}

						$count_subs_posts = $this->model->counts($this->table_orders, ['order_response_id_sub' => $id, 'service_type' => 'default']);

						$insert = [
							'user_id' => $subscription['user_id'],
							'type' => 'manual',
							'category_id' => $subscription['category_id'],
							'service_id' => $subscription['service_id'],
							'service_type' => 'default',
							'order_response_id_sub' => $id,
							'link' => "https://www.instagram.com/" . $subscription['username'],
							'quantity' => ($remains > 0) ? $remains : 0,
							'charge' => $real_charge,
							'start_counter' => $start_count,
							'remains' => $remains,
							'status' => 'completed',
							'created_at' => NOW,
							'updated_at' => NOW,
						];

						for ($i = 0; $i < $posts; $i++) {
							$this->model->insert($this->table_orders, $insert);
						}

						$this->model->update($this->table_users, ['id' => $subscription['user_id']], '', false, true, $real_charge);
						$this->model->update($this->table_orders, "id = $id AND (status_sub = 'Paused' or status_sub = 'Active')", [
							'status_sub' => ($count_subs_posts == $subscription['posts_sub'] ? 'Completed' : 'Active'),
							'api_provider_id' => '0',
							'api_service_id' => '0',
							'order_response_id_sub' => $id,
							'order_response_posts_sub' => (empty($count_subs_posts) ? 1 : $count_subs_posts),
							'updated_at' => NOW
						]);

						json([
							'csrf' => $this->security->get_csrf_hash(),
							'type' => 'success',
							'message' => lang("success_status_updated"),
						]);
					}
					break;

				case 'expired':
					$this->model->update($this->table_orders, "id = $id AND (status_sub = 'Paused' or status_sub = 'Active')", ['status_sub' => 'Expired', 'updated_at' => NOW]);
					redirect(base_url('admin/subscriptions'));
					break;

				case 'resume':
					$this->model->update($this->table_orders, ['id' => $id, 'status_sub' => 'Paused'], ['status_sub' => 'Active', 'updated_at' => NOW]);
					redirect(base_url('admin/subscriptions'));
					break;

				case 'stop':
					$this->model->update($this->table_orders, ['id' => $id, 'status_sub' => 'Paused'], ['status_sub' => 'Canceled', 'updated_at' => NOW]);
					redirect(base_url('admin/subscriptions'));
					break;
			}
		}
	}

	public function search()
	{
		if (isset($_POST) && !empty($_POST)) {
			$search = $this->input->post('searchAdminSubscriptions', true);

			$data = [
				'search_subscriptions' => $this->model->search($this->table_orders, 'updated_at', 'desc', ['id' => $search], ['username' => $search, 'service_id' => $search], ['service_type' => 'subscriptions']),
			];

			view('panel/admin/management/history/admin/subscriptions/search_subscriptions', $data);
		}
	}

	public function subscription_for_status($type)
	{
		$this->load->library('pagination');

		$config = [
			'base_url' => base_url("admin/subscriptions/" . $type),
			'total_rows' => $this->model->counts($this->table_orders, ['status_sub' => $type, 'service_type' => 'subscriptions']),
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

		if (!in_array($type, ['active', 'paused', 'completed', 'expired', 'canceled'])) return redirect(base_url('admin/subscriptions'));

		$this->pagination->initialize($config);
		$page = (uri(4)) ? uri(4) : 0;
		$offset = $page == 0 ? 0 : ($page - 1) * $config["per_page"];

		switch ($type) {
			case 'active':
				$data = [
					'title' => lang("title_subscriptions") . " - " . lang("status_active"),
					'subscriptions_for_status' => $this->model->fetch('*', $this->table_orders, ['status_sub' => 'Active', 'service_type' => 'subscriptions'], "updated_at", "desc", $offset, $config["per_page"]),
					'pagination_links' => $this->pagination->create_links(),
				];
				break;

			case 'paused':
				$data = [
					'title' => lang("title_subscriptions") . " - " . lang("status_subs_paused"),
					'subscriptions_for_status' => $this->model->fetch('*', $this->table_orders, ['status_sub' => 'Paused', 'service_type' => 'subscriptions'], "updated_at", "desc", $offset, $config["per_page"]),
					'pagination_links' => $this->pagination->create_links(),
				];
				break;

			case 'completed':
				$data = [
					'title' => lang("title_subscriptions") . " - " . lang("status_completed"),
					'subscriptions_for_status' => $this->model->fetch('*', $this->table_orders, ['status_sub' => 'Completed', 'service_type' => 'subscriptions'], "updated_at", "desc", $offset, $config["per_page"]),
					'pagination_links' => $this->pagination->create_links(),
				];
				break;

			case 'expired':
				$data = [
					'title' => lang("title_subscriptions") . " - " . lang("status_subs_expired"),
					'subscriptions_for_status' => $this->model->fetch('*', $this->table_orders, ['status_sub' => 'Expired', 'service_type' => 'subscriptions'], "updated_at", "desc", $offset, $config["per_page"]),
					'pagination_links' => $this->pagination->create_links(),
				];
				break;

			case 'canceled':
				$data = [
					'title' => lang("title_subscriptions") . " - " . lang("status_canceled"),
					'subscriptions_for_status' => $this->model->fetch('*', $this->table_orders, ['status_sub' => 'Canceled', 'service_type' => 'subscriptions'], "updated_at", "desc", $offset, $config["per_page"]),
					'pagination_links' => $this->pagination->create_links(),
				];
				break;
		}

		view('layouts/auth_header', $data);
		view("panel/admin/management/history/admin/subscriptions/subscription_for_status");
		view('layouts/auth_footer');
	}
}
