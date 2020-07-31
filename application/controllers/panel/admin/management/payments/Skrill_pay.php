<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Skrill_pay extends MY_Controller
{
    protected $table;
    protected $table_users;

    public function __construct()
    {
        parent::__construct();

        if (!userLevel(logged(), 'user') && config_payment('skrill_status', 'value') != 'on' && config_payment('skrill_email', 'value') == '') return redirect(base_url());

        $this->table = TABLE_TRANSACTION_LOGS;
        $this->table_users = TABLE_USERS;

        $this->load->library("skrill");
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

            if ($amount < config_payment('skrill_min_payment', 'value') || (config_payment('skrill_max_payment', 'value') != 0 && $amount > config_payment('skrill_max_payment', 'value'))) {
                json([
                    'csrf' => $this->security->get_csrf_hash(),
                    'type' => 'error',
                    'message' => sprintf(lang('error_min_max_payment'), configs('currency_symbol', 'value') . config_payment('skrill_min_payment', 'value'), (config_payment('skrill_max_payment', 'value') == 0 ? lang('unlimited') : configs('currency_symbol', 'value') . config_payment('skrill_max_payment', 'value'))),
                ]);
            }

            if (!isset($agree)) {
                json([
                    'csrf' => $this->security->get_csrf_hash(),
                    'type' => 'error',
                    'message' => lang('error_confirm_conditions_paying'),
                ]);
            }

            $transaction = create_random_api_key(20);
            $skrill_redirect = $this->skrill->access($amount, $transaction);

            set_session('payee_name', dataUser(logged(), 'name'));
            set_session('payee_amount_total', $amount);

            $this->model->insert($this->table, [
                'user_id' => dataUser(logged(), 'id'),
                'transaction_id' => $transaction,
                'payment_method' => 'skrill',
                'amount' => $amount,
                'status' => 'pending',
                'created_at' => NOW,
                'updated_at' => NOW,
            ]);

            if (notification('payment_notification', 'value') == 1) {
                $subject = (email_tpl('payments_notification_subject', 'value') == '') ? email_template('payments_notification')->subject : email_tpl('payments_notification_subject', 'value');
                $email_content = (email_tpl('payments_notification_content', 'value') == '') ? email_template('payments_notification')->content : email_tpl('payments_notification_content', 'value');
                $email_content = str_replace("{{username}}", dataUser(logged(), 'username'), $email_content);
                $email_content = str_replace("{{transaction_id}}", $transaction, $email_content);
                $email_content = str_replace("{{method_payment}}", 'Skrill', $email_content);

                email_send(dataUser(logged(), 'email'), $subject, $email_content);
            }

            json([
                'csrf' => $this->security->get_csrf_hash(),
                'type' => 'success',
                'link' => $skrill_redirect,
            ]);
        }
    }

    public function success()
    {
        if (session('payee_name') && session('payee_amount_total')) {
            $transaction_id = $this->input->get('transaction_id', true);

            $data = [
                'type' => 'skrill',
                'payee_name' => session('payee_name'),
                'payee_transaction' => $transaction_id,
                'payee_amount_total' => session('payee_amount_total'),
            ];

            view('layouts/auth_header', ['title' => 'Skrill']);
            view('panel/admin/management/payments/payment_completed', $data);
            view('layouts/auth_footer');

            // Clear Sessions
            unset_session('payee_name');
            unset_session('payee_amount_total');
        } else {
            redirect(base_url('add/balance'));
        }
    }

    public function status()
    {
        $this->skrill->getStatus();
    }

    public function cancel()
    {
        $data = ['type' => 'skrill'];

        view('layouts/auth_header', ['title' => "Skrill"]);
        view('panel/admin/management/payments/payment_canceled', $data);
        view('layouts/auth_footer');
    }
}
