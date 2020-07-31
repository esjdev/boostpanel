<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Recaptcha
{
	protected $CI;

	public function __construct()
	{
		$this->CI = &get_instance();
	}

	/**
	 * Connection reCaptcha Google V2
	 * @param [type] $key_secret
	 * @param [type] $recaptchaResponse
	 * @return void
	 */
	public function recaptcha($key_secret, $recaptchaResponse)
	{
		$curl = curl_init();
		$captcha_verify_url = "https://www.google.com/recaptcha/api/siteverify";

		curl_setopt($curl, CURLOPT_URL, $captcha_verify_url);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, "secret=" . $key_secret . "&response=" . $recaptchaResponse);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

		$captcha_output = curl_exec($curl);
		curl_close($curl);
		return json_decode($captcha_output);
	}
}
