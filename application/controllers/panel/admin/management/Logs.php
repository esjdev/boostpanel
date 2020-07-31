<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Logs extends MY_Controller
{
	protected $table;

	public function __construct()
	{
		parent::__construct();
		if (!userLevel(logged(), 'admin')) return redirect(base_url());

		$this->table = TABLE_LOGS;
	}

	public function index()
	{
		$this->load->library('pagination');

		$config = [
			'base_url' => base_url() . "admin/logs",
			'total_rows' => $this->model->counts($this->table),
			'per_page' => 10,
			'uri_segment' => 3,
			'use_page_numbers' => true,
			'reuse_query_string' => true,

			'first_link' => lang("pagination_first"),
			'last_link' => lang("pagination_last"),
			'full_tag_open' => '<div class="pagging mb-3"><nav><ul class="pagination pagination-lg justify-content-center">',
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
			'title' => lang("title_logs"),
			'list_logs' => $this->model->fetch('*', $this->table, '', 'updated_at', 'desc', $offset, $config["per_page"]),
			'pagination_links' => $this->pagination->create_links(),
			'count' => $offset + 1,
		];

		view('layouts/auth_header', $data);
		view('panel/admin/management/logs/logs_index');
		view('layouts/auth_footer');
	}

	public function destroy($id)
	{
		if (DEMO_VERSION != true) {
			$delete_log = $this->model->counts($this->table, ['id' => $id]);

			if ($delete_log > 0) {
				$this->model->delete($this->table, ['id' => $id]);
			}
		} else {
			set_flashdata('error', lang("demo"));
		}
		redirect(base_url('admin/logs'));
	}
}
