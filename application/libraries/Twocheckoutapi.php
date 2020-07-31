<?php defined('BASEPATH') or exit('No direct script access allowed');

require_once '2Checkout/Twocheckout.php';

class Twocheckoutapi
{
    public function __construct()
    {
        $mode = config_payment('2checkout_environment', 'value');

        Twocheckout::privateKey(config_payment('2checkout_private_key', 'value'));
        Twocheckout::sellerId(config_payment('2checkout_seller_id', 'value'));

        if ($mode == "Sandbox") {
            Twocheckout::sandbox(true);
        } else {
            Twocheckout::sandbox(false);
        }
    }

    public function access($data)
    {
        try {
            // Charge a credit card
            $response = Twocheckout_Charge::auth($data);
            return $response;
        } catch (Twocheckout_Error $e) {
            set_flashdata('error_transaction', $e->getMessage());
            redirect(base_url('add/balance'));
        }
    }
}
