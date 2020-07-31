<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Cron extends MY_Controller
{
    protected $table_orders;
    protected $table_api_providers;
    protected $table_logs;
    protected $table_users;
    protected $table_services;
    protected $table_transaction;

    public function __construct()
    {
        parent::__construct();
        $security = config('security_token');

        if ($this->input->get('security', true) != $security || DEMO_VERSION == true) return die(lang("error_access_not_allowed"));

        $this->table_orders = TABLE_ORDERS;
        $this->table_api_providers = TABLE_API_PROVIDERS;
        $this->table_logs = TABLE_LOGS;
        $this->table_users = TABLE_USERS;
        $this->table_services = TABLE_SERVICES;
        $this->table_transaction = TABLE_TRANSACTION_LOGS;
    }

    public function index($type)
    {
        $orders_list = $this->model->fetch('*', $this->table_orders, ['type' => 'api', 'api_order_id' => '0', 'api_service_id !=' => '0', 'api_provider_id !=' => '0', 'status' => 'pending', 'service_type !=' => 'subscriptions']);
        $subscriptions_list = $this->model->fetch('*', $this->table_orders, ['type' => 'api', 'order_response_id_sub' => '0', 'api_service_id !=' => '0', 'api_provider_id !=' => '0', 'status_sub' => 'Active', 'service_type' => 'subscriptions']);

        switch ($type) {
            case 'orders':
                if (!empty($orders_list)) {
                    foreach ($orders_list as $value) {
                        $api = $this->model->get('*', $this->table_api_providers, ['id' => $value->api_provider_id], '', '', true);

                        $type_services = ($value->type == 'api' ? ($api['type_parameter'] == 'api_token' ? 'package' : 'service') : '');

                        $replace_comments = str_replace(',', '\n', $value->comments);
                        $replace_usernames = str_replace(',', '\n', $value->usernames);
                        $replace_hashtags = str_replace(',', '\n', $value->hashtags);
                        $replace_seo_keywords = str_replace(',', '\n', $value->seo_keywords);

                        if (!empty($api)) {
                            switch ($value->service_type) {
                                case 'default':
                                    $data_array = [
                                        $api['type_parameter'] => $api['key'],
                                        'action' => 'add',
                                        $type_services => $value->api_service_id,
                                        'link' => $value->link,
                                        'quantity' => $value->quantity
                                    ];

                                    if ($value->is_drip_feed == 1) {
                                        $data_array['quantity'] = $value->dripfeed_quantity;
                                        $data_array['runs'] = $value->runs;
                                        $data_array['interval'] = $value->interval;
                                    } else {
                                        $data_array['quantity'] = $value->quantity;
                                    }
                                    break;

                                case 'custom_data':
                                    $data_array = [
                                        $api['type_parameter'] => $api['key'],
                                        'action' => 'add',
                                        $type_services => $value->api_service_id,
                                        'link' => $value->link,
                                        'quantity' => $value->quantity,
                                        'custom_data' => $replace_comments,
                                    ];
                                    break;

                                case 'custom_comments':
                                case 'custom_comments_package':
                                    $data_array = [
                                        $api['type_parameter'] => $api['key'],
                                        'action' => 'add',
                                        $type_services => $value->api_service_id,
                                        'link' => $value->link,
                                        'comments' => $replace_comments,
                                    ];
                                    break;

                                case 'mentions_with_hashtags':
                                    $data_array = [
                                        $api['type_parameter'] => $api['key'],
                                        'action' => 'add',
                                        $type_services => $value->api_service_id,
                                        'link' => $value->link,
                                        'quantity' => $value->quantity,
                                        'usernames' => $replace_usernames,
                                        'hashtags' => $replace_hashtags,
                                    ];
                                    break;

                                case 'mentions_custom_list':
                                    $data_array = [
                                        $api['type_parameter'] => $api['key'],
                                        'action' => 'add',
                                        $type_services => $value->api_service_id,
                                        'link' => $value->link,
                                        'usernames' => $replace_usernames,
                                    ];
                                    break;

                                case 'mentions_hashtag':
                                    $data_array = [
                                        $api['type_parameter'] => $api['key'],
                                        'action' => 'add',
                                        $type_services => $value->api_service_id,
                                        'link' => $value->link,
                                        'quantity' => $value->quantity,
                                        'hashtag' => $value->hashtag,
                                    ];
                                    break;

                                case 'comment_likes':
                                case 'mentions_user_followers':
                                    $data_array = [
                                        $api['type_parameter'] => $api['key'],
                                        'action' => 'add',
                                        $type_services => $value->api_service_id,
                                        'link' => $value->link,
                                        'quantity' => $value->quantity,
                                        'username' => $value->username,
                                    ];
                                    break;

                                case 'mentions_media_likers':
                                    $data_array = [
                                        $api['type_parameter'] => $api['key'],
                                        'action' => 'add',
                                        $type_services => $value->api_service_id,
                                        'link' => $value->link,
                                        'quantity' => $value->quantity,
                                        'media' => $value->media,
                                    ];
                                    break;

                                case 'package':
                                    $data_array = [
                                        $api['type_parameter'] => $api['key'],
                                        'action' => 'add',
                                        $type_services => $value->api_service_id,
                                        'link' => $value->link,
                                    ];
                                    break;

                                case 'poll':
                                    $data_array = [
                                        $api['type_parameter'] => $api['key'],
                                        'action' => 'add',
                                        $type_services => $value->api_service_id,
                                        'link' => $value->link,
                                        'quantity' => $value->quantity,
                                        'answer_number' => $value->poll_answer_number,
                                    ];
                                    break;

                                case 'seo':
                                    $data_array = [
                                        $api['type_parameter'] => $api['key'],
                                        'action' => 'add',
                                        $type_services => $value->api_service_id,
                                        'link' => $value->link,
                                        'quantity' => $value->quantity,
                                        'keywords' => $replace_seo_keywords,
                                    ];
                                    break;
                            }

                            $item = api_connect($api['url'], $data_array, true);

                            if (!empty($item['order']) && $item['order'] != "") {
                                $this->model->update($this->table_orders, ['id' => $value->id, 'type' => 'api', 'status' => 'pending', 'api_order_id' => '0'], ['api_order_id' => $item['order']]);
                            }

                            if (isset($item['error']) || isset($item['errors'])) {
                                add_log("Error Order", $value->id, "<b>" . lang("error") . " API " . $api['name'] . "</b> <span class='badge badge-warning text-white'>(ID Order: " . $value->id . ")</span><br><br>" . ($api['type_parameter'] == 'api_token' ? $item['errors'] : $item['error']));
                            }
                        }
                    }
                }
                break;

            case 'subscriptions':
                if (!empty($subscriptions_list)) {
                    foreach ($subscriptions_list as $value) {
                        $api = $this->model->get('*', $this->table_api_providers, ['id' => $value->api_provider_id], '', '', true);

                        $type_services = ($value->type == 'api' ? ($api['type_parameter'] == 'api_token' ? 'package' : 'service') : '');

                        if (!empty($api)) {
                            $data_array = [
                                $api['type_parameter'] => $api['key'],
                                'action' => 'add',
                                $type_services => $value->api_service_id,
                                'username' => $value->username,
                                'min' => $value->min_sub,
                                'max' => $value->max_sub,
                                'posts' => $value->posts_sub,
                                'delay' => $value->delay_sub,
                            ];

                            if (!empty($value->expiry_sub) && $value->expiry_sub != "") {
                                $data_array['expiry'] = $value->expiry_sub;
                            }

                            $item = api_connect($api['url'], $data_array, true);

                            if (!empty($item['order']) && $item['order'] != "") {
                                $this->model->update($this->table_orders, ['id' => $value->id, 'type' => 'api', 'status_sub' => 'Active', 'order_response_id_sub' => '0'], ['order_response_id_sub' => $item['order']]);
                            }

                            if (isset($item['error']) || isset($item['errors'])) {
                                add_log("Error Sub", $value->id, "<b>" . lang("error") . " API " . $api['name'] . "</b> <span class='badge badge-warning text-white'>(ID Order: " . $value->id . ")</span><br><br>" . ($api['type_parameter'] == 'api_token' ? $item['errors'] : $item['error']));
                            }
                        }
                    }
                }
                break;

            case 'status_orders':
                $orders_status = $this->model->fetch('*', $this->table_orders, "(status = 'processing' or status = 'inprogress' or status = 'pending') AND type = 'api' AND api_order_id != 0 AND api_service_id != 0 AND api_provider_id != 0 AND service_type != 'subscriptions'");

                if (!empty($orders_status)) {
                    foreach ($orders_status as $value) {
                        $services_list = $this->model->get('*', $this->table_services, ['id' => $value->service_id], '', '', true);

                        $api = $this->model->get('*', $this->table_api_providers, ['id' => $value->api_provider_id], '', '', true);

                        if (!empty($api)) {
                            $data_array = [
                                ($api['type_parameter'] == 'key' ? 'key' : 'api_token') => $api['key'],
                                'action' => 'status',
                                'order' => $value->api_order_id,
                            ];

                            $item = api_connect($api['url'], $data_array, true);

                            if (isset($item['error']) || isset($item['errors'])) {
                                add_log("Error Status Order", $value->id, "<b>" . lang("error") . " API " . $api['name'] . "</b> <span class='badge badge-warning text-white'>(ID Order: " . $value->id . ")</span><br><br>" . ($api['type_parameter'] == 'api_token' ? $item['errors'] : $item['error']));
                            }

                            if (in_array($item['status'], ['Completed', 'completed', 'Complete', 'complete'])) {
                                $status = 'completed';
                            }

                            if (in_array($item['status'], ['Processing', 'processing'])) {
                                $status = 'processing';
                            }

                            if (in_array($item['status'], ['In progress', 'Inprogress', 'inprogress'])) {
                                $status = 'inprogress';
                            }

                            if (in_array($item['status'], ['Pending', 'pending'])) {
                                $status = 'pending';
                            }

                            if (in_array($item['status'], ['Partial', 'partial'])) {
                                $status = 'partial';
                            }

                            if (in_array($item['status'], ['Cancelled', 'canceled', 'Canceled'])) {
                                $status = 'canceled';
                            }

                            if (in_array($item['status'], ['Refunded', 'refunded'])) {
                                $status = 'refunded';
                            }

                            $data_update = [
                                'status' => $status,
                                'start_counter' => ($item['start_count'] == null ? 0 : $item['start_count']),
                                'remains' => ($item['remains'] == 0 || $item['remains'] == null ? 0 : $item['remains']),
                            ];

                            $this->model->update($this->table_orders, ['api_order_id' => $value->api_order_id], $data_update);

                            if (in_array($item['status'], ['Cancelled', 'canceled', 'Canceled', 'Refunded', 'refunded'])) {
                                $this->model->update($this->table_users, ['id' => $value->user_id], '', true, false, $value->charge);
                                $this->model->update($this->table_orders, ['api_order_id' => $value->api_order_id], ['charge' => '0']);
                            }

                            if (in_array($item['status'], ['Partial', 'partial']) && $item['remains'] <= $value->quantity) {
                                $real_charge = ($services_list['price'] * $item['remains']) / 1000;
                                $refundAmount = $value->charge - $real_charge;

                                $this->model->update($this->table_users, ['id' => $value->user_id], '', true, false, $refundAmount);
                                $this->model->update($this->table_orders, ['api_order_id' => $value->api_order_id], ['charge' => $refundAmount]);
                            }
                        }
                    }
                }
                break;

            case 'status_subscriptions':
                $subscriptions_status = $this->model->fetch('*', $this->table_orders, "(status_sub = 'Active' or status_sub = 'Paused') AND type = 'api' AND order_response_id_sub != 0 AND api_service_id != 0 AND api_provider_id != 0 AND service_type = 'subscriptions'");

                if (!empty($subscriptions_status)) {
                    foreach ($subscriptions_status as $value) {
                        $services_list = $this->model->get('*', $this->table_services, ['id' => $value->service_id], '', '', true);

                        $api = $this->model->get('*', $this->table_api_providers, ['id' => $value->api_provider_id, 'type_parameter' => 'key'], '', '', true);

                        if (!empty($api)) {
                            $data_array = [
                                'key' => $api['key'],
                                'action' => 'status',
                                'order' => $value->order_response_id_sub,
                            ];

                            $item = api_connect($api['url'], $data_array, true);

                            if (isset($item['error']) || isset($item['errors'])) {
                                add_log("Error Status Sub", $value->id, "<b>" . lang("error") . " API " . $api['name'] . "</b> <span class='badge badge-warning text-white'>(ID Order: " . $value->id . ")</span><br><br>" . ($api['type_parameter'] == 'api_token' ? $item['errors'] : $item['error']));
                            }

                            if (!empty($item['orders'])) {
                                foreach ($item['orders'] as $key => $id_order) {
                                    $check_order = $this->model->counts($this->table_orders, ['api_order_id' => $id_order, 'service_type' => 'default']);

                                    $data_status_order = [
                                        'key' => $api['key'],
                                        'action' => 'status',
                                        'order' => $id_order,
                                    ];

                                    $status_order = api_connect($api['url'], $data_status_order, true);

                                    if (in_array($status_order['status'], ['Completed', 'completed', 'Complete', 'complete'])) {
                                        $status = 'completed';
                                        $charge = $status_order['charge'];

                                        if (dataUserId($value->user_id, 'custom_rate') > 0) {
                                            $charge = $charge - (($charge * dataUserId($value->user_id, 'custom_rate')) / 100);
                                        }

                                        $this->model->update($this->table_users, ['id' => $value->user_id], '', false, true, $charge);
                                    }

                                    if (in_array($status_order['status'], ['Processing', 'processing'])) {
                                        $status = 'processing';
                                    }

                                    if (in_array($status_order['status'], ['In progress', 'Inprogress', 'inprogress'])) {
                                        $status = 'inprogress';
                                    }

                                    if (in_array($status_order['status'], ['Pending', 'pending'])) {
                                        $status = 'pending';
                                    }

                                    if (in_array($status_order['status'], ['Partial', 'partial'])) {
                                        $status = 'partial';

                                        if ($status_order['remains'] <= $value->max_sub) {
                                            $real_charge = ($status_order['remains'] * $item['posts'] * $services_list['price']) / 1000;
                                            $refundAmount = dataUserId($value->user_id, 'balance') - $real_charge;

                                            $this->model->update($this->table_users, ['id' => $value->user_id], '', false, true, $refundAmount);
                                        }
                                    }

                                    if (in_array($status_order['status'], ['Cancelled', 'canceled', 'Canceled'])) {
                                        $status = 'canceled';

                                        $real_charge = ($status_order['remains'] * $item['posts'] * $services_list['price']) / 1000;
                                        $this->model->update($this->table_users, ['id' => $value->user_id], '', true, false, $real_charge);
                                    }

                                    if (in_array($status_order['status'], ['Refunded', 'refunded'])) {
                                        $status = 'refunded';

                                        $real_charge = ($status_order['remains'] * $item['posts'] * $services_list['price']) / 1000;
                                        $this->model->update($this->table_users, ['id' => $value->user_id], '', true, false, $real_charge);
                                    }

                                    if ($item['posts'] != $check_order && $value->posts_sub != $check_order) {
                                        $insert = [
                                            'user_id' => $value->user_id,
                                            'type' => 'api',
                                            'category_id' => $value->category_id,
                                            'service_id' => $value->service_id,
                                            'service_type' => 'default',
                                            'api_provider_id' => $value->api_provider_id,
                                            'api_service_id' => $value->api_service_id,
                                            'api_order_id' => $id_order,
                                            'order_response_id_sub' => $value->order_response_id_sub,
                                            'order_response_posts_sub' => 1,
                                            'link' => "https://www.instagram.com/" . $value->username,
                                            'quantity' => ($status_order['remains'] > 0) ? $status_order['remains'] : 0,
                                            'charge' => $status_order['charge'],
                                            'start_counter' => $status_order['start_count'],
                                            'remains' => $status_order['remains'],
                                            'status' => $status,
                                            'created_at' => NOW,
                                            'updated_at' => NOW,
                                        ];

                                        $posts_sub = $item['posts'] - $check_order;

                                        for ($i = 0; $i < $posts_sub; $i++) {
                                            if ($i == $key) {
                                                $this->model->insert($this->table_orders, $insert);
                                            }
                                        }

                                        $this->model->update($this->table_orders, "(id = {$value->id} AND (status_sub = 'Active' or status_sub = 'Paused') AND service_type = 'subscriptions')", ['status_sub' => $item['status'], 'order_response_posts_sub' => $item['posts'], 'updated_at' => NOW]);
                                    }

                                    if (in_array($status_order['status'], ['Processing', 'processing', 'In progress', 'Inprogress', 'inprogress', 'Pending', 'pending'])) {
                                        $this->model->update($this->table_orders, ['api_order_id' => $check_order['api_order_id']], ['status' => $status]);
                                    }
                                }
                            }
                        }
                    }
                }
                break;

            case 'payments_status':
                if (config_payment('pagseguro_status', 'value') == 'on' && config_payment('pagseguro_token', 'value') != '' && config_payment('pagseguro_email', 'value') != '') {
                    $transactions_list_ps = $this->model->fetch('*', $this->table_transaction, 'payment_method = "pagseguro" AND status = "pending" or status = "processing"');

                    if (!empty($transactions_list_ps)) {
                        $this->load->library('pagseguro');

                        foreach ($transactions_list_ps as $value_pg) {
                            $transaction = $this->pagseguro->transaction_reference($value_pg->transaction_id);

                            if ($transaction->resultsInThisPage != 0) {
                                switch ($transaction->transactions->transaction->status) {
                                    case '1':
                                        $this->model->update($this->table_transaction, ['transaction_id' => $value_pg->transaction_id], ['status' => 'pending', 'updated_at' => NOW]);
                                        break;

                                    case '2':
                                        $this->model->update($this->table_transaction, ['transaction_id' => $value_pg->transaction_id], ['status' => 'processing', 'updated_at' => NOW]);
                                        break;

                                    case '3':
                                    case '4':
                                        $amount = convert_exchange('BRL', configs("currency_code", "value"), $transaction->transactions->transaction->netAmount);
                                        $this->model->update($this->table_transaction, ['transaction_id' => $value_pg->transaction_id], ['status' => 'paid', 'updated_at' => NOW]);
                                        $this->model->update($this->table_users, ['id' => $value_pg->user_id], '', true, false, $amount);
                                        break;
                                }
                            }
                        }
                    }
                }

                if (config_payment('mercadopago_status', 'value') == 'on' && config_payment('mercadopago_access_token', 'value') != '') {
                    $transactions_list_mp = $this->model->fetch('*', $this->table_transaction, 'payment_method = "mercadopago" AND status = "pending" or status = "processing"');

                    if (!empty($transactions_list_mp)) {
                        $this->load->library('mercadopago');

                        foreach ($transactions_list_mp as $value_mp) {
                            $transaction = $this->mercadopago->transaction_reference($value_mp->transaction_id);

                            if ($transaction['paging']['total'] != 0) {
                                switch ($transaction['results'][0]['status']) {
                                    case 'pending':
                                        $this->model->update($this->table_transaction, ['transaction_id' => $value_mp->transaction_id], ['status' => 'pending', 'updated_at' => NOW]);
                                        break;

                                    case 'approved':
                                        $amount = (empty($transaction['results'][0]['fee_details']) || $transaction['results'][0]['fee_details'] == '' ? $transaction['results'][0]['transaction_amount'] : $transaction['results'][0]['transaction_details']['net_received_amount']);
                                        $amount = convert_exchange('BRL', configs("currency_code", "value"), $amount);

                                        $this->model->update($this->table_transaction, ['transaction_id' => $value_mp->transaction_id], ['status' => 'paid', 'updated_at' => NOW]);
                                        $this->model->update($this->table_users, ['id' => $value_mp->user_id], '', true, false, $amount);
                                        break;

                                    case 'in_process':
                                        $this->model->update($this->table_transaction, ['transaction_id' => $value_mp->transaction_id], ['status' => 'processing', 'updated_at' => NOW]);
                                        break;

                                    case 'rejected':
                                    case 'cancelled':
                                        $this->model->update($this->table_transaction, ['transaction_id' => $value_mp->transaction_id], ['status' => 'canceled', 'updated_at' => NOW]);
                                        break;
                                }
                            }
                        }
                    }
                }

                if (config_payment('coinpayments_status', 'value') == 'on' && config_payment('coinpayments_public_key', 'value') != '' && config_payment('coinpayments_private_key', 'value') != '') {
                    $transactions_list_cp = $this->model->fetch('*', $this->table_transaction, 'payment_method = "coinpayments" AND status = "pending" or status = "processing"');

                    if (!empty($transactions_list_cp)) {
                        $this->load->library('coinpayments');

                        foreach ($transactions_list_cp as $value_cp) {
                            $transaction = $this->coinpayments->getTransactionInfo($value_cp->transaction_id);

                            if ($transaction['error'] == 'ok') {
                                switch ($transaction['result']['status']) {
                                    case ($transaction['result']['status'] >= '100'):
                                        $this->model->update($this->table_transaction, ['transaction_id' => $value_cp->transaction_id], ['status' => 'paid', 'updated_at' => NOW]);
                                        $this->model->update($this->table_users, ['id' => $value_cp->user_id], '', true, false, $value_cp->amount);
                                        break;

                                    case '0':
                                        $this->model->update($this->table_transaction, ['transaction_id' => $value_cp->transaction_id], ['status' => 'pending', 'updated_at' => NOW]);
                                        break;

                                    case '1':
                                    case '2':
                                        $this->model->update($this->table_transaction, ['transaction_id' => $value_cp->transaction_id], ['status' => 'processing', 'updated_at' => NOW]);
                                        break;

                                    case '-1':
                                        $this->model->update($this->table_transaction, ['transaction_id' => $value_cp->transaction_id], ['status' => 'canceled', 'updated_at' => NOW]);
                                        break;
                                }
                            }
                        }
                    }
                }

                if (config_payment('paytm_status', 'value') == 'on' && config_payment('paytm_merchant_key', 'value') != '' && config_payment('paytm_merchant_mid', 'value') != '' && config_payment('paytm_merchant_website', 'value') != '') {
                    $transactions_list_paytm = $this->model->fetch('*', $this->table_transaction, 'payment_method = "paytm" AND status = "pending"');

                    if (!empty($transactions_list_paytm)) {
                        $this->load->helper('paytm_encdec');

                        $paytmParams = [];
                        $paytmParams["MID"] = config_payment('paytm_merchant_mid', 'value');

                        foreach ($transactions_list_paytm as $value_ptm) {
                            $paytmParams["ORDERID"] = $value_ptm->transaction_id;

                            $checksum = getChecksumFromArray($paytmParams, config_payment('paytm_merchant_key', 'value'));
                            $paytmParams["CHECKSUMHASH"] = $checksum;
                            $post_data = json_encode($paytmParams, JSON_UNESCAPED_SLASHES);

                            $PAYTM_STATUS_URL_TEST = 'https://securegw-stage.paytm.in/order/status'; // ENVIRONMENT Test
                            $PAYTM_STATUS_URL_PROD = 'https://securegw.paytm.in/order/status'; // ENVIRONMENT Prod

                            if (config_payment('paytm_environment', 'value') == 'Sandbox') {
                                $url = $PAYTM_STATUS_URL_TEST;
                            } else {
                                $url = $PAYTM_STATUS_URL_PROD;
                            }

                            $ch = curl_init($url);
                            curl_setopt($ch, CURLOPT_POST, 1);
                            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
                            $response = curl_exec($ch);
                            $response = json_decode($response);

                            if ($response->STATUS == 'TXN_SUCCESS') {
                                $this->model->update($this->table_users, ['id' => $value_ptm->user_id], '', true, false, $value_ptm->amount);
                                $this->model->update($this->table_transaction, ['transaction_id' => $value_ptm->transaction_id], ['status' => 'paid']);
                            } else {
                                $this->model->update($this->table_transaction, ['transaction_id' => $value_ptm->transaction_id], ['status' => 'canceled']);
                            }
                        }
                    }
                }

                if (config_payment('mollie_status', 'value') == 'on' && config_payment('mollie_api_key', 'value') != '') {
                    $transactions_list_mollie = $this->model->fetch('*', $this->table_transaction, 'payment_method = "mollie" AND status = "pending"');

                    if (!empty($transactions_list_mollie)) {
                        $this->load->library('mollie');

                        foreach ($transactions_list_mollie as $value_mollie) {
                            $response = $this->mollie->retrieving_payments($value_mollie->transaction_id);

                            if ($response->status == 'paid') {
                                $this->model->update($this->table_users, ['id' => $value_mollie->user_id], '', true, false, $value_mollie->amount);
                                $this->model->update($this->table_transaction, ['transaction_id' => $value_mollie->transaction_id], ['status' => 'paid']);
                            } else {
                                $this->model->update($this->table_transaction, ['transaction_id' => $value_mollie->transaction_id], ['status' => 'canceled']);
                            }
                        }
                    }
                }

                if (config_payment('instamojo_status', 'value') == 'on' && config_payment('instamojo_api_key', 'value') != '' && config_payment('instamojo_auth_token', 'value') != '') {
                    $transactions_list_instamojo = $this->model->fetch('*', $this->table_transaction, 'payment_method = "instamojo" AND status = "pending"');

                    if (!empty($transactions_list_instamojo)) {
                        $this->load->library('instamojoapi/instamojores');

                        foreach ($transactions_list_instamojo as $value_instamojo) {
                            $response = $this->instamojores->status_transaction($value_instamojo->transaction_id);

                            if ($response['status'] == 'Completed') {
                                $this->model->update($this->table_users, ['id' => $value_instamojo->user_id], '', true, false, $value_instamojo->amount);
                                $this->model->update($this->table_transaction, ['transaction_id' => $value_instamojo->transaction_id], ['status' => 'paid']);
                            } else {
                                $this->model->update($this->table_transaction, ['transaction_id' => $value_instamojo->transaction_id], ['status' => 'canceled']);
                            }
                        }
                    }
                }
                break;
        }
    }
}
