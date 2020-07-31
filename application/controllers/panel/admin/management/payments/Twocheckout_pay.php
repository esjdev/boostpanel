<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Twocheckout_pay extends MY_Controller
{
    protected $table;
    protected $table_users;

    public function __construct()
    {
        parent::__construct();

        if (
            !userLevel(logged(), 'user') && config_payment('2checkout_status', 'value') != 'on'
            && config_payment('2checkout_publishable_key', 'value') == ''
            && config_payment('2checkout_private_key', 'value') == ''
            && config_payment('2checkout_seller_id', 'value') == ''
        ) return redirect(base_url());

        $this->table = TABLE_TRANSACTION_LOGS;
        $this->table_users = TABLE_USERS;
    }

    public function index()
    {
        if (isset($_POST) && !empty($_POST)) {
            $amount = $this->input->post('amount', true);
            $agree = $this->input->post('agree', true);

            if (empty($amount)) {
                json([
                    'csrf' => $this->security->get_csrf_hash(),
                    'type' => 'error',
                    'message' => sprintf(lang('error_empty_field'), lang('amount')),
                ]);
            }

            if ($amount < config_payment('2checkout_min_payment', 'value') || (config_payment('2checkout_max_payment', 'value') != 0 && $amount > config_payment('2checkout_max_payment', 'value'))) {
                json([
                    'csrf' => $this->security->get_csrf_hash(),
                    'type' => 'error',
                    'message' => sprintf(lang('error_min_max_payment'), configs('currency_symbol', 'value') . config_payment('2checkout_min_payment', 'value'), (config_payment('2checkout_max_payment', 'value') == 0 ? lang('unlimited') : configs('currency_symbol', 'value') . config_payment('2checkout_max_payment', 'value'))),
                ]);
            }

            if (!isset($agree)) {
                json([
                    'csrf' => $this->security->get_csrf_hash(),
                    'type' => 'error',
                    'message' => lang('error_confirm_conditions_paying'),
                ]);
            }

            set_session('twocheckout_amount', $amount);
            set_session('twocheckout_protected', token_rand());

            json([
                'csrf' => $this->security->get_csrf_hash(),
                'type' => 'success',
                'link' => base_url('twocheckout/create_payment'),
            ]);
        }
    }

    public function create_payment()
    {
        $amount = session('twocheckout_amount');
        $protected = session('twocheckout_protected');

        if (isset($amount) && isset($protected)) {
            view('layouts/auth_header', ['title' => lang('payment_twocheckout_title')]);
            view('panel/addbalance/form_twocheckout');
            view('layouts/auth_footer');
        } else {
            redirect(base_url('add/balance'));
        }
    }

    public function success()
    {
        $amount = session('twocheckout_amount');
        $token = $this->input->post('token', true);

        if (!empty($token)) {
            $this->load->library('twocheckoutapi');

            $country = $this->input->post('country', true);
            $address = $this->input->post('address', true);
            $city = $this->input->post('city', true);
            $state = $this->input->post('state', true);
            $zipcode = $this->input->post('zipcode', true);

            $buyer_info = [
                "name" => dataUser(logged(), 'name'),
                "addrLine1" => $address,
                "city" => $city,
                "state" => $state,
                "zipCode" => $zipcode,
                "country" => $country,
                "email" => dataUser(logged(), 'email'),
                "phoneNumber" => '555-555-5555',
            ];

            $orderID = create_random_api_key(14);

            $data_charge = [
                "merchantOrderId" => $orderID,
                "token" => $token,
                "currency" => configs('currency_code', 'value'),
                "total" => $amount,
                "billingAddr" => $buyer_info,
            ];
            $result = $this->twocheckoutapi->access($data_charge);

            if (!empty($result) && $result['response']['responseCode'] == 'APPROVED') {
                unset_session('twocheckout_amount');

                set_flashdata('payee_name', $result['response']['billingAddr']['name']);
                set_flashdata('payee_transaction', $result['response']['transactionId']);
                set_flashdata('payee_amount_total', $result['response']['total']);

                $this->model->insert($this->table, [
                    'user_id' => dataUser(logged(), 'id'),
                    'transaction_id' => $result['response']['transactionId'],
                    'payment_method' => 'twocheckout',
                    'amount' => $result['response']['total'],
                    'status' => 'paid',
                    'created_at' => NOW,
                    'updated_at' => NOW,
                ]);

                if (notification('payment_notification', 'value') == 1) {
                    $subject = (email_tpl('payments_notification_subject', 'value') == '') ? email_template('payments_notification')->subject : email_tpl('payments_notification_subject', 'value');
                    $email_content = (email_tpl('payments_notification_content', 'value') == '') ? email_template('payments_notification')->content : email_tpl('payments_notification_content', 'value');
                    $email_content = str_replace("{{username}}", dataUser(logged(), 'username'), $email_content);
                    $email_content = str_replace("{{transaction_id}}", $result['response']['transactionId'], $email_content);
                    $email_content = str_replace("{{method_payment}}", '2Checkout', $email_content);

                    email_send(dataUser(logged(), 'email'), $subject, $email_content);
                }

                $this->model->update($this->table_users, ['id' => dataUser(logged(), 'id')], '', true, false, $result['response']['total']);
                unset_session('twocheckout_protected');

                redirect(base_url('twocheckout/completed'));
            } else {
                redirect(base_url('twocheckout/cancel'));
            }
        } else {
            redirect(base_url('add/balance'));
        }
    }

    public function completed()
    {
        $name = flashdata('payee_name');
        $payee_transaction = flashdata('payee_transaction');
        $payee_amount_total = flashdata('payee_amount_total');

        if ($name && $payee_transaction && $payee_amount_total) {
            $data = [
                'type' => 'twocheckout',
                'payee_name' => $name,
                'payee_transaction' => $payee_transaction,
                'payee_amount_total' => $payee_amount_total,
            ];

            view('layouts/auth_header', ['title' => lang('payment_twocheckout_title')]);
            view('panel/admin/management/payments/payment_completed', $data);
            view('layouts/auth_footer');
        } else {
            redirect(base_url('add/balance'));
        }
    }

    public function cancel()
    {
        $data = ['type' => 'twocheckout'];

        view('layouts/auth_header', ['title' => lang('lang_stripe_title')]);
        view('panel/admin/management/payments/payment_canceled', $data);
        view('layouts/auth_footer');
    }
}
