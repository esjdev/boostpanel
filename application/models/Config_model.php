<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Config_model extends MY_Model
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
					'field' => 'app_title',
					'label' => lang("app_title"),
					'rules' => 'required',
					'errors' => [
						'required' => lang("error_empty_field"),
					],
				],
				[
					'field' => 'meta_description',
					'label' => lang("meta_description"),
					'rules' => 'required',
					'errors' => [
						'required' => lang("error_empty_field"),
					],
				],
				[
					'field' => 'meta_keywords',
					'label' => lang("meta_keywords"),
					'rules' => 'required',
					'errors' => [
						'required' => lang("error_empty_field"),
					],
				],
			]
		);
	}

	/**
	 * Validation Form Currency Settings
	 */
	public function validate_form_currency()
	{
		$this->form_validation(
			[
				[
					'field' => 'symbol_currency',
					'label' => lang("currency_symbol"),
					'rules' => 'required',
					'errors' => [
						'required' => lang("error_empty_field"),
					],
				],
			]
		);
	}

	/**
	 * Validation Form Recaptcha
	 */
	public function validate_form_recaptcha()
	{
		$this->form_validation(
			[
				[
					'field' => 'public_key',
					'label' => lang("key_public"),
					'rules' => 'required|alpha_dash',
					'errors' => [
						'required' => lang("error_empty_field"),
						'alpha_dash' => lang("error_invalid_characters"),
					],
				],
				[
					'field' => 'private_key',
					'label' => lang("private_key"),
					'rules' => 'required|alpha_dash',
					'errors' => [
						'required' => lang("error_empty_field"),
						'alpha_dash' => lang("error_invalid_characters"),
					],
				],
			]
		);
	}

	/**
	 * Validation Form Email Settings
	 */
	public function validate_form_email_settings($boolean = true)
	{
		if ($boolean) {
			$this->form_validation(
				[
					[
						'field' => 'email_from',
						'label' => lang("from"),
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
						'field' => 'email_from',
						'label' => lang("from"),
						'rules' => 'required|valid_email',
						'errors' => [
							'required' => lang("error_empty_field"),
							'valid_email' => lang("error_email_invalid"),
						],
					],
					[
						'field' => 'smtp_server',
						'label' => lang("smtp_server"),
						'rules' => 'required',
						'errors' => [
							'required' => lang("error_empty_field"),
						],
					],
					[
						'field' => 'smtp_port',
						'label' => lang("smtp_port"),
						'rules' => 'required|numeric',
						'errors' => [
							'required' => lang("error_empty_field"),
							'numeric' => lang("error_only_numbers"),
						],
					],
					[
						'field' => 'smtp_encryption',
						'label' => lang("smtp_encryption"),
						'rules' => 'callback_select_validation[' . lang("error_select_smtp_encryption") . ']',
					],
					[
						'field' => 'smtp_username',
						'label' => lang("smtp_username"),
						'rules' => 'required',
						'errors' => [
							'required' => lang("error_empty_field"),
						],
					],
					[
						'field' => 'smtp_password',
						'label' => lang("smtp_password"),
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
