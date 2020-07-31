<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Instamojo_pay extends MY_Controller
{
    protected $table;
    protected $table_users;

    public function __construct()
    {
        parent::__construct();

        if (
            !userLevel(logged(), 'user') && config_payment('instamojo_status', 'value') != 'on'
            && config_payment('instamojo_api_key', 'value') == ''
            && config_payment('instamojo_auth_token', 'value') == ''
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

            if ($amount < config_payment('instamojo_min_payment', 'value') || (config_payment('instamojo_max_payment', 'value') != 0 && $amount > config_payment('instamojo_max_payment', 'value'))) {
                json([
                    'csrf' => $this->security->get_csrf_hash(),
                    'type' => 'error',
                    'message' => sprintf(lang('error_min_max_payment'), configs('currency_symbol', 'value') . config_payment('instamojo_min_payment', 'value'), (config_payment('instamojo_max_payment', 'value') == 0 ? lang('unlimited') : configs('currency_symbol', 'value') . config_payment('instamojo_max_payment', 'value'))),
                ]);
            }

            if (!isset($agree)) {
                json([
                    'csrf' => $this->security->get_csrf_hash(),
                    'type' => 'error',
                    'message' => lang('error_confirm_conditions_paying'),
                ]);
            }

            set_session('instamojo_protected', substr(hash('sha256', mt_rand() . microtime() . logged()), 0, 20));

            // Library Instamojo
            $this->load->library('instamojoapi/instamojores');
            $response = $this->instamojores->access(lang('adding_account_balance'), $amount, dataUser(logged(), 'email'), base_url('instamojo/response'));

            json([
                'csrf' => $this->security->get_csrf_hash(),
                'type' => 'success',
                'link' => $response['longurl'],
            ]);
        }
    }

    public function status()
    {
        $instamojo_protected = session('instamojo_protected');
        $payment_id = $this->input->get('payment_id', true);
        $payment_request_id = $this->input->get('payment_request_id', true);

        if (isset($instamojo_protected) && isset($payment_id) && isset($payment_request_id)) {
            // Library Instamojo
            $this->load->library('instamojoapi/instamojores');
            $response = $this->instamojores->status_transaction($payment_request_id);

            if ($response['status'] == 'Completed') {
                // Add Transaction Instamojo
                $this->model->insert($this->table, [
                    'user_id' => dataUser(logged(), 'id'),
                    'transaction_id' => $payment_request_id,
                    'payment_method' => 'instamojo',
                    'amount' => $response['payments'][0]['amount'],
                    'status' => 'paid',
                    'created_at' => NOW,
                    'updated_at' => NOW,
                ]);

                $this->model->update($this->table_users, ['id' => dataUser(logged(), 'id')], '', true, false, $response['payments'][0]['amount']);

                if (notification('payment_notification', 'value') == 1) {
                    $subject = (email_tpl('payments_notification_subject', 'value') == '') ? email_template('payments_notification')->subject : email_tpl('payments_notification_subject', 'value');
                    $email_content = (email_tpl('payments_notification_content', 'value') == '') ? email_template('payments_notification')->content : email_tpl('payments_notification_content', 'value');
                    $email_content = str_replace("{{username}}", dataUser(logged(), 'username'), $email_content);
                    $email_content = str_replace("{{transaction_id}}", $payment_request_id, $email_content);
                    $email_content = str_replace("{{method_payment}}", 'Instamojo', $email_content);

                    email_send(dataUser(logged(), 'email'), $subject, $email_content);
                }

                $data = [
                    'type' => 'instamojo',
                    'payee_name' => $response['payments'][0]['buyer_name'],
                    'payee_transaction' => $payment_request_id,
                    'payee_amount_total' => $response['payments'][0]['amount']
                ];

                view('layouts/auth_header', ['title' => "Instamojo"]);
                view('panel/admin/management/payments/payment_completed', $data);
                view('layouts/auth_footer');
            } else if ($response['status'] == 'Pending') {
                $data = ['type' => 'instamojo'];

                // Add Transaction Instamojo
                $this->model->insert($this->table, [
                    'user_id' => dataUser(logged(), 'id'),
                    'transaction_id' => $payment_request_id,
                    'payment_method' => 'instamojo',
                    'amount' => $response['payments'][0]['amount'],
                    'status' => 'pending',
                    'created_at' => NOW,
                    'updated_at' => NOW,
                ]);

                view('layouts/auth_header', ['title' => "Instamojo"]);
                view('panel/admin/management/payments/payment_pendent', $data);
                view('layouts/auth_footer');
            } else {
                $data = ['type' => 'instamojo'];

                view('layouts/auth_header', ['title' => "Instamojo"]);
                view('panel/admin/management/payments/payment_canceled', $data);
                view('layouts/auth_footer');
            }

            // Clear Sessions
            unset_session('instamojo_protected');
        } else {
            redirect(base_url('add/balance'));
        }
    }
}
