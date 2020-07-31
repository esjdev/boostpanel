<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Paytm_pay extends MY_Controller
{
    protected $table;
    protected $table_users;

    public function __construct()
    {
        parent::__construct();

        if (
            !userLevel(logged(), 'user') && config_payment('paytm_status', 'value') != 'on'
            && config_payment('paytm_merchant_key', 'value') == ''
            && config_payment('paytm_merchant_mid', 'value') == ''
            && config_payment('paytm_merchant_website', 'value') == ''
        ) return redirect(base_url());

        $this->table = TABLE_TRANSACTION_LOGS;
        $this->table_users = TABLE_USERS;

        $this->load->helper('paytm_encdec');
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

            if ($amount < config_payment('paytm_min_payment', 'value') || (config_payment('paytm_max_payment', 'value') != 0 && $amount > config_payment('paytm_max_payment', 'value'))) {
                json([
                    'csrf' => $this->security->get_csrf_hash(),
                    'type' => 'error',
                    'message' => sprintf(lang('error_min_max_payment'), configs('currency_symbol', 'value') . config_payment('paytm_min_payment', 'value'), (config_payment('paytm_max_payment', 'value') == 0 ? lang('unlimited') : configs('currency_symbol', 'value') . config_payment('paytm_max_payment', 'value'))),
                ]);
            }

            if (!isset($agree)) {
                json([
                    'csrf' => $this->security->get_csrf_hash(),
                    'type' => 'error',
                    'message' => lang('error_confirm_conditions_paying'),
                ]);
            }

            set_session('paytm_amount', $amount);
            set_session('paytm_protected', token_rand());

            json([
                'csrf' => $this->security->get_csrf_hash(),
                'type' => 'success',
                'link' => base_url('paytm/create_payment'),
            ]);
        }
    }

    public function create_payment()
    {
        $amount = session('paytm_amount');
        $protected = session('paytm_protected');

        if (isset($amount) && isset($protected)) {
            set_session('order_id', "paytm_" . substr(hash('sha256', mt_rand() . microtime() . logged()), 0, 20));
            set_session('cust_id', "CUST001");
            set_session('industry_type_id', "Retail");
            set_session('channel_id', "WEB");

            $data = [
                'order_id' => session('order_id'),
                'cust_id' => session('cust_id'),
                'industry_type_id' => session('industry_type_id'),
                'channel_id' => session('channel_id'),
                'amount' => $amount
            ];

            view('layouts/auth_header', ['title' => "PayTM"]);
            view('panel/addbalance/form_paytm', $data);
            view('layouts/auth_footer');
        } else {
            redirect(base_url('add/balance'));
        }
    }

    public function success()
    {
        $amount = session('paytm_amount');
        $protected = session('paytm_protected');

        if (isset($amount) && isset($protected)) {
            // URLs PayTM
            $PAYTM_TXN_URL_TEST = 'https://securegw-stage.paytm.in/theia/processTransaction'; // ENVIRONMENT Test
            $PAYTM_TXN_URL_PROD = 'https://securegw.paytm.in/theia/processTransaction'; // ENVIRONMENT Prod

            $paramList = [];

            // Create an array having all required parameters for creating checksum.
            $paramList["MID"] = config_payment('paytm_merchant_mid', 'value');
            $paramList["ORDER_ID"] = session('order_id');
            $paramList["CUST_ID"] = session('cust_id');
            $paramList["INDUSTRY_TYPE_ID"] = session('industry_type_id');
            $paramList["CHANNEL_ID"] = session('channel_id');
            $paramList["TXN_AMOUNT"] = $amount;
            $paramList["WEBSITE"] = config_payment('paytm_merchant_website', 'value');
            $paramList["CALLBACK_URL"] = base_url('paytm/response');

            $checkSum = getChecksumFromArray($paramList, config_payment('paytm_merchant_key', 'value'));

            $data = [
                'paramList' => $paramList,
                'checkSum' => $checkSum,

                // Action
                'action' => (config_payment('paytm_environment', 'value') == 'Sandbox' ? $PAYTM_TXN_URL_TEST : $PAYTM_TXN_URL_PROD)
            ];

            view('layouts/auth_header', ['title' => "PayTM"]);
            view('panel/addbalance/confirm_form_paytm', $data);
            view('layouts/auth_footer');
        } else {
            redirect(base_url('add/balance'));
        }
    }

    public function response()
    {
        $amount = session('paytm_amount');
        $protected = session('paytm_protected');

        if (isset($amount) && isset($protected)) {
            $paramList = [];
            $isValidChecksum = "FALSE";

            $paramList = $_POST;
            $paytmChecksum = isset($_POST["CHECKSUMHASH"]) ? $_POST["CHECKSUMHASH"] : "";

            $isValidChecksum = verifychecksum_e($paramList, config_payment('paytm_merchant_key', 'value'), $paytmChecksum);

            if ($isValidChecksum == "TRUE") {
                if ($_POST["STATUS"] == "TXN_SUCCESS") {
                    $data = [
                        'type' => 'paytm',
                        'payee_transaction' => session('order_id'),
                        'payee_name' => dataUser(logged(), 'name'),
                        'payee_amount_total' => $amount
                    ];

                    // Add Transaction PayTM
                    $this->model->insert($this->table, [
                        'user_id' => dataUser(logged(), 'id'),
                        'transaction_id' => session('order_id'),
                        'payment_method' => 'paytm',
                        'amount' => $amount,
                        'status' => 'paid',
                        'created_at' => NOW,
                        'updated_at' => NOW,
                    ]);

                    $this->model->update($this->table_users, ['id' => dataUser(logged(), 'id')], '', true, false, $amount);

                    if (notification('payment_notification', 'value') == 1) {
                        $subject = (email_tpl('payments_notification_subject', 'value') == '') ? email_template('payments_notification')->subject : email_tpl('payments_notification_subject', 'value');
                        $email_content = (email_tpl('payments_notification_content', 'value') == '') ? email_template('payments_notification')->content : email_tpl('payments_notification_content', 'value');
                        $email_content = str_replace("{{username}}", dataUser(logged(), 'username'), $email_content);
                        $email_content = str_replace("{{transaction_id}}", session('order_id'), $email_content);
                        $email_content = str_replace("{{method_payment}}", 'PayTM', $email_content);

                        email_send(dataUser(logged(), 'email'), $subject, $email_content);
                    }

                    view('layouts/auth_header', ['title' => "PayTM"]);
                    view('panel/admin/management/payments/payment_completed', $data);
                    view('layouts/auth_footer');
                } else if ($_POST["STATUS"] == "PENDING") {
                    $data = ['type' => 'paytm'];

                    // Add Transaction PayTM
                    $this->model->insert($this->table, [
                        'user_id' => dataUser(logged(), 'id'),
                        'transaction_id' => session('order_id'),
                        'payment_method' => 'paytm',
                        'amount' => $amount,
                        'status' => 'pending',
                        'created_at' => NOW,
                        'updated_at' => NOW,
                    ]);

                    view('layouts/auth_header', ['title' => "PayTM"]);
                    view('panel/admin/management/payments/payment_pendent', $data);
                    view('layouts/auth_footer');
                } else {
                    $data = ['type' => 'paytm'];

                    view('layouts/auth_header', ['title' => "PayTM"]);
                    view('panel/admin/management/payments/payment_canceled', $data);
                    view('layouts/auth_footer');
                }
            } else {
                $data = ['type' => 'paytm'];

                view('layouts/auth_header', ['title' => "PayTM"]);
                view('panel/admin/management/payments/payment_canceled', $data);
                view('layouts/auth_footer');
            }

            // Clear Sessions
            unset_session('paytm_amount');
            unset_session('paytm_protected');
            unset_session('order_id');
            unset_session('cust_id');
            unset_session('industry_type_id');
            unset_session('channel_id');
        } else {
            redirect(base_url('add/balance'));
        }
    }
}
