<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Login_model extends MY_Model
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
					'field' => 'email',
					'rules' => 'required|valid_email',
					'errors' => [
						'required' => lang("error_empty_field"),
						'valid_email' => lang("error_email_invalid"),
					],
				],
				[
					'field' => 'password',
					'rules' => 'required',
					'errors' => [
						'required' => lang("error_empty_field"),
					],
				],
			]
		);
	}

	/**
	 * Verify Login
	 *
	 * @param $table
	 * @param $email
	 * @param $password
	 * @return bool
	 */
	public function resolve_login($table, $email, $password)
	{
		$this->db->select('password');
		$this->db->from($table);
		$this->db->where('email', $email);
		$hash = $this->db->get()->row('password');

		return $this->verify_password_hash($password, $hash);
	}

	/**
	 * Verify password user
	 *
	 * @param [type] $password
	 * @param [type] $hash
	 * @return bool
	 */
	private function verify_password_hash($password, $hash)
	{
		return password_verify($password, $hash);
	}
}
