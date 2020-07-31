"use strict";

// Click close alert in Settings
$('.close').on('click', function () {
	$('.alert').hide();
});

/**
 * Generate New Token Cron Job
 */
$(document).on('click', '#generate_new_token', function () {
	event.preventDefault();
	$("#generate_new_token").addClass('d-none');
	$(".spinner").removeClass('d-none');
	setTimeout(function () {
		window.location.href = base + 'admin/settings/generate/token';
	}, 2000);
});

/**
 * Ajax Form Web Settings
 */
$('.webSettings').on('submit', function () {
	event.preventDefault();
	$('.loading').attr('style', 'display:block');
	var data = new FormData(this);
	$.ajax({
		type: 'POST',
		url: this.action,
		data: data,
		dataType: 'json',
		processData: false,
		contentType: false,
		success: function (response) {
			if (response.type == 'error') {
				$("input[name=csrf_boostpanel]").val(response.csrf);
				$('.loading').attr('style', 'display:none;');
				$('.success').attr('style', 'display:none;');
				$('.error').attr('style', 'display:block;');
				$('.error-message').html(response.message);
			} else if (response.type == 'success') {
				$("input[name=csrf_boostpanel]").val(response.csrf);
				$('.loading').attr('style', 'display:none;');
				$('.error').attr('style', 'display:none;');
				$('.success').attr('style', 'display:block;');
				$('.success-message').html(response.message);
			}
		}
	});
});

/**
 * Ajax Form Settings
 */
$('.settingsForm').on('submit', function () {
	event.preventDefault();
	$('.loading').attr('style', 'display:block');
	var data = $(this).serialize();

	$.post(this.action, data, function (response) {
		if (response.type == 'error') {
			$("input[name=csrf_boostpanel]").val(response.csrf);
			$('.loading').attr('style', 'display:none;');
			$('.success').attr('style', 'display:none;');
			$('.error').attr('style', 'display:block;');
			$('.error-message').html(response.message);
		} else if (response.type == 'success') {
			$("input[name=csrf_boostpanel]").val(response.csrf);
			$('.loading').attr('style', 'display:none;');
			$('.error').attr('style', 'display:none;');
			$('.success').attr('style', 'display:block;');
			$('.success-message').html(response.message);
		}
	}, 'json');
});

/**
 * Ajax Template Email
 */
$(".templateEmail").on('submit', function () {
	event.preventDefault();
	var data = $(this).serialize();

	$.post(this.action, data, function (response) {
		if (response.type == 'error') {
			$("input[name=csrf_boostpanel]").val(response.csrf);
			Swal.fire({
				title: 'ERROR',
				text: response.message,
				icon: response.type
			});
		} else if (response.type == 'success') {
			$("input[name=csrf_boostpanel]").val(response.csrf);
			Swal.fire({
				title: 'SUCCESS',
				text: response.message,
				icon: response.type
			});
		}
	}, 'json');
});

/**
 * Update Notifications
 */
$('#update-notifications').on('change', function () {
	var data = $(this).serialize();
	$.post(base + 'admin/settings/notifications-settings', data, function () {
		if ($('#verification_news_account').is(':checked')) {
			$('.notifications-active-1').removeClass('d-none');
		} else if (!$('#verification_news_account').is(':checked')) {
			$('.notifications-active-1').addClass('d-none');
		}

		if ($('#new_user_welcome').is(':checked')) {
			$('.notifications-active-2').removeClass('d-none');
		} else if (!$('#new_user_welcome').is(':checked')) {
			$('.notifications-active-2').addClass('d-none');
		}

		if ($('#new_user_notification').is(':checked')) {
			$('.notifications-active-3').removeClass('d-none');
		} else if (!$('#new_user_notification').is(':checked')) {
			$('.notifications-active-3').addClass('d-none');
		}

		if ($('#notification_ticket').is(':checked')) {
			$('.notifications-active-5').removeClass('d-none');
		} else if (!$('#notification_ticket').is(':checked')) {
			$('.notifications-active-5').addClass('d-none');
		}

		if ($('#payment_notification').is(':checked')) {
			$('.notifications-active-6').removeClass('d-none');
		} else if (!$('#payment_notification').is(':checked')) {
			$('.notifications-active-6').addClass('d-none');
		}
	});
});

/**
 * Ajax Update on/off Page Register
 */
$('#update-register-settings').on('change', function () {
	var data = $(this).serialize();

	$.post(this.action, data, function () {
		if ($('input[name=registration_page]').is(':checked')) {
			$('input[name=registration_page]').prop("checked", true);
		} else if (!$('input[name=registration_page]').is(':checked')) {
			$('input[name=registration_page]').prop("checked", false);
		}

		$("#msg-update-settings").removeClass('d-none');
		$("#msg-update-settings").show().delay(2000).fadeOut();
	});
});

/**
 * Select type protocol Email Settings
 */
$(document).on("change", "input[type=radio][name=email_protocol]", function () {
	var type = $(this).val();
	if (type == 'smtp') {
		$('.smtp-config').removeClass('d-none');
	} else {
		$('.smtp-config').addClass('d-none');
	}
});

/**
 * Change on/off Google Recaptcha Settings
 */
$(document).on("change", "input[type=checkbox][name=recaptcha_on_off]", function () {
	if ($(this).is(':checked')) {
		$('.off-recaptcha').removeClass('d-none');
	} else {
		$('.off-recaptcha').addClass('d-none');
	}
});
