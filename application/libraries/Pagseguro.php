<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Pagseguro
{
    protected $CI;

    const TEST_URL_CHECKOUT = 'https://ws.sandbox.pagseguro.uol.com.br/v2/checkout';
    const PRODUCTION_URL_CHECKOUT = 'https://ws.pagseguro.uol.com.br/v2/checkout';

    const TEST_URL_TRANSACTIONS = 'https://ws.sandbox.pagseguro.uol.com.br/v2/transactions/?reference=';
    const PRODUCTION_URL_TRANSACTIONS = 'https://ws.pagseguro.uol.com.br/v2/transactions/?reference=';

    public function __construct()
    {
        $this->CI = &get_instance();
    }

    public function access($return)
    {
        $data = [
            'token' => config_payment('pagseguro_token', 'value'),
            'email' => config_payment('pagseguro_email', 'value'),
            'currency' => 'BRL',
            'itemId1' => 1,
            'itemQuantity1' => 1,
            'itemAmount1' => ($return->amount <= 999 ? currency_format($return->amount, 2, '.', ',') : number_format($return->amount, 2, '.', '')),
            'itemDescription1' => lang('adding_account_balance') . ' - ' . configs('app_title', 'value'),
            'reference' => $return->refer,
        ];

        $url = (config_payment('pagseguro_environment', 'value') == 'Sandbox' ? self::TEST_URL_CHECKOUT : self::PRODUCTION_URL_CHECKOUT);
        $curl = api_connect($url, $data);

        return $curl;
    }

    public function transaction_reference($transaction_id)
    {
        $data = [
            'token' => config_payment('pagseguro_token', 'value'),
            'email' => config_payment('pagseguro_email', 'value'),
        ];
        $data = http_build_query($data);

        $url = (config_payment('pagseguro_environment', 'value') == 'Sandbox' ? self::TEST_URL_TRANSACTIONS . $transaction_id . '&' . $data : self::PRODUCTION_URL_TRANSACTIONS . $transaction_id . '&' . $data);

        $transaction = api_connect($url, $data);
        $transaction = simplexml_load_string($transaction);

        return $transaction;
    }
}
