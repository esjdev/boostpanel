<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Order_model extends MY_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Form Validation
	 */
	public function validation_form($type)
	{
		if ($type == 'default') {
			$this->form_validation(
				[
					[
						'field' => 'link',
						'label' => lang("link"),
						'rules' => 'required|valid_url',
						'errors' => [
							'required' => lang("error_empty_field"),
						],
					],
					[
						'field' => 'quantity',
						'label' => lang("quantity"),
						'rules' => 'required|numeric',
						'errors' => [
							'required' => lang("error_empty_field"),
							'numeric' => lang("error_only_numbers"),
						],
					],
				]
			);
		} elseif ($type == 'custom_data') {
			$this->form_validation(
				[
					[
						'field' => 'link',
						'label' => lang("link"),
						'rules' => 'required|valid_url',
						'errors' => [
							'required' => lang("error_empty_field"),
						],
					],
					[
						'field' => 'quantity',
						'label' => lang("quantity"),
						'rules' => 'required|numeric',
						'errors' => [
							'required' => lang("error_empty_field"),
							'numeric' => lang("error_only_numbers"),
						],
					],
					[
						'field' => 'comments_custom_package',
						'label' => lang("comments"),
						'rules' => 'required|alpha_numeric_punct',
						'errors' => [
							'required' => lang("error_empty_field"),
							'alpha_numeric_punct' => lang("error_invalid_characters"),
						],
					],
				]
			);
		} elseif ($type == 'subscriptions') {
			$this->form_validation(
				[
					[
						'field' => 'username_subscriptions',
						'label' => lang("input_username"),
						'rules' => 'required|alpha_dash',
						'errors' => [
							'required' => lang("error_empty_field"),
							'alpha_dash' => lang("error_invalid_characters"),
						],
					],
					[
						'field' => 'new_posts_subs',
						'label' => lang("new_posts_order"),
						'rules' => 'required|is_natural_no_zero',
						'errors' => [
							'required' => lang("error_empty_field"),
							'is_natural_no_zero' => lang("error_min_one_post"),
						],
					],
				]
			);
		} elseif ($type == 'custom_comments') {
			$this->form_validation(
				[
					[
						'field' => 'link',
						'label' => lang("link"),
						'rules' => 'required|valid_url',
						'errors' => [
							'required' => lang("error_empty_field"),
						],
					],
					[
						'field' => 'quantity',
						'label' => lang("quantity"),
						'rules' => 'required|numeric',
						'errors' => [
							'required' => lang("error_empty_field"),
							'numeric' => lang("error_only_numbers"),
						],
					],
					[
						'field' => 'comments_custom_package',
						'label' => lang("comments"),
						'rules' => 'required|alpha_numeric_punct',
						'errors' => [
							'required' => lang("error_empty_field"),
							'alpha_numeric_punct' => lang("error_invalid_characters"),
						],
					],
				]
			);
		} elseif ($type == 'custom_comments_package') {
			$this->form_validation(
				[
					[
						'field' => 'link',
						'label' => lang("link"),
						'rules' => 'required|valid_url',
						'errors' => [
							'required' => lang("error_empty_field"),
						],
					],
					[
						'field' => 'comments_custom_package',
						'label' => lang("comments"),
						'rules' => 'required|alpha_numeric_punct',
						'errors' => [
							'required' => lang("error_empty_field"),
							'alpha_numeric_punct' => lang("error_invalid_characters"),
						],
					],
				]
			);
		} elseif ($type == 'mentions_with_hashtags') {
			$this->form_validation(
				[
					[
						'field' => 'link',
						'label' => lang("link"),
						'rules' => 'required|valid_url',
						'errors' => [
							'required' => lang("error_empty_field"),
						],
					],
					[
						'field' => 'quantity',
						'label' => lang("quantity"),
						'rules' => 'required|numeric',
						'errors' => [
							'required' => lang("error_empty_field"),
							'numeric' => lang("error_only_numbers"),
						],
					],
					[
						'field' => 'usernames_hashtags',
						'label' => lang("usernames"),
						'rules' => 'required|alpha_numeric_punct',
						'errors' => [
							'required' => lang("error_empty_field"),
							'alpha_numeric_punct' => lang("error_invalid_characters"),
						],
					],
					[
						'field' => 'mentions_with_hashtags',
						'label' => lang("hashtags_new_order"),
						'rules' => 'required|alpha_numeric_punct',
						'errors' => [
							'required' => lang("error_empty_field"),
							'alpha_numeric_punct' => lang("error_invalid_characters"),
						],
					],
				]
			);
		} elseif ($type == 'mentions_custom_list') {
			$this->form_validation(
				[
					[
						'field' => 'link',
						'label' => lang("link"),
						'rules' => 'required|valid_url',
						'errors' => [
							'required' => lang("error_empty_field"),
						],
					],
					[
						'field' => 'quantity',
						'label' => lang("quantity"),
						'rules' => 'required|numeric',
						'errors' => [
							'required' => lang("error_empty_field"),
							'numeric' => lang("error_only_numbers"),
						],
					],
					[
						'field' => 'usernames_hashtags',
						'label' => lang("usernames"),
						'rules' => 'required|alpha_numeric_punct',
						'errors' => [
							'required' => lang("error_empty_field"),
							'alpha_numeric_punct' => lang("error_invalid_characters"),
						],
					],
				]
			);
		} elseif ($type == 'mentions_hashtag') {
			$this->form_validation(
				[
					[
						'field' => 'link',
						'label' => lang("link"),
						'rules' => 'required|valid_url',
						'errors' => [
							'required' => lang("error_empty_field"),
						],
					],
					[
						'field' => 'quantity',
						'label' => lang("quantity"),
						'rules' => 'required|numeric',
						'errors' => [
							'required' => lang("error_empty_field"),
							'numeric' => lang("error_only_numbers"),
						],
					],
					[
						'field' => 'mentions_with_hashtag',
						'label' => lang("hashtag_new_order"),
						'rules' => 'required|alpha_numeric_punct',
						'errors' => [
							'required' => lang("error_empty_field"),
							'alpha_numeric_punct' => lang("error_invalid_characters"),
						],
					],
				]
			);
		} elseif ($type == 'mentions_user_followers') {
			$this->form_validation(
				[
					[
						'field' => 'link',
						'label' => lang("link"),
						'rules' => 'required|valid_url',
						'errors' => [
							'required' => lang("error_empty_field"),
						],
					],
					[
						'field' => 'quantity',
						'label' => lang("quantity"),
						'rules' => 'required|numeric',
						'errors' => [
							'required' => lang("error_empty_field"),
							'numeric' => lang("error_only_numbers"),
						],
					],
					[
						'field' => 'username_follower',
						'label' => lang("input_username"),
						'rules' => 'required|alpha_dash',
						'errors' => [
							'required' => lang("error_empty_field"),
							'alpha_dash' => lang("error_invalid_characters"),
						],
					],
				]
			);
		} elseif ($type == 'mentions_media_likers') {
			$this->form_validation(
				[
					[
						'field' => 'link',
						'label' => lang("link"),
						'rules' => 'required|valid_url',
						'errors' => [
							'required' => lang("error_empty_field"),
						],
					],
					[
						'field' => 'quantity',
						'label' => lang("quantity"),
						'rules' => 'required|numeric',
						'errors' => [
							'required' => lang("error_empty_field"),
							'numeric' => lang("error_only_numbers"),
						],
					],
					[
						'field' => 'media_url',
						'label' => lang("media_url_new_order"),
						'rules' => 'required|valid_url',
						'errors' => [
							'required' => lang("error_empty_field"),
						],
					],
				]
			);
		} elseif ($type == 'package') {
			$this->form_validation(
				[
					[
						'field' => 'link',
						'label' => lang("link"),
						'rules' => 'required|valid_url',
						'errors' => [
							'required' => lang("error_empty_field"),
						],
					],
				]
			);
		} elseif ($type == 'comment_likes') {
			$this->form_validation(
				[
					[
						'field' => 'link',
						'label' => lang("link"),
						'rules' => 'required|valid_url',
						'errors' => [
							'required' => lang("error_empty_field"),
						],
					],
					[
						'field' => 'quantity',
						'label' => lang("quantity"),
						'rules' => 'required|numeric',
						'errors' => [
							'required' => lang("error_empty_field"),
							'numeric' => lang("error_only_numbers"),
						],
					],
					[
						'field' => 'username_follower',
						'label' => lang("input_username"),
						'rules' => 'required|alpha_dash',
						'errors' => [
							'required' => lang("error_empty_field"),
							'alpha_dash' => lang("error_invalid_characters"),
						],
					],
				]
			);
		} elseif ($type == 'poll') {
			$this->form_validation(
				[
					[
						'field' => 'link',
						'label' => lang("link"),
						'rules' => 'required|valid_url',
						'errors' => [
							'required' => lang("error_empty_field"),
						],
					],
					[
						'field' => 'quantity',
						'label' => lang("quantity"),
						'rules' => 'required|numeric',
						'errors' => [
							'required' => lang("error_empty_field"),
							'numeric' => lang("error_only_numbers"),
						],
					],
					[
						'field' => 'poll_answer_number',
						'label' => lang("answer_number"),
						'rules' => 'required|alpha_dash',
						'errors' => [
							'required' => lang("error_empty_field"),
							'alpha_dash' => lang("error_invalid_characters"),
						],
					],
				]
			);
		} elseif ($type == 'seo') {
			$this->form_validation(
				[
					[
						'field' => 'link',
						'label' => lang("link"),
						'rules' => 'required|valid_url',
						'errors' => [
							'required' => lang("error_empty_field"),
						],
					],
					[
						'field' => 'quantity',
						'label' => lang("quantity"),
						'rules' => 'required|numeric',
						'errors' => [
							'required' => lang("error_empty_field"),
							'numeric' => lang("error_only_numbers"),
						],
					],
					[
						'field' => 'seo_keywords',
						'label' => lang("keywords_seo"),
						'rules' => 'required|alpha_numeric_punct',
						'errors' => [
							'required' => lang("error_empty_field"),
							'alpha_numeric_punct' => lang("error_invalid_characters"),
						],
					],
				]
			);
		} elseif ($type == 'mass_order') {
			$this->form_validation(
				[
					[
						'field' => 'mass_order',
						'rules' => 'required',
						'rules' => 'required|alpha_numeric_punct',
						'errors' => [
							'required' => lang("error_empty_field"),
							'alpha_numeric_punct' => lang("error_invalid_characters"),
						],
					],
				]
			);
		}

		if ($this->form_validation->run() == false) {
			foreach ($this->form_validation->error_array() as $key => $value) {
				json(
					[
						'csrf' => $this->security->get_csrf_hash(),
						'type' => 'error',
						'message' => form_error($key, false, false),
					]
				);
			}
		}
	}
}
