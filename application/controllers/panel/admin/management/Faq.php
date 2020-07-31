<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Faq extends MY_Controller
{
	protected $table;

	public function __construct()
	{
		parent::__construct();
		if (!userLevel(logged(), 'admin')) return redirect(base_url());

		$this->table = TABLE_PAGES;

		$this->load->model('pages_model');
	}

	public function index()
	{
		$faq = $this->model->fetch('*', $this->table, ['type' => 'faq'], 'id', 'asc', '', '');

		$data = [
			'title' => lang("title_faq_management"),
			'list_faq' => $faq,
		];

		view('layouts/auth_header', $data);
		view('panel/admin/management/pages/faq');
		view('layouts/auth_footer');

		$this->store(); // Add line Page FAQ
	}

	public function store()
	{
		if (isset($_POST) && !empty($_POST)) {
			if (DEMO_VERSION != true) {
				$title = $this->input->post('title', true);
				$description = $this->input->post('description', true);

				$this->pages_model->validation_form('title', 'description');

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
					'title' => $title,
					'content' => $description,
					'type' => 'faq',
				];
				$this->model->insert($this->table, $data);

				json([
					'csrf' => $this->security->get_csrf_hash(),
					'type' => 'success',
					'message' => lang('success_faq'),
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
				$title = $this->input->post('title_edit', true);
				$description = $this->input->post('description_edit', true);

				$this->pages_model->validation_form('title_edit', 'description_edit');

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
					'title' => $title,
					'content' => $description,
				];
				$this->model->update($this->table, ['id' => $id, 'type' => 'faq'], $data);

				json([
					'csrf' => $this->security->get_csrf_hash(),
					'type' => 'success',
					'message' => lang('success_edit_faq'),
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
		$faqs = $this->model->counts($this->table, ['id' => $id]);
		if (DEMO_VERSION != true) {
			if ($faqs > 0) {
				$this->model->delete($this->table, ['id' => $id]);
			}
		} else {
			set_flashdata('error', lang("demo"));
		}
		redirect(base_url('admin/faq'));
	}
}
