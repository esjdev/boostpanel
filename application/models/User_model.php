<?php

defined('BASEPATH') or exit('No direct script access allowed');

class User_model extends MY_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Validation Form
	 */
	public function validate_form($balance = null)
	{
		if (!isset($balance)) {
			$this->form_validation(
				[
					[
						'field' => 'new_pass',
						'label' => lang("input_new_password"),
						'rules' => 'required|min_length[4]|alpha_numeric',
						'errors' => [
							'required' => lang("error_empty_field"),
							'min_length' => lang("error_min_length"),
						],
					],
					[
						'field' => 're_pass_new',
						'label' => lang("input_confirm_password"),
						'rules' => 'required|min_length[4]|alpha_numeric|matches[new_pass]',
						'errors' => [
							'required' => lang("error_empty_field"),
							'matches' => lang("error_matches"),
							'min_length' => lang("error_min_length"),
						],
					],
				]
			);
		} else {
			$this->form_validation(
				[
					[
						'field' => 'amount',
						'label' => lang("amount"),
						'rules' => 'required|numeric|is_natural_no_zero',
						'errors' => [
							'required' => lang("error_empty_field"),
							'numeric' => lang("error_only_numbers"),
							'is_natural_no_zero' => lang("error_natural_no_zero"),
						],
					],
				]
			);
		}
	}

	/**
	 * Validation Form Update Profile User
	 */
	public function validate_form_profile()
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
					'field' => 'email',
					'label' => lang("input_email"),
					'rules' => 'required|valid_email|is_unique[users.email]',
					'errors' => [
						'required' => lang("error_empty_field"),
						'valid_email' => lang("error_email_invalid"),
						'is_unique' => lang('error_is_unique'),
					],
				],
			]
		);
	}

	/**
	 * Validation Form Create user
	 */
	public function validate_form_create_user()
	{
		$this->form_validation(
			[
				[
					'field' => 'name_user',
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
					'field' => 'email_user',
					'label' => lang("input_email"),
					'rules' => 'required|valid_email|is_unique[users.email]',
					'errors' => [
						'required' => lang("error_empty_field"),
						'valid_email' => lang("error_email_invalid"),
						'is_unique' => lang('error_is_unique'),
					],
				],
				[
					'field' => 'role_user',
					'label' => lang("role"),
					'rules' => 'callback_select_validation[' . lang("error_no_select_role") . ']',
				],
				[
					'field' => 'password_user',
					'label' => lang("input_password"),
					'rules' => 'required|min_length[4]',
					'errors' => [
						'required' => lang("error_empty_field"),
						'min_length' => lang("error_min_length"),
					],
				],
				[
					'field' => 'cf_password_user',
					'label' => lang("input_current_password"),
					'rules' => 'required|min_length[4]|alpha_numeric|matches[password_user]',
					'errors' => [
						'required' => lang("error_empty_field"),
						'matches' => lang("error_matches"),
						'min_length' => lang("error_min_length"),
					],
				],
			]
		);
	}
}
