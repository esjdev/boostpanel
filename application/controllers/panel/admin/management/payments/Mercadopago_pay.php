<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Mercadopago_pay extends MY_Controller
{
    protected $table;

    public function __construct()
    {
        parent::__construct();

        if (
            !userLevel(logged(), 'user') && config_payment('mercadopago_status', 'value') != 'on'
            && config_payment('mercadopago_access_token', 'value') == ''
        ) return redirect(base_url());

        $this->table = TABLE_TRANSACTION_LOGS;
    }

    public function index()
    {
        if (isset($_POST) && !empty($_POST)) {
            $this->load->library('mercadopago');

            $amount = $this->input->post('amount', true);
            $agree = $this->input->post('agree', true);
            $payment_method = $this->input->post('payment_method', true);

            if (empty($amount)) {
                json([
                    'csrf' => $this->security->get_csrf_hash(),
                    'type' => 'error',
                    'message' => sprintf(lang('error_empty_field'), lang('amount')),
                ]);
            }

            if ($amount < config_payment('mercadopago_min_payment', 'value') || (config_payment('mercadopago_max_payment', 'value') != 0 && $amount > config_payment('mercadopago_max_payment', 'value'))) {
                json([
                    'csrf' => $this->security->get_csrf_hash(),
                    'type' => 'error',
                    'message' => sprintf(lang('error_min_max_payment'), configs('currency_symbol', 'value') . config_payment('mercadopago_min_payment', 'value'), (config_payment('mercadopago_max_payment', 'value') == 0 ? lang('unlimited') : configs('currency_symbol', 'value') . config_payment('mercadopago_max_payment', 'value'))),
                ]);
            }

            if (!isset($agree)) {
                json([
                    'csrf' => $this->security->get_csrf_hash(),
                    'type' => 'error',
                    'message' => lang('error_confirm_conditions_paying'),
                ]);
            }

            $refer = 'MP_' . create_random_api_key(20);
            $amount = convert_exchange(configs("currency_code", "value"), 'BRL', $amount);
            $data = (object) [
                'amount' => $amount,
                'refer' => $refer
            ];
            $mercadopago = $this->mercadopago->access($data);

            if (isset($mercadopago->error)) {
                add_log("Error MercadoPago", 'error_mercadopago', sprintf(lang("error_config_incorrect_payment"), 'MercadoPago'));

                json([
                    'csrf' => $this->security->get_csrf_hash(),
                    'type' => 'error',
                    'message' => lang('error_payment_not_purchase'),
                ]);
            }

            $this->model->insert($this->table, [
                'user_id' => dataUser(logged(), 'id'),
                'transaction_id' => $refer,
                'payment_method' => $payment_method,
                'amount' => $amount,
                'status' => 'pending',
                'created_at' => NOW,
                'updated_at' => NOW,
            ]);

            if (notification('payment_notification', 'value') == 1) {
                $subject = (email_tpl('payments_notification_subject', 'value') == '') ? email_template('payments_notification')->subject : email_tpl('payments_notification_subject', 'value');
                $email_content = (email_tpl('payments_notification_content', 'value') == '') ? email_template('payments_notification')->content : email_tpl('payments_notification_content', 'value');
                $email_content = str_replace("{{username}}", dataUser(logged(), 'username'), $email_content);
                $email_content = str_replace("{{transaction_id}}", $refer, $email_content);
                $email_content = str_replace("{{method_payment}}", 'MercadoPago', $email_content);

                email_send(dataUser(logged(), 'email'), $subject, $email_content);
            }

            json([
                'csrf' => $this->security->get_csrf_hash(),
                'type' => 'success',
                'link' => (config_payment('mercadopago_environment', 'value') == 'Sandbox' ? $mercadopago->sandbox_init_point : $mercadopago->init_point),
            ]);
        }
    }
}
