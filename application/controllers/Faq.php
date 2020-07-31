<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Faq extends MY_Controller
{
    protected $table;

    public function __construct()
    {
        parent::__construct();

        $this->table = TABLE_PAGES;
    }

    public function index()
    {
        $faq = $this->model->fetch('*', $this->table, ['type' => 'faq'], 'id', 'asc', '', '');
        $count_faq = $this->model->counts($this->table, ['type' => 'faq']);

        $data = [
            'title' => configs('app_title', 'value') . " - " . lang("title_faq"),
            'list_faq' => $faq,
            'faq_counts' => $count_faq
        ];

        if (!logged()) {
			view('layouts/header', $data);
		} else {
			view('layouts/auth_header', $data);
        }

        view('pages/faq');

        if (!logged()) {
			view('layouts/footer');
		} else {
			view('layouts/auth_footer');
		}
    }
}
