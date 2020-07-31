<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Addbalance extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        if (!userLevel(logged(), 'user')) return redirect(base_url());
    }

    public function index()
    {
        $data = [
            'title' => lang("title_add_balance"),
        ];

        view('layouts/auth_header', $data);
        view('panel/addbalance/addbalance_index');
        view('layouts/auth_footer');
    }
}
