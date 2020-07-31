<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Home extends MY_Controller
{
    protected $table_user;
    protected $table_orders;

    public function __construct()
    {
        parent::__construct();

        $this->table_user = TABLE_USERS;
        $this->table_orders = TABLE_ORDERS;
    }

    public function index()
    {
        $data = [
            'title' => configs('app_title', 'value') . " - " . lang("slogan"),

            'total_users_satisfied' => $this->model->counts_join('*', $this->table_user, 'orders', 'orders.user_id = users.id', 'users.role = "USER" AND orders.status = "completed"'),
            'total_orders' => $this->model->counts($this->table_orders),
            'total_users' => $this->model->counts($this->table_user, ['role' => 'USER']),
        ];

        view('layouts/header', $data);
        view('pages/home');
        view('layouts/footer');
    }
}
