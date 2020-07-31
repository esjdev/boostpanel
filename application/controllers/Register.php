<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Register extends MY_Controller
{
    protected $table_users;

    public function __construct()
    {
        parent::__construct();
        if (logged() || configs('registration_page', 'value') != 'on') return redirect(base_url());

        $this->table_users = TABLE_USERS;

        $this->load->model('register_model');
    }

    public function index()
    {
        $data = ['title' => configs('app_title', 'value') . " - " . lang("title_create_account")];

        view('pages/register', $data);

        $this->store(); // Insert new account
    }

    public function store()
    {
        $this->load->library('recaptcha'); // loading library recaptcha

        if (isset($_POST) && !empty($_POST)) {
            // Forms Register
            $termsofservice = $this->input->post("termsofservice", true);
            $name = $this->input->post('name', true);
            $username = $this->input->post('username', true);
            $email = $this->input->post('email', true);
            $re_password = $this->input->post('re-password', true);
            $recaptchaResponse = $this->input->post('g-recaptcha-response');

            // Recaptcha v2 Google
            $recaptcha = $this->recaptcha->recaptcha(configs('recaptcha_private_key', 'value'), $recaptchaResponse);

            $this->register_model->validate_form();

            if ($this->form_validation->run() == false) {
                foreach ($this->form_validation->error_array() as $key => $value) {
                    json([
                        'csrf' => $this->security->get_csrf_hash(),
                        'type' => 'error',
                        'message' => form_error($key, false, false),
                        'captcha' => configs('google_recaptcha', 'value')
                    ]);
                }
            }

            if (!$recaptcha->success and configs('google_recaptcha', 'value') == 'on') {
                json([
                    'csrf' => $this->security->get_csrf_hash(),
                    'type' => 'error',
                    'message' => lang("error_captcha"),
                    'captcha' => configs('google_recaptcha', 'value')
                ]);
            }

            if (!isset($termsofservice)) {
                json([
                    'csrf' => $this->security->get_csrf_hash(),
                    'type' => 'error',
                    'message' => lang("error_agree_not_terms"),
                    'captcha' => configs('google_recaptcha', 'value')
                ]);
            }

            $timezone = config('timezone');

            $data = [
                'uuid' => uuid(),
                'name' => $name,
                'username' => $username,
                'email' => $email,
                'password' => password_hash($re_password, PASSWORD_DEFAULT),
                'timezone' => $timezone,
                'balance' => 0,
                'role' => 'USER',
                'api_key' => create_random_api_key(""),
                'activation_token' => (notification('email_verification_new_account', 'value') == 1) ? token_rand() : 0,
                'custom_rate' => 0,
                'status' => (notification('email_verification_new_account', 'value') == 1) ? 'Inactive' : 'Active',
                'created_at' => NOW,
            ];
            $this->model->insert($this->table_users, $data);

            $id = $this->db->insert_id();

            // Verify User Login
            $user = $this->model->get('*', $this->table_users, ['id' => $id], '', '', true);

            if (notification('email_verification_new_account', 'value') == 1) {
                $subject = (email_tpl('verification_account_subject', 'value') == '') ? email_template('verification_account')->subject : email_tpl('verification_account_subject', 'value');
                $subject = str_replace("{{app_name}}", configs('app_title', 'value'), $subject);
                $email_content = (email_tpl('verification_account_content', 'value') == '') ? email_template('verification_account')->content : email_tpl('verification_account_content', 'value');
                $email_content = str_replace("{{app_name}}", configs('app_title', 'value'), $email_content);
                $email_content = str_replace("{{username}}", $username, $email_content);
                $email_content = str_replace("{{activation_link}}", base_url("activation/account/" . $user['activation_token']), $email_content);

                email_send($email, $subject, $email_content);

                json([
                    'csrf' => $this->security->get_csrf_hash(),
                    'type' => 'success',
                    'message' => lang("success_email_verification_new_account_message"),
                    'captcha' => configs('google_recaptcha', 'value')
                ]);
            }

            if (notification('new_user_welcome', 'value') == 1) {
                $subject = (email_tpl('welcome_user_subject', 'value') == '') ? email_template('welcome_user')->subject : email_tpl('welcome_user_subject', 'value');
                $subject = str_replace("{{app_name}}", configs('app_title', 'value'), $subject);
                $email_content = (email_tpl('welcome_user_content', 'value') == '') ? email_template('welcome_user')->content : email_tpl('welcome_user_content', 'value');
                $email_content = str_replace("{{app_name}}", configs('app_title', 'value'), $email_content);
                $email_content = str_replace("{{username}}", $username, $email_content);
                $email_content = str_replace("{{name}}", $name, $email_content);
                $email_content = str_replace("{{user_email}}", $email, $email_content);
                $email_content = str_replace("{{user_timezone}}", $timezone, $email_content);

                email_send($email, $subject, $email_content);
            }

            json([
                'csrf' => $this->security->get_csrf_hash(),
                'type' => 'success',
                'message' => lang("success_register"),
                'captcha' => configs('google_recaptcha', 'value')
            ]);
        }
    }

    public function activation($token)
    {
        $active = $this->model->get('*', $this->table_users, ['activation_token' => $token], '', '', true);

        if (!empty($active) && $active['status'] == 'Inactive') {
            $data = [
                'title' => lang("title_activating_account"),
                'user' => $active,
            ];

            $this->model->update($this->table_users, ['activation_token' => $token], ['activation_token' => '0', 'status' => 'Active']);

            if (notification('new_user_welcome', 'value') == 1) {
                $subject = (email_tpl('welcome_user_subject', 'value') == '') ? email_template('welcome_user')->subject : email_tpl('welcome_user_subject', 'value');
                $subject = str_replace("{{app_name}}", configs('app_title', 'value'), $subject);
                $email_content = (email_tpl('welcome_user_content', 'value') == '') ? email_template('welcome_user')->content : email_tpl('welcome_user_content', 'value');
                $email_content = str_replace("{{app_name}}", configs('app_title', 'value'), $email_content);
                $email_content = str_replace("{{username}}", $active['username'], $email_content);
                $email_content = str_replace("{{name}}", $active['name'], $email_content);
                $email_content = str_replace("{{user_email}}", $active['email'], $email_content);
                $email_content = str_replace("{{user_timezone}}", $active['timezone'], $email_content);

                email_send($active['email'], $subject, $email_content);
            }

            if (notification('new_user_notification', 'value') == 1) {
                $subject = (email_tpl('new_user_to_admin_subject', 'value') == '') ? email_template('new_user_to_admin')->subject : email_tpl('new_user_to_admin_subject', 'value');
                $subject = str_replace("{{app_name}}", configs('app_title', 'value'), $subject);
                $email_content = (email_tpl('new_user_to_admin_content', 'value') == '') ? email_template('new_user_to_admin')->content : email_tpl('new_user_to_admin_content', 'value');
                $email_content = str_replace("{{app_name}}", configs('app_title', 'value'), $email_content);
                $email_content = str_replace("{{username}}", $active['username'], $email_content);
                $email_content = str_replace("{{name}}", $active['name'], $email_content);
                $email_content = str_replace("{{user_email}}", $active['email'], $email_content);
                $email_content = str_replace("{{user_timezone}}", $active['timezone'], $email_content);

                email_send(configs('email', 'value'), $subject, $email_content);
            }

            view('layouts/header', $data);
            view('panel/admin/management/settings/email_actived');
            view('layouts/footer');
        } else {
            redirect(base_url());
        }
    }
}
