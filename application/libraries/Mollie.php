<?php defined('BASEPATH') or exit('No direct script access allowed');

require_once 'MollieAPI/vendor/autoload.php';

class Mollie
{
    protected $CI;
    protected $api;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->api = new \Mollie\Api\MollieApiClient();

        $this->api->setApiKey(config_payment('mollie_api_key', 'value'));
    }

    public function access($amount)
    {
        try {
            $payment = $this->api->payments->create([
                "amount" => [
                    "currency" => configs('currency_code', 'value'),
                    "value" => currency_format($amount)
                ],
                "description" => lang('adding_account_balance'),
                "redirectUrl" => base_url('mollie/success'),
            ]);

            return $payment;
        } catch (\Mollie\Api\Exceptions\ApiException $e) {
            add_log("Error Mollie", 'error_mollie', sprintf(lang("error_config_incorrect_payment"), 'Mollie'));

            json([
                'csrf' => $this->CI->security->get_csrf_hash(),
                'type' => 'error',
                'message' => lang('error_payment_not_purchase'),
            ]);
        }
    }

    public function retrieving_payments($id)
    {
        try {
            $payment = $this->api->payments->get($id);
            return $payment;
        } catch (\Mollie\Api\Exceptions\ApiException $e) {
            add_log("Error Mollie", 'error_mollie', sprintf(lang("error_config_incorrect_payment"), 'Mollie'));
        }
    }
}
