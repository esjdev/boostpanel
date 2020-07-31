<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Setup_model extends MY_Model
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
					'field' => 'host',
					'label' => 'Host',
					'rules' => 'required',
					'errors' => [
						'required' => lang("error_empty_field"),
					],
				],
				[
					'field' => 'db_user',
					'label' => 'Database user',
					'rules' => 'required',
					'errors' => [
						'required' => lang("error_empty_field"),
					],
				],
				[
					'field' => 'db_password',
					'label' => 'Database password',
					'rules' => 'required',
					'errors' => [
						'required' => lang("error_empty_field"),
					],
				],
				[
					'field' => 'db_name',
					'label' => 'Database name',
					'rules' => 'required',
					'errors' => [
						'required' => lang("error_empty_field"),
					],
				],
				[
					'field' => 'username',
					'label' => 'Username',
					'rules' => 'required|min_length[3]|max_length[15]|alpha_numeric',
					'errors' => [
						'required' => lang("error_empty_field"),
						'min_length' => lang("error_min_length"),
						'max_length' => lang("error_max_length"),
						'alpha_numeric' => lang("error_alpha_numeric"),
					],
				],
				[
					'field' => 'email',
					'label' => 'Email',
					'rules' => 'required|valid_email',
					'errors' => [
						'required' => lang("error_empty_field"),
						'valid_email' => lang("error_email_invalid"),
					],
				],
				[
					'field' => 'password',
					'label' => 'Password',
					'rules' => 'required|min_length[4]',
					'errors' => [
						'required' => lang("error_empty_field"),
						'min_length' => lang("error_min_length"),
					],
				],
			]
		);
	}

	/**
	 * Create Tables in the Database
	 * @param [type] $sql
	 * @return void
	 */
	public function createTable($sql)
	{
		$this->db->query($sql);
	}
}
