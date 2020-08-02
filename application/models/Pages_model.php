<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Pages_model extends MY_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Form Validation
	 */
	public function validation_form($title, $description)
	{
		$this->form_validation(
			[
				[
					'field' => $title,
					'label' => lang("title"),
					'rules' => 'required|min_length[2]|max_length[50]',
					'errors' => [
						'required' => lang("error_empty_field"),
						'min_length' => lang("error_min_length"),
						'max_length' => lang("error_max_length"),
					],
				],
				[
					'field' => $description,
					'label' => lang("description"),
					'rules' => 'required',
					'errors' => [
						'required' => lang("error_empty_field"),
					],
				],
			]
		);
	}
}
