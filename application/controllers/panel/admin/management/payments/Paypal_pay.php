<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Paypal_pay extends MY_Controller
{
    protected $table;
    protected $table_users;

    public function __construct()
    {
        parent::__construct();

        if (
            !userLevel(logged(), 'user') && config_payment('paypal_status', 'value') != 'on'
            && config_payment('paypal_client_id', 'value') == ''
            && config_payment('paypal_client_secret', 'value') == ''
        ) return redirect(base_url());

        $this->table = TABLE_TRANSACTION_LOGS;
        $this->table_users = TABLE_USERS;
    }

    public function index()
    {
        if (isset($_POST) && !empty($_POST)) {
            $this->load->library('paypal');

            $amount = $this->input->post('amount', true);
            $agree = $this->input->post('agree', true);

            if (empty($amount)) {
                json([
                    'csrf' => $this->security->get_csrf_hash(),
                    'type' => 'error',
                    'message' => sprintf(lang('error_empty_field'), lang('amount')),
                ]);
            }

            if ($amount < config_payment('paypal_min_payment', 'value') || (config_payment('paypal_max_payment', 'value') != 0 && $amount > config_payment('paypal_max_payment', 'value'))) {
                json([
                    'csrf' => $this->security->get_csrf_hash(),
                    'type' => 'error',
                    'message' => sprintf(lang('error_min_max_payment'), configs('currency_symbol', 'value') . config_payment('paypal_min_payment', 'value'), (config_payment('paypal_max_payment', 'value') == 0 ? lang('unlimited') : configs('currency_symbol', 'value') . config_payment('paypal_max_payment', 'value'))),
                ]);
            }

            if (!isset($agree)) {
                json([
                    'csrf' => $this->security->get_csrf_hash(),
                    'type' => 'error',
                    'message' => lang('error_confirm_conditions_paying'),
                ]);
            }

            $data = (object) [
                'amount' => $amount,
                'redirectUrls' => base_url('paypal/processing'),
                'cancelUrl' => base_url('paypal/cancel'),
            ];
            $curl = $this->paypal->access($data);

            json([
                'csrf' => $this->security->get_csrf_hash(),
                'type' => 'success',
                'link' => $curl,
            ]);
        }
    }

    public function completed()
    {
        $paymentId = $this->input->get('paymentId', true);
        $Token = $this->input->get('token', true);
        $PayerID = $this->input->get('PayerID', true);

        if (isset($paymentId) && $paymentId != "" && isset($Token) && $Token != "" && isset($PayerID) && $PayerID != "") {
            $this->load->library('paypal');

            $paypal = $this->paypal->execute_payment($paymentId, $PayerID);

            if (!empty($paypal) && in_array($paypal->getState(), ['approved', 'created'])) {
                $transaction = $paypal->getTransactions()[0];
                $resources = $transaction->getRelatedResources()[0];
                $sale = $resources->getSale();
                $amount = $sale->amount->total - $sale->transaction_fee->value;
                $sale_state = $sale->getState();
                $status = ($sale_state == 'completed' ? 'paid' : 'pending');
                $sale_id = $sale->getId();

                if ($sale_state == 'completed') {
                    set_flashdata('payee_name', $transaction->item_list->shipping_address->recipient_name);
                    set_flashdata('payee_transaction', $sale_id);
                    set_flashdata('payee_amount', $amount);
                    set_flashdata('payee_amount_total', $sale->amount->total);

                    $this->model->insert($this->table, [
                        'user_id' => dataUser(logged(), 'id'),
                        'transaction_id' => $sale_id,
                        'payment_method' => 'paypal',
                        'amount' => $amount,
                        'status' => $status,
                        'created_at' => NOW,
                        'updated_at' => NOW,
                    ]);

                    if (notification('payment_notification', 'value') == 1) {
                        $subject = (email_tpl('payments_notification_subject', 'value') == '') ? email_template('payments_notification')->subject : email_tpl('payments_notification_subject', 'value');
                        $email_content = (email_tpl('payments_notification_content', 'value') == '') ? email_template('payments_notification')->content : email_tpl('payments_notification_content', 'value');
                        $email_content = str_replace("{{username}}", dataUser(logged(), 'username'), $email_content);
                        $email_content = str_replace("{{transaction_id}}", $sale_id, $email_content);
                        $email_content = str_replace("{{method_payment}}", 'PayPal', $email_content);

                        email_send(dataUser(logged(), 'email'), $subject, $email_content);
                    }

                    $this->model->update($this->table_users, ['id' => dataUser(logged(), 'id')], '', true, false, $amount);

                    redirect(base_url('paypal/success'));
                } else {
                    redirect(base_url('paypal/cancel'));
                }
            } else {
                redirect(base_url('paypal/cancel'));
            }
        } else {
            redirect(base_url('add/balance'));
        }
    }

    public function success()
    {
        $name = flashdata('payee_name');
        $payee_transaction = flashdata('payee_transaction');
        $payee_amount = flashdata('payee_amount');
        $payee_amount_total = flashdata('payee_amount_total');

        if ($name && $payee_transaction && $payee_amount && $payee_amount_total) {
            $data = [
                'type' => 'paypal',
                'payee_name' => $name,
                'payee_transaction' => $payee_transaction,
                'payee_amount' => $payee_amount,
                'payee_amount_total' => $payee_amount_total,
            ];

            view('layouts/auth_header', ['title' => lang('title_payment_paypal')]);
            view('panel/admin/management/payments/payment_completed', $data);
            view('layouts/auth_footer');
        } else {
            redirect(base_url('add/balance'));
        }
    }

    public function cancel()
    {
        $Token = $this->input->get('token', true);

        if (isset($Token) && $Token != "") {
            $data = ['type' => 'paypal'];

            view('layouts/auth_header', ['title' => lang('title_payment_paypal')]);
            view('panel/admin/management/payments/payment_canceled', $data);
            view('layouts/auth_footer');
        } else {
            redirect(base_url('add/balance'));
        }
    }
}
