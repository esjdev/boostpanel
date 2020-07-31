<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Transaction_logs extends MY_Controller
{
    private $user;

    protected $table;
    protected $table_user;

    public function __construct()
    {
        parent::__construct();

        $this->table = TABLE_TRANSACTION_LOGS;
        $this->table_user = TABLE_USERS;

        $this->user = $this->model->get('*', $this->table_user, ['email' => logged()], '', '', true);

        $this->load->library('pagination');
    }

    public function index_user()
    {
        if (!userLevel(logged(), 'user')) return redirect(base_url());

        $config = [
            'base_url' => base_url('transactions/user'),
            'total_rows' => $this->model->counts($this->table, ['user_id' => $this->user['id']]),
            'per_page' => 10,
            'uri_segment' => 3,
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
        $page = (uri(3)) ? uri(3) : 0;
        $offset = $page == 0 ? 0 : ($page - 1) * $config["per_page"];

        $data = [
            'title' => lang("title_transaction_logs"),
            'list_transaction' => $this->model->fetch('*', $this->table, ['user_id' => $this->user['id']], 'updated_at', 'desc', $offset, $config["per_page"]),
            'pagination_links' => $this->pagination->create_links(),
        ];

        view('layouts/auth_header', $data);
        view('panel/admin/management/transactionslogs/transactions_user');
        view('layouts/auth_footer');
    }

    public function index_admin()
    {
        if (userLevel(logged(), 'user') || userLevel(logged(), 'banned')) return redirect(base_url());

        $config = [
            'base_url' => base_url('admin/transactions'),
            'total_rows' => $this->model->counts($this->table),
            'per_page' => 10,
            'uri_segment' => 3,
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
        $page = (uri(3)) ? uri(3) : 0;
        $offset = $page == 0 ? 0 : ($page - 1) * $config["per_page"];

        $data = [
            'title' => lang("title_transaction_logs"),
            'list_transaction' => $this->model->fetch('*', $this->table, '', 'updated_at', 'desc', $offset, $config["per_page"]),
            'pagination_links' => $this->pagination->create_links(),
        ];

        view('layouts/auth_header', $data);
        view('panel/admin/management/transactionslogs/transactions_admin');
        view('layouts/auth_footer');
    }

    public function search()
    {
        if (userLevel(logged(), 'user') || userLevel(logged(), 'banned')) return redirect(base_url());

        if (isset($_POST) and !empty($_POST)) {
            $search = $this->input->post('searchTransaction', true);

            $data = [
                'search_transaction' => $this->model->search($this->table, 'updated_at', 'desc', ['transaction_id' => $search], ['id', $search]),
            ];

            view('panel/admin/management/transactionslogs/transactions_admin_search', $data);
        }
    }

    public function destroy($id)
    {
        if (!userLevel(logged(), 'admin')) return redirect(base_url('admin/transactions'));

        if (DEMO_VERSION != true) {
            $this->model->delete($this->table, ['id' => $id]);
        } else {
            set_flashdata('error', lang("demo"));
        }

        redirect(base_url('admin/transactions'));
    }
}
