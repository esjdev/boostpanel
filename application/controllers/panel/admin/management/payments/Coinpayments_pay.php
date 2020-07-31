<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Coinpayments_pay extends MY_Controller
{
    protected $table;
    protected $table_users;

    public function __construct()
    {
        parent::__construct();

        if (
            !userLevel(logged(), 'user') && config_payment('coinpayments_status', 'value') != 'on'
            && config_payment('coinpayments_public_key', 'value') == ''
            && config_payment('coinpayments_private_key', 'value') == ''
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

            if ($amount < config_payment('coinpayments_min_payment', 'value') || (config_payment('coinpayments_max_payment', 'value') != 0 && $amount > config_payment('coinpayments_max_payment', 'value'))) {
                json([
                    'csrf' => $this->security->get_csrf_hash(),
                    'type' => 'error',
                    'message' => sprintf(lang('error_min_max_payment'), configs('currency_symbol', 'value') . config_payment('coinpayments_min_payment', 'value'), (config_payment('coinpayments_max_payment', 'value') == 0 ? lang('unlimited') : configs('currency_symbol', 'value') . config_payment('coinpayments_max_payment', 'value'))),
                ]);
            }

            if (!isset($agree)) {
                json([
                    'csrf' => $this->security->get_csrf_hash(),
                    'type' => 'error',
                    'message' => lang('error_confirm_conditions_paying'),
                ]);
            }

            set_session('coinpayments_amount', $amount);
            set_session('coinpayments_protected', token_rand());

            json([
                'csrf' => $this->security->get_csrf_hash(),
                'type' => 'success',
                'link' => base_url('coinpayments/create_payment'),
            ]);
        }
    }

    public function create_payment()
    {
        $amount = session('coinpayments_amount');
        $protected = session('coinpayments_protected');

        if (isset($amount) && isset($protected)) {
            view('layouts/auth_header', ['title' => "CoinPayments"]);
            view('panel/addbalance/form_coinpayments');
            view('layouts/auth_footer');
        } else {
            redirect(base_url('add/balance'));
        }
    }

    public function success()
    {
        if (isset($_POST) && !empty($_POST)) {
            $amount = session('coinpayments_amount');
            $type_coin = $this->input->post('type_coin', true);

            if (isset($amount)) {
                if ($type_coin == 'no_select') {
                    json([
                        'csrf' => $this->security->get_csrf_hash(),
                        'type' => 'error',
                        'message' => lang('choose_coin'),
                    ]);
                }

                $this->load->library('coinpayments');

                $data = (object) [
                    'amount' => $amount,
                    'type_currency' => $type_coin,
                    'buyer_name' => dataUser(logged(), 'name'),
                    'dwqdqw' => 'dwqdwq',
                ];

                $transaction = $this->coinpayments->access($data);

                if ($transaction['error'] != 'ok') {
                    add_log("Error CoinPayments", 'error_coinpayments', $transaction['error']);

                    json([
                        'csrf' => $this->security->get_csrf_hash(),
                        'type' => 'error',
                        'message' => lang('error_payment_not_purchase'),
                    ]);
                }

                if ($transaction['error'] == 'ok') {
                    set_flashdata('payee_type_coin', $type_coin);
                    set_flashdata('payee_name', dataUser(logged(), 'name'));
                    set_flashdata('payee_transaction', $transaction['result']['txn_id']);
                    set_flashdata('payee_amount', $amount);
                    set_flashdata('payee_address_coin', $transaction['result']['address']);
                    set_flashdata('payee_amount_in_coin', $transaction['result']['amount']);
                    set_flashdata('payee_qrcode', $transaction['result']['qrcode_url']);
                    set_flashdata('payee_timeout', $transaction['result']['timeout']);

                    $this->model->insert($this->table, [
                        'user_id' => dataUser(logged(), 'id'),
                        'transaction_id' => $transaction['result']['txn_id'],
                        'payment_method' => 'coinpayments',
                        'amount' => $amount,
                        'status' => 'pending',
                        'created_at' => NOW,
                        'updated_at' => NOW,
                    ]);

                    $last_id = $this->db->insert_id();

                    // Send to Email
                    email_send(dataUser(logged(), 'email'), "CoinPayments - " . lang('select_option_order') . " (" . $last_id . ")", "
                    <center>
                        <img src='" . set_image('coinpayments.png') . "' width='300'>
                        <div style='font-size:16px;'>
                            <b>" . lang('hello') . ", " . dataUser(logged(), 'name') . ".</b><br>
                            " . lang('success_your_order_success') . "<br><br>

                            <b>" . lang('input_transaction_id') . ":</b> " . $transaction['result']['txn_id'] . "<br>
                            <b>" . lang('amount_paid') . ":</b> " . currency_format($amount) . " " . configs('currency_code', 'value') . "<br>
                        </div>

                        <h5 style='width:400px;font-size:23px;background-color:#3a3f44;padding:2px;color:#fff;'>" . lang('total_amount_to_send') . "</h5>

                        <div style='font-size:16px;'>
                            <b style='color:red;'>" . lang('amount') . "</b><br>
                            " . $transaction['result']['amount'] . " (" . $type_coin . ")<br><br>

                            <b style='color:red;'>" . lang('send_to_address') . "</b><br>
                            " . $transaction['result']['address'] . "<br><br>

                            <b>" . strtoupper(lang('or')) . " QRCODE</b><br>
                            <img src='" . $transaction['result']['qrcode_url'] . "'><br>

                            <h5 class='font-size:14px;font-weight:bold;'>" . sprintf(lang('you_have_time_send_payment'), gmdate("H:i:s", $transaction['result']['timeout'])) . "</h5><br>
                            <small>Automatic email, please do not reply.</small>
                        </div>
                    </center>");

                    unset_session('coinpayments_protected');

                    json([
                        'csrf' => $this->security->get_csrf_hash(),
                        'type' => 'success',
                        'link' => base_url('coinpayments/completed'),
                    ]);
                }

                json([
                    'csrf' => $this->security->get_csrf_hash(),
                    'type' => 'error',
                    'message' => lang('error_transaction_been_failed'),
                ]);
            }
        }
    }

    public function completed()
    {
        $payee_type_coin = flashdata('payee_type_coin');
        $name = flashdata('payee_name');
        $payee_transaction = flashdata('payee_transaction');
        $payee_amount = flashdata('payee_amount');
        $payee_address_coin = flashdata('payee_address_coin');
        $payee_amount_in_coin = flashdata('payee_amount_in_coin');
        $payee_qrcode = flashdata('payee_qrcode');
        $payee_timeout = flashdata('payee_timeout');

        if ($payee_type_coin && $name && $payee_transaction && $payee_amount && $payee_address_coin && $payee_amount_in_coin && $payee_qrcode && $payee_timeout) {
            unset_session('coinpayments_amount');

            $data = [
                'type' => 'coinpayments',
                'payee_type_coin' => $payee_type_coin,
                'payee_name' => $name,
                'payee_transaction' => $payee_transaction,
                'payee_amount' => $payee_amount,
                'payee_address_coin' => $payee_address_coin,
                'payee_amount_in_coin' => $payee_amount_in_coin,
                'payee_qrcode' => $payee_qrcode,
                'payee_timeout' => $payee_timeout,
            ];

            view('layouts/auth_header', ['title' => 'CoinPayments']);
            view('panel/admin/management/payments/payment_coinpayments.php', $data);
            view('layouts/auth_footer');
        } else {
            redirect(base_url('add/balance'));
        }
    }
}
