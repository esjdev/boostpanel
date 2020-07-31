<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Nojs extends MY_Controller
{
	public function index()
	{
		view("nojavascript");
	}
}
