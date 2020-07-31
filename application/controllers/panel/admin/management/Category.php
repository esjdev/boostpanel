<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Category extends MY_Controller
{
	protected $table;
	protected $table_services;

	public function __construct()
	{
		parent::__construct();
		if (!userLevel(logged(), 'admin')) return redirect(base_url());

		$this->table = TABLE_CATEGORIES;
		$this->table_services = TABLE_SERVICES;

		$this->load->model("panel/category/category_model");
	}

	public function index()
	{
		$this->load->library('pagination');

		$config = [
			'base_url' => base_url("admin/category"),
			'total_rows' => $this->model->counts($this->table),
			'per_page' => 15,
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
			'title' => lang("title_category_admin"),
			'list_category' => $this->model->fetch('*', $this->table, '', 'created_at', 'desc', $offset, $config["per_page"]),
			'pagination_links' => $this->pagination->create_links(),
			'count' => $offset + 1,
		];

		view('layouts/auth_header', $data);
		view('panel/admin/management/category/index_category');
		view('layouts/auth_footer');
	}

	public function create()
	{
		if (isset($_POST) && !empty($_POST)) {
			$category_name = $this->input->post('category_name', true);
			$status_category = $this->input->post('status_category', true);

			$this->category_model->validate_form();

			if ($this->form_validation->run() == false) {
				foreach ($this->form_validation->error_array() as $key => $value) {
					json([
						'csrf' => $this->security->get_csrf_hash(),
						'type' => 'error',
						'message' => form_error($key, false, false),
					]);
				}
			}

			$return_status = ($status_category == 1 ? '1' : '0');
			$data = [
				'name' => $category_name,
				'status' => $return_status,
				'created_at' => NOW,
			];
			$this->model->insert($this->table, $data);

			json([
				'csrf' => $this->security->get_csrf_hash(),
				'type' => 'success',
				'message' => lang("success_add_category"),
			]);
		}
	}

	public function edit($id)
	{
		if (isset($_POST) && !empty($_POST)) {
			$edit_category_name = $this->input->post('edit_category_name', true);
			$edit_status_category = $this->input->post('edit_status_category', true);

			$this->category_model->validate_form(true);

			if ($this->form_validation->run() == false) {
				foreach ($this->form_validation->error_array() as $key => $value) {
					json([
						'csrf' => $this->security->get_csrf_hash(),
						'type' => 'error',
						'message' => form_error($key, false, false),
					]);
				}
			}

			$return_status = ($edit_status_category == 1 ? '1' : '0');
			$data = [
				'name' => $edit_category_name,
				'status' => $return_status,
				'created_at' => NOW,
			];
			$this->model->update($this->table, ['id' => $id], $data);

			json([
				'csrf' => $this->security->get_csrf_hash(),
				'type' => 'success',
				'message' => lang("success_edit_category"),
			]);
		}
	}

	public function destroy($id)
	{
		if (DEMO_VERSION != true) {
			$delete_category = $this->model->counts($this->table, ['id' => $id]);

			if ($delete_category > 0) {
				$this->model->delete($this->table, ['id' => $id]);
				$this->model->delete($this->table_services, ['category_id' => $id]);
			}
		} else {
			set_flashdata('error', lang("demo"));
		}

		redirect(base_url('admin/category'));
	}
}
