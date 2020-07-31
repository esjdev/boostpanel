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
					'rules' => 'required|min_length[2]|max_length[50]|alpha_numeric_punct',
					'errors' => [
						'required' => lang("error_empty_field"),
						'min_length' => lang("error_min_length"),
						'max_length' => lang("error_max_length"),
						'alpha_numeric_punct' => lang("error_invalid_characters"),
					],
				],
				[
					'field' => $description,
					'label' => lang("description"),
					'rules' => 'required|alpha_numeric_punct',
					'errors' => [
						'required' => lang("error_empty_field"),
						'alpha_numeric_punct' => lang("error_invalid_characters"),
					],
				],
			]
		);
	}
}
