<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Terms extends MY_Controller
{
    protected $table_configs;

    public function __construct()
    {
        parent::__construct();

        $this->table_configs = TABLE_CONFIG;
    }

    public function index()
    {
        $terms = $this->model->fetch('*', $this->table_configs, ['name' => 'terms_content']);
        $policy = $this->model->fetch('*', $this->table_configs, ['name' => 'policy_content']);

        $data = [
            'title' => lang("title_terms_privacy_policy"),
            'show_terms' => $terms,
            'show_policy' => $policy,
        ];

        if (!logged()) {
			view('layouts/header', $data);
		} else {
			view('layouts/auth_header', $data);
        }

        view('pages/terms');

        if (!logged()) {
			view('layouts/footer', $data);
		} else {
			view('layouts/auth_footer', $data);
        }
    }
}
