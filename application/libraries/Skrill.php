<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Skrill
{
    protected $CI;
    protected $EMAIL_SKRILL;

    const SKRILL_URL = "https://pay.skrill.com/?";

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->EMAIL_SKRILL = config_payment('skrill_email', 'value');
    }

    public function access($amount, $transaction)
    {
        $params = [
            'pay_to_email' => $this->EMAIL_SKRILL,
            'language' => 'EN',
            'amount' => $amount,
            'detail1_description' => lang('adding_account_balance') . ' - ' . configs('app_title', 'value'),
            'currency' => strtoupper(configs('currency_code', 'value')),
            'return_url' => base_url('skrill/success'),
            'cancel_url' => base_url('skrill/cancel'),
            'status_url' => base_url('skrill/status'),
            'status_url2' => 'mailto:' . dataUser(logged(), 'email'),
            'transaction_id' => $transaction
        ];

        $url = self::SKRILL_URL . http_build_query($params);
        $json = api_connect($url, '', true);

        if ($json['code'] != 'BAD_REQUEST') {
            return $url;
        } else {
            add_log("Error Skrill", 'error_skrill', $json['message']);

            json([
                'csrf' => $this->CI->security->get_csrf_hash(),
                'type' => 'error',
                'message' => lang('error_payment_not_purchase'),
            ]);
        }
    }

    public function getStatus()
    {
        $pay_to_email = $this->CI->input->post('pay_to_email', true);
        $merchant_id = $this->CI->input->post('merchant_id', true);
        $transaction_id = $this->CI->input->post('transaction_id', true);
        $mb_amount = $this->CI->input->post('mb_amount', true);
        $mb_currency = $this->CI->input->post('mb_currency', true);
        $status = $this->CI->input->post('status', true);
        $md5sig = $this->CI->input->post('md5sig', true);

        $secret_word = strtoupper(md5(token_rand()));
        $md5signature =  $merchant_id . $transaction_id . $secret_word . $mb_amount . $mb_currency . $status;

        $transaction_logs = $this->CI->model->get('*', TABLE_TRANSACTION_LOGS, ['transaction_id' => $transaction_id], '', '', true);

        if (strtoupper(md5($md5signature)) == strtoupper($md5sig) && !empty($transaction_logs) && $pay_to_email == $this->EMAIL_SKRILL) {
            switch ($status) {
                case '2':
                    $this->CI->model->update(TABLE_TRANSACTION_LOGS, ['transaction_id' => $transaction_logs['transaction_id']], ['status' => 'paid', 'updated_at' => NOW]);
                    $this->CI->model->update(TABLE_USERS, ['id' => $transaction_logs['user_id']], '', true, false, $mb_amount);
                    break;

                case '0':
                    $this->CI->model->update(TABLE_TRANSACTION_LOGS, ['transaction_id' => $transaction_logs['transaction_id']], ['status' => 'pending', 'updated_at' => NOW]);
                    break;

                case '-1':
                case '-2':
                    $this->CI->model->update(TABLE_TRANSACTION_LOGS, ['transaction_id' => $transaction_logs['transaction_id']], ['status' => 'canceled', 'updated_at' => NOW]);
                    break;
            }
        }
    }
}
