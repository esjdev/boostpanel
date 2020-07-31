<?php

defined('BASEPATH') or exit('No direct script access allowed');

require_once 'MercadoPago/init.php';

class Mercadopago
{
    const URL_API_SEARCH_TRANSACTION = 'https://api.mercadopago.com/v1/payments/search/?external_reference=';

    public function __construct()
    {
        MercadoPago\SDK::setAccessToken(config_payment('mercadopago_access_token', 'value'));
    }

    public function access($data)
    {
        $preference = new MercadoPago\Preference();
        $item = new MercadoPago\Item();

        $item->title = lang('adding_account_balance') . ' - ' . configs('app_title', 'value');
        $item->quantity = 1;
        $item->unit_price = $data->amount;
        $item->currency_id = "BRL";

        $preference->items = [$item];
        $preference->external_reference = $data->refer;
        $preference->save();

        return $preference;
    }

    public function transaction_reference($transaction_id)
    {
        $url = self::URL_API_SEARCH_TRANSACTION . $transaction_id . '&access_token=' . config_payment('mercadopago_access_token', 'value');
        $transaction = api_connect($url, '', true);

        return $transaction;
    }
}
