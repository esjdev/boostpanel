<?php defined('BASEPATH') or exit('No direct script access allowed');

require_once 'RazorPayAPI/Razorpay.php';

use Razorpay\Api\Api;

class Razorpay
{
    protected $CI;
    protected $api;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->api = new Api(config_payment('razorpay_key_id', 'value'), config_payment('razorpay_key_secret', 'value'));
    }

    public function access($amount, $email)
    {
        try {
            $link = $this->api->invoice->create([
                'type' => 'link',
                'amount' => $amount * 100,
                'description' => lang('adding_account_balance'),
                'customer' => [
                    'name' => dataUser($email, 'name'),
                    'email' => $email
                ],
                'receipt' => 'RAZOR_' . token_rand(),
                'currency' => 'INR',
                'email_notify' => 0,
                'callback_url' => base_url('razorpay/redirect'),
                'callback_method' => 'get',
            ]);

            return $link;
        } catch (Exception $e) {
            add_log("Error RazorPay", 'error_razorpay', sprintf(lang("error_config_incorrect_payment"), 'RazorPay'));

            json([
                'csrf' => $this->CI->security->get_csrf_hash(),
                'type' => 'error',
                'message' => lang('error_payment_not_purchase'),
            ]);
        }
    }

    public function fetch_payment($id)
    {
        $payment = $this->api->payment->fetch($id);
        return $payment;
    }

    public function fetch_order($id)
    {
        $order = $this->api->order->fetch($id);
        return $order;
    }
}
