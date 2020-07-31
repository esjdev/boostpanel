<?php

defined('BASEPATH') or exit('No direct script access allowed');

class News_model extends MY_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Validation form
	 *
	 * @return void
	 */
	public function validate_form($edit = null)
	{
		if (!isset($edit)) {
			$this->form_validation(
				[
					[
						'field' => 'title_news',
						'label' => lang("title"),
						'rules' => 'required|alpha_numeric_punct',
						'errors' => [
							'required' => lang("error_empty_field"),
							'alpha_numeric_punct' => lang("error_invalid_characters"),
						],
					],
					[
						'field' => 'text-area-input-news-description',
						'label' => lang("description"),
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
						'field' => 'edit_title_news',
						'label' => lang("title"),
						'rules' => 'required|alpha_numeric_punct',
						'errors' => [
							'required' => lang("error_empty_field"),
							'alpha_numeric_punct' => lang("error_invalid_characters"),
						],
					],
					[
						'field' => 'edit-text-area-input-news-description',
						'label' => lang("description"),
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
