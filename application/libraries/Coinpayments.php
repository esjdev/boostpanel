<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once 'CoinPayments/CoinpaymentsAPI.php';

class Coinpayments
{
    protected $api;

    public function __construct()
    {
        $this->api = new CoinpaymentsAPI(config_payment('coinpayments_private_key', 'value'), config_payment('coinpayments_public_key', 'value'), 'json');
    }

    public function access($data)
    {
        $amount = $data->amount;
        $currency1 = configs('currency_code', 'value');
        $currency2 = $data->type_currency;
        $buyer_email = logged();
        $address = '';
        $buyer_name = $data->buyer_name;
        $item_name = lang('adding_account_balance') . ' - ' . configs('app_title', 'value');
        $item_number = create_random_api_key(5);
        $invoice = token_rand();
        $custom = 'Express order';
        $ipn_url = base_url();

        $transaction_response = $this->api->CreateComplexTransaction($amount, $currency1, $currency2, $buyer_email, $address, $buyer_name, $item_name, $item_number, $invoice, $custom, $ipn_url);

        return $transaction_response;
    }

    public function getTransactionInfo($transaction_id)
    {
        $transaction_history = $this->api->GetTxInfoSingle($transaction_id);
        return $transaction_history;
    }
}
