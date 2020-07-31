<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Api extends MY_Controller
{
	protected $table;
	protected $table_services;
	protected $table_category;
	protected $table_orders;
	protected $table_api_providers;

	public function __construct()
	{
		parent::__construct();

		$this->table = TABLE_USERS;
		$this->table_services = TABLE_SERVICES;
		$this->table_category = TABLE_CATEGORIES;
		$this->table_orders = TABLE_ORDERS;
		$this->table_api_providers = TABLE_API_PROVIDERS;
	}

	public function index()
	{
		$data = ['title' => lang("title_api_docs")];

		if (!logged()) {
			view('layouts/header', $data);
		} else {
			view('layouts/auth_header', $data);
		}

		view('panel/admin/management/api/api_docs');

		if (!logged()) {
			view('layouts/footer');
		} else {
			view('layouts/auth_footer');
		}
	}

	public function v2()
	{
		$key = $this->input->get_post('key', true);
		$action = $this->input->get_post('action', true);
		$order = $this->input->get_post('order', true);
		$orders = $this->input->get_post('orders', true);

		// Types Order ADD
		$service_id = (int) $this->input->get_post('service', true);
		$link = $this->input->get_post('link', true);
		$quantity = (int) $this->input->get_post('quantity', true);
		$runs = (int) $this->input->get_post('runs', true);
		$interval = (int) $this->input->get_post('interval', true);
		$custom_data = $this->input->get_post('custom_data', true);
		$username = $this->input->get_post('username', true);
		$min = (int) $this->input->get_post('min', true);
		$max = (int) $this->input->get_post('max', true);
		$posts = (int) $this->input->get_post('posts', true);
		$delay = (int) $this->input->get_post('delay', true);
		$expiry = $this->input->get_post('expiry', true);
		$comments = $this->input->get_post('comments', true);
		$usernames = $this->input->get_post('usernames', true);
		$hashtags = $this->input->get_post('hashtags', true);
		$hashtag = $this->input->get_post('hashtag', true);
		$media = $this->input->get_post('media', true);
		$answer_number = $this->input->get_post('answer_number', true);
		$seo_keywords = $this->input->get_post('keywords', true);

		if (isset($key)) {
			$key = urldecode($key);
		}

		if (isset($action)) {
			$action = urldecode($action);
		}

		if (isset($order)) {
			$order = urldecode($order);
		}

		if (isset($orders)) {
			$orders = $orders;
		}

		if (isset($service_id)) {
			$service_id = urldecode($service_id);
		}

		if (isset($link)) {
			$link = urldecode($link);
		}

		$token = $this->model->get('api_key', $this->table, ['api_key' => $key, "status" => 'Active', "role" => 'USER'], '', '', true);

		if ($token['api_key'] == '' || empty($token)) {
			json_api(['error' => lang("error_api")]);
		}

		$actions = ['add', 'status', 'services', 'balance'];
		if ($action == "" || !in_array($action, $actions)) {
			json_api(['error' => lang("error_request_action_incorrect")]);
		}

		switch ($action) {
			case 'services':
				$response = [];
				$services = $this->model->fetch('id, category_id, name, type, price, min, max, dripfeed', $this->table_services, ['status' => '1'], "id", "asc");

				if (!empty($services)) {
					foreach ($services as $service) {
						$category = $this->model->get('name', $this->table_category, ['id' => $service->category_id], '', '', true);
						$type = str_replace('_', ' ', $service->type);
						$type = ($type == 'seo' ? 'SEO' : $type);

						$response[] = [
							'service' => $service->id,
							'name' => $service->name,
							'type' => ucwords($type),
							'rate' => $service->price,
							'min' => $service->min,
							'max' => $service->max,
							'dripfeed' => ($service->dripfeed == '1' ? true : false),
							'category' => $category['name'],
						];
					}

					json_api($response);
				}

				json_api(['error' => lang("error_api_not_exists_services")]);
				break;

			case 'add':
				$this->add($key, $service_id, $link, $quantity, $runs, $interval, $custom_data, $username, $min, $max, $posts, $delay, $expiry, $comments, $usernames, $hashtags, $hashtag, $media, $answer_number, $seo_keywords);
				break;

			case 'status':
				if (isset($order)) {
					$this->status($order);
				}

				if (isset($orders)) {
					$this->multi_status($orders);
				}

				json_api(['error' => lang("error_request_action_incorrect")]);
				break;

			case 'balance':
				$this->balance($key);
				break;
		}
	}

	private function add(
		$token,
		$service_id,
		$link,
		$quantity,
		$runs,
		$interval,
		$custom_data,
		$username,
		$min,
		$max,
		$posts,
		$delay,
		$expiry,
		$comments,
		$usernames,
		$hashtags,
		$hashtag,
		$media,
		$answer_number,
		$seo_keywords
	) {
		if ($service_id == '' || empty($service_id)) {
			json_api(['error' => lang("error_request_action_incorrect")]);
		}

		$check_service = $this->model->get('*', $this->table_services, ['id' => $service_id], '', '', true);
		$user = $this->model->get('*', $this->table, ['api_key' => $token], '', '', true);

		if (!empty($check_service)) {
			$charge = ($check_service['price'] * $quantity) / 1000;

			if (isset($user['custom_rate']) && $user['custom_rate'] > 0) {
				$charge = $charge - (($charge * $user['custom_rate']) / 100);
			}

			if (dataUserId($user['id'], 'balance') < $charge && !in_array($check_service['type'], ['subscriptions', 'custom_comments', 'custom_comments_package', 'mentions_custom_list', 'package'])) {
				json_api(['error' => lang("error_not_balance")]);
			}

			switch ($check_service['type']) {
				case 'default':
					if (isset($quantity)) {
						$quantity = urldecode($quantity);
					}

					if ($link && $quantity) {
						if (!valid_url($link)) {
							json_api(['error' => lang("error_bad_link_api")]);
						}

						if ($quantity < $check_service['min'] || $quantity > $check_service['max']) {
							json_api([
								'error' => sprintf(lang("error_limit_quantity"), $check_service['min'], $check_service['max'])
							]);
						}

						$insert = [
							'user_id' => $user['id'],
							'type' => $check_service['add_type'],
							'category_id' => $check_service['category_id'],
							'service_id' => $check_service['id'],
							'service_type' => $check_service['type'],
							'api_provider_id' => $check_service['api_provider_id'],
							'api_service_id' => $check_service['api_service_id'],
							'link' => $link,
							'quantity' => $quantity,
							'charge' => $charge,
							'created_at' => NOW,
							'updated_at' => NOW,
						];

						if ($check_service['dripfeed'] == 1) {
							$runs = urldecode($runs);
							$interval = urldecode($interval);

							if (isset($runs) && isset($interval) && in_array($interval, [10, 20, 30, 40, 50, 60])) {
								$insert['runs'] = ($runs ? $runs : 0);
								$insert['interval'] = ($interval ? $interval : 0);
								$insert['is_drip_feed'] = 1;
								$insert['dripfeed_quantity'] = $quantity * $runs;
								$charge = $runs * $quantity;
							} else {
								json_api(['error' => lang("error_request_action_incorrect")]);
							}
						}

						$this->model->insert($this->table_orders, $insert);
						$last_id = $this->db->insert_id();

						$this->model->update($this->table, ['id' => $user['id']], '', false, true, $charge);

						json_api(['order' => $last_id]);
					}

					json_api(['error' => lang("error_request_action_incorrect")]);
					break;

				case 'custom_data':
					if (isset($quantity)) {
						$quantity = urldecode($quantity);
					}

					if (isset($custom_data)) {
						$custom_data = urldecode($custom_data);
					}

					if ($link && $quantity && $custom_data) {
						if (!valid_url($link)) {
							json_api(['error' => lang("error_bad_link_api")]);
						}

						if ($quantity < $check_service['min'] || $quantity > $check_service['max']) {
							json_api([
								'error' => sprintf(lang("error_limit_quantity"), $check_service['min'], $check_service['max'])
							]);
						}

						$insert = [
							'user_id' => $user['id'],
							'type' => $check_service['add_type'],
							'category_id' => $check_service['category_id'],
							'service_id' => $check_service['id'],
							'service_type' => $check_service['type'],
							'api_provider_id' => $check_service['api_provider_id'],
							'api_service_id' => $check_service['api_service_id'],
							'link' => $link,
							'quantity' => $quantity,
							'comments' => $custom_data,
							'charge' => $charge,
							'created_at' => NOW,
							'updated_at' => NOW,
						];

						$this->model->insert($this->table_orders, $insert);
						$last_id = $this->db->insert_id();

						$this->model->update($this->table, ['id' => $user['id']], '', false, true, $charge);

						json_api(['order' => $last_id]);
					}

					json_api(['error' => lang("error_request_action_incorrect")]);
					break;

				case 'subscriptions':
					if (isset($username)) {
						$username = urldecode($username);
					}

					if (isset($min)) {
						$min = urldecode($min);
					}

					if (isset($max)) {
						$max = urldecode($max);
					}

					if (isset($posts)) {
						$posts = urldecode($posts);
					}

					if (isset($delay)) {
						$delay = urldecode($delay);
					}

					if ($username == '' || empty($username)) {
						json_api(['error' => lang('error_username_subs_via_api_order')]);
					}

					if ($posts == 0) {
						json_api(['error' => lang("error_min_one_post")]);
					}

					if ($username && $min && $max && $posts && $delay) {
						if ($min < $check_service['min'] || $max > $check_service['max']) {
							json_api([
								'error' => sprintf(lang("error_limit_quantity"), $check_service['min'], $check_service['max'])
							]);
						}

						if ($min > $max) {
							json_api(['error' => lang("error_min_cannot_higher_max")]);
						}

						if (!in_array($delay, [0, 5, 10, 15, 30, 60, 90])) {
							json_api(['error' => lang("error_delay_incorrect")]);
						}

						$insert = [
							'user_id' => $user['id'],
							'type' => $check_service['add_type'],
							'category_id' => $check_service['category_id'],
							'service_id' => $check_service['id'],
							'service_type' => $check_service['type'],
							'api_provider_id' => $check_service['api_provider_id'],
							'api_service_id' => $check_service['api_service_id'],
							'min_sub' => $min,
							'max_sub' => $max,
							'posts_sub' => $posts,
							'delay_sub' => $delay,
							'status_sub' => 'Active',
							'username' => $username,
							'created_at' => NOW,
							'updated_at' => NOW,
						];

						if (isset($expiry)) {
							$expiry = urldecode($expiry);
							$insert['expiry_sub'] = $expiry;
						} else {
							$insert['expiry_sub'] = 0;
						}

						$this->model->insert($this->table_orders, $insert);
						$last_id = $this->db->insert_id();

						json_api(['order' => $last_id]);
					}

					json_api(['error' => lang("error_request_action_incorrect")]);
					break;

				case 'custom_comments':
					if (isset($comments)) {
						$comments = urldecode($comments);
					}

					$total_comments = explode('\n', str_replace(['\n', '\r\n'], '\n', $comments));
					$total_comments = count($total_comments);

					$charge = ($check_service['price'] * $total_comments) / 1000;

					if (isset($user['custom_rate']) && $user['custom_rate'] > 0) {
						$charge = $charge - (($charge * $user['custom_rate']) / 100);
					}

					if (dataUserId($user['id'], 'balance') < $charge) {
						json_api(['error' => lang("error_not_balance")]);
					}

					if ($link && $comments) {
						if (!valid_url($link)) {
							json_api(['error' => lang("error_bad_link_api")]);
						}

						if ($total_comments < $check_service['min'] || $total_comments > $check_service['max']) {
							json_api([
								'error' => sprintf(lang("error_limit_quantity"), $check_service['min'], $check_service['max'])
							]);
						}

						$comments = str_replace(['\r\n', '\n'], ',', $comments);

						$insert = [
							'user_id' => $user['id'],
							'type' => $check_service['add_type'],
							'category_id' => $check_service['category_id'],
							'service_id' => $check_service['id'],
							'service_type' => $check_service['type'],
							'api_provider_id' => $check_service['api_provider_id'],
							'api_service_id' => $check_service['api_service_id'],
							'link' => $link,
							'quantity' => $total_comments,
							'comments' => $comments,
							'charge' => $charge,
							'created_at' => NOW,
							'updated_at' => NOW,
						];

						$this->model->insert($this->table_orders, $insert);
						$last_id = $this->db->insert_id();

						$this->model->update($this->table, ['id' => $user['id']], '', false, true, $charge);

						json_api(['order' => $last_id]);
					}

					json_api(['error' => lang("error_request_action_incorrect")]);
					break;

				case 'custom_comments_package':
					if (isset($comments)) {
						$comments = urldecode($comments);
					}

					$total_comments = explode('\n', str_replace(['\n', '\r\n'], '\n', $comments));
					$total_comments = count($total_comments);

					$charge = $check_service['price'];

					if (isset($user['custom_rate']) && $user['custom_rate'] > 0) {
						$charge = $charge - (($charge * $user['custom_rate']) / 100);
					}

					if (dataUserId($user['id'], 'balance') < $charge) {
						json_api(['error' => lang("error_not_balance")]);
					}

					if ($link && $comments) {
						if (!valid_url($link)) {
							json_api(['error' => lang("error_bad_link_api")]);
						}

						if ($total_comments < $check_service['min'] || $total_comments > $check_service['max']) {
							json_api([
								'error' => sprintf(lang("error_limit_quantity"), $check_service['min'], $check_service['max'])
							]);
						}

						$comments = str_replace(['\r\n', '\n'], ',', $comments);

						$insert = [
							'user_id' => $user['id'],
							'type' => $check_service['add_type'],
							'category_id' => $check_service['category_id'],
							'service_id' => $check_service['id'],
							'service_type' => $check_service['type'],
							'api_provider_id' => $check_service['api_provider_id'],
							'api_service_id' => $check_service['api_service_id'],
							'link' => $link,
							'comments' => $comments,
							'charge' => $charge,
							'created_at' => NOW,
							'updated_at' => NOW,
						];

						$this->model->insert($this->table_orders, $insert);
						$last_id = $this->db->insert_id();

						$this->model->update($this->table, ['id' => $user['id']], '', false, true, $charge);

						json_api(['order' => $last_id]);
					}

					json_api(['error' => lang("error_request_action_incorrect")]);
					break;

				case 'mentions_with_hashtags':
					if (isset($quantity)) {
						$quantity = urldecode($quantity);
					}

					if (isset($usernames)) {
						$usernames = urldecode($usernames);
					}

					if (isset($hashtags)) {
						$hashtags = urldecode($hashtags);
					}

					if ($link && $quantity && $usernames && $hashtags) {
						if (!valid_url($link)) {
							json_api(['error' => lang("error_bad_link_api")]);
						}

						if ($quantity < $check_service['min'] || $quantity > $check_service['max']) {
							json_api([
								'error' => sprintf(lang("error_limit_quantity"), $check_service['min'], $check_service['max'])
							]);
						}

						$usernames = str_replace(['\r\n', '\n'], ',', $usernames);
						$hashtags = str_replace(['\r\n', '\n'], ',', $hashtags);

						$insert = [
							'user_id' => $user['id'],
							'type' => $check_service['add_type'],
							'category_id' => $check_service['category_id'],
							'service_id' => $check_service['id'],
							'service_type' => $check_service['type'],
							'api_provider_id' => $check_service['api_provider_id'],
							'api_service_id' => $check_service['api_service_id'],
							'link' => $link,
							'quantity' => $quantity,
							'usernames' => $usernames,
							'hashtags' => $hashtags,
							'charge' => $charge,
							'created_at' => NOW,
							'updated_at' => NOW,
						];

						$this->model->insert($this->table_orders, $insert);
						$last_id = $this->db->insert_id();

						$this->model->update($this->table, ['id' => $user['id']], '', false, true, $charge);

						json_api(['order' => $last_id]);
					}

					json_api(['error' => lang("error_request_action_incorrect")]);
					break;

				case 'mentions_custom_list':
					if (isset($usernames)) {
						$usernames = urldecode($usernames);
					}

					$total_usernames = explode('\n', str_replace(['\n', '\r\n'], '\n', $usernames));
					$total_usernames = count($total_usernames);

					$charge = ($check_service['price'] * $total_usernames) / 1000;

					if (isset($user['custom_rate']) && $user['custom_rate'] > 0) {
						$charge = $charge - (($charge * $user['custom_rate']) / 100);
					}

					if (dataUserId($user['id'], 'balance') < $charge) {
						json_api(['error' => lang("error_not_balance")]);
					}

					if ($link && $usernames) {
						if (!valid_url($link)) {
							json_api(['error' => lang("error_bad_link_api")]);
						}

						if ($total_usernames < $check_service['min'] || $total_usernames > $check_service['max']) {
							json_api([
								'error' => sprintf(lang("error_limit_quantity"), $check_service['min'], $check_service['max'])
							]);
						}

						$usernames = str_replace(['\r\n', '\n'], ',', $usernames);

						$insert = [
							'user_id' => $user['id'],
							'type' => $check_service['add_type'],
							'category_id' => $check_service['category_id'],
							'service_id' => $check_service['id'],
							'service_type' => $check_service['type'],
							'api_provider_id' => $check_service['api_provider_id'],
							'api_service_id' => $check_service['api_service_id'],
							'link' => $link,
							'usernames' => $usernames,
							'charge' => $charge,
							'created_at' => NOW,
							'updated_at' => NOW,
						];

						$this->model->insert($this->table_orders, $insert);
						$last_id = $this->db->insert_id();

						$this->model->update($this->table, ['id' => $user['id']], '', false, true, $charge);

						json_api(['order' => $last_id]);
					}

					json_api(['error' => lang("error_request_action_incorrect")]);
					break;

				case 'mentions_hashtag':
					if (isset($quantity)) {
						$quantity = urldecode($quantity);
					}

					if (isset($hashtag)) {
						$hashtag = urldecode($hashtag);
					}

					if ($link && $quantity && $hashtag) {
						if (!valid_url($link)) {
							json_api(['error' => lang("error_bad_link_api")]);
						}

						if ($quantity < $check_service['min'] || $quantity > $check_service['max']) {
							json_api([
								'error' => sprintf(lang("error_limit_quantity"), $check_service['min'], $check_service['max'])
							]);
						}

						$insert = [
							'user_id' => $user['id'],
							'type' => $check_service['add_type'],
							'category_id' => $check_service['category_id'],
							'service_id' => $check_service['id'],
							'service_type' => $check_service['type'],
							'api_provider_id' => $check_service['api_provider_id'],
							'api_service_id' => $check_service['api_service_id'],
							'link' => $link,
							'quantity' => $quantity,
							'hashtag' => $hashtag,
							'charge' => $charge,
							'created_at' => NOW,
							'updated_at' => NOW,
						];

						$this->model->insert($this->table_orders, $insert);
						$last_id = $this->db->insert_id();

						$this->model->update($this->table, ['id' => $user['id']], '', false, true, $charge);

						json_api(['order' => $last_id]);
					}

					json_api(['error' => lang("error_request_action_incorrect")]);
					break;

				case 'mentions_user_followers':
					if (isset($quantity)) {
						$quantity = urldecode($quantity);
					}

					if (isset($username)) {
						$username = urldecode($username);
					}

					if ($username == '' || empty($username)) {
						json_api(['error' => lang('error_username_subs_via_api_order')]);
					}

					if ($link && $quantity && $username) {
						if (!valid_url($link)) {
							json_api(['error' => lang("error_bad_link_api")]);
						}

						if ($quantity < $check_service['min'] || $quantity > $check_service['max']) {
							json_api([
								'error' => sprintf(lang("error_limit_quantity"), $check_service['min'], $check_service['max'])
							]);
						}

						$insert = [
							'user_id' => $user['id'],
							'type' => $check_service['add_type'],
							'category_id' => $check_service['category_id'],
							'service_id' => $check_service['id'],
							'service_type' => $check_service['type'],
							'api_provider_id' => $check_service['api_provider_id'],
							'api_service_id' => $check_service['api_service_id'],
							'link' => $link,
							'quantity' => $quantity,
							'username' => $username,
							'charge' => $charge,
							'created_at' => NOW,
							'updated_at' => NOW,
						];

						$this->model->insert($this->table_orders, $insert);
						$last_id = $this->db->insert_id();

						$this->model->update($this->table, ['id' => $user['id']], '', false, true, $charge);

						json_api(['order' => $last_id]);
					}

					json_api(['error' => lang("error_request_action_incorrect")]);
					break;

				case 'mentions_media_likers':
					if (isset($quantity)) {
						$quantity = urldecode($quantity);
					}

					if (isset($media)) {
						$media = urldecode($media);
					}

					if ($link && $quantity && $media) {
						if (!valid_url($link)) {
							json_api(['error' => lang("error_bad_link_api")]);
						}

						if (!valid_url($media)) {
							json_api(['error' => lang("error_bad_link_api")]);
						}

						if ($quantity < $check_service['min'] || $quantity > $check_service['max']) {
							json_api([
								'error' => sprintf(lang("error_limit_quantity"), $check_service['min'], $check_service['max'])
							]);
						}

						$insert = [
							'user_id' => $user['id'],
							'type' => $check_service['add_type'],
							'category_id' => $check_service['category_id'],
							'service_id' => $check_service['id'],
							'service_type' => $check_service['type'],
							'api_provider_id' => $check_service['api_provider_id'],
							'api_service_id' => $check_service['api_service_id'],
							'link' => $link,
							'quantity' => $quantity,
							'media' => $media,
							'charge' => $charge,
							'created_at' => NOW,
							'updated_at' => NOW,
						];

						$this->model->insert($this->table_orders, $insert);
						$last_id = $this->db->insert_id();

						$this->model->update($this->table, ['id' => $user['id']], '', false, true, $charge);

						json_api(['order' => $last_id]);
					}

					json_api(['error' => lang("error_request_action_incorrect")]);
					break;

				case 'package':
					$charge = $check_service['price'];

					if (isset($user['custom_rate']) && $user['custom_rate'] > 0) {
						$charge = $charge - (($charge * $user['custom_rate']) / 100);
					}

					if (dataUserId($user['id'], 'balance') < $charge) {
						json_api(['error' => lang("error_not_balance")]);
					}

					if ($link) {
						if (!valid_url($link)) {
							json_api(['error' => lang("error_bad_link_api")]);
						}

						$insert = [
							'user_id' => $user['id'],
							'type' => $check_service['add_type'],
							'category_id' => $check_service['category_id'],
							'service_id' => $check_service['id'],
							'service_type' => $check_service['type'],
							'api_provider_id' => $check_service['api_provider_id'],
							'api_service_id' => $check_service['api_service_id'],
							'link' => $link,
							'charge' => $charge,
							'created_at' => NOW,
							'updated_at' => NOW,
						];

						$this->model->insert($this->table_orders, $insert);
						$last_id = $this->db->insert_id();

						$this->model->update($this->table, ['id' => $user['id']], '', false, true, $charge);

						json_api(['order' => $last_id]);
					}

					json_api(['error' => lang("error_request_action_incorrect")]);
					break;

				case 'comment_likes':
					if (isset($quantity)) {
						$quantity = urldecode($quantity);
					}

					if (isset($username)) {
						$username = urldecode($username);
					}

					if ($username == '' || empty($username)) {
						json_api(['error' => lang('error_username_subs_via_api_order')]);
					}

					if ($link && $quantity && $username) {
						if (!valid_url($link)) {
							json_api(['error' => lang("error_bad_link_api")]);
						}

						if ($quantity < $check_service['min'] || $quantity > $check_service['max']) {
							json_api([
								'error' => sprintf(lang("error_limit_quantity"), $check_service['min'], $check_service['max'])
							]);
						}

						$insert = [
							'user_id' => $user['id'],
							'type' => $check_service['add_type'],
							'category_id' => $check_service['category_id'],
							'service_id' => $check_service['id'],
							'service_type' => $check_service['type'],
							'api_provider_id' => $check_service['api_provider_id'],
							'api_service_id' => $check_service['api_service_id'],
							'link' => $link,
							'quantity' => $quantity,
							'username' => $username,
							'charge' => $charge,
							'created_at' => NOW,
							'updated_at' => NOW,
						];

						$this->model->insert($this->table_orders, $insert);
						$last_id = $this->db->insert_id();

						$this->model->update($this->table, ['id' => $user['id']], '', false, true, $charge);

						json_api(['order' => $last_id]);
					}

					json_api(['error' => lang("error_request_action_incorrect")]);
					break;

				case 'poll':
					if (isset($quantity)) {
						$quantity = urldecode($quantity);
					}

					if (isset($answer_number)) {
						$answer_number = urldecode($answer_number);
					}

					if ($answer_number == '' || empty($answer_number)) {
						json_api(['error' => lang('error_answer_number')]);
					}

					if ($link && $quantity && $answer_number) {
						if (!valid_url($link)) {
							json_api(['error' => lang("error_bad_link_api")]);
						}

						if ($quantity < $check_service['min'] || $quantity > $check_service['max']) {
							json_api([
								'error' => sprintf(lang("error_limit_quantity"), $check_service['min'], $check_service['max'])
							]);
						}

						$insert = [
							'user_id' => $user['id'],
							'type' => $check_service['add_type'],
							'category_id' => $check_service['category_id'],
							'service_id' => $check_service['id'],
							'service_type' => $check_service['type'],
							'api_provider_id' => $check_service['api_provider_id'],
							'api_service_id' => $check_service['api_service_id'],
							'link' => $link,
							'quantity' => $quantity,
							'poll_answer_number' => $answer_number,
							'charge' => $charge,
							'created_at' => NOW,
							'updated_at' => NOW,
						];

						$this->model->insert($this->table_orders, $insert);
						$last_id = $this->db->insert_id();

						$this->model->update($this->table, ['id' => $user['id']], '', false, true, $charge);

						json_api(['order' => $last_id]);
					}

					json_api(['error' => lang("error_request_action_incorrect")]);
					break;

				case 'seo':
					if (isset($quantity)) {
						$quantity = urldecode($quantity);
					}

					if (isset($seo_keywords)) {
						$seo_keywords = urldecode($seo_keywords);
					}

					if ($link && $quantity && $seo_keywords) {
						if (!valid_url($link)) {
							json_api(['error' => lang("error_bad_link_api")]);
						}

						if ($quantity < $check_service['min'] || $quantity > $check_service['max']) {
							json_api([
								'error' => sprintf(lang("error_limit_quantity"), $check_service['min'], $check_service['max'])
							]);
						}

						$seo_keywords = str_replace(['\r\n', '\n'], ',', $seo_keywords);

						$insert = [
							'user_id' => $user['id'],
							'type' => $check_service['add_type'],
							'category_id' => $check_service['category_id'],
							'service_id' => $check_service['id'],
							'service_type' => $check_service['type'],
							'api_provider_id' => $check_service['api_provider_id'],
							'api_service_id' => $check_service['api_service_id'],
							'link' => $link,
							'quantity' => $quantity,
							'seo_keywords' => $seo_keywords,
							'charge' => $charge,
							'created_at' => NOW,
							'updated_at' => NOW,
						];

						$this->model->insert($this->table_orders, $insert);
						$last_id = $this->db->insert_id();

						$this->model->update($this->table, ['id' => $user['id']], '', false, true, $charge);

						json_api(['order' => $last_id]);
					}

					json_api(['error' => lang("error_request_action_incorrect")]);
					break;
			}
		}

		json_api(['error' => lang("error_incorrect_service_id")]);
	}

	private function status($order_id)
	{
		if ($order_id == '' || empty($order_id)) {
			json_api(['error' => lang("error_request_action_incorrect")]);
		}

		if (!is_numeric($order_id)) {
			json_api(['error' => lang("error_incorrect_order_id")]);
		}

		$order = $this->model->get('*', $this->table_orders, ['id' => $order_id], '', '', true);

		if (!empty($order)) {
			if ($order['service_type'] != 'subscriptions') {
				json_api([
					'charge' => $order['charge'],
					'start_count' => $order['start_counter'],
					'status' => ucfirst($order['status']),
					'remains' => $order['remains'],
					'currency' => configs('currency_code', 'value'),
				]);
			}

			$order_sub = $this->model->fetch('*', $this->table_orders, ['order_response_id_sub' => $order['order_response_id_sub'], 'service_type' => 'default']);

			if (!empty($order_sub)) {
				foreach ($order_sub as $value) {
					$status_subscriptions = $this->model->get('*', $this->table_orders, ['order_response_id_sub' => $order['order_response_id_sub'], 'service_type' => 'subscriptions'], '', '', true);

					$ids[] = $value->id;
					$status_sub = $status_subscriptions['status_sub'];
					$expiry_sub = $status_subscriptions['expiry_sub'];
				}

				json_api([
					'status' => ucfirst($status_sub),
					'expiry' => ($expiry_sub == '' || empty($expiry_sub) ? null : $expiry_sub),
					'posts' => $order['order_response_posts_sub'],
					'orders' => $ids,
				]);
			}

			$order_sub_not_orders = $this->model->fetch('*', $this->table_orders, ['service_type' => 'subscriptions']);

			if (!empty($order_sub_not_orders)) {
				foreach ($order_sub_not_orders as $value) {
					$status_sub = $value->status_sub;
					$expiry_sub = $value->expiry_sub;
				}

				json_api([
					'status' => ucfirst($status_sub),
					'expiry' => ($expiry_sub == '' || empty($expiry_sub) ? null : $expiry_sub),
					'posts' => $order['order_response_posts_sub'],
					'orders' => [],
				]);
			}
		}

		json_api(['error' => lang("error_order_not_found")]);
	}

	private function multi_status($order_ids)
	{
		if ($order_ids == '' || empty($order_ids)) {
			json_api(['error' => lang("error_request_action_incorrect")]);
		}

		$order_ids = explode(",", $order_ids);
		$order_ids = json_decode(json_encode($order_ids), true);

		if (is_array($order_ids)) {
			$response = [];
			$ids = [];
			foreach ($order_ids as $order_id) {
				$order = $this->model->get('*', $this->table_orders, ['id' => $order_id], '', '', true);

				if (!empty($order)) {
					if ($order['service_type'] != 'subscriptions') {
						$response[$order_id] = [
							'charge' => $order['charge'],
							'start_count' => $order['start_counter'],
							'status' => ucfirst($order['status']),
							'remains' => $order['remains'],
							'currency' => configs('currency_code', 'value'),
						];
					}

					$order_sub = $this->model->fetch('*', $this->table_orders, ['order_response_id_sub' => $order['order_response_id_sub'], 'service_type' => 'subscriptions']);

					if (!empty($order_sub)) {
						foreach ($order_sub as $key => $value) {
							$id = $value->id;
							$ids[$key] = $value->id;
							$status_sub = $value->status_sub;
							$expiry_sub = $value->expiry_sub;
						}

						$response[$id] = [
							'status' => ucfirst($status_sub),
							'expiry' => ($expiry_sub == '' || empty($expiry_sub) ? null : $expiry_sub),
							'posts' => $order['order_response_posts_sub'],
							'orders' => $ids,
						];
						unset($ids);
					}

					$order_sub_not_orders = $this->model->fetch('*', $this->table_orders, ['service_type' => 'subscriptions']);

					if (!empty($order_sub_not_orders)) {
						foreach ($order_sub_not_orders as $value) {
							$id = $value->id;
							$status_sub = $value->status_sub;
							$expiry_sub = $value->expiry_sub;
						}

						$response[$id] = [
							'status' => ucfirst($status_sub),
							'expiry' => ($expiry_sub == '' || empty($expiry_sub) ? null : $expiry_sub),
							'posts' => $order['order_response_posts_sub'],
							'orders' => [],
						];
					}
				} else {
					$response[$order_id] = ['error' => lang("error_incorrect_order_id")];
				}
			}

			json_api($response);
		}
	}

	private function balance($api_token)
	{
		$balance = $this->model->get("balance", $this->table, ['api_key' => $api_token, "role" => 'USER'], '', '', true);

		if (!empty($balance)) {
			json_api([
				'balance' => $balance['balance'],
				'currency' => configs('currency_code', 'value'),
			]);
		}
	}
}
