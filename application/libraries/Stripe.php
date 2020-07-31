<?php defined('BASEPATH') or exit('No direct script access allowed');

require_once 'Stripe/init.php';

class Stripe
{
    public function __construct()
    {
        \Stripe\Stripe::setApiKey(config_payment('stripe_secret_key', 'value'), config_payment('stripe_publishable_key', 'value'));
    }

    public function customer_create($data)
    {
        try {
            $result = \Stripe\Customer::create($data);
            return $result;
        } catch (Exception $e) {
            set_flashdata('error_transaction', lang("error_transaction_been_failed"));
            unset_session('amount');
            unset_session('protected');

            redirect(base_url('add/balance'));
        }
    }

    public function access($data)
    {
        try {
            $response = \Stripe\Charge::create($data);
            return $response;
        } catch (Exception $e) {
            set_flashdata('error_transaction', lang("error_transaction_been_failed"));
            unset_session('amount');
            unset_session('protected');

            redirect(base_url('add/balance'));
        }
    }

    public function list_transaction($id)
    {
        try {
            $transaction = \Stripe\BalanceTransaction::retrieve($id);
            return $transaction;
        } catch (Exception $e) {
            set_flashdata('error_transaction', lang("error_transaction_been_failed"));
            unset_session('amount');
            unset_session('protected');

            redirect(base_url('add/balance'));
        }
    }
}
