<?php defined('BASEPATH') or exit('No direct script access allowed');

require_once 'PayPal/autoload.php';

use PayPal\Api\Amount;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;

class Paypal
{
    protected $CI;
    protected $api;

    public function __construct()
    {
        $this->CI = &get_instance();

        $this->api = new \PayPal\Rest\ApiContext(new \PayPal\Auth\OAuthTokenCredential(config_payment('paypal_client_id', 'value'), config_payment('paypal_client_secret', 'value')));

        $this->api->setConfig(['mode' => (config_payment('paypal_environment', 'value') == 'Live' ? 'live' : 'sandbox')]);
    }

    public function access($data)
    {
        // Create new payer and method
        $payer = new Payer();
        $payer->setPaymentMethod("paypal");

        // Set redirect URLs
        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl($data->redirectUrls)
            ->setCancelUrl($data->cancelUrl);

        // Set payment amount
        $amount = new Amount();
        $amount->setCurrency(configs('currency_code', 'value'))
            ->setTotal($data->amount);

        // Set transaction object
        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setDescription(lang('adding_account_balance') . ' - ' . configs('app_title', 'value'));

        // Create the full payment object
        $payment = new Payment();
        $payment->setIntent('sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirectUrls)
            ->setTransactions([$transaction]);

        // Create payment with valid API context
        try {
            $payment->create($this->api);

            // Get PayPal redirect URL and redirect the customer
            $approvalUrl = $payment->getApprovalLink();

            // Redirect the customer to $approvalUrl
            return $approvalUrl;
        } catch (PayPal\Exception\PayPalConnectionException $ex) {
            add_log("Error PayPal", 'error_paypal', sprintf(lang("error_config_incorrect_payment"), 'PayPal'));

            json([
                'csrf' => $this->CI->security->get_csrf_hash(),
                'type' => 'error',
                'message' => lang('error_payment_not_purchase'),
            ]);
        }
    }

    public function execute_payment($paymentId, $payerId)
    {
        // Get payment object by passing paymentId
        $payment = Payment::get($paymentId, $this->api);

        // Execute payment with payer ID
        $execution = new PaymentExecution();
        $execution->setPayerId($payerId);

        try {
            // Execute payment
            $result = $payment->execute($execution, $this->api);
        } catch (PayPal\Exception\PayPalConnectionException $ex) {
            die('Some error occur, Sorry for inconvenient');
        }

        return $result;
    }
}
