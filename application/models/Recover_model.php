<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Recover_model extends MY_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Validation Form
	 * @param bool $send
	 */
	public function validate_form($send = true)
	{
		if ($send) {
			$this->form_validation(
				[
					[
						'field' => 'email_recover',
						'label' => lang("input_email"),
						'rules' => 'required|valid_email',
						'errors' => [
							'required' => lang("error_empty_field"),
							'valid_email' => lang("error_email_invalid"),
						],
					],
				]
			);
		} else {
			$this->form_validation(
				[
					[
						'field' => 'new_pass',
						'label' => lang("input_new_password"),
						'rules' => 'required|min_length[4]|alpha_numeric',
						'errors' => [
							'required' => lang("error_empty_field"),
							'min_length' => lang("error_min_length"),
							'alpha_numeric' => lang("error_alpha_numeric"),
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
							'alpha_numeric' => lang("error_alpha_numeric"),
						],
					],
				]
			);
		}
	}
}
