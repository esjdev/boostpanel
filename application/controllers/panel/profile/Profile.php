<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Profile extends MY_Controller
{
    protected $table_user;
    private $user;

    public function __construct()
    {
        parent::__construct();

        if (!logged()) return redirect(base_url());

        $this->table_user = TABLE_USERS;

        $this->load->model("panel/profile/profile_model");
        $this->user = $this->model->get('*', $this->table_user, ['email' => logged()], '', '', true);
    }

    public function index()
    {
        $data = ['title' => lang("title_your_profile")];

        view('layouts/auth_header', $data);
		view('panel/profile/profile_show');
		view('layouts/auth_footer');
    }

    public function update()
    {
        if (isset($_POST) && !empty($_POST)) {
            if (DEMO_VERSION != true) {
                $password_current = $this->input->post('password_current', true);
                $cf_new_password = $this->input->post('cf_new_password', true);

                $this->profile_model->validate_form();

                if ($this->form_validation->run() == false) {
                    foreach ($this->form_validation->error_array() as $key => $value) {
                        json([
                            'csrf' => $this->security->get_csrf_hash(),
                            'type' => 'error',
                            'message' => form_error($key, false, false),
                        ]);
                    }
                }

                if (!password_verify($password_current, $this->user['password'])) {
                    json([
                        'csrf' => $this->security->get_csrf_hash(),
                        'type' => 'error',
                        'message' => lang("error_pass_equal"),
                    ]);
                }

                if ($password_current == $cf_new_password) {
                    json([
                        'csrf' => $this->security->get_csrf_hash(),
                        'type' => 'error',
                        'message' => lang("error_password_equal"),
                    ]);
                }

                $this->model->update($this->table_user, ['email' => $this->user['email']], ['password' => password_hash($cf_new_password, PASSWORD_DEFAULT)]);

                json([
                    'csrf' => $this->security->get_csrf_hash(),
                    'type' => 'success',
                    'message' => lang("success_change_profile"),
                ]);
            }

            json([
                'csrf' => $this->security->get_csrf_hash(),
                'type' => 'error',
                'message' => lang("demo"),
            ]);
        }
    }

    public function change_timezone()
    {
        if (isset($_POST) && !empty($_POST)) {
            if (DEMO_VERSION != true) {
                $timezone = $this->input->post("timezone", true);

                if ($timezone == 'noselect') {
                    json([
                        'csrf' => $this->security->get_csrf_hash(),
                        'type' => 'error',
                        'message' => lang("error_select_timezone"),
                    ]);
                }

                $this->model->update($this->table_user, ['email' => $this->user['email']], ['timezone' => $timezone]);

                json([
                    'csrf' => $this->security->get_csrf_hash(),
                    'type' => 'success',
                    'message' => lang("success_change_timezone"),
                ]);
            }

            json([
                'csrf' => $this->security->get_csrf_hash(),
                'type' => 'error',
                'message' => lang("demo"),
            ]);
        }
    }

    public function generate_new_token_api()
    {
        if (DEMO_VERSION != true) {
            if (userLevel(logged(), 'user')) {
                $this->model->update($this->table_user, ['email' => $this->user['email']], ['api_key' => create_random_api_key("")]);
            }
        }
        redirect(base_url('profile'));
    }
}
