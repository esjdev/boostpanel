<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Razorpay_pay extends MY_Controller
{
    protected $table;
    protected $table_users;

    public function __construct()
    {
        parent::__construct();

        if (
            !userLevel(logged(), 'user') && config_payment('razorpay_status', 'value') != 'on'
            && config_payment('razorpay_key_id', 'value') == '' && config_payment('razorpay_key_secret', 'value') == ''
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
                    'message' => sprintf(lang('error_empty_field'), lang('amount'))
                ]);
            }

            if ($amount < config_payment('razorpay_min_payment', 'value') || (config_payment('razorpay_max_payment', 'value') != 0 && $amount > config_payment('razorpay_max_payment', 'value'))) {
                json([
                    'csrf' => $this->security->get_csrf_hash(),
                    'type' => 'error',
                    'message' => sprintf(lang('error_min_max_payment'), configs('currency_symbol', 'value') . config_payment('razorpay_min_payment', 'value'), (config_payment('razorpay_max_payment', 'value') == 0 ? lang('unlimited') : configs('currency_symbol', 'value') . config_payment('razorpay_max_payment', 'value')))
                ]);
            }

            if (!isset($agree)) {
                json([
                    'csrf' => $this->security->get_csrf_hash(),
                    'type' => 'error',
                    'message' => lang('error_confirm_conditions_paying')
                ]);
            }

            $this->load->library('razorpay');
            $pay = $this->razorpay->access($amount, logged());

            set_session('protected_razorpay', 'razorpay_' . token_rand());

            json([
                'csrf' => $this->security->get_csrf_hash(),
                'type' => 'success',
                'link' => $pay->short_url
            ]);
        }
    }

    public function redirect()
    {
        $protected_razorpay = session('protected_razorpay');
        $razorpay_payment_id = $this->input->get('razorpay_payment_id', true);
        $razorpay_invoice_id = $this->input->get('razorpay_invoice_id', true);
        $razorpay_invoice_receipt = $this->input->get('razorpay_invoice_receipt', true);
        $razorpay_invoice_status = $this->input->get('razorpay_invoice_status', true);
        $razorpay_signature = $this->input->get('razorpay_signature', true);

        if (
            isset($protected_razorpay) && isset($razorpay_payment_id) && isset($razorpay_invoice_id) && isset($razorpay_invoice_receipt) && isset($razorpay_invoice_status) && isset($razorpay_signature)
        ) {
            $this->load->library('razorpay');
            $payment_id = $this->razorpay->fetch_payment($razorpay_payment_id);
            $order_id = $this->razorpay->fetch_order($payment_id->order_id);

            $amount = currency_format($order_id->amount / 100);

            if ($order_id->status == 'paid') {
                $this->model->insert($this->table, [
                    'user_id' => dataUser($payment_id->email, 'id'),
                    'transaction_id' => $order_id->id,
                    'payment_method' => 'razorpay',
                    'amount' => $amount,
                    'status' => 'paid',
                    'created_at' => NOW,
                    'updated_at' => NOW,
                ]);

                $this->model->update($this->table_users, ['id' => dataUser($payment_id->email, 'id')], '', true, false, $amount); // Add Balance User

                if (notification('payment_notification', 'value') == 1) {
                    $subject = (email_tpl('payments_notification_subject', 'value') == '') ? email_template('payments_notification')->subject : email_tpl('payments_notification_subject', 'value');
                    $email_content = (email_tpl('payments_notification_content', 'value') == '') ? email_template('payments_notification')->content : email_tpl('payments_notification_content', 'value');
                    $email_content = str_replace("{{username}}", dataUser($payment_id->email, 'username'), $email_content);
                    $email_content = str_replace("{{transaction_id}}", $order_id, $email_content);
                    $email_content = str_replace("{{method_payment}}", 'RazorPay', $email_content);

                    email_send(logged(), $subject, $email_content);
                }

                $data = [
                    'type' => 'razorpay',
                    'payee_name' => dataUser($payment_id->email, 'name'),
                    'payee_transaction' => $order_id->id,
                    'payee_amount_total' => $amount
                ];

                view('layouts/auth_header', ['title' => "RazorPay"]);
                view('panel/admin/management/payments/payment_completed', $data);
                view('layouts/auth_footer');
            } else {
                $data = ['type' => 'razorpay'];

                view('layouts/auth_header', ['title' => "RazorPay"]);
                view('panel/admin/management/payments/payment_canceled', $data);
                view('layouts/auth_footer');
            }

            unset_session('protected_razorpay');
        } else {
            redirect(base_url('add/balance'));
        }
    }
}
