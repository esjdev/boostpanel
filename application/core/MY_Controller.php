<?php

defined('BASEPATH') or exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$database = config('database');

		if ($database == '__DATABASE__') {
			redirect(base_url('install'));
		} else {
			set_timezone();
			if (logged() && userLevel(logged(), 'banned')) {
				$this->session->sess_destroy();
				set_flashdata('user_banned', lang("error_user_banned_detected"));
				redirect(base_url());
			} // Verify user banned
		}
	}
}
