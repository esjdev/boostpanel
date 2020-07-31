<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Subscriptions extends MY_Controller
{
    protected $table;

    public function __construct()
    {
        parent::__construct();

        if (!userLevel(logged(), 'user')) return redirect(base_url());

        $this->table = TABLE_ORDERS;
    }

    public function all()
    {
        $this->load->library('pagination');

        $config = [
            'base_url' => base_url('subscriptions'),
            'total_rows' => $this->model->counts($this->table, ['user_id' => dataUser(logged(), 'id'), 'service_type' => 'subscriptions']),
            'per_page' => 20,
            'uri_segment' => 2,
            'use_page_numbers' => true,
            'reuse_query_string' => false,

            'first_link' => lang("pagination_first"),
            'last_link' => lang("pagination_last"),
            'full_tag_open' => '<div class="pagging text-center"><nav><ul class="pagination justify-content-center">',
            'full_tag_close' => '</ul></nav></div>',
            'num_tag_open' => '<li class="page-item">',
            'num_tag_close' => '</li>',
            'cur_tag_open' => '<li class="page-item active"><span class="page-link">',
            'cur_tag_close' => '<span class="sr-only">(current)</span></span></li>',
            'next_tag_open' => '<li class="page-item">',
            'next_tagl_close' => '<span aria-hidden="true">&raquo;</span></li>',
            'prev_tag_open' => '<li class="page-item">',
            'prev_tagl_close' => '</li>',
            'first_tag_open' => '<li class="page-item">',
            'first_tagl_close' => '</li>',
            'last_tag_open' => '<li class="page-item">',
            'last_tagl_close' => '</li>',
            'attributes' => ['class' => 'page-link'],
        ];

        $this->pagination->initialize($config);
        $page = (uri(2)) ? uri(2) : 0;
        $offset = $page == 0 ? 0 : ($page - 1) * $config["per_page"];

        $data = [
            'title' => lang("title_subscriptions"),
            'all_subscriptions' => $this->model->fetch('*', $this->table, ['user_id' => dataUser(logged(), 'id'), 'service_type' => 'subscriptions'], "updated_at", "desc", $offset, $config["per_page"]),
            'pagination_links' => $this->pagination->create_links(),
        ];

        view('layouts/auth_header', $data);
        view("panel/admin/management/history/user/subscriptions/all_subscription");
        view('layouts/auth_footer');
    }

    public function status_subscription($type, $id)
    {
        $subscription = $this->model->get('*', $this->table, ['user_id' => dataUser(logged(), 'id'), 'id' => $id, 'service_type' => 'subscriptions'], "", "", true);

        if (!in_array($subscription['status_sub'], ['Completed', 'Expired', 'Canceled'])) {
            switch ($type) {
                case 'pause':
                    $this->model->update($this->table, ['id' => $id, 'status_sub' => 'Active', 'user_id' => dataUser(logged(), 'id'), 'service_type' => 'subscriptions'], ['status_sub' => 'Paused', 'updated_at' => NOW]);
                    redirect(base_url('subscriptions'));
                    break;

                case 'resume':
                    $this->model->update($this->table, ['id' => $id, 'status_sub' => 'Paused', 'user_id' => dataUser(logged(), 'id'), 'service_type' => 'subscriptions'], ['status_sub' => 'Active', 'updated_at' => NOW]);
                    redirect(base_url('subscriptions'));
                    break;

                case 'stop':
                    $this->model->update($this->table, ['id' => $id, 'status_sub' => 'Paused', 'user_id' => dataUser(logged(), 'id'), 'service_type' => 'subscriptions'], ['status_sub' => 'Canceled', 'updated_at' => NOW]);
                    redirect(base_url('subscriptions'));
                    break;
            }
        }

        redirect(base_url("subscriptions"));
    }

    public function search()
    {
        if (isset($_POST) && !empty($_POST)) {
            $search = $this->input->post('searchSubscriptions', true);

            $data = [
                'search_subscriptions' => $this->model->search($this->table, 'updated_at', 'desc', ['id' => $search], ['username' => $search], ['user_id' => dataUser(logged(), 'id'), 'service_type' => 'subscriptions']),
            ];

            view('panel/admin/management/history/user/subscriptions/search_subscriptions', $data);
        }
    }

    public function subscription_for_status($type)
    {
        $this->load->library('pagination');

        $config = [
            'base_url' => base_url('subscriptions/type/' . $type),
            'total_rows' => $this->model->counts($this->table, ['user_id' => dataUser(logged(), 'id'), 'status_sub' => $type, 'service_type' => 'subscriptions']),
            'per_page' => 20,
            'uri_segment' => 4,
            'use_page_numbers' => true,
            'reuse_query_string' => false,

            'first_link' => lang("pagination_first"),
            'last_link' => lang("pagination_last"),
            'full_tag_open' => '<div class="pagging text-center"><nav><ul class="pagination justify-content-center">',
            'full_tag_close' => '</ul></nav></div>',
            'num_tag_open' => '<li class="page-item">',
            'num_tag_close' => '</li>',
            'cur_tag_open' => '<li class="page-item active"><span class="page-link">',
            'cur_tag_close' => '<span class="sr-only">(current)</span></span></li>',
            'next_tag_open' => '<li class="page-item">',
            'next_tagl_close' => '<span aria-hidden="true">&raquo;</span></li>',
            'prev_tag_open' => '<li class="page-item">',
            'prev_tagl_close' => '</li>',
            'first_tag_open' => '<li class="page-item">',
            'first_tagl_close' => '</li>',
            'last_tag_open' => '<li class="page-item">',
            'last_tagl_close' => '</li>',
            'attributes' => ['class' => 'page-link'],
        ];

        if (!in_array($type, ['active', 'paused', 'completed', 'expired', 'canceled'])) return redirect(base_url('subscriptions'));

        $this->pagination->initialize($config);
        $page = (uri(4)) ? uri(4) : 0;
        $offset = $page == 0 ? 0 : ($page - 1) * $config["per_page"];

        switch ($type) {
            case 'active':
                $data = [
                    'title' => lang("title_subscriptions") . " - " . lang("status_active"),
                    'subscriptions_for_status' => $this->model->fetch('*', $this->table, ['user_id' => dataUser(logged(), 'id'), 'status_sub' => 'Active', 'service_type' => 'subscriptions'], "updated_at", "desc", $offset, $config["per_page"]),
                    'pagination_links' => $this->pagination->create_links(),
                ];
                break;

            case 'paused':
                $data = [
                    'title' => lang("title_subscriptions") . " - " . lang("status_subs_paused"),
                    'subscriptions_for_status' => $this->model->fetch('*', $this->table, ['user_id' => dataUser(logged(), 'id'), 'status_sub' => 'Paused', 'service_type' => 'subscriptions'], "updated_at", "desc", $offset, $config["per_page"]),
                    'pagination_links' => $this->pagination->create_links(),
                ];
                break;

            case 'completed':
                $data = [
                    'title' => lang("title_subscriptions") . " - " . lang("status_completed"),
                    'subscriptions_for_status' => $this->model->fetch('*', $this->table, ['user_id' => dataUser(logged(), 'id'), 'status_sub' => 'Completed', 'service_type' => 'subscriptions'], "updated_at", "desc", $offset, $config["per_page"]),
                    'pagination_links' => $this->pagination->create_links(),
                ];
                break;

            case 'expired':
                $data = [
                    'title' => lang("title_subscriptions") . " - " . lang("status_subs_expired"),
                    'subscriptions_for_status' => $this->model->fetch('*', $this->table, [ 'user_id' => dataUser(logged(), 'id'), 'status_sub' => 'Expired', 'service_type' => 'subscriptions'], "updated_at", "desc", $offset, $config["per_page"]),
                    'pagination_links' => $this->pagination->create_links(),
                ];
                break;

            case 'canceled':
                $data = [
                    'title' => lang("title_subscriptions") . " - " . lang("status_canceled"),
                    'subscriptions_for_status' => $this->model->fetch('*', $this->table, ['user_id' => dataUser(logged(), 'id'), 'status_sub' => 'Canceled', 'service_type' => 'subscriptions'], "updated_at", "desc", $offset, $config["per_page"]),
                    'pagination_links' => $this->pagination->create_links(),
                ];
                break;
        }

        view('layouts/auth_header', $data);
        view("panel/admin/management/history/user/subscriptions/subscription_for_status");
        view('layouts/auth_footer');
    }
}
