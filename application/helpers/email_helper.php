<?php

defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('notification')) {
	/**
	 * Get data notification in Database
	 * @param [type] $name
	 * @param [type] $object
	 * @return void
	 */
	function notification($name, $object)
	{
		$ci = &get_instance();
		$notification = $ci->model->get("*", TABLE_NOTIFICATIONS, ['name' => $name], '', '', true);

		if (isset($notification[$object])) return $notification[$object];
	}
}

if (!function_exists('email_tpl')) {
	/**
	 * Get data email templates in Database
	 * @param [type] $name
	 * @param [type] $object
	 * @return void
	 */
	function email_tpl($name, $object)
	{
		$ci = &get_instance();
		$templates = $ci->model->get("*", TABLE_EMAIL_TEMPLATES, ['name' => $name], '', '', true);

		if (isset($templates[$object])) return $templates[$object];
	}
}

if (!function_exists('email_send')) {
	/**
	 * @param $email
	 * @param $title
	 * @param $msg
	 * @param $type
	 */
	function email_send($email, $title, $msg)
	{
		$ci = &get_instance();
		if (configs('protocol', 'value') == 'mail') {
			mail($email, $title, $msg, "From: " . configs('app_title', 'value'));
		}

		if (configs('protocol', 'value') == 'smtp') {
			$smtp_username = configs('smtp_username', 'value');
			$smtp_password = configs('smtp_password', 'value');
			$protocol = configs('protocol', 'value');
			$smtp_host = configs('smtp_encryption', 'value') . "://" . configs('smtp_host', 'value');
			$smtp_port = configs('smtp_port', 'value');

			$config = array(
				'protocol' => $protocol,
				'smtp_host' => $smtp_host,
				'smtp_port' => $smtp_port,
				'smtp_user' => $smtp_username,
				'smtp_pass' => $smtp_password,
				'mailtype' => 'html',
				'charset' => 'utf-8',
				'wordwrap' => true,
				'validate' => true,
			);

			$ci->load->library('email', $config);

			$ci->email->from(configs('email', 'value'), configs('app_title', 'value'));
			$ci->email->to($email);

			$ci->email->subject($title);
			$ci->email->message($msg);
			$ci->email->set_newline("\r\n");

			$ci->email->send();
		}
	}
}

if (!function_exists('email_template')) {
	/**
	 * @param string $key
	 * @return object
	 */
	function email_template($key = "")
	{
		$email = (object) [];
		$email->subject = '';
		$email->content = '';
		if (!empty($key)) {
			switch ($key) {
				case 'recover_password_link':
					$email->subject = "{{app_name}} - Password reset request";
					$email->content = "<p>Hello <strong>{{username}}</strong>!</p><p>Please follow the link to set your password.<br><a href='{{recover_password_link}}'>{{recover_password_link}}</a></p>";
					return $email;
					break;

				case 'verification_account':
					$email->subject = "{{app_name}} - Please validate your account";
					$email->content = "<p><strong>Welcome to {{app_name}}!</strong></p><p>Hello <strong>{{username}}</strong>!</p><p>Thanks for joining! We are pleased to have you as a member of the community and have stock for you to start exploring our service. If you do not verify your account, you will not be able to use our service.</p><p>All you need to do is activate your account by clicking this link: <br><a href='{{activation_link}}'>{{activation_link}}</a></p><p>Thanks and Best Regards!</p>";
					return $email;
					break;

				case 'welcome_user':
					$email->subject = "{{app_name}} - Welcome to our service!";
					$email->content = "<p><strong>Welcome to {{app_name}}!</strong></p><p>Hello <strong>{{username}}</strong>!</p><p>Congratulations! <br>You have successfully signed up for our service - {{app_name}} with the following data</p><ul><li>Name: {{name}}</li><li>Email: {{user_email}}</li><li>Timezone: {{user_timezone}}</li></ul><p>We want to exceed your expectations, so please do not hesitate to reach out at any time if you have any questions or concerns. We look to working with you.</p><p>Best Regards,</p>";
					return $email;
					break;

				case 'new_user_to_admin':
					$email->subject = "{{app_name}} - New Registration";
					$email->content = "<p>Hi Admin!</p><p>Someone signed up in <strong>{{app_name}}</strong> with follow data</p><ul><li>Name: {{name}}</li><li>Email: {{user_email}}</li><li>Timezone: {{user_timezone}}</li></ul> ";
					return $email;
					break;

				case 'notification_ticket_reply':
					$email->subject = "{{app_name}} - Your ticket has been answered!";
					$email->content = "<p><strong>Welcome to {{app_name}}!</strong></p><p>Hello <strong>{{username}}</strong>!</p><p>Your ticket has been answered by the administrator.</p><p><a href='{{link_ticket}}'>Click here to access</a></p>";
					return $email;
					break;

				case 'payments_notification':
					$email->subject = "Deposit request received. Thanks!";
					$email->content = "Hi <strong>{{username}}</strong>!<br><br>Your deposit was successful.<br><br><span style='font-size:14px'><strong><span style='color:#f71000'>Order information</span></strong></span><br><br><strong>Transaction ID:</strong> {{transaction_id}}<br><strong>Payment method:</strong> {{method_payment}}<br><br><div style='background:#4e5f70; border:1px solid #cccccc; padding:5px 10px'><span style='font-size:12px'><span style='color:#ecf0f1'>Just wait for your payment to be approved and you will receive it automatically.</span></span></div><br>Thanks and Best Regards!";
					return $email;
					break;
			}
		}
	}
}
