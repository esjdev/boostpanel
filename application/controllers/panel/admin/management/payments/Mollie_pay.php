<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Mollie_pay extends MY_Controller
{
    protected $table;
    protected $table_users;

    public function __construct()
    {
        parent::__construct();

        if (
            !userLevel(logged(), 'user') && config_payment('mollie_status', 'value') != 'on'
            && config_payment('mollie_api_key', 'value') == ''
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

            if ($amount < config_payment('mollie_min_payment', 'value') || (config_payment('mollie_max_payment', 'value') != 0 && $amount > config_payment('mollie_max_payment', 'value'))) {
                json([
                    'csrf' => $this->security->get_csrf_hash(),
                    'type' => 'error',
                    'message' => sprintf(lang('error_min_max_payment'), configs('currency_symbol', 'value') . config_payment('mollie_min_payment', 'value'), (config_payment('mollie_max_payment', 'value') == 0 ? lang('unlimited') : configs('currency_symbol', 'value') . config_payment('mollie_max_payment', 'value')))
                ]);
            }

            if (!isset($agree)) {
                json([
                    'csrf' => $this->security->get_csrf_hash(),
                    'type' => 'error',
                    'message' => lang('error_confirm_conditions_paying')
                ]);
            }

            $this->load->library('mollie');
            $payments = $this->mollie->access($amount);

            set_session('mollie_amount', $amount);
            set_session('mollie_id', $payments->id);

            json([
                'csrf' => $this->security->get_csrf_hash(),
                'type' => 'success',
                'link' => $payments->getCheckoutUrl()
            ]);
        }
    }

    public function success()
    {
        $amount = session('mollie_amount');
        $mollie_id = session('mollie_id');

        if (isset($amount) && isset($mollie_id)) {
            $this->load->library('mollie');
            $payments = $this->mollie->retrieving_payments($mollie_id);

            if ($payments->status == 'paid') {
                // Add Transaction Mollie
                $this->model->insert($this->table, [
                    'user_id' => dataUser(logged(), 'id'),
                    'transaction_id' => $mollie_id,
                    'payment_method' => 'mollie',
                    'amount' => $amount,
                    'status' => 'paid',
                    'created_at' => NOW,
                    'updated_at' => NOW,
                ]);

                $order = $this->model->get('*', $this->table, ['transaction_id' => $mollie_id], '', '', true);
                $user = $this->model->get('*', $this->table_users, ['id' => $order['user_id']], '', '', true);

                $this->model->update($this->table_users, ['id' => $order['user_id']], '', true, false, $amount); // Add Balance User

                if (notification('payment_notification', 'value') == 1) {
                    $subject = (email_tpl('payments_notification_subject', 'value') == '') ? email_template('payments_notification')->subject : email_tpl('payments_notification_subject', 'value');
                    $email_content = (email_tpl('payments_notification_content', 'value') == '') ? email_template('payments_notification')->content : email_tpl('payments_notification_content', 'value');
                    $email_content = str_replace("{{username}}", $user['username'], $email_content);
                    $email_content = str_replace("{{transaction_id}}", $mollie_id, $email_content);
                    $email_content = str_replace("{{method_payment}}", 'Mollie', $email_content);

                    email_send($user['email'], $subject, $email_content);
                }

                $data = [
                    'type' => 'mollie',
                    'payee_name' => $user['name'],
                    'payee_transaction' => $mollie_id,
                    'payee_amount_total' => $amount
                ];

                view('layouts/auth_header', ['title' => "Mollie"]);
                view('panel/admin/management/payments/payment_completed', $data);
                view('layouts/auth_footer');
            } else if ($payments->status == 'open' || $payments->status == 'pending') {
                $data = ['type' => 'mollie'];

                // Add Transaction Mollie
                $this->model->insert($this->table, [
                    'user_id' => dataUser(logged(), 'id'),
                    'transaction_id' => $mollie_id,
                    'payment_method' => 'mollie',
                    'amount' => $amount,
                    'status' => 'pending',
                    'created_at' => NOW,
                    'updated_at' => NOW,
                ]);

                view('layouts/auth_header', ['title' => "Mollie"]);
                view('panel/admin/management/payments/payment_pendent', $data);
                view('layouts/auth_footer');
            } else if (in_array($payments->status, ['canceled', 'failed'])) {
                $data = ['type' => 'mollie'];

                view('layouts/auth_header', ['title' => "Mollie"]);
                view('panel/admin/management/payments/payment_canceled', $data);
                view('layouts/auth_footer');
            }

            // Clear Sessions
            unset_session('mollie_amount');
            unset_session('mollie_id');
        } else {
            redirect(base_url('add/balance'));
        }
    }
}
