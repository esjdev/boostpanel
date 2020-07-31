<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Recover extends MY_Controller
{
    protected $table;
    protected $table_users;

    public function __construct()
    {
        parent::__construct();

        if (logged()) return redirect(base_url());

        $this->table = TABLE_PASSWORD_RESETS;
        $this->table_users = TABLE_USERS;

        $this->load->model('recover_model');
    }

    public function index()
    {
        $data = ['title' => configs('app_title', 'value') . " - " . lang("title_recover_password_page")];

        view("pages/recover", $data);

        $this->store(); // send email recover password
    }

    public function store()
    {
        $this->load->library('recaptcha'); // loading library recaptcha

        if (isset($_POST) && !empty($_POST)) {
            if (DEMO_VERSION != true) {
                if (configs('protocol', 'value') != '') {
                    // Forms Contact
                    $email = $this->input->post('email_recover', true);
                    $recaptchaResponse = $this->input->post('g-recaptcha-response'); // Input recaptcha Google

                    $recaptcha = $this->recaptcha->recaptcha(configs('recaptcha_private_key', 'value'), $recaptchaResponse); // Recatpcha Google

                    // Return id Email
                    $id_user = $this->model->get('*', $this->table_users, ['email' => $email], '', '', true);

                    // Verify email Database
                    $verify_email = $this->model->counts($this->table_users, ['email' => $email]);

                    $this->recover_model->validate_form(true);

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

                    if ($verify_email == 0) {
                        json([
                            'csrf' => $this->security->get_csrf_hash(),
                            'type' => 'error',
                            'message' => lang("error_email_not_exists"),
                            'captcha' => configs('google_recaptcha', 'value')
                        ]);
                    }

                    $token = md5(time() . rand(0, 999) . $id_user['email'] . time()); // Generate Token

                    if (!$recaptcha->success and configs('google_recaptcha', 'value') == 'on') {
                        json([
                            'csrf' => $this->security->get_csrf_hash(),
                            'type' => 'error',
                            'message' => lang("error_captcha"),
                            'captcha' => configs('google_recaptcha', 'value')
                        ]);
                    }

                    if (configs('protocol', 'value') == 'mail' || configs('protocol', 'value') == 'smtp') {
                        $subject = (email_tpl('link_recover_password_subject', 'value') == '') ? email_template('recover_password_link')->subject : email_tpl('link_recover_password_subject', 'value');
                        $subject = str_replace("{{app_name}}", configs('app_title', 'value'), $subject);
                        $email_content = (email_tpl('link_recover_password_content', 'value') == '') ? email_template('recover_password_link')->content : email_tpl('link_recover_password_content', 'value');
                        $email_content = str_replace("{{username}}", $id_user['username'], $email_content);
                        $email_content = str_replace("{{recover_password_link}}", base_url('recover/token/' . $token), $email_content);

                        email_send($email, $subject, $email_content);

                        $counts_resets = $this->model->counts($this->table, ['id_user' => $id_user['id']]);

                        if ($counts_resets > 0) {
                            $this->model->delete($this->table, ['id_user' => $id_user['id']]);
                        }

                        $this->model->insert($this->table, ['id_user' => $id_user['id'], 'hash' => $token]);

                        json([
                            'csrf' => $this->security->get_csrf_hash(),
                            'type' => 'success',
                            'message' => lang("success_send_recover_email"),
                            'captcha' => configs('google_recaptcha', 'value')
                        ]);
                    }
                } else {
                    redirect(base_url('recover'));
                }
            }

            json([
                'csrf' => $this->security->get_csrf_hash(),
                'type' => 'error',
                'message' => lang("demo"),
            ]);
        }
    }

    public function show($token = false)
    {
        if ($token) {
            $data = [
                'title' => lang("title_changing_password_via_token"),
                // Hash token
                "hash" => $token,
            ];

            $hash = $this->model->counts($this->table, ['hash' => $token]);
            $id = $this->model->get('*', $this->table, ['hash' => $token], '', '', true);

            if ($hash > 0) {
                view('layouts/header', $data);
                view('pages/action-recover/action-change-password/change_pass');
                view('layouts/footer');

                if (isset($_POST) && !empty($_POST)) {
                    if (DEMO_VERSION != true) {
                        $re_pass_new = $this->input->post('re_pass_new', true);

                        $this->recover_model->validate_form(false);

                        if ($this->form_validation->run() == false) {
                            foreach ($this->form_validation->error_array() as $key => $value) {
                                json([
                                    'csrf' => $this->security->get_csrf_hash(),
                                    'type' => 'error',
                                    'message' => form_error($key, false, false),
                                ]);
                            }
                        }

                        $userPass = $this->model->get('*', $this->table_users, ['id' => $id['id_user']], '', '', true);

                        if (password_verify($re_pass_new, $userPass['password'])) {
                            json([
                                'csrf' => $this->security->get_csrf_hash(),
                                'type' => 'error',
                                'message' => lang('error_password_equal'),
                            ]);
                        }

                        $this->model->update($this->table_users, ['id' => $id['id_user']], ['password' => password_hash($re_pass_new, PASSWORD_DEFAULT)]);
                        $this->model->delete($this->table, ['hash' => $token]);

                        json([
                            'csrf' => $this->security->get_csrf_hash(),
                            'type' => 'success',
                            'message' => lang("success_recover_pass") . "<br><b>" . lang("success_token_redirect_success") . "</b>",
                            'base_url' => base_url('login'),
                        ]);
                    }

                    json([
                        'csrf' => $this->security->get_csrf_hash(),
                        'type' => 'error',
                        'message' => lang("demo"),
                    ]);
                }
            } else {
                redirect(base_url());
            }
        }
    }
}
