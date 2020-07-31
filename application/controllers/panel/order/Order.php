<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Order extends MY_Controller
{
    protected $table_orders;
    protected $table_users;
    protected $table_services;

    private $user;

    public function __construct()
    {
        parent::__construct();

        if (!logged()) return redirect(base_url());

        $this->table_orders = TABLE_ORDERS;
        $this->table_users = TABLE_USERS;
        $this->table_services = TABLE_SERVICES;

        $this->load->model("order_model");

        $this->user = $this->model->get('*', $this->table_users, ['email' => logged()], '', '', true);
    }

    public function index()
    {
        if (!userLevel(logged(), 'user')) return redirect(base_url());

        $data = [
            'title' => lang("title_new_order"),
            'user_custom_rate' => $this->user['custom_rate'],
        ];

        view('layouts/auth_header', $data);
        view('panel/order/new_order');
        view('layouts/auth_footer');
    }

    public function get_category_ajax()
    {
        if (isset($_POST) && !empty($_POST)) {
            $category = $this->input->post('category', true);
            $service = $this->input->post('services', true);

            $services = $this->model->fetch('*', $this->table_services, ['category_id' => $category, 'status' => '1']);
            $services_list = $this->model->get('*', $this->table_services, ['id' => $service, 'status' => '1'], '', '', true);

            if (!empty($services)) {
                echo "<select id='services' class='form-control' name='services'>";
                echo '<option value="noselect" class="font-weight-bold">' . lang("select_service") . '</option>';
                foreach ($services as $value) {
                    echo '<option value="' . $value->id . '">' . $value->name . ' - ' . configs('currency_symbol', 'value') . currency_format($value->price) . '</option>';
                }
                echo "</select>";
            }

            if ($service != 'noselect' && $service != "") {
                json([
                    'min' => $services_list['min'],
                    'max' => $services_list['max'],
                    'description' => $services_list['description'],
                    'price_per_1k' => $services_list['price'],
                    'service_type' => $services_list['type'],
                    'dripfeed' => $services_list['dripfeed']
                ]);
            }
        }
    }

    public function store()
    {
        if (!userLevel(logged(), 'user')) return redirect(base_url());

        if (isset($_POST) && !empty($_POST)) {
            $category = $this->input->post('category', true);
            $service = $this->input->post('services', true);
            $link = $this->input->post('link', true);
            $quantity = $this->input->post('quantity', true);
            $comments_package = $this->input->post('comments_custom_package', true);
            $usernames_hashtags = $this->input->post('usernames_hashtags', true);
            $usernames = $this->input->post('usernames', true);
            $mentions_with_hashtags = $this->input->post('mentions_with_hashtags', true);
            $mentions_with_hashtag = $this->input->post('mentions_with_hashtag', true);
            $username_follower = $this->input->post('username_follower', true);
            $media_url = $this->input->post('media_url', true);
            $dripfeed = $this->input->post('dripfeed', true);
            $runs = $this->input->post('runs', true);
            $interval = $this->input->post('interval', true);
            $total_quantity_dripfeed = $this->input->post('total_quantity_dripfeed', true);
            $username_subscriptions = $this->input->post('username_subscriptions', true);
            $new_posts_subs = $this->input->post('new_posts_subs', true);
            $quantity_subscription_min = $this->input->post('quantity_subscription_min', true);
            $quantity_subscription_max = $this->input->post('quantity_subscription_max', true);
            $delay_subscription = $this->input->post('delay_subscription', true);
            $expiry_subscription = $this->input->post('expiry_subscription', true);
            $answer_number = $this->input->post('poll_answer_number', true);
            $seo_keywords = $this->input->post('seo_keywords', true);

            if ($category == 'noselect') {
                json([
                    'csrf' => $this->security->get_csrf_hash(),
                    'type' => 'error',
                    'message' => lang("error_no_select_category"),
                ]);
            }

            if ($service == 'noselect') {
                json([
                    'csrf' => $this->security->get_csrf_hash(),
                    'type' => 'error',
                    'message' => lang("error_no_select_service"),
                ]);
            }

            $services_list = $this->model->get('*', $this->table_services, ['id' => $service, 'status' => '1'], '', '', true);

            $quantity = (empty($quantity) || $quantity == '' ? $quantity = 0 : $quantity);
            $charge = ($services_list['price'] * $quantity) / 1000;

            if (isset($this->user['custom_rate']) && $this->user['custom_rate'] > 0) {
                $charge = $charge - (($charge * $this->user['custom_rate']) / 100);
            }

            if (userBalance(logged(), true) < $charge && !in_array($services_list['type'], ['subscriptions', 'custom_comments_package', 'package'])) {
                json([
                    'csrf' => $this->security->get_csrf_hash(),
                    'type' => 'error',
                    'message' => lang("error_not_balance"),
                ]);
            }

            switch ($services_list['type']) {
                case 'default':
                    $this->order_model->validation_form('default');

                    if (!valid_url($link)) {
                        json([
                            'csrf' => $this->security->get_csrf_hash(),
                            'type' => 'error',
                            'message' => lang("error_bad_link"),
                        ]);
                    }

                    if ($quantity < $services_list['min'] || $quantity > $services_list['max']) {
                        json([
                            'csrf' => $this->security->get_csrf_hash(),
                            'type' => 'error',
                            'message' => sprintf(lang("error_limit_quantity"), $services_list['min'], $services_list['max']),
                        ]);
                    }

                    if (isset($dripfeed) && empty($runs) && $runs == 0) {
                        json([
                            'csrf' => $this->security->get_csrf_hash(),
                            'type' => 'error',
                            'message' => sprintf(lang("error_empty_field"), lang("runs_new_order")),
                        ]);
                    }

                    if (isset($dripfeed) && !in_array($interval, [10, 20, 30, 40, 50, 60])) {
                        json([
                            'csrf' => $this->security->get_csrf_hash(),
                            'type' => 'error',
                            'message' => lang("error_interval_incorrect"),
                        ]);
                    }

                    $insert = [
                        'user_id' => $this->user['id'],
                        'type' => $services_list['add_type'],
                        'category_id' => $category,
                        'service_id' => $service,
                        'service_type' => $services_list['type'],
                        'api_provider_id' => $services_list['api_provider_id'],
                        'api_service_id' => $services_list['api_service_id'],
                        'link' => $link,
                        'quantity' => $quantity,
                        'charge' => $charge,
                        'created_at' => NOW,
                        'updated_at' => NOW,
                    ];

                    if (isset($dripfeed)) {
                        $insert['runs'] = ($runs ? $runs : 0);
                        $insert['interval'] = ($interval ? $interval : 0);
                        $insert['is_drip_feed'] = (isset($dripfeed) ? 1 : 0);
                        $insert['dripfeed_quantity'] = $total_quantity_dripfeed;
                        $charge = $runs * $quantity;
                    }

                    $this->model->insert($this->table_orders, $insert);
                    $this->model->update($this->table_users, ['id' => $this->user['id']], '', false, true, $charge);
                    break;

                case 'custom_data':
                    $this->order_model->validation_form('custom_data');

                    if (!valid_url($link)) {
                        json([
                            'csrf' => $this->security->get_csrf_hash(),
                            'type' => 'error',
                            'message' => lang("error_bad_link"),
                        ]);
                    }

                    if ($quantity < $services_list['min'] || $quantity > $services_list['max']) {
                        json([
                            'csrf' => $this->security->get_csrf_hash(),
                            'type' => 'error',
                            'message' => sprintf(lang("error_limit_quantity"), $services_list['min'], $services_list['max']),
                        ]);
                    }

                    $insert = [
                        'user_id' => $this->user['id'],
                        'type' => $services_list['add_type'],
                        'category_id' => $category,
                        'service_id' => $service,
                        'service_type' => $services_list['type'],
                        'api_provider_id' => $services_list['api_provider_id'],
                        'api_service_id' => $services_list['api_service_id'],
                        'link' => $link,
                        'quantity' => $quantity,
                        'comments' => $comments_package,
                        'charge' => $charge,
                        'created_at' => NOW,
                        'updated_at' => NOW,
                    ];

                    $this->model->insert($this->table_orders, $insert);
                    $this->model->update($this->table_users, ['id' => $this->user['id']], '', false, true, $charge);
                    break;

                case 'subscriptions':
                    $this->order_model->validation_form('subscriptions');

                    if ($quantity_subscription_min < $services_list['min'] || $quantity_subscription_max > $services_list['max']) {
                        json([
                            'csrf' => $this->security->get_csrf_hash(),
                            'type' => 'error',
                            'message' => sprintf(lang("error_limit_quantity"), $services_list['min'], $services_list['max']),
                        ]);
                    }

                    if ($quantity_subscription_min > $quantity_subscription_max) {
                        json([
                            'csrf' => $this->security->get_csrf_hash(),
                            'type' => 'error',
                            'message' => lang("error_min_cannot_higher_max"),
                        ]);
                    }

                    if (!in_array($delay_subscription, [0, 5, 10, 15, 30, 60, 90])) {
                        json([
                            'csrf' => $this->security->get_csrf_hash(),
                            'type' => 'error',
                            'message' => lang("error_delay_incorrect"),
                        ]);
                    }

                    $insert = [
                        'user_id' => $this->user['id'],
                        'type' => $services_list['add_type'],
                        'category_id' => $category,
                        'service_id' => $service,
                        'service_type' => $services_list['type'],
                        'api_provider_id' => $services_list['api_provider_id'],
                        'api_service_id' => $services_list['api_service_id'],
                        'min_sub' => $quantity_subscription_min,
                        'max_sub' => $quantity_subscription_max,
                        'posts_sub' => $new_posts_subs,
                        'delay_sub' => $delay_subscription,
                        'status_sub' => 'Active',
                        'username' => $username_subscriptions,
                        'created_at' => NOW,
                        'updated_at' => NOW,
                    ];

                    $insert['expiry_sub'] = (!empty($expiry_subscription) ? $expiry_subscription : 0);

                    $this->model->insert($this->table_orders, $insert);

                    $delay = ($delay_subscription == '0' ? lang('no_delay') : $delay_subscription . " " . lang("minutes"));
                    $last_id = $this->db->insert_id();

                    json([
                        'csrf' => $this->security->get_csrf_hash(),
                        'type' => 'success',
                        'message' =>
                        "<div class'alert alert-success alert-dismissible'>
							<h4 class='fs-25 text-white'>" . lang("success_order_received") . "</h4>
							<strong>" . lang("lang_subscription_id_subs") . ":</strong> " . $last_id . "<br>
							<strong>" . lang("lang_service_price_subs") . ":</strong> " . $services_list['name'] . "<br>
							<strong>" . lang("input_username") . ":</strong> " . $username_subscriptions . "<br>
							<strong>" . lang("quantity") . ":</strong> " . $quantity_subscription_min . " / " . $quantity_subscription_max . "<br>
							<strong>" . lang("new_posts_order") . ":</strong> " . $new_posts_subs . "<br>
							<strong>" . lang("delay_new_order") . ":</strong> " . $delay . "
						</div>",
                    ]);
                    break;

                case 'custom_comments':
                    $this->order_model->validation_form('custom_comments');

                    if (!valid_url($link)) {
                        json([
                            'csrf' => $this->security->get_csrf_hash(),
                            'type' => 'error',
                            'message' => lang("error_bad_link"),
                        ]);
                    }

                    if ($quantity < $services_list['min'] || $quantity > $services_list['max']) {
                        json([
                            'csrf' => $this->security->get_csrf_hash(),
                            'type' => 'error',
                            'message' => sprintf(lang("error_limit_quantity"), $services_list['min'], $services_list['max']),
                        ]);
                    }

                    $insert = [
                        'user_id' => $this->user['id'],
                        'type' => $services_list['add_type'],
                        'category_id' => $category,
                        'service_id' => $service,
                        'service_type' => $services_list['type'],
                        'api_provider_id' => $services_list['api_provider_id'],
                        'api_service_id' => $services_list['api_service_id'],
                        'link' => $link,
                        'quantity' => $quantity,
                        'comments' => $comments_package,
                        'charge' => $charge,
                        'created_at' => NOW,
                        'updated_at' => NOW,
                    ];

                    $this->model->insert($this->table_orders, $insert);
                    $this->model->update($this->table_users, ['id' => $this->user['id']], '', false, true, $charge);
                    break;

                case 'custom_comments_package':
                    $this->order_model->validation_form('custom_comments_package');

                    $charge = $services_list['price'];

                    if (isset($this->user['custom_rate']) && $this->user['custom_rate'] > 0) {
                        $charge = $charge - (($charge * $this->user['custom_rate']) / 100);
                    }

                    if (userBalance(logged(), true) < $charge) {
                        json([
                            'csrf' => $this->security->get_csrf_hash(),
                            'type' => 'error',
                            'message' => lang("error_not_balance"),
                        ]);
                    }

                    if (!valid_url($link)) {
                        json([
                            'csrf' => $this->security->get_csrf_hash(),
                            'type' => 'error',
                            'message' => lang("error_bad_link"),
                        ]);
                    }

                    $insert = [
                        'user_id' => $this->user['id'],
                        'type' => $services_list['add_type'],
                        'category_id' => $category,
                        'service_id' => $service,
                        'service_type' => $services_list['type'],
                        'api_provider_id' => $services_list['api_provider_id'],
                        'api_service_id' => $services_list['api_service_id'],
                        'link' => $link,
                        'comments' => $comments_package,
                        'charge' => $charge,
                        'created_at' => NOW,
                        'updated_at' => NOW,
                    ];

                    $this->model->insert($this->table_orders, $insert);
                    $this->model->update($this->table_users, ['id' => $this->user['id']], '', false, true, $charge);
                    break;

                case 'mentions_with_hashtags':
                    $this->order_model->validation_form('mentions_with_hashtags');

                    if (!valid_url($link)) {
                        json([
                            'csrf' => $this->security->get_csrf_hash(),
                            'type' => 'error',
                            'message' => lang("error_bad_link"),
                        ]);
                    }

                    if ($quantity < $services_list['min'] || $quantity > $services_list['max']) {
                        json([
                            'csrf' => $this->security->get_csrf_hash(),
                            'type' => 'error',
                            'message' => sprintf(lang("error_limit_quantity"), $services_list['min'], $services_list['max']),
                        ]);
                    }

                    $insert = [
                        'user_id' => $this->user['id'],
                        'type' => $services_list['add_type'],
                        'category_id' => $category,
                        'service_id' => $service,
                        'service_type' => $services_list['type'],
                        'api_provider_id' => $services_list['api_provider_id'],
                        'api_service_id' => $services_list['api_service_id'],
                        'link' => $link,
                        'quantity' => $quantity,
                        'usernames' => $usernames,
                        'hashtags' => $mentions_with_hashtags,
                        'charge' => $charge,
                        'created_at' => NOW,
                        'updated_at' => NOW,
                    ];

                    $this->model->insert($this->table_orders, $insert);
                    $this->model->update($this->table_users, ['id' => $this->user['id']], '', false, true, $charge);
                    break;

                case 'mentions_custom_list':
                    $this->order_model->validation_form('mentions_custom_list');

                    if (!valid_url($link)) {
                        json([
                            'csrf' => $this->security->get_csrf_hash(),
                            'type' => 'error',
                            'message' => lang("error_bad_link"),
                        ]);
                    }

                    if ($quantity < $services_list['min'] || $quantity > $services_list['max']) {
                        json([
                            'csrf' => $this->security->get_csrf_hash(),
                            'type' => 'error',
                            'message' => sprintf(lang("error_limit_quantity"), $services_list['min'], $services_list['max']),
                        ]);
                    }

                    $insert = [
                        'user_id' => $this->user['id'],
                        'type' => $services_list['add_type'],
                        'category_id' => $category,
                        'service_id' => $service,
                        'service_type' => $services_list['type'],
                        'api_provider_id' => $services_list['api_provider_id'],
                        'api_service_id' => $services_list['api_service_id'],
                        'link' => $link,
                        'usernames' => $usernames_hashtags,
                        'charge' => $charge,
                        'created_at' => NOW,
                        'updated_at' => NOW,
                    ];

                    $this->model->insert($this->table_orders, $insert);
                    $this->model->update($this->table_users, ['id' => $this->user['id']], '', false, true, $charge);
                    break;

                case 'mentions_hashtag':
                    $this->order_model->validation_form('mentions_hashtag');

                    if (!valid_url($link)) {
                        json([
                            'csrf' => $this->security->get_csrf_hash(),
                            'type' => 'error',
                            'message' => lang("error_bad_link"),
                        ]);
                    }

                    if ($quantity < $services_list['min'] || $quantity > $services_list['max']) {
                        json([
                            'csrf' => $this->security->get_csrf_hash(),
                            'type' => 'error',
                            'message' => sprintf(lang("error_limit_quantity"), $services_list['min'], $services_list['max']),
                        ]);
                    }

                    $insert = [
                        'user_id' => $this->user['id'],
                        'type' => $services_list['add_type'],
                        'category_id' => $category,
                        'service_id' => $service,
                        'service_type' => $services_list['type'],
                        'api_provider_id' => $services_list['api_provider_id'],
                        'api_service_id' => $services_list['api_service_id'],
                        'link' => $link,
                        'quantity' => $quantity,
                        'hashtag' => $mentions_with_hashtag,
                        'charge' => $charge,
                        'created_at' => NOW,
                        'updated_at' => NOW,
                    ];

                    $this->model->insert($this->table_orders, $insert);
                    $this->model->update($this->table_users, ['id' => $this->user['id']], '', false, true, $charge);
                    break;

                case 'mentions_user_followers':
                    $this->order_model->validation_form('mentions_user_followers');

                    if (!valid_url($link)) {
                        json([
                            'csrf' => $this->security->get_csrf_hash(),
                            'type' => 'error',
                            'message' => lang("error_bad_link"),
                        ]);
                    }

                    if ($quantity < $services_list['min'] || $quantity > $services_list['max']) {
                        json([
                            'csrf' => $this->security->get_csrf_hash(),
                            'type' => 'error',
                            'message' => sprintf(lang("error_limit_quantity"), $services_list['min'], $services_list['max']),
                        ]);
                    }

                    $insert = [
                        'user_id' => $this->user['id'],
                        'type' => $services_list['add_type'],
                        'category_id' => $category,
                        'service_id' => $service,
                        'service_type' => $services_list['type'],
                        'api_provider_id' => $services_list['api_provider_id'],
                        'api_service_id' => $services_list['api_service_id'],
                        'link' => $link,
                        'quantity' => $quantity,
                        'username' => $username_follower,
                        'charge' => $charge,
                        'created_at' => NOW,
                        'updated_at' => NOW,
                    ];

                    $this->model->insert($this->table_orders, $insert);
                    $this->model->update($this->table_users, ['id' => $this->user['id']], '', false, true, $charge);
                    break;

                case 'mentions_media_likers':
                    $this->order_model->validation_form('mentions_media_likers');

                    if (!valid_url($link)) {
                        json([
                            'csrf' => $this->security->get_csrf_hash(),
                            'type' => 'error',
                            'message' => lang("error_bad_link"),
                        ]);
                    }

                    if (!valid_url($media_url)) {
                        json([
                            'csrf' => $this->security->get_csrf_hash(),
                            'type' => 'error',
                            'message' => lang("error_bad_link"),
                        ]);
                    }

                    if ($quantity < $services_list['min'] || $quantity > $services_list['max']) {
                        json([
                            'csrf' => $this->security->get_csrf_hash(),
                            'type' => 'error',
                            'message' => sprintf(lang("error_limit_quantity"), $services_list['min'], $services_list['max']),
                        ]);
                    }

                    $insert = [
                        'user_id' => $this->user['id'],
                        'type' => $services_list['add_type'],
                        'category_id' => $category,
                        'service_id' => $service,
                        'service_type' => $services_list['type'],
                        'api_provider_id' => $services_list['api_provider_id'],
                        'api_service_id' => $services_list['api_service_id'],
                        'link' => $link,
                        'quantity' => $quantity,
                        'media' => $media_url,
                        'charge' => $charge,
                        'created_at' => NOW,
                        'updated_at' => NOW,
                    ];

                    $this->model->insert($this->table_orders, $insert);
                    $this->model->update($this->table_users, ['id' => $this->user['id']], '', false, true, $charge);
                    break;

                case 'package':
                    $this->order_model->validation_form('package');

                    $charge = $services_list['price'];

                    if (isset($this->user['custom_rate']) && $this->user['custom_rate'] > 0) {
                        $charge = $charge - (($charge * $this->user['custom_rate']) / 100);
                    }

                    if (userBalance(logged(), true) < $charge) {
                        json([
                            'csrf' => $this->security->get_csrf_hash(),
                            'type' => 'error',
                            'message' => lang("error_not_balance"),
                        ]);
                    }

                    if (!valid_url($link)) {
                        json([
                            'csrf' => $this->security->get_csrf_hash(),
                            'type' => 'error',
                            'message' => lang("error_bad_link"),
                        ]);
                    }

                    $insert = [
                        'user_id' => $this->user['id'],
                        'type' => $services_list['add_type'],
                        'category_id' => $category,
                        'service_id' => $service,
                        'service_type' => $services_list['type'],
                        'api_provider_id' => $services_list['api_provider_id'],
                        'api_service_id' => $services_list['api_service_id'],
                        'link' => $link,
                        'charge' => $charge,
                        'created_at' => NOW,
                        'updated_at' => NOW,
                    ];

                    $this->model->insert($this->table_orders, $insert);
                    $this->model->update($this->table_users, ['id' => $this->user['id']], '', false, true, $charge);
                    break;

                case 'comment_likes':
                    $this->order_model->validation_form('comment_likes');

                    if (!valid_url($link)) {
                        json([
                            'csrf' => $this->security->get_csrf_hash(),
                            'type' => 'error',
                            'message' => lang("error_bad_link"),
                        ]);
                    }

                    if ($quantity < $services_list['min'] || $quantity > $services_list['max']) {
                        json([
                            'csrf' => $this->security->get_csrf_hash(),
                            'type' => 'error',
                            'message' => sprintf(lang("error_limit_quantity"), $services_list['min'], $services_list['max']),
                        ]);
                    }

                    $insert = [
                        'user_id' => $this->user['id'],
                        'type' => $services_list['add_type'],
                        'category_id' => $category,
                        'service_id' => $service,
                        'service_type' => $services_list['type'],
                        'api_provider_id' => $services_list['api_provider_id'],
                        'api_service_id' => $services_list['api_service_id'],
                        'link' => $link,
                        'quantity' => $quantity,
                        'username' => $username_follower,
                        'charge' => $charge,
                        'created_at' => NOW,
                        'updated_at' => NOW,
                    ];

                    $this->model->insert($this->table_orders, $insert);
                    $this->model->update($this->table_users, ['id' => $this->user['id']], '', false, true, $charge);
                    break;

                case 'poll':
                    $this->order_model->validation_form('poll');

                    if (!valid_url($link)) {
                        json([
                            'csrf' => $this->security->get_csrf_hash(),
                            'type' => 'error',
                            'message' => lang("error_bad_link"),
                        ]);
                    }

                    if ($quantity < $services_list['min'] || $quantity > $services_list['max']) {
                        json([
                            'csrf' => $this->security->get_csrf_hash(),
                            'type' => 'error',
                            'message' => sprintf(lang("error_limit_quantity"), $services_list['min'], $services_list['max']),
                        ]);
                    }

                    $insert = [
                        'user_id' => $this->user['id'],
                        'type' => $services_list['add_type'],
                        'category_id' => $category,
                        'service_id' => $service,
                        'service_type' => $services_list['type'],
                        'api_provider_id' => $services_list['api_provider_id'],
                        'api_service_id' => $services_list['api_service_id'],
                        'link' => $link,
                        'quantity' => $quantity,
                        'poll_answer_number' => $answer_number,
                        'charge' => $charge,
                        'created_at' => NOW,
                        'updated_at' => NOW,
                    ];

                    $this->model->insert($this->table_orders, $insert);
                    $this->model->update($this->table_users, ['id' => $this->user['id']], '', false, true, $charge);
                    break;

                case 'seo':
                    $this->order_model->validation_form('seo');

                    if (!valid_url($link)) {
                        json([
                            'csrf' => $this->security->get_csrf_hash(),
                            'type' => 'error',
                            'message' => lang("error_bad_link"),
                        ]);
                    }

                    if ($quantity < $services_list['min'] || $quantity > $services_list['max']) {
                        json([
                            'csrf' => $this->security->get_csrf_hash(),
                            'type' => 'error',
                            'message' => sprintf(lang("error_limit_quantity"), $services_list['min'], $services_list['max']),
                        ]);
                    }

                    $insert = [
                        'user_id' => $this->user['id'],
                        'type' => $services_list['add_type'],
                        'category_id' => $category,
                        'service_id' => $service,
                        'service_type' => $services_list['type'],
                        'api_provider_id' => $services_list['api_provider_id'],
                        'api_service_id' => $services_list['api_service_id'],
                        'link' => $link,
                        'quantity' => $quantity,
                        'seo_keywords' => $seo_keywords,
                        'charge' => $charge,
                        'created_at' => NOW,
                        'updated_at' => NOW,
                    ];

                    $this->model->insert($this->table_orders, $insert);
                    $this->model->update($this->table_users, ['id' => $this->user['id']], '', false, true, $charge);
                    break;
            }

            if ($services_list['type'] != 'subscriptions') {
                json([
                    'csrf' => $this->security->get_csrf_hash(),
                    'type' => 'success',
                    'message' => lang("success_order_successfully"),
                ]);
            }
        }
    }

    public function mass_order()
    {
        if (!userLevel(logged(), 'user')) return redirect(base_url());

        if (isset($_POST) && !empty($_POST)) {
            $this->order_model->validation_form('mass_order');

            $mass_order = explode(PHP_EOL, $this->input->post('mass_order'));
            $total_charge = 0;

            for ($i = 0; $i < count($mass_order); $i++) {
                $order = explode('|', $mass_order[$i]);

                $service_id = (int) $order[0];

                $services_list = $this->model->get('*', $this->table_services, ['id' => $service_id, 'status' => '1'], '', '', true);

                if (count($order) > 3 || count($order) < 3) {
                    json([
                        'csrf' => $this->security->get_csrf_hash(),
                        'type' => 'error',
                        'message' => lang("error_mass_order_invalid"),
                    ]);
                }

                $quantity = (int) $order[1];
                $link = $order[2];

                if (empty($services_list)) {
                    json([
                        'csrf' => $this->security->get_csrf_hash(),
                        'type' => 'error',
                        'message' => lang("error_service_not_exists"),
                    ]);
                }

                if ($services_list['type'] != "default") {
                    json([
                        'csrf' => $this->security->get_csrf_hash(),
                        'type' => 'error',
                        'message' => lang("error_not_compatible_mass_order"),
                    ]);
                }

                if ($quantity < $services_list['min'] || $quantity > $services_list['max']) {
                    json([
                        'csrf' => $this->security->get_csrf_hash(),
                        'type' => 'error',
                        'message' => sprintf(lang("error_limit_quantity"), $services_list['min'], $services_list['max']),
                    ]);
                }

                $charge = ($services_list['price'] * $quantity) / 1000;

                if (isset($this->user['custom_rate']) && $this->user['custom_rate'] > 0) {
                    $charge = $charge - (($charge * $this->user['custom_rate']) / 100);
                }

                $insert[] = [
                    'user_id' => $this->user['id'],
                    'type' => $services_list['add_type'],
                    'category_id' => $services_list['category_id'],
                    'service_id' => $services_list['id'],
                    'service_type' => $services_list['type'],
                    'api_provider_id' => $services_list['api_provider_id'],
                    'api_service_id' => $services_list['api_service_id'],
                    'link' => $link,
                    'quantity' => $quantity,
                    'charge' => $charge,
                    'created_at' => NOW,
                    'updated_at' => NOW,

                ];
                $total_charge += $charge;
            }

            if (userBalance(logged(), true) < $total_charge) {
                json([
                    'csrf' => $this->security->get_csrf_hash(),
                    'type' => 'error',
                    'message' => lang("error_not_balance"),
                ]);
            }

            if (!empty($insert)) {
                $this->model->insert_in_batch($this->table_orders, $insert);
                $this->model->update($this->table_users, ['id' => $this->user['id']], '', false, true, $total_charge);

                json([
                    'csrf' => $this->security->get_csrf_hash(),
                    'type' => 'success',
                    'message' => lang("success_order_successfully"),
                ]);
            }
        }
    }
}
