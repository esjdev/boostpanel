<?php defined('BASEPATH') or exit('No direct script access allowed');

require_once 'instamojo.php';

class Instamojores
{
    protected $CI;
    protected $api;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->api = new Instamojo\Instamojo(config_payment('instamojo_api_key', 'value'), config_payment('instamojo_auth_token', 'value'), (config_payment('instamojo_environment', 'value') == 'Sandbox' ? 'https://test.instamojo.com/api/1.1/' : null));
    }

    public function access($description, $amount, $email, $redirect_url)
    {
        try {
            $response = $this->api->paymentRequestCreate([
                "buyer_name" => dataUser($email, 'name'),
                "purpose" => $description,
                "amount" => $amount,
                "email" => $email,
                "redirect_url" => $redirect_url
            ]);

            return $response;
        } catch (Exception $e) {
            add_log("Error Instamojo", 'error_instamojo', sprintf(lang("error_config_incorrect_payment"), 'Instamojo'));

            json([
                'csrf' => $this->CI->security->get_csrf_hash(),
                'type' => 'error',
                'message' => lang('error_payment_not_purchase'),
            ]);
        }
    }

    public function status_transaction($id)
    {
        $response = $this->api->paymentRequestStatus($id);
        return $response;
    }
}
