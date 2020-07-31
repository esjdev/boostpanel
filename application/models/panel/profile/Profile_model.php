<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Profile_model extends MY_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Validation Form
	 */
	public function validate_form()
	{
		$this->form_validation(
			[
				[
					'field' => 'password_current',
					'label' => lang("input_current_password"),
					'rules' => 'required',
					'errors' => [
						'required' => lang("error_empty_field"),
					],
				],
				[
					'field' => 'new_password',
					'label' => lang("input_new_password"),
					'rules' => 'required|min_length[4]|alpha_numeric',
					'errors' => [
						'required' => lang("error_empty_field"),
						'min_length' => lang("error_min_length"),
						'alpha_numeric' => lang("error_alpha_numeric"),
					],
				],
				[
					'field' => 'cf_new_password',
					'label' => lang("input_confirm_password"),
					'rules' => 'required|min_length[4]|alpha_numeric|matches[new_password]',
					'errors' => [
						'required' => lang("error_empty_field"),
						'matches' => lang("error_matches"),
						'min_length' => lang("error_min_length"),
						'alpha_numeric' => lang("error_alpha_numeric"),
					],
				],
			]
		);
	}
}
