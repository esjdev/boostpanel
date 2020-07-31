<?php

defined('BASEPATH') or exit('No direct script access allowed');

class News extends MY_Controller
{
	protected $table;

	public function __construct()
	{
		parent::__construct();
		if (!userLevel(logged(), 'admin')) return redirect(base_url());

		$this->table = TABLE_NEWS;

		$this->load->model("news_model");
	}

	public function index()
	{
		$this->load->library('pagination');

		$config = [
			'base_url' => base_url("admin/news"),
			'total_rows' => $this->model->counts($this->table, ['type' => 'general']),
			'per_page' => 10,
			'uri_segment' => 3,
			'use_page_numbers' => true,
			'reuse_query_string' => true,

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
			'title' => lang("title_news_announcement"),
			'list_news' => $this->model->fetch('*', $this->table, ['type' => 'general'], 'created_at', 'desc', $offset, $config["per_page"]),
			'pagination_links' => $this->pagination->create_links(),
			'count' => $offset + 1,
		];

		view('layouts/auth_header', $data);
		view('panel/admin/management/news/manager_news');
		view('layouts/auth_footer');
	}

	public function store()
	{
		if (isset($_POST) && !empty($_POST)) {
			if (DEMO_VERSION != true) {
				$title_news = $this->input->post('title_news', true);
				$description_news = $this->input->post('text-area-input-news-description');

				$this->news_model->validate_form();

				if ($this->form_validation->run() == false) {
					foreach ($this->form_validation->error_array() as $key => $value) {
						json([
							'csrf' => $this->security->get_csrf_hash(),
							'type' => 'error',
							'message' => form_error($key, false, false),
						]);
					}
				}

				$data = [
					'type' => 'general',
					'title' => $title_news,
					'description' => $description_news,
					'created_at' => NOW,
				];
				$this->model->insert($this->table, $data);

				json([
					'csrf' => $this->security->get_csrf_hash(),
					'type' => 'success',
					'message' => lang("success_news_add"),
				]);
			}

			json([
				'csrf' => $this->security->get_csrf_hash(),
				'type' => 'error',
				'message' => lang("demo"),
			]);
		}
	}

	public function edit($id)
	{
		if (isset($_POST) && !empty($_POST)) {
			if (DEMO_VERSION != true) {
				$news = $this->model->get("*", $this->table, ['id' => $id], "", "", true);

				if (empty($news)) {
					json([
						'csrf' => $this->security->get_csrf_hash(),
						'type' => 'error',
						'message' => lang("error_unable_to_edit"),
					]);
				}

				$title_news = $this->input->post('edit_title_news', true);
				$description_news = $this->input->post('edit-text-area-input-news-description');

				$this->news_model->validate_form(true);

				if ($this->form_validation->run() == false) {
					foreach ($this->form_validation->error_array() as $key => $value) {
						json([
							'csrf' => $this->security->get_csrf_hash(),
							'type' => 'error',
							'message' => form_error($key, false, false),
						]);
					}
				}

				$data = [
					'type' => 'general',
					'title' => $title_news,
					'description' => $description_news,
					'created_at' => NOW,
				];
				$this->model->update($this->table, ['id' => $id], $data);

				json([
					'csrf' => $this->security->get_csrf_hash(),
					'type' => 'success',
					'message' => lang("success_news_edit"),
				]);
			}

			json([
				'csrf' => $this->security->get_csrf_hash(),
				'type' => 'error',
				'message' => lang("demo"),
			]);
		}
	}

	public function destroy($id)
	{
		if (DEMO_VERSION != true) {
			if ($id) {
				$this->model->delete($this->table, ['id' => $id]);
			}
		} else {
			set_flashdata('error', lang("demo"));
		}
		redirect(base_url('admin/news'));
	}
}
