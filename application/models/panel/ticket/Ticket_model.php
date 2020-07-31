<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Ticket_model extends MY_Model
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
					'field' => 'subject_ticket',
					'rules' => 'callback_select_validation[' . lang("error_ticket_no_select") . ']',
				],
				[
					'field' => 'message',
					'label' => lang("input_message"),
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
