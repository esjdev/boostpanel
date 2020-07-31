<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Payumoney_pay extends MY_Controller
{
    protected $table;
    protected $table_users;

    public function __construct()
    {
        parent::__construct();

        if (
            !userLevel(logged(), 'user') && config_payment('payumoney_status', 'value') != 'on'
            && config_payment('payumoney_merchant_key', 'value') == ''
            && config_payment('payumoney_merchant_salt', 'value') == ''
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

            if ($amount < config_payment('payumoney_min_payment', 'value') || (config_payment('payumoney_max_payment', 'value') != 0 && $amount > config_payment('payumoney_max_payment', 'value'))) {
                json([
                    'csrf' => $this->security->get_csrf_hash(),
                    'type' => 'error',
                    'message' => sprintf(lang('error_min_max_payment'), configs('currency_symbol', 'value') . config_payment('payumoney_min_payment', 'value'), (config_payment('payumoney_max_payment', 'value') == 0 ? lang('unlimited') : configs('currency_symbol', 'value') . config_payment('payumoney_max_payment', 'value'))),
                ]);
            }

            if (!isset($agree)) {
                json([
                    'csrf' => $this->security->get_csrf_hash(),
                    'type' => 'error',
                    'message' => lang('error_confirm_conditions_paying'),
                ]);
            }

            set_session('payumoney_amount', $amount);
            set_session('payumoney_protected', token_rand());

            json([
                'csrf' => $this->security->get_csrf_hash(),
                'type' => 'success',
                'link' => base_url('payumoney/create_payment'),
            ]);
        }
    }

    public function create_payment()
    {
        $amount = session('payumoney_amount');
        $protected = session('payumoney_protected');

        if (isset($amount) && isset($protected)) {
            view('layouts/auth_header', ['title' => "PayUmoney"]);
            view('panel/addbalance/form_payumoney');
            view('layouts/auth_footer');
        } else {
            redirect(base_url('add/balance'));
        }
    }

    public function proccess()
    {
        if (isset($_POST) && !empty($_POST)) {
            $product_info = $this->input->post('product_info', true);
            $mobile_number = $this->input->post('mobile_number', true);

            if (empty($product_info) || empty($mobile_number)) {
                json([
                    'csrf' => $this->security->get_csrf_hash(),
                    'type' => 'error',
                    'message' => lang('error_fill_all_fields'),
                ]);
            }

            set_session('product_info', $product_info);
            set_session('mobile_number', $mobile_number);

            json([
                'csrf' => $this->security->get_csrf_hash(),
                'type' => 'success',
                'base_url' => base_url('payumoney/confirm'),
            ]);
        }
    }

    public function confirm_pay()
    {
        $amount = session('payumoney_amount');
        $product_info = session('product_info');
        $mobile_number = session('mobile_number');
        $protected = session('payumoney_protected');

        if (isset($amount) && isset($product_info) && isset($mobile_number) && isset($protected)) {
            $customer_name = dataUser(logged(), 'name');
            $customer_email = dataUser(logged(), 'email');

            //PayUmoney details
            $MERCHANT_KEY = config_payment('payumoney_merchant_key', 'value');
            $SALT = config_payment('payumoney_merchant_salt', 'value');

            $txnid = "payu_" . substr(hash('sha256', mt_rand() . microtime() . logged()), 0, 20);

            $hashstring = $MERCHANT_KEY . '|' . $txnid . '|' . $amount . '|' . $product_info . '|' . $customer_name . '|' . $customer_email . '|||||||||||' . $SALT;
            $hash = strtolower(hash('sha512', $hashstring));

            $success = base_url('payumoney/status');
            $fail = base_url('payumoney/status');
            $cancel = base_url('payumoney/status');

            $data = [
                'key' => $MERCHANT_KEY,
                'tid' => $txnid,
                'hash' => $hash,
                'amount' => $amount,
                'name' => $customer_name,
                'productinfo' => $product_info,
                'email' => $customer_email,
                'phone' => $mobile_number,
                'action' => (config_payment('payumoney_environment', 'value') == 'Sandbox' ? 'https://test.payu.in' : 'https://secure.payu.in'),
                'success' => $success,
                'failure' => $fail,
                'cancel' => $cancel
            ];

            view('layouts/auth_header', ['title' => "PayUmoney"]);
            view('panel/addbalance/confirm_form_payumoney', $data);
            view('layouts/auth_footer');
        } else {
            redirect(base_url('add/balance'));
        }
    }

    public function status()
    {
        $status = $this->input->post('status', true);

        // Sessions
        $amount_session = session('payumoney_amount');
        $product_info = session('product_info');
        $mobile_number = session('mobile_number');
        $protected = session('payumoney_protected');

        if (empty($status)) redirect(base_url('add/balance'));

        $name = $this->input->post('firstname', true);
        $email = $this->input->post('email', true);
        $amount = $this->input->post('amount', true);
        $txnid = $this->input->post('txnid', true);
        $posted_hash = $this->input->post('hash', true);

        if ($status == 'success' & isset($amount_session) && isset($product_info) && isset($mobile_number) && isset($protected)) {
            $data = [
                'type' => 'payumoney',
                'payee_name' => $name,
                'payee_amount_total' => $amount,
                'payee_transaction' => $txnid,
                'posted_hash' => $posted_hash
            ];

            // Clear Sessions
            unset_session('payumoney_amount');
            unset_session('product_info');
            unset_session('mobile_number');
            unset_session('payumoney_protected');

            $user = $this->model->get('*', TABLE_USERS, ['email' => $email], '', '', true);
            $this->model->insert($this->table, [
                'user_id' => $user['id'],
                'transaction_id' => $txnid,
                'payment_method' => 'payumoney',
                'amount' => $amount,
                'status' => 'paid',
                'created_at' => NOW,
                'updated_at' => NOW,
            ]);

            $this->model->update($this->table_users, ['id' => $user['id']], '', true, false, $amount);

            if (notification('payment_notification', 'value') == 1) {
                $subject = (email_tpl('payments_notification_subject', 'value') == '') ? email_template('payments_notification')->subject : email_tpl('payments_notification_subject', 'value');
                $email_content = (email_tpl('payments_notification_content', 'value') == '') ? email_template('payments_notification')->content : email_tpl('payments_notification_content', 'value');
                $email_content = str_replace("{{username}}", $user['username'], $email_content);
                $email_content = str_replace("{{transaction_id}}", $txnid, $email_content);
                $email_content = str_replace("{{method_payment}}", 'PayUmoney', $email_content);

                email_send($user['email'], $subject, $email_content);
            }

            view('layouts/auth_header', ['title' => "PayUmoney"]);
            view('panel/admin/management/payments/payment_completed', $data);
            view('layouts/auth_footer');
        } else if ($status == 'cancel' || $status == 'fail') {
            $data = ['type' => 'payumoney'];

            // Clear Sessions
            unset_session('payumoney_amount');
            unset_session('product_info');
            unset_session('mobile_number');
            unset_session('payumoney_protected');

            view('layouts/auth_header', ['title' => "PayUmoney"]);
            view('panel/admin/management/payments/payment_canceled', $data);
            view('layouts/auth_footer');
        } else {
            redirect(base_url('add/balance'));
        }
    }
}
