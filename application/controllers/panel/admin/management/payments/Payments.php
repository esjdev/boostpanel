<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Payments extends MY_Controller
{
    protected $table;

    public function __construct()
    {
        parent::__construct();
        if (!userLevel(logged(), 'admin')) return redirect(base_url());

        $this->table = TABLE_PAYMENTS_CONFIG;
    }

    public function index()
    {
        $data = [
            'title' => lang("title_payment_integrations"),
        ];

        view('layouts/auth_header', $data);
        view('panel/admin/management/payments/payment_index');
        view('layouts/auth_footer');
    }

    public function edit_payment($type)
    {
        if (isset($_POST) && !empty($_POST)) {
            if (DEMO_VERSION != true) {
                $edit_environment_payment = $this->input->post('edit_environment_payment', true);
                $edit_name_payment = $this->input->post('edit_name_payment', true);
                $edit_minimal_payment = $this->input->post('edit_minimal_payment', true);
                $edit_maximal_payment = $this->input->post('edit_maximal_payment', true);

                switch ($type) {
                    case 'pagseguro':
                        $edit_email_payment = $this->input->post('edit_email_payment', true);
                        $edit_token_payment = $this->input->post('edit_token_payment', true);

                        if (!in_array($edit_environment_payment, ['Sandbox', 'Live'])) {
                            json([
                                'csrf' => $this->security->get_csrf_hash(),
                                'type' => 'error',
                                'message' => lang('error_environment_invalid'),
                            ]);
                        }

                        if (empty($edit_name_payment)) {
                            json([
                                'csrf' => $this->security->get_csrf_hash(),
                                'type' => 'error',
                                'message' => sprintf(lang('error_empty_field'), lang('input_name')),
                            ]);
                        }

                        if (empty($edit_minimal_payment) || $edit_maximal_payment == '') {
                            json([
                                'csrf' => $this->security->get_csrf_hash(),
                                'type' => 'error',
                                'message' => sprintf(lang('error_empty_fields'), lang('minimal_payment'), lang('maximal_payment')),
                            ]);
                        }

                        if (!is_numeric($edit_minimal_payment) || !is_numeric($edit_maximal_payment)) {
                            json([
                                'csrf' => $this->security->get_csrf_hash(),
                                'type' => 'error',
                                'message' => sprintf(lang('error_alpha_numeric_fields'), lang('minimal_payment'), lang('maximal_payment')),
                            ]);
                        }

                        if ($edit_maximal_payment != 0 && $edit_minimal_payment > $edit_maximal_payment) {
                            json([
                                'csrf' => $this->security->get_csrf_hash(),
                                'type' => 'error',
                                'message' => lang('error_payment_min_cannot_higher_max'),
                            ]);
                        }

                        $this->model->one_update($this->table, 'name', 'pagseguro_environment', ['value' => $edit_environment_payment, 'updated_at' => NOW]);
                        $this->model->one_update($this->table, 'name', 'pagseguro_name', ['value' => $edit_name_payment, 'updated_at' => NOW]);
                        $this->model->one_update($this->table, 'name', 'pagseguro_min_payment', ['value' => ($edit_minimal_payment == 0 ? 1 : $edit_minimal_payment), 'updated_at' => NOW]);
                        $this->model->one_update($this->table, 'name', 'pagseguro_max_payment', ['value' => $edit_maximal_payment, 'updated_at' => NOW]);
                        $this->model->one_update($this->table, 'name', 'pagseguro_token', ['value' => $edit_token_payment, 'updated_at' => NOW]);
                        $this->model->one_update($this->table, 'name', 'pagseguro_email', ['value' => $edit_email_payment, 'updated_at' => NOW]);

                        json([
                            'csrf' => $this->security->get_csrf_hash(),
                            'type' => 'success',
                            'message' => lang('success_edited'),
                        ]);
                        break;

                    case 'mercadopago':
                        $edit_accesstoken_payment = $this->input->post('edit_accesstoken_payment', true);

                        if (!in_array($edit_environment_payment, ['Sandbox', 'Live'])) {
                            json([
                                'csrf' => $this->security->get_csrf_hash(),
                                'type' => 'error',
                                'message' => lang('error_environment_invalid'),
                            ]);
                        }

                        if (empty($edit_name_payment)) {
                            json([
                                'csrf' => $this->security->get_csrf_hash(),
                                'type' => 'error',
                                'message' => sprintf(lang('error_empty_field'), lang('input_name')),
                            ]);
                        }

                        if (empty($edit_minimal_payment) || $edit_maximal_payment == '') {
                            json([
                                'csrf' => $this->security->get_csrf_hash(),
                                'type' => 'error',
                                'message' => sprintf(lang('error_empty_fields'), lang('minimal_payment'), lang('maximal_payment')),
                            ]);
                        }

                        if (!is_numeric($edit_minimal_payment) || !is_numeric($edit_maximal_payment)) {
                            json([
                                'csrf' => $this->security->get_csrf_hash(),
                                'type' => 'error',
                                'message' => sprintf(lang('error_alpha_numeric_fields'), lang('minimal_payment'), lang('maximal_payment')),
                            ]);
                        }

                        if ($edit_maximal_payment != 0 && $edit_minimal_payment > $edit_maximal_payment) {
                            json([
                                'csrf' => $this->security->get_csrf_hash(),
                                'type' => 'error',
                                'message' => lang('error_payment_min_cannot_higher_max'),
                            ]);
                        }

                        $this->model->one_update($this->table, 'name', 'mercadopago_environment', ['value' => $edit_environment_payment, 'updated_at' => NOW]);
                        $this->model->one_update($this->table, 'name', 'mercadopago_name', ['value' => $edit_name_payment, 'updated_at' => NOW]);
                        $this->model->one_update($this->table, 'name', 'mercadopago_min_payment', ['value' => ($edit_minimal_payment == 0 ? 1 : $edit_minimal_payment), 'updated_at' => NOW]);
                        $this->model->one_update($this->table, 'name', 'mercadopago_max_payment', ['value' => $edit_maximal_payment, 'updated_at' => NOW]);
                        $this->model->one_update($this->table, 'name', 'mercadopago_access_token', ['value' => $edit_accesstoken_payment, 'updated_at' => NOW]);

                        json([
                            'csrf' => $this->security->get_csrf_hash(),
                            'type' => 'success',
                            'message' => lang('success_edited'),
                        ]);
                        break;

                    case 'paypal':
                        $edit_client_id_payment = $this->input->post('edit_client_id_payment', true);
                        $edit_client_secret_payment = $this->input->post('edit_client_secret_payment', true);

                        if (!in_array($edit_environment_payment, ['Sandbox', 'Live'])) {
                            json([
                                'csrf' => $this->security->get_csrf_hash(),
                                'type' => 'error',
                                'message' => lang('error_environment_invalid'),
                            ]);
                        }

                        if (empty($edit_name_payment)) {
                            json([
                                'csrf' => $this->security->get_csrf_hash(),
                                'type' => 'error',
                                'message' => sprintf(lang('error_empty_field'), lang('input_name')),
                            ]);
                        }

                        if (empty($edit_minimal_payment) || $edit_maximal_payment == '') {
                            json([
                                'csrf' => $this->security->get_csrf_hash(),
                                'type' => 'error',
                                'message' => sprintf(lang('error_empty_fields'), lang('minimal_payment'), lang('maximal_payment')),
                            ]);
                        }

                        if (!is_numeric($edit_minimal_payment) || !is_numeric($edit_maximal_payment)) {
                            json([
                                'csrf' => $this->security->get_csrf_hash(),
                                'type' => 'error',
                                'message' => sprintf(lang('error_alpha_numeric_fields'), lang('minimal_payment'), lang('maximal_payment')),
                            ]);
                        }

                        if ($edit_maximal_payment != 0 && $edit_minimal_payment > $edit_maximal_payment) {
                            json([
                                'csrf' => $this->security->get_csrf_hash(),
                                'type' => 'error',
                                'message' => lang('error_payment_min_cannot_higher_max'),
                            ]);
                        }

                        $this->model->one_update($this->table, 'name', 'paypal_environment', ['value' => $edit_environment_payment, 'updated_at' => NOW]);
                        $this->model->one_update($this->table, 'name', 'paypal_name', ['value' => $edit_name_payment, 'updated_at' => NOW]);
                        $this->model->one_update($this->table, 'name', 'paypal_min_payment', ['value' => ($edit_minimal_payment == 0 ? 1 : $edit_minimal_payment), 'updated_at' => NOW]);
                        $this->model->one_update($this->table, 'name', 'paypal_max_payment', ['value' => $edit_maximal_payment, 'updated_at' => NOW]);
                        $this->model->one_update($this->table, 'name', 'paypal_client_id', ['value' => $edit_client_id_payment, 'updated_at' => NOW]);
                        $this->model->one_update($this->table, 'name', 'paypal_client_secret', ['value' => $edit_client_secret_payment, 'updated_at' => NOW]);

                        json([
                            'csrf' => $this->security->get_csrf_hash(),
                            'type' => 'success',
                            'message' => lang('success_edited'),
                        ]);
                        break;

                    case 'stripe':
                        $edit_publishable_key_payment = $this->input->post('edit_publishable_key_payment', true);
                        $edit_secret_key_payment = $this->input->post('edit_secret_key_payment', true);

                        if (empty($edit_name_payment)) {
                            json([
                                'csrf' => $this->security->get_csrf_hash(),
                                'type' => 'error',
                                'message' => sprintf(lang('error_empty_field'), lang('input_name')),
                            ]);
                        }

                        if (empty($edit_minimal_payment) || $edit_maximal_payment == '') {
                            json([
                                'csrf' => $this->security->get_csrf_hash(),
                                'type' => 'error',
                                'message' => sprintf(lang('error_empty_fields'), lang('minimal_payment'), lang('maximal_payment')),
                            ]);
                        }

                        if (!is_numeric($edit_minimal_payment) || !is_numeric($edit_maximal_payment)) {
                            json([
                                'csrf' => $this->security->get_csrf_hash(),
                                'type' => 'error',
                                'message' => sprintf(lang('error_alpha_numeric_fields'), lang('minimal_payment'), lang('maximal_payment')),
                            ]);
                        }

                        if ($edit_maximal_payment != 0 && $edit_minimal_payment > $edit_maximal_payment) {
                            json([
                                'csrf' => $this->security->get_csrf_hash(),
                                'type' => 'error',
                                'message' => lang('error_payment_min_cannot_higher_max'),
                            ]);
                        }

                        $this->model->one_update($this->table, 'name', 'stripe_name', ['value' => $edit_name_payment, 'updated_at' => NOW]);
                        $this->model->one_update($this->table, 'name', 'stripe_min_payment', ['value' => ($edit_minimal_payment == 0 ? 1 : $edit_minimal_payment), 'updated_at' => NOW]);
                        $this->model->one_update($this->table, 'name', 'stripe_max_payment', ['value' => $edit_maximal_payment, 'updated_at' => NOW]);
                        $this->model->one_update($this->table, 'name', 'stripe_secret_key', ['value' => $edit_secret_key_payment, 'updated_at' => NOW]);
                        $this->model->one_update($this->table, 'name', 'stripe_publishable_key', ['value' => $edit_publishable_key_payment, 'updated_at' => NOW]);

                        json([
                            'csrf' => $this->security->get_csrf_hash(),
                            'type' => 'success',
                            'message' => lang('success_edited'),
                        ]);
                        break;

                    case 'twocheckout':
                        $edit_publishable_key_payment = $this->input->post('edit_publishable_key_payment', true);
                        $edit_private_key_payment = $this->input->post('edit_private_key_payment', true);
                        $edit_seller_id_payment = $this->input->post('edit_seller_id_payment', true);

                        if (empty($edit_name_payment)) {
                            json([
                                'csrf' => $this->security->get_csrf_hash(),
                                'type' => 'error',
                                'message' => sprintf(lang('error_empty_field'), lang('input_name')),
                            ]);
                        }

                        if (empty($edit_minimal_payment) || $edit_maximal_payment == '') {
                            json([
                                'csrf' => $this->security->get_csrf_hash(),
                                'type' => 'error',
                                'message' => sprintf(lang('error_empty_fields'), lang('minimal_payment'), lang('maximal_payment')),
                            ]);
                        }

                        if (!is_numeric($edit_minimal_payment) || !is_numeric($edit_maximal_payment)) {
                            json([
                                'csrf' => $this->security->get_csrf_hash(),
                                'type' => 'error',
                                'message' => sprintf(lang('error_alpha_numeric_fields'), lang('minimal_payment'), lang('maximal_payment')),
                            ]);
                        }

                        if ($edit_maximal_payment != 0 && $edit_minimal_payment > $edit_maximal_payment) {
                            json([
                                'csrf' => $this->security->get_csrf_hash(),
                                'type' => 'error',
                                'message' => lang('error_payment_min_cannot_higher_max'),
                            ]);
                        }

                        $this->model->one_update($this->table, 'name', '2checkout_environment', ['value' => $edit_environment_payment, 'updated_at' => NOW]);
                        $this->model->one_update($this->table, 'name', '2checkout_name', ['value' => $edit_name_payment, 'updated_at' => NOW]);
                        $this->model->one_update($this->table, 'name', '2checkout_min_payment', ['value' => ($edit_minimal_payment == 0 ? 1 : $edit_minimal_payment), 'updated_at' => NOW]);
                        $this->model->one_update($this->table, 'name', '2checkout_max_payment', ['value' => $edit_maximal_payment, 'updated_at' => NOW]);
                        $this->model->one_update($this->table, 'name', '2checkout_publishable_key', ['value' => $edit_publishable_key_payment, 'updated_at' => NOW]);
                        $this->model->one_update($this->table, 'name', '2checkout_private_key', ['value' => $edit_private_key_payment, 'updated_at' => NOW]);
                        $this->model->one_update($this->table, 'name', '2checkout_seller_id', ['value' => $edit_seller_id_payment, 'updated_at' => NOW]);

                        json([
                            'csrf' => $this->security->get_csrf_hash(),
                            'type' => 'success',
                            'message' => lang('success_edited'),
                        ]);
                        break;

                    case 'coinpayments':
                        $edit_public_key_payment = $this->input->post('edit_public_key_payment', true);
                        $edit_private_key_payment = $this->input->post('edit_private_key_payment', true);

                        if (!in_array($edit_environment_payment, ['Sandbox', 'Live'])) {
                            json([
                                'csrf' => $this->security->get_csrf_hash(),
                                'type' => 'error',
                                'message' => lang('error_environment_invalid'),
                            ]);
                        }

                        if (empty($edit_name_payment)) {
                            json([
                                'csrf' => $this->security->get_csrf_hash(),
                                'type' => 'error',
                                'message' => sprintf(lang('error_empty_field'), lang('input_name')),
                            ]);
                        }

                        if (empty($edit_minimal_payment) || $edit_maximal_payment == '') {
                            json([
                                'csrf' => $this->security->get_csrf_hash(),
                                'type' => 'error',
                                'message' => sprintf(lang('error_empty_fields'), lang('minimal_payment'), lang('maximal_payment')),
                            ]);
                        }

                        if (!is_numeric($edit_minimal_payment) || !is_numeric($edit_maximal_payment)) {
                            json([
                                'csrf' => $this->security->get_csrf_hash(),
                                'type' => 'error',
                                'message' => sprintf(lang('error_alpha_numeric_fields'), lang('minimal_payment'), lang('maximal_payment')),
                            ]);
                        }

                        if ($edit_maximal_payment != 0 && $edit_minimal_payment > $edit_maximal_payment) {
                            json([
                                'csrf' => $this->security->get_csrf_hash(),
                                'type' => 'error',
                                'message' => lang('error_payment_min_cannot_higher_max'),
                            ]);
                        }

                        $this->model->one_update($this->table, 'name', 'coinpayments_environment', ['value' => $edit_environment_payment, 'updated_at' => NOW]);
                        $this->model->one_update($this->table, 'name', 'coinpayments_name', ['value' => $edit_name_payment, 'updated_at' => NOW]);
                        $this->model->one_update($this->table, 'name', 'coinpayments_min_payment', ['value' => ($edit_minimal_payment == 0 ? 1 : $edit_minimal_payment), 'updated_at' => NOW]);
                        $this->model->one_update($this->table, 'name', 'coinpayments_max_payment', ['value' => $edit_maximal_payment, 'updated_at' => NOW]);
                        $this->model->one_update($this->table, 'name', 'coinpayments_public_key', ['value' => $edit_public_key_payment, 'updated_at' => NOW]);
                        $this->model->one_update($this->table, 'name', 'coinpayments_private_key', ['value' => $edit_private_key_payment, 'updated_at' => NOW]);

                        json([
                            'csrf' => $this->security->get_csrf_hash(),
                            'type' => 'success',
                            'message' => lang('success_edited'),
                        ]);
                        break;

                    case 'skrill':
                        $edit_email_skrill_payment = $this->input->post('edit_email_skrill_payment', true);

                        if (empty($edit_name_payment)) {
                            json([
                                'csrf' => $this->security->get_csrf_hash(),
                                'type' => 'error',
                                'message' => sprintf(lang('error_empty_field'), lang('input_name')),
                            ]);
                        }

                        if (empty($edit_minimal_payment) || $edit_maximal_payment == '') {
                            json([
                                'csrf' => $this->security->get_csrf_hash(),
                                'type' => 'error',
                                'message' => sprintf(lang('error_empty_fields'), lang('minimal_payment'), lang('maximal_payment')),
                            ]);
                        }

                        if (!is_numeric($edit_minimal_payment) || !is_numeric($edit_maximal_payment)) {
                            json([
                                'csrf' => $this->security->get_csrf_hash(),
                                'type' => 'error',
                                'message' => sprintf(lang('error_alpha_numeric_fields'), lang('minimal_payment'), lang('maximal_payment')),
                            ]);
                        }

                        if ($edit_maximal_payment != 0 && $edit_minimal_payment > $edit_maximal_payment) {
                            json([
                                'csrf' => $this->security->get_csrf_hash(),
                                'type' => 'error',
                                'message' => lang('error_payment_min_cannot_higher_max'),
                            ]);
                        }

                        $this->model->one_update($this->table, 'name', 'skrill_name', ['value' => $edit_name_payment, 'updated_at' => NOW]);
                        $this->model->one_update($this->table, 'name', 'skrill_min_payment', ['value' => ($edit_minimal_payment == 0 ? 1 : $edit_minimal_payment), 'updated_at' => NOW]);
                        $this->model->one_update($this->table, 'name', 'skrill_max_payment', ['value' => $edit_maximal_payment, 'updated_at' => NOW]);
                        $this->model->one_update($this->table, 'name', 'skrill_email', ['value' => $edit_email_skrill_payment, 'updated_at' => NOW]);

                        json([
                            'csrf' => $this->security->get_csrf_hash(),
                            'type' => 'success',
                            'message' => lang('success_edited'),
                        ]);
                        break;

                    case 'payumoney':
                        $edit_merchant_key_payment = $this->input->post('edit_merchant_key_payment', true);
                        $edit_merchant_salt_payment = $this->input->post('edit_merchant_salt_payment', true);

                        if (!in_array($edit_environment_payment, ['Sandbox', 'Live'])) {
                            json([
                                'csrf' => $this->security->get_csrf_hash(),
                                'type' => 'error',
                                'message' => lang('error_environment_invalid'),
                            ]);
                        }

                        if (empty($edit_name_payment)) {
                            json([
                                'csrf' => $this->security->get_csrf_hash(),
                                'type' => 'error',
                                'message' => sprintf(lang('error_empty_field'), lang('input_name')),
                            ]);
                        }

                        if (empty($edit_minimal_payment) || $edit_maximal_payment == '') {
                            json([
                                'csrf' => $this->security->get_csrf_hash(),
                                'type' => 'error',
                                'message' => sprintf(lang('error_empty_fields'), lang('minimal_payment'), lang('maximal_payment')),
                            ]);
                        }

                        if (!is_numeric($edit_minimal_payment) || !is_numeric($edit_maximal_payment)) {
                            json([
                                'csrf' => $this->security->get_csrf_hash(),
                                'type' => 'error',
                                'message' => sprintf(lang('error_alpha_numeric_fields'), lang('minimal_payment'), lang('maximal_payment')),
                            ]);
                        }

                        if ($edit_maximal_payment != 0 && $edit_minimal_payment > $edit_maximal_payment) {
                            json([
                                'csrf' => $this->security->get_csrf_hash(),
                                'type' => 'error',
                                'message' => lang('error_payment_min_cannot_higher_max'),
                            ]);
                        }

                        $this->model->one_update($this->table, 'name', 'payumoney_environment', ['value' => $edit_environment_payment, 'updated_at' => NOW]);
                        $this->model->one_update($this->table, 'name', 'payumoney_name', ['value' => $edit_name_payment, 'updated_at' => NOW]);
                        $this->model->one_update($this->table, 'name', 'payumoney_merchant_key', ['value' => $edit_merchant_key_payment, 'updated_at' => NOW]);
                        $this->model->one_update($this->table, 'name', 'payumoney_merchant_salt', ['value' => $edit_merchant_salt_payment, 'updated_at' => NOW]);
                        $this->model->one_update($this->table, 'name', 'payumoney_min_payment', ['value' => ($edit_minimal_payment == 0 ? 1 : $edit_minimal_payment), 'updated_at' => NOW]);
                        $this->model->one_update($this->table, 'name', 'payumoney_max_payment', ['value' => $edit_maximal_payment, 'updated_at' => NOW]);

                        json([
                            'csrf' => $this->security->get_csrf_hash(),
                            'type' => 'success',
                            'message' => lang('success_edited'),
                        ]);
                        break;

                    case 'paytm':
                        $edit_paytm_merchant_key_payment = $this->input->post('edit_paytm_merchant_key_payment', true);
                        $edit_merchant_id_payment = $this->input->post('edit_merchant_id_payment', true);
                        $edit_merchant_website_payment = $this->input->post('edit_merchant_website_payment', true);

                        if (!in_array($edit_environment_payment, ['Sandbox', 'Live'])) {
                            json([
                                'csrf' => $this->security->get_csrf_hash(),
                                'type' => 'error',
                                'message' => lang('error_environment_invalid'),
                            ]);
                        }

                        if (empty($edit_name_payment)) {
                            json([
                                'csrf' => $this->security->get_csrf_hash(),
                                'type' => 'error',
                                'message' => sprintf(lang('error_empty_field'), lang('input_name')),
                            ]);
                        }

                        if (empty($edit_minimal_payment) || $edit_maximal_payment == '') {
                            json([
                                'csrf' => $this->security->get_csrf_hash(),
                                'type' => 'error',
                                'message' => sprintf(lang('error_empty_fields'), lang('minimal_payment'), lang('maximal_payment')),
                            ]);
                        }

                        if (!is_numeric($edit_minimal_payment) || !is_numeric($edit_maximal_payment)) {
                            json([
                                'csrf' => $this->security->get_csrf_hash(),
                                'type' => 'error',
                                'message' => sprintf(lang('error_alpha_numeric_fields'), lang('minimal_payment'), lang('maximal_payment')),
                            ]);
                        }

                        if ($edit_maximal_payment != 0 && $edit_minimal_payment > $edit_maximal_payment) {
                            json([
                                'csrf' => $this->security->get_csrf_hash(),
                                'type' => 'error',
                                'message' => lang('error_payment_min_cannot_higher_max'),
                            ]);
                        }

                        $this->model->one_update($this->table, 'name', 'paytm_environment', ['value' => $edit_environment_payment, 'updated_at' => NOW]);
                        $this->model->one_update($this->table, 'name', 'paytm_name', ['value' => $edit_name_payment, 'updated_at' => NOW]);
                        $this->model->one_update($this->table, 'name', 'paytm_merchant_key', ['value' => $edit_paytm_merchant_key_payment, 'updated_at' => NOW]);
                        $this->model->one_update($this->table, 'name', 'paytm_merchant_mid', ['value' => $edit_merchant_id_payment, 'updated_at' => NOW]);
                        $this->model->one_update($this->table, 'name', 'paytm_merchant_website', ['value' => $edit_merchant_website_payment, 'updated_at' => NOW]);
                        $this->model->one_update($this->table, 'name', 'paytm_min_payment', ['value' => ($edit_minimal_payment == 0 ? 1 : $edit_minimal_payment), 'updated_at' => NOW]);
                        $this->model->one_update($this->table, 'name', 'paytm_max_payment', ['value' => $edit_maximal_payment, 'updated_at' => NOW]);

                        json([
                            'csrf' => $this->security->get_csrf_hash(),
                            'type' => 'success',
                            'message' => lang('success_edited'),
                        ]);
                        break;

                    case 'instamojo':
                        $edit_api_key_payment = $this->input->post('edit_api_key_payment', true);
                        $edit_auth_token_payment = $this->input->post('edit_auth_token_payment', true);

                        if (!in_array($edit_environment_payment, ['Sandbox', 'Live'])) {
                            json([
                                'csrf' => $this->security->get_csrf_hash(),
                                'type' => 'error',
                                'message' => lang('error_environment_invalid'),
                            ]);
                        }

                        if (empty($edit_name_payment)) {
                            json([
                                'csrf' => $this->security->get_csrf_hash(),
                                'type' => 'error',
                                'message' => sprintf(lang('error_empty_field'), lang('input_name')),
                            ]);
                        }

                        if (empty($edit_minimal_payment) || $edit_maximal_payment == '') {
                            json([
                                'csrf' => $this->security->get_csrf_hash(),
                                'type' => 'error',
                                'message' => sprintf(lang('error_empty_fields'), lang('minimal_payment'), lang('maximal_payment')),
                            ]);
                        }

                        if (!is_numeric($edit_minimal_payment) || !is_numeric($edit_maximal_payment)) {
                            json([
                                'csrf' => $this->security->get_csrf_hash(),
                                'type' => 'error',
                                'message' => sprintf(lang('error_alpha_numeric_fields'), lang('minimal_payment'), lang('maximal_payment')),
                            ]);
                        }

                        if ($edit_maximal_payment != 0 && $edit_minimal_payment > $edit_maximal_payment) {
                            json([
                                'csrf' => $this->security->get_csrf_hash(),
                                'type' => 'error',
                                'message' => lang('error_payment_min_cannot_higher_max'),
                            ]);
                        }

                        $this->model->one_update($this->table, 'name', 'instamojo_environment', ['value' => $edit_environment_payment, 'updated_at' => NOW]);
                        $this->model->one_update($this->table, 'name', 'instamojo_name', ['value' => $edit_name_payment, 'updated_at' => NOW]);
                        $this->model->one_update($this->table, 'name', 'instamojo_api_key', ['value' => $edit_api_key_payment, 'updated_at' => NOW]);
                        $this->model->one_update($this->table, 'name', 'instamojo_auth_token', ['value' => $edit_auth_token_payment, 'updated_at' => NOW]);
                        $this->model->one_update($this->table, 'name', 'instamojo_min_payment', ['value' => ($edit_minimal_payment == 0 ? 1 : $edit_minimal_payment), 'updated_at' => NOW]);
                        $this->model->one_update($this->table, 'name', 'instamojo_max_payment', ['value' => $edit_maximal_payment, 'updated_at' => NOW]);

                        json([
                            'csrf' => $this->security->get_csrf_hash(),
                            'type' => 'success',
                            'message' => lang('success_edited'),
                        ]);
                        break;

                    case 'mollie':
                        $edit_mollie_api_key_payment = $this->input->post('edit_mollie_api_key_payment', true);

                        if (empty($edit_name_payment)) {
                            json([
                                'csrf' => $this->security->get_csrf_hash(),
                                'type' => 'error',
                                'message' => sprintf(lang('error_empty_field'), lang('input_name')),
                            ]);
                        }

                        if (empty($edit_minimal_payment) || $edit_maximal_payment == '') {
                            json([
                                'csrf' => $this->security->get_csrf_hash(),
                                'type' => 'error',
                                'message' => sprintf(lang('error_empty_fields'), lang('minimal_payment'), lang('maximal_payment')),
                            ]);
                        }

                        if (!is_numeric($edit_minimal_payment) || !is_numeric($edit_maximal_payment)) {
                            json([
                                'csrf' => $this->security->get_csrf_hash(),
                                'type' => 'error',
                                'message' => sprintf(lang('error_alpha_numeric_fields'), lang('minimal_payment'), lang('maximal_payment')),
                            ]);
                        }

                        if ($edit_maximal_payment != 0 && $edit_minimal_payment > $edit_maximal_payment) {
                            json([
                                'csrf' => $this->security->get_csrf_hash(),
                                'type' => 'error',
                                'message' => lang('error_payment_min_cannot_higher_max'),
                            ]);
                        }

                        $this->model->one_update($this->table, 'name', 'mollie_name', ['value' => $edit_name_payment, 'updated_at' => NOW]);
                        $this->model->one_update($this->table, 'name', 'mollie_api_key', ['value' => $edit_mollie_api_key_payment, 'updated_at' => NOW]);
                        $this->model->one_update($this->table, 'name', 'mollie_min_payment', ['value' => ($edit_minimal_payment == 0 ? 1 : $edit_minimal_payment), 'updated_at' => NOW]);
                        $this->model->one_update($this->table, 'name', 'mollie_max_payment', ['value' => $edit_maximal_payment, 'updated_at' => NOW]);

                        json([
                            'csrf' => $this->security->get_csrf_hash(),
                            'type' => 'success',
                            'message' => lang('success_edited'),
                        ]);
                        break;

                    case 'razorpay':
                        $edit_key_id_payment = $this->input->post('edit_key_id_payment', true);
                        $edit_key_secret_payment = $this->input->post('edit_key_secret_payment', true);

                        if (empty($edit_name_payment)) {
                            json([
                                'csrf' => $this->security->get_csrf_hash(),
                                'type' => 'error',
                                'message' => sprintf(lang('error_empty_field'), lang('input_name')),
                            ]);
                        }

                        if (empty($edit_minimal_payment) || $edit_maximal_payment == '') {
                            json([
                                'csrf' => $this->security->get_csrf_hash(),
                                'type' => 'error',
                                'message' => sprintf(lang('error_empty_fields'), lang('minimal_payment'), lang('maximal_payment')),
                            ]);
                        }

                        if (!is_numeric($edit_minimal_payment) || !is_numeric($edit_maximal_payment)) {
                            json([
                                'csrf' => $this->security->get_csrf_hash(),
                                'type' => 'error',
                                'message' => sprintf(lang('error_alpha_numeric_fields'), lang('minimal_payment'), lang('maximal_payment')),
                            ]);
                        }

                        if ($edit_maximal_payment != 0 && $edit_minimal_payment > $edit_maximal_payment) {
                            json([
                                'csrf' => $this->security->get_csrf_hash(),
                                'type' => 'error',
                                'message' => lang('error_payment_min_cannot_higher_max'),
                            ]);
                        }

                        $this->model->one_update($this->table, 'name', 'razorpay_name', ['value' => $edit_name_payment, 'updated_at' => NOW]);
                        $this->model->one_update($this->table, 'name', 'razorpay_key_id', ['value' => $edit_key_id_payment, 'updated_at' => NOW]);
                        $this->model->one_update($this->table, 'name', 'razorpay_key_secret', ['value' => $edit_key_secret_payment, 'updated_at' => NOW]);
                        $this->model->one_update($this->table, 'name', 'razorpay_min_payment', ['value' => ($edit_minimal_payment == 0 ? 1 : $edit_minimal_payment), 'updated_at' => NOW]);
                        $this->model->one_update($this->table, 'name', 'razorpay_max_payment', ['value' => $edit_maximal_payment, 'updated_at' => NOW]);

                        json([
                            'csrf' => $this->security->get_csrf_hash(),
                            'type' => 'success',
                            'message' => lang('success_edited'),
                        ]);
                        break;
                }
            }

            json([
                'csrf' => $this->security->get_csrf_hash(),
                'type' => 'error',
                'message' => lang("demo"),
            ]);
        }
    }

    public function updateStatusPayment($type)
    {
        if (DEMO_VERSION != true) {
            if (isset($_POST) && !empty($_POST)) {
                $payment_status = $this->input->post('payment_status_' . $type, true);
                $payment_status = (empty($payment_status) || $payment_status == '' ? 'off' : 'on');

                switch ($type) {
                    case 'paypal':
                        $name_column = 'paypal_status';
                        break;

                    case 'pagseguro':
                        $name_column = 'pagseguro_status';
                        break;

                    case 'mercadopago':
                        $name_column = 'mercadopago_status';
                        break;

                    case 'stripe':
                        $name_column = 'stripe_status';
                        break;

                    case 'twocheckout':
                        $name_column = '2checkout_status';
                        break;

                    case 'coinpayments':
                        $name_column = 'coinpayments_status';
                        break;

                    case 'skrill':
                        $name_column = 'skrill_status';
                        break;

                    case 'payumoney':
                        $name_column = 'payumoney_status';
                        break;

                    case 'paytm':
                        $name_column = 'paytm_status';
                        break;

                    case 'instamojo':
                        $name_column = 'instamojo_status';
                        break;

                    case 'mollie':
                        $name_column = 'mollie_status';
                        break;

                    case 'razorpay':
                        $name_column = 'razorpay_status';
                        break;

                    case 'manual':
                        $name_column = 'manual_status';
                        break;
                }

                $this->model->one_update($this->table, 'name', $name_column, ['value' => $payment_status, 'updated_at' => NOW]);
            }
        }
    }
}
