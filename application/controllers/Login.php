<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Login extends MY_Controller
{
    protected $table_users;

    public function __construct()
    {
        parent::__construct();

        $this->table_users = TABLE_USERS;

        $this->load->model(['login_model', 'user_model']);
    }

    public function index()
    {
        if (logged()) return redirect(base_url());

        $data = [
            'title' => configs('app_title', 'value'),
        ];

        view("pages/login", $data);

        $this->store(); // Auth login
    }

    public function store()
    {
        $this->load->library('recaptcha'); // loading library recaptcha

        if (isset($_POST) && !empty($_POST)) {
            $email = $this->input->post('email', true);
            $password = $this->input->post('password', true);
            $recaptchaResponse = $this->input->post('g-recaptcha-response');

            // Recaptcha v2 Google
            $recaptcha = $this->recaptcha->recaptcha(configs('recaptcha_private_key', 'value'), $recaptchaResponse);

            $this->login_model->validate_form();

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

            if (notification('email_verification_new_account', 'value') == 1 && dataUser($email, 'status') == 'Inactive') {
                $subject = email_template('verification_account')->subject;
                $subject = str_replace("{{app_name}}", configs('app_title', 'value'), $subject);
                $email_content = email_template("verification_account")->content;
                $email_content = str_replace("{{app_name}}", configs('app_title', 'value'), $email_content);
                $email_content = str_replace("{{username}}", dataUser($email, 'username'), $email_content);
                $email_content = str_replace("{{activation_link}}", base_url("activation/account/" . dataUser($email, 'activation_token')), $email_content);

                email_send($email, $subject, $email_content);

                json([
                    'csrf' => $this->security->get_csrf_hash(),
                    'type' => 'error',
                    'message' => lang("error_account_has_not_been_activated"),
                    'captcha' => configs('google_recaptcha', 'value')
                ]);
            }

            if ($this->login_model->resolve_login($this->table_users, $email, $password)) {
                if (!$recaptcha->success and configs('google_recaptcha', 'value') == 'on') {
                    json([
                        'csrf' => $this->security->get_csrf_hash(),
                        'type' => 'error',
                        'message' => lang("error_captcha"),
                        'captcha' => configs('google_recaptcha', 'value')
                    ]);
                }

                if (dataUser($email, 'role') == 'BANNED') { // Verify user ban
                    json([
                        'csrf' => $this->security->get_csrf_hash(),
                        'type' => 'error',
                        'message' => lang("error_banned"),
                        'captcha' => configs('google_recaptcha', 'value')
                    ]);
                }

                set_session('email', $email);
                json([
                    'csrf' => $this->security->get_csrf_hash(),
                    'type' => 'success',
                    'base_url' => base_url('your/dashboard'),
                    'captcha' => configs('google_recaptcha', 'value')
                ]);
            }

            json([
                'csrf' => $this->security->get_csrf_hash(),
                'type' => 'error',
                'message' => lang("error_login"),
                'captcha' => configs('google_recaptcha', 'value')
            ]);
        }
    }

    public function destroy()
    {
        if (logged()) {
            $this->session->sess_destroy();
        }

        redirect(base_url());
    }
}
