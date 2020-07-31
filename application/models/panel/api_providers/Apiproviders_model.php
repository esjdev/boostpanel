<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Apiproviders_model extends MY_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Validation Form
	 */
	public function validate_form($edit = null)
	{
		if ($edit) {
			$this->form_validation(
				[
					[
						'field' => 'edit_name_api',
						'label' => lang("input_name"),
						'rules' => 'required',
						'errors' => [
							'required' => lang("error_empty_field"),
						],
					],
					[
						'field' => 'edit_url_api',
						'label' => lang("api_url"),
						'rules' => 'required',
						'errors' => [
							'required' => lang("error_empty_field"),
						],
					],
					[
						'field' => 'edit_key_api',
						'label' => lang("api_key"),
						'rules' => 'required|is_unique[api_providers.key]',
						'errors' => [
							'required' => lang("error_empty_field"),
							'is_unique' => lang('error_is_unique_key_api'),
						],
					],
				]
			);
		} else {
			$this->form_validation(
				[
					[
						'field' => 'name_api',
						'label' => lang("input_name"),
						'rules' => 'required',
						'errors' => [
							'required' => lang("error_empty_field"),
						],
					],
					[
						'field' => 'url_api',
						'label' => lang("api_url"),
						'rules' => 'required',
						'errors' => [
							'required' => lang("error_empty_field"),
						],
					],
					[
						'field' => 'key_api',
						'label' => lang("api_key"),
						'rules' => 'required|is_unique[api_providers.key]',
						'errors' => [
							'required' => lang("error_empty_field"),
							'is_unique' => lang('error_is_unique_key_api'),
						],
					],
				]
			);
		}
	}
}
