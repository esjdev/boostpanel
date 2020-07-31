<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Stripe_pay extends MY_Controller
{
    protected $table;
    protected $table_users;

    public function __construct()
    {
        parent::__construct();

        if (
            !userLevel(logged(), 'user') && config_payment('stripe_status', 'value') != 'on'
            && config_payment('stripe_secret_key', 'value') == ''
            && config_payment('stripe_publishable_key', 'value') == ''
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

            if ($amount < config_payment('stripe_min_payment', 'value') || (config_payment('stripe_max_payment', 'value') != 0 && $amount > config_payment('stripe_max_payment', 'value'))) {
                json([
                    'csrf' => $this->security->get_csrf_hash(),
                    'type' => 'error',
                    'message' => sprintf(lang('error_min_max_payment'), configs('currency_symbol', 'value') . config_payment('stripe_min_payment', 'value'), (config_payment('stripe_max_payment', 'value') == 0 ? lang('unlimited') : configs('currency_symbol', 'value') . config_payment('stripe_max_payment', 'value'))),
                ]);
            }

            if (!isset($agree)) {
                json([
                    'csrf' => $this->security->get_csrf_hash(),
                    'type' => 'error',
                    'message' => lang('error_confirm_conditions_paying'),
                ]);
            }

            set_session('amount', $amount);
            set_session('protected', token_rand());

            json([
                'csrf' => $this->security->get_csrf_hash(),
                'type' => 'success',
                'link' => base_url('stripe/create_payment'),
            ]);
        }
    }

    public function create_payment()
    {
        $amount = session('amount');
        $protected = session('protected');

        if (isset($amount) && isset($protected)) {
            view('layouts/auth_header', ['title' => lang('lang_stripe_title')]);
            view('panel/addbalance/form_stripe');
            view('layouts/auth_footer');
        } else {
            redirect(base_url('add/balance'));
        }
    }

    public function success()
    {
        $amount = session('amount');
        $token = $this->input->post('stripeToken', true);

        if (!empty($token)) {
            $this->load->library('stripe');

            // Buyer info
            $buyer_info = array(
                "source" => $token,
                "email" => logged(),
            );

            //Add Customer to Stripe
            $customer = $this->stripe->customer_create($buyer_info);

            $data_charge = [
                'customer' => $customer->id,
                'amount' => $amount * 100,
                'currency' => strtolower(configs('currency_code', 'value')),
                'description' => lang('adding_account_balance') . ' - ' . configs('app_title', 'value'),
            ];

            //Charge a Credit or a Debit Card
            $result = $this->stripe->access($data_charge);

            if (!empty($result) && $result->paid == true && $result->status == 'succeeded') {
                $transaction = $this->stripe->list_transaction($result->balance_transaction);
                $money_fee = currency_format($transaction->net / 100);
                $total_amount = currency_format($result->amount / 100);

                unset_session('amount');

                set_flashdata('payee_name', dataUser(logged(), 'name'));
                set_flashdata('payee_transaction', $result->id);
                set_flashdata('payee_amount', $money_fee);
                set_flashdata('payee_amount_total', $total_amount);

                $this->model->insert($this->table, [
                    'user_id' => dataUser(logged(), 'id'),
                    'transaction_id' => $result->id,
                    'payment_method' => 'stripe',
                    'amount' => $total_amount,
                    'status' => 'paid',
                    'created_at' => NOW,
                    'updated_at' => NOW,
                ]);

                if (notification('payment_notification', 'value') == 1) {
                    $subject = (email_tpl('payments_notification_subject', 'value') == '') ? email_template('payments_notification')->subject : email_tpl('payments_notification_subject', 'value');
                    $email_content = (email_tpl('payments_notification_content', 'value') == '') ? email_template('payments_notification')->content : email_tpl('payments_notification_content', 'value');
                    $email_content = str_replace("{{username}}", dataUser(logged(), 'username'), $email_content);
                    $email_content = str_replace("{{transaction_id}}", $result->id, $email_content);
                    $email_content = str_replace("{{method_payment}}", 'Stripe', $email_content);

                    email_send(dataUser(logged(), 'email'), $subject, $email_content);
                }

                $this->model->update($this->table_users, ['id' => dataUser(logged(), 'id')], '', true, false, $money_fee);
                unset_session('protected');

                redirect(base_url('stripe/completed'));
            } else {
                redirect(base_url('stripe/cancel'));
            }
        } else {
            redirect(base_url('add/balance'));
        }
    }

    public function completed()
    {
        $name = flashdata('payee_name');
        $payee_transaction = flashdata('payee_transaction');
        $payee_amount = flashdata('payee_amount');
        $payee_amount_total = flashdata('payee_amount_total');

        if ($name && $payee_transaction && $payee_amount && $payee_amount_total) {
            $data = [
                'type' => 'stripe',
                'payee_name' => $name,
                'payee_transaction' => $payee_transaction,
                'payee_amount' => $payee_amount,
                'payee_amount_total' => $payee_amount_total,
            ];

            view('layouts/auth_header', ['title' => lang('lang_stripe_title')]);
            view('panel/admin/management/payments/payment_completed', $data);
            view('layouts/auth_footer');
        } else {
            redirect(base_url('add/balance'));
        }
    }

    public function cancel()
    {
        $data = ['type' => 'stripe'];

        view('layouts/auth_header', ['title' => lang('lang_stripe_title')]);
        view('panel/admin/management/payments/payment_canceled', $data);
        view('layouts/auth_footer');
    }
}
