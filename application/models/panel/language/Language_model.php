<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Language_model extends MY_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Validation Form
	 */
	public function validate_form($add = true)
	{
		if ($add) {
			$this->form_validation(
				[
					[
						'field' => 'language_code',
						'rules' => 'callback_select_validation[' . lang("select_language") . ']|callback_validation_language',
					],
					[
						'field' => 'country_code',
						'rules' => 'callback_select_validation[' . lang("select_country") . ']|callback_validation_country_code',
					],
					[
						'field' => 'status_lang',
						'rules' => 'callback_select_validation[' . lang("select_status") . ']|callback_validate_number_select',
					],
					[
						'field' => 'lang_default',
						'rules' => 'callback_select_validation[' . lang("select_default_language") . ']|callback_validate_number_select',
					],
					[
						'field' => 'lang[]',
						'rules' => 'required',
						'errors' => [
							'required' => lang("error_empty_field"),
						],
					],
				]
			);
		} else {
			$this->form_validation(
				[
					[
						'field' => 'status_lang',
						'rules' => 'callback_select_validation[' . lang("select_status") . ']|callback_validate_number_select',
					],
					[
						'field' => 'lang_default',
						'rules' => 'callback_select_validation[' . lang("select_default_language") . ']|callback_validate_number_select',
					],
					[
						'field' => 'lang[]',
						'rules' => 'required',
						'errors' => [
							'required' => lang("error_empty_field"),
						],
					],
				]
			);
		}
	}
}
