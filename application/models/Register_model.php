<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Register_model extends MY_Model
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
					'field' => 'name',
					'label' => lang("input_name"),
					'rules' => 'required|alpha_numeric_spaces',
					'errors' => [
						'required' => lang("error_empty_field"),
						'alpha_numeric_spaces' => lang("error_invalid_characters"),
					],
				],
				[
					'field' => 'username',
					'label' => lang("input_username"),
					'rules' => 'required|min_length[3]|max_length[15]|alpha_dash|is_unique[users.username]',
					'errors' => [
						'required' => lang("error_empty_field"),
						'min_length' => lang("error_min_length"),
						'max_length' => lang("error_max_length"),
						'alpha_dash' => lang("error_invalid_characters"),
						'is_unique' => lang('error_is_unique'),
					],
				],
				[
					'field' => 'email',
					'label' => lang("input_email"),
					'rules' => 'required|valid_email|is_unique[users.email]',
					'errors' => [
						'required' => lang("error_empty_field"),
						'valid_email' => lang("error_email_invalid"),
						'is_unique' => lang('error_is_unique'),
					],
				],
				[
					'field' => 'password',
					'label' => lang("input_password"),
					'rules' => 'required|min_length[4]',
					'errors' => [
						'required' => lang("error_empty_field"),
						'min_length' => lang("error_min_length")
					],
				],
				[
					'field' => 're-password',
					'label' => lang("input_confirm_password"),
					'rules' => 'required|min_length[4]|matches[password]',
					'errors' => [
						'required' => lang("error_empty_field"),
						'min_length' => lang("error_min_length"),
						'matches' => lang("error_matches"),
					],
				],
			]
		);
	}
}
