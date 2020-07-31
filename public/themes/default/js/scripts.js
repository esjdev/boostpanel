"use strict";

/**
 * Ajax Forms to Login, Register and Recover Password
 */

$('.actionForm').on('submit', function () {
	event.preventDefault();
	pageOverlay.show();
	var data = $(this).serialize();
	var id = $(this).attr('id');

	$.post(this.action, data, function (response) {
		if (response.type == 'error') {
			pageOverlay.hide();
			$("input[name=csrf_boostpanel]").val(response.csrf);
			$('.error').attr('style', 'display:block;');
			$('.error-message').html(response.message);

			if (id == 'recover-form' || id == 'register-form') {
				$('.success').attr('style', 'display:none;');
			}
		} else if (response.type == 'success') {
			pageOverlay.hide();

			if (id == 'recover-form' || id == 'register-form') {
				$("input[name=csrf_boostpanel]").val(response.csrf);
				$('.error').attr('style', 'display:none;');
				$('.success').attr('style', 'display:block;');
				$('.success-message').html(response.message);
			}

			if (id == 'recover-form') {
				$('#recover-form')[0].reset();
			}

			if (id == 'register-form') {
				$('#register-form')[0].reset();
			}

			if (id == 'login-form') {
				window.location.href = response.base_url;
			}
		}

		if (response.captcha == 'on') grecaptcha.reset();
	}, 'json');
});

/**
 * Form Token Recover Password
 */
$('#token-recover-form').on('submit', function () {
	event.preventDefault();
	pageOverlay.show();
	var data = $(this).serialize();
	$.post(this.action, data, function (response) {
		if (response.type == 'error') {
			pageOverlay.hide();
			$("input[name=csrf_boostpanel]").val(response.csrf);
			$('.success').attr('style', 'display:none;');
			$('.error').attr('style', 'display:block;');
			$('.error-message').html(response.message);
		} else if (response.type == 'success') {
			pageOverlay.hide();
			$("input[name=csrf_boostpanel]").val(response.csrf);
			$('.error').attr('style', 'display:none;');
			$('.success').attr('style', 'display:block;');
			$('.success-message').html(response.message);
			$('#token-recover-form')[0].reset();
			setInterval(function () {
				window.location.href = response.base_url;
			}, 2000);
		}
	}, 'json');
});

/**
 * Ajax Change Profile
 */
$("#change-profile").on('submit', function () {
	event.preventDefault();
	pageOverlay.show();
	var data = $(this).serialize();
	$.post(this.action, data, function (response) {
		if (response.type == 'error') {
			pageOverlay.hide();
			$("input[name=csrf_boostpanel]").val(response.csrf);
			$('.success').attr('style', 'display:none;');
			$('.error').attr('style', 'display:block;');
			$('.error-message').html(response.message);
		} else if (response.type == 'success') {
			pageOverlay.hide();
			$("input[name=csrf_boostpanel]").val(response.csrf);
			$('.error').attr('style', 'display:none;');
			$('.success').attr('style', 'display:block;');
			$('.success-message').html(response.message);
			$("#change-profile")[0].reset();
		}
	}, 'json');
});

/**
 * Change Timezone Profile
 */
$("#change-timezone-profile").on('submit', function () {
	event.preventDefault();
	pageOverlay.show();
	var data = $(this).serialize();
	$.post(this.action, data, function (response) {
		if (response.type == 'error') {
			pageOverlay.hide();
			$("input[name=csrf_boostpanel]").val(response.csrf);
			$('.success').attr('style', 'display:none;');
			$('.error').attr('style', 'display:block;');
			$('.error-message').html(response.message);
		} else if (response.type == 'success') {
			pageOverlay.hide();
			$("input[name=csrf_boostpanel]").val(response.csrf);
			$('.error').attr('style', 'display:none;');
			$('.success').attr('style', 'display:block;');
			$('.success-message').html(response.message);
		}
	}, 'json');
});

/**
 * Generate New Token API in page Profile
 */
$(document).on('click', '#generate_new_token_api', function () {
	event.preventDefault();
	$("#generate_new_token_api").addClass('d-none');
	$(".spinner").removeClass('d-none');
	setTimeout(function () {
		window.location.href = '/profile/generate/token';
		$("#generate_new_token_api").removeClass('d-none');
		$(".spinner").addClass('d-none');
	}, 2000);
});

/**
 * Ajax Manage Category Admin (Add)
 */
$("#add-category-panel").on('submit', function () {
	event.preventDefault();
	pageOverlay.show();
	var data = $(this).serialize();
	$.post(this.action, data, function (response) {
		if (response.type == 'error') {
			pageOverlay.hide();
			$("input[name=csrf_boostpanel]").val(response.csrf);
			$('.success').attr('style', 'display:none;');
			$('.error').attr('style', 'display:block;');
			$('.error-message').html(response.message);
		} else if (response.type == 'success') {
			pageOverlay.hide();
			$("input[name=csrf_boostpanel]").val(response.csrf);
			$('.error').attr('style', 'display:none;');
			$('.success').attr('style', 'display:block;');
			$('.success-message').html("<div class=\"spinner-border spinner-border-sm\" role=\"status\"></div> " + response.message);
			$('#add-category-panel')[0].reset();
			setTimeout(function () {
				location.reload();
			}, 2000);
		}
	}, 'json');
});

/**
 * Ajax Manage Category Admin (Edit)
 */
$("#edit-category-panel").on('submit', function () {
	event.preventDefault();
	pageOverlay.show();
	var data = $(this).serialize();
	$.post(this.action, data, function (response) {
		if (response.type == 'error') {
			pageOverlay.hide();
			$("input[name=csrf_boostpanel]").val(response.csrf);
			$('.success').attr('style', 'display:none;');
			$('.error').attr('style', 'display:block;');
			$('.error-message').html(response.message);
		} else if (response.type == 'success') {
			pageOverlay.hide();
			$("input[name=csrf_boostpanel]").val(response.csrf);
			$('.error').attr('style', 'display:none;');
			$('.success').attr('style', 'display:block;');
			$('.success-message').html("<div class=\"spinner-border spinner-border-sm\" role=\"status\"></div> " + response.message);
			setTimeout(function () {
				location.reload();
			}, 2000);
		}
	}, 'json');
});

/**
 * Ajax Add Ticket Support
 */
$("#ticket-support").on('submit', function () {
	event.preventDefault();
	pageOverlay.show();
	var data = $(this).serialize();
	$.post(this.action, data, function (response) {
		if (response.type == 'error') {
			pageOverlay.hide();
			$("input[name=csrf_boostpanel]").val(response.csrf);
			$('.success').attr('style', 'display:none;');
			$('.error').attr('style', 'display:block;');
			$('.error-message').html(response.message);
		} else if (response.type == 'success') {
			pageOverlay.hide();
			$("input[name=csrf_boostpanel]").val(response.csrf);
			$('.error').attr('style', 'display:none;');
			$('.success').attr('style', 'display:block;');
			$('.success-message').html("<div class=\"spinner-border spinner-border-sm\" role=\"status\"></div> " + response.message);
			$('#ticket-support')[0].reset();
			setTimeout(function () {
				location.reload();
			}, 1500);
		}
	}, 'json');
});

/**
 * Input Search Tickets
 */
$(document).on("input", ".searchTicketsAjax", function () {
	event.preventDefault();
	pageOverlay.show();
	var action = $(this).data("url");
	var data = $(this).serialize();
	$.post(action, data, function (response) {
		if ($('input[name=searchTickets]').val() != '') {
			pageOverlay.hide();
			$(".table-search-tickets").empty().append(response);
		} else {
			pageOverlay.hide();
			location.reload();
		}
	});
});

/**
 * Save Reply of Ticket
 */
$("#ticket-messages").on('submit', function () {
	event.preventDefault();
	pageOverlay.show();
	var data = $(this).serialize();
	$.post(this.action, data, function (response) {
		if (response.type == 'error') {
			pageOverlay.hide();
			$("input[name=csrf_boostpanel]").val(response.csrf);
			$('.success').attr('style', 'display:none;');
			$('.error').attr('style', 'display:block;');
			$('.error-message').html(response.message);
		} else if (response.type == 'success') {
			pageOverlay.hide();
			$("input[name=csrf_boostpanel]").val(response.csrf);
			$('.error').attr('style', 'display:none;');
			$('#ticket-messages')[0].reset();
			window.location.href = response.base_url;
		}
	}, 'json');
});

/**
 * Ajax Search Transactions
 */
$(document).on("input", ".searchTransactionAjax", function () {
	event.preventDefault();
	pageOverlay.show();
	var action = $(this).data("url");
	var data = $(this).serialize();
	var field = $(this).val();
	$.post(action, data, function (response) {
		if (field != '') {
			pageOverlay.hide();
			$(".table-transactions").empty().append(response);
		} else {
			pageOverlay.hide();
			location.reload();
		}
	});
});

/**
 * Ajax Search Orders (User)
 */
$(document).on("input", ".searchOrdersAjax", function () {
	event.preventDefault();
	pageOverlay.show();
	var action = $(this).data("url");
	var data = $(this).serialize();
	var field = $(this).val();
	$.post(action, data, function (response) {
		if (field != '') {
			pageOverlay.hide();
			$(".table-orders-search").empty().append(response);
		} else {
			pageOverlay.hide();
			location.reload();
		}
	});
});

/**
 * Ajax Search Orders (Admin)
 */
$(document).on("input", ".searchAdminOrdersAjax", function () {
	event.preventDefault();
	pageOverlay.show();
	var action = $(this).data("url");
	var data = $(this).serialize();
	var field = $(this).val();
	$.post(action, data, function (response) {
		if (field != '') {
			pageOverlay.hide();
			$(".table-orders-search").empty().append(response);
		} else {
			pageOverlay.hide();
			location.reload();
		}
	});
});

/**
 * Ajax Search Subscriptions
 */
$(document).on("input", ".searchSubscriptionsAjax", function () {
	event.preventDefault();
	pageOverlay.show();
	var action = $(this).data("url");
	var data = $(this).serialize();
	var field = $(this).val();
	$.post(action, data, function (response) {
		if (field != '') {
			pageOverlay.hide();
			$(".table-subscriptions-search").empty().append(response);
		} else {
			pageOverlay.hide();
			location.reload();
		}
	});
});

/**
 * Ajax Search Subscriptions (Admin)
 */
$(document).on("input", ".searchAdminSubscriptionsAjax", function () {
	event.preventDefault();
	pageOverlay.show();
	var action = $(this).data("url");
	var data = $(this).serialize();
	var field = $(this).val();
	$.post(action, data, function (response) {
		if (field != '') {
			pageOverlay.hide();
			$(".table-subscriptions-search").empty().append(response);
		} else {
			pageOverlay.hide();
			location.reload();
		}
	});
});

/**
 * Ajax Button Subscription Admin (Status to Completed)
 */
$("#update-status-completed-subs").on('submit', function () {
	event.preventDefault();
	pageOverlay.show();
	var data = $(this).serialize();
	$.post(this.action, data, function (response) {
		if (response.type == 'error') {
			pageOverlay.hide();
			$("input[name=csrf_boostpanel]").val(response.csrf);
			$('.success').attr('style', 'display:none;');
			$('.error').attr('style', 'display:block;');
			$('.error-message').html(response.message);
		} else if (response.type == 'success') {
			pageOverlay.hide();
			$("input[name=csrf_boostpanel]").val(response.csrf);
			$('.error').attr('style', 'display:none;');
			$('.success').attr('style', 'display:block;');
			$('.success-message').html(response.message);
			$("#update-status-completed-subs")[0].reset();
		}
	}, 'json');
});

/**
 * Ajax Buttons Orders Admin (Edit Link Order)
 */
$("#edit-link-order").on('submit', function () {
	event.preventDefault();
	pageOverlay.show();
	var data = $(this).serialize();
	$.post(this.action, data, function (response) {
		if (response.type == 'error') {
			pageOverlay.hide();
			$("input[name=csrf_boostpanel]").val(response.csrf);
			$('.success').attr('style', 'display:none;');
			$('.error').attr('style', 'display:block;');
			$('.error-message').html(response.message);
		} else if (response.type == 'success') {
			pageOverlay.hide();
			$("input[name=csrf_boostpanel]").val(response.csrf);
			$('.error').attr('style', 'display:none;');
			$('.success').attr('style', 'display:block;');
			$('.success-message').html(response.message);
		}
	}, 'json');
});

/**
 * Ajax Form Admin Set Start Count Order
 */
$("#form_set_start_count").on('submit', function () {
	event.preventDefault();
	pageOverlay.show();
	var data = $(this).serialize();
	$.post(this.action, data, function (response) {
		if (response.type == 'error') {
			pageOverlay.hide();
			$("input[name=csrf_boostpanel]").val(response.csrf);
			$('.success').attr('style', 'display:none;');
			$('.error').attr('style', 'display:block;');
			$('.error-message').html(response.message);
		} else if (response.type == 'success') {
			pageOverlay.hide();
			$("input[name=csrf_boostpanel]").val(response.csrf);
			$('.error').attr('style', 'display:none;');
			$('.success').attr('style', 'display:block;');
			$('.success-message').html(response.message);
		}
	}, 'json');
});

/**
 * Ajax Form Admin Set Partial Order
 */
$("#form_set_partial").on('submit', function () {
	event.preventDefault();
	pageOverlay.show();
	var data = $(this).serialize();
	$.post(this.action, data, function (response) {
		if (response.type == 'error') {
			pageOverlay.hide();
			$("input[name=csrf_boostpanel]").val(response.csrf);
			$('.success').attr('style', 'display:none;');
			$('.error').attr('style', 'display:block;');
			$('.error-message').html(response.message);
		} else if (response.type == 'success') {
			pageOverlay.hide();
			$("input[name=csrf_boostpanel]").val(response.csrf);
			$('.error').attr('style', 'display:none;');
			$('.success').attr('style', 'display:block;');
			$('.success-message').html(response.message);
		}
	}, 'json');
});

/**
 * Ajax Add API Provider
 */
$("#add-api-providers").on('submit', function () {
	event.preventDefault();
	pageOverlay.show();
	var data = $(this).serialize();
	$.post(this.action, data, function (response) {
		if (response.type == 'error') {
			pageOverlay.hide();
			$("input[name=csrf_boostpanel]").val(response.csrf);
			$('.success').attr('style', 'display:none;');
			$('.error').attr('style', 'display:block;');
			$('.error-message').html(response.message);
		} else if (response.type == 'success') {
			pageOverlay.hide();
			$("input[name=csrf_boostpanel]").val(response.csrf);
			$('.error').attr('style', 'display:none;');
			$('.success').attr('style', 'display:block;');
			$('#add-api-providers')[0].reset();
			$('.success-message').html("<div class=\"spinner-border spinner-border-sm\" role=\"status\"></div> " + response.message);
			setTimeout(function () {
				location.reload();
			}, 2000);
		}
	}, 'json');
});

/**
 * Ajax Edit API Provider
 */
$("#edit-api-providers").bind('submit', function () {
	event.preventDefault();
	pageOverlay.show();
	var data = $(this).serialize();
	$.post(this.action, data, function (response) {
		if (response.type == 'error') {
			pageOverlay.hide();
			$("input[name=csrf_boostpanel]").val(response.csrf);
			$('.success').attr('style', 'display:none;');
			$('.error').attr('style', 'display:block;');
			$('.error-message').html(response.message);
		} else if (response.type == 'success') {
			pageOverlay.hide();
			$("input[name=csrf_boostpanel]").val(response.csrf);
			$('.error').attr('style', 'display:none;');
			$('.success').attr('style', 'display:block;');
			$('.success-message').html("<div class=\"spinner-border spinner-border-sm\" role=\"status\"></div> " + response.message);
			setTimeout(function () {
				location.reload();
			}, 2000);
		}
	}, 'json');
});

/**
 * Ajax Sync Services via API Provider
 */
$("#sync-services-api").on('submit', function () {
	event.preventDefault();
	pageOverlay.show();
	var data = $(this).serialize();
	$.post(this.action, data, function (response) {
		if (response.type == 'error') {
			pageOverlay.hide();
			$("input[name=csrf_boostpanel]").val(response.csrf);
			$('.error').attr('style', 'display:block;');
			$('.error-message').html(response.message);
		} else if (response.type == 'success') {
			pageOverlay.hide();
			$("input[name=csrf_boostpanel]").val(response.csrf);
			$('.error').attr('style', 'display:none;');
			$('select[name=synchronous_request]').val('noselect');
			Swal.fire({
				title: response.title,
				html: response.message,
				icon: response.type,
				showConfirmButton: true
			});
		}
	}, 'json');
});

/**
 * Ajax Add Service via API Provider
 */
$("#add-service-via-api").on('submit', function () {
	event.preventDefault();
	pageOverlay.show();
	var data = $(this).serialize();
	$.post(this.action, data, function (response) {
		if (response.type == 'error') {
			pageOverlay.hide();
			$("input[name=csrf_boostpanel]").val(response.csrf);
			$('.error').attr('style', 'display:block;');
			$('.error-message').html(response.message);
		} else if (response.type == 'success') {
			pageOverlay.hide();
			$("input[name=csrf_boostpanel]").val(response.csrf);
			$('.error').attr('style', 'display:none;');
			$('select[name=category_service]').val('noselect');
			$('select[name=price_percentage_increase]').val('0');
			$('input[name=auto_convert_currency_service]').prop("checked", false);
			Swal.fire({
				title: response.title,
				html: response.message,
				icon: response.type,
				showConfirmButton: false,
				timer: 3000,
				onBeforeOpen: function onBeforeOpen() {
					Swal.showLoading();
				}
			});
		}
	}, 'json');
});

/**
 * Ajax Add News
 */
$("#add-news").on('submit', function () {
	event.preventDefault();
	pageOverlay.show();
	var data = $(this).serialize();
	$.post(this.action, data, function (response) {
		if (response.type == 'error') {
			pageOverlay.hide();
			$("input[name=csrf_boostpanel]").val(response.csrf);
			$('.success').attr('style', 'display:none;');
			$('.error').attr('style', 'display:block;');
			$('.error-message').html(response.message);
		} else if (response.type == 'success') {
			pageOverlay.hide();
			$("input[name=csrf_boostpanel]").val(response.csrf);
			$('.error').attr('style', 'display:none;');
			$('.success').attr('style', 'display:block;');
			$('.success-message').html("<div class=\"spinner-border spinner-border-sm\" role=\"status\"></div> " + response.message);
			setTimeout(function () {
				$('.success').remove();
				$('#add-news')[0].reset();
				window.location.href = '/admin/news';
			}, 2000);
		}
	}, 'json');
});

/**
 * Ajax Edit News
 */
$("#edit-news").on('submit', function () {
	event.preventDefault();
	pageOverlay.show();
	var data = $(this).serialize();
	$.post(this.action, data, function (response) {
		if (response.type == 'error') {
			pageOverlay.hide();
			$("input[name=csrf_boostpanel]").val(response.csrf);
			$('.success').attr('style', 'display:none;');
			$('.error').attr('style', 'display:block;');
			$('.error-message').html(response.message);
		} else if (response.type == 'success') {
			pageOverlay.hide();
			$("input[name=csrf_boostpanel]").val(response.csrf);
			$('.error').attr('style', 'display:none;');
			$('.success').attr('style', 'display:block;');
			$('.success-message').html("<div class=\"spinner-border spinner-border-sm\" role=\"status\"></div> " + response.message);
			setTimeout(function () {
				$('.success').remove();
				$('#edit-news')[0].reset();
				window.location.href = '/admin/news';
			}, 2000);
		}
	}, 'json');
});

/**
 * Ajax Add FAQ
 */
$("#add-faq").on('submit', function () {
	event.preventDefault();
	pageOverlay.show();
	var data = $(this).serialize();
	$.post(this.action, data, function (response) {
		if (response.type == 'error') {
			pageOverlay.hide();
			$("input[name=csrf_boostpanel]").val(response.csrf);
			$('.success').attr('style', 'display:none;');
			$('.error').attr('style', 'display:block;');
			$('.error-message').html(response.message);
		} else if (response.type == 'success') {
			pageOverlay.hide();
			$("input[name=csrf_boostpanel]").val(response.csrf);
			$('.error').attr('style', 'display:none;');
			$('.success').attr('style', 'display:block;');
			$('.success-message').html("<div class=\"spinner-border spinner-border-sm\" role=\"status\"></div> " + response.message);
			$('#add-faq')[0].reset();
			setTimeout(function () {
				location.reload();
			}, 2000);
		}
	}, 'json');
});

/**
 * Ajax Edit FAQ
 */
$("#edit-faq").on('submit', function () {
	event.preventDefault();
	pageOverlay.show();
	var data = $(this).serialize();
	$.post(this.action, data, function (response) {
		if (response.type == 'error') {
			pageOverlay.hide();
			$("input[name=csrf_boostpanel]").val(response.csrf);
			$('.success').attr('style', 'display:none;');
			$('.error').attr('style', 'display:block;');
			$('.error-message').html(response.message);
		} else if (response.type == 'success') {
			pageOverlay.hide();
			$("input[name=csrf_boostpanel]").val(response.csrf);
			$('.error').attr('style', 'display:none;');
			$('.success').attr('style', 'display:block;');
			$('.success-message').html("<div class=\"spinner-border spinner-border-sm\" role=\"status\"></div> " + response.message);
			setTimeout(function () {
				location.reload();
			}, 2000);
		}
	}, 'json');
});

/**
 * Ajax Add Language
 */
$("#add-new-language").on('submit', function () {
	event.preventDefault();
	pageOverlay.show();
	var data = $(this).serialize();
	$.post(this.action, data, function (response) {
		if (response.type == 'error') {
			pageOverlay.hide();
			$("input[name=csrf_boostpanel]").val(response.csrf);
			$('.success').attr('style', 'display:none;');
			$('.error').attr('style', 'display:block;');
			$('.error-message').html(response.message);
		} else if (response.type == 'success') {
			$("input[name=csrf_boostpanel]").val(response.csrf);
			$('.error').attr('style', 'display:none;');
			setTimeout(function () {
				pageOverlay.hide();
				$('#add-new-language')[0].reset();
			}, 2000);
			$('.success').attr('style', 'display:block;');
			$('.success-message').html(response.message);
		}
	}, 'json');
});

/**
 * Ajax Edit Language
 */
$("#edit-language").on('submit', function () {
	event.preventDefault();
	pageOverlay.show();
	var data = $(this).serialize();
	$.post(this.action, data, function (response) {
		if (response.type == 'error') {
			pageOverlay.hide();
			$("input[name=csrf_boostpanel]").val(response.csrf);
			$('.success').attr('style', 'display:none;');
			$('.error').attr('style', 'display:block;');
			$('.error-message').html(response.message);
		} else if (response.type == 'success') {
			$("input[name=csrf_boostpanel]").val(response.csrf);
			$('.error').attr('style', 'display:none;');
			setTimeout(function () {
				pageOverlay.hide();
				$('#edit-language')[0].reset();
				location.reload();
			}, 2000);
			$('.success').attr('style', 'display:block;');
			$('.success-message').html(response.message);
		}
	}, 'json');
});

/**
 * Ajax Search Management Users
 */
$(document).on("input", ".searchUserAjax", function () {
	event.preventDefault();
	pageOverlay.show();
	var action = $(this).data("url");
	var data = $(this).serialize();
	var field = $(this).val();
	$.post(action, data, function (response) {
		if (field != '') {
			pageOverlay.hide();
			$(".table-users").empty().append(response);
		} else {
			pageOverlay.hide();
			location.reload();
		}
	});
});

/**
 * Ajax Add New User (Admin)
 */
$("#add-new-user").on('submit', function () {
	event.preventDefault();
	pageOverlay.show();
	var data = $(this).serialize();
	$.post(this.action, data, function (response) {
		if (response.type == 'error') {
			pageOverlay.hide();
			$("input[name=csrf_boostpanel]").val(response.csrf);
			$('.success').attr('style', 'display:none;');
			$('.error').attr('style', 'display:block;');
			$('.error-message').html(response.message);
		} else if (response.type == 'success') {
			pageOverlay.hide();
			$("input[name=csrf_boostpanel]").val(response.csrf);
			$('.error').attr('style', 'display:none;');
			$('.success').attr('style', 'display:block;');
			$('.success-message').html(response.message);
			$('#add-new-user')[0].reset();
		}
	}, 'json');
});

/**
 * Ajax Update Profile via ID USER (ADMIN)
 */
$("#update-profile-user").on('submit', function () {
	event.preventDefault();
	pageOverlay.show();
	var data = $(this).serialize();
	$.post(this.action, data, function (response) {
		if (response.type == 'error') {
			pageOverlay.hide();
			$("input[name=csrf_boostpanel]").val(response.csrf);
			$('.success').attr('style', 'display:none;');
			$('.error').attr('style', 'display:block;');
			$('.error-message').html(response.message);
		} else if (response.type == 'success') {
			pageOverlay.hide();
			$("input[name=csrf_boostpanel]").val(response.csrf);
			$('.error').attr('style', 'display:none;');
			$('.success').attr('style', 'display:block;');
			$('.success-message').html(response.message);
		}
	}, 'json');
});

/**
 * Ajax Update Custom Rate via ID USER (ADMIN)
 */
$("#update-custom-rate-user").on('submit', function () {
	event.preventDefault();
	pageOverlay.show();
	var data = $(this).serialize();
	$.post(this.action, data, function (response) {
		if (response.type == 'error') {
			pageOverlay.hide();
			$("input[name=csrf_boostpanel]").val(response.csrf);
			$('.success').attr('style', 'display:none;');
			$('.error').attr('style', 'display:block;');
			$('.error-message').html(response.message);
		} else if (response.type == 'success') {
			pageOverlay.hide();
			$("input[name=csrf_boostpanel]").val(response.csrf);
			$('.error').attr('style', 'display:none;');
			$('.success').attr('style', 'display:block;');
			$('.success-message').html(response.message);
		}
	}, 'json');
});

/**
 * Ajax Update Role User (ADMIN)
 */
$("#update-role-user").on('submit', function () {
	event.preventDefault();
	pageOverlay.show();
	var data = $(this).serialize();
	$.post(this.action, data, function (response) {
		if (response.type == 'error') {
			pageOverlay.hide();
			$("input[name=csrf_boostpanel]").val(response.csrf);
			$('.success').attr('style', 'display:none;');
			$('.error').attr('style', 'display:block;');
			$('.error-message').html(response.message);
		} else if (response.type == 'success') {
			pageOverlay.hide();
			$("input[name=csrf_boostpanel]").val(response.csrf);
			$('.error').attr('style', 'display:none;');
			$('.success').attr('style', 'display:block;');
			$('.success-message').html(response.message);
		}
	}, 'json');
});

/**
 * Ajax Change Password User for ID (ADMIN)
 */
$("#change-password").on('submit', function () {
	event.preventDefault();
	pageOverlay.show();
	var data = $(this).serialize();
	$.post(this.action, data, function (response) {
		if (response.type == 'error') {
			pageOverlay.hide();
			$("input[name=csrf_boostpanel]").val(response.csrf);
			$('.success').attr('style', 'display:none;');
			$('.error').attr('style', 'display:block;');
			$('.error-message').html(response.message);
		} else if (response.type == 'success') {
			pageOverlay.hide();
			$("input[name=csrf_boostpanel]").val(response.csrf);
			$('.error').attr('style', 'display:none;');
			$('.success').attr('style', 'display:block;');
			$('.success-message').html(response.message);
			$('#change-password')[0].reset();
		}
	}, 'json');
});

/**
 * Ajax Add and Remove Balance User
 */
$("#balance-action").on('submit', function () {
	event.preventDefault();
	pageOverlay.show();
	var data = $(this).serialize();
	$.post(this.action, data, function (response) {
		if (response.type == 'error') {
			pageOverlay.hide();
			$("input[name=csrf_boostpanel]").val(response.csrf);
			$('.success').attr('style', 'display:none;');
			$('.error').attr('style', 'display:block;');
			$('.error-message').html(response.message);
		} else if (response.type == 'success') {
			pageOverlay.hide();
			$("input[name=csrf_boostpanel]").val(response.csrf);
			$('.error').attr('style', 'display:none;');
			$('.success').attr('style', 'display:block;');
			$('.success-message').html(response.message);
			$('#balance-action')[0].reset();
		}
	}, 'json');
});

/**
 * Ajax New Order
 */
$("#add-new-order").on('submit', function () {
	event.preventDefault();
	pageOverlay.show();
	var data = $(this).serialize();
	$.post(this.action, data, function (response) {
		if (response.type == 'error') {
			pageOverlay.hide();
			$("input[name=csrf_boostpanel]").val(response.csrf);
			$('.success').attr('style', 'display:none;');
			$('.error').attr('style', 'display:block;');
			$('.error-message').html(response.message);
		} else if (response.type == 'success') {
			pageOverlay.hide();
			$("input[name=csrf_boostpanel]").val(response.csrf);
			$('.services-input').addClass('d-none');
			$('.link-no').addClass('d-none');
			$('.quantity-no').addClass('d-none');
			$('.total-price-new-order').addClass('d-none');
			$('.comments-custom').addClass('d-none');
			$('.usernames').addClass('d-none');
			$('.usernames_hashtags').addClass('d-none');
			$('.mentions_with_hashtags').addClass('d-none');
			$('.mentions_with_hashtag').addClass('d-none');
			$('.username-follower').addClass('d-none');
			$('.media_url').addClass('d-none');
			$('.dripfeed').addClass('d-none');
			$('.subscriptions').addClass('d-none');
			$('.text-min-max').addClass('d-none');
			$('.description-service').addClass('d-none');
			$('.answer_number_poll').addClass('d-none');
			$('.seo_keywords').addClass('d-none');
			$('.error').attr('style', 'display:none;');
			$('.success').attr('style', 'display:block;');
			$('.success-message').html(response.message);
			$('#add-new-order')[0].reset();
		}
	}, 'json');
});

/**
 * Ajax Add Mass Order
 */
$("#mass-order").on('submit', function () {
	event.preventDefault();
	pageOverlay.show();
	var data = $(this).serialize();
	$.post(this.action, data, function (response) {
		if (response.type == 'error') {
			pageOverlay.hide();
			$("input[name=csrf_boostpanel]").val(response.csrf);
			$('.success').attr('style', 'display:none;');
			$('.error').attr('style', 'display:block;');
			$('.error-message').html(response.message);
		} else if (response.type == 'success') {
			pageOverlay.hide();
			$("input[name=csrf_boostpanel]").val(response.csrf);
			$('.error').attr('style', 'display:none;');
			$('.success').attr('style', 'display:block;');
			$('.success-message').html(response.message);
			$('#mass-order')[0].reset();
		}
	}, 'json');
});

/**
 * Ajax Get Content Settings Admin
 */
$(document).on("click", ".getContents", function () {
	event.preventDefault();
	pageOverlay.show();
	var type = $(this).data("content");
	var action = $(this).data("url");
	var data = $.param({
		type: type
	});

	$.post(action, data, function (response) {
		$(".ajax_content").html(response);
		setTimeout(function () {
			pageOverlay.hide();
		}, 500);
	});
});

/**
 * Update Balance API Provider
 *
 * @param {*} id
 */
function updateBalanceApi(id) {
	$(document).on('click', '#update_balance_api' + id, function () {
		event.preventDefault();
		$("#update_balance_api" + id).addClass('d-none');
		$(".spinner" + id).removeClass('d-none');
		setTimeout(function () {
			window.location.href = base + 'admin/api/update/balance/' + id;
		}, 2000);
	});
}

/**
 * Ajax Add Balance Form
 *
 * @param {*} type
 */
$(".balanceAddForm").on('submit', function () {
	event.preventDefault();
	pageOverlay.show();
	var loading_balance = $('#loading_balance').val();
	var pay_balance = $('#pay_balance').val();
	$("#btnSubmitAddBalance").html(loading_balance + ' ...').attr("disabled", true);

	var data = $(this).serialize();

	$.post(this.action, data, function (response) {
		if (response.type == 'error') {
			pageOverlay.hide();
			$("input[name=csrf_boostpanel]").val(response.csrf);
			$('.success').attr('style', 'display:none;');
			$('.error').attr('style', 'display:block;');
			$('.error-message').html(response.message);
			$("#btnSubmitAddBalance").html(pay_balance).removeAttr("disabled");
		} else if (response.type == 'success') {
			pageOverlay.hide();
			$("input[name=csrf_boostpanel]").val(response.csrf);
			$('.error').attr('style', 'display:none;');
			$('.balanceAddForm')[0].reset();
			window.location.href = response.link;
		}
	}, 'json');
});

/**
 * Ajax Edit Payment
 */
$(".editPayment").on('submit', function () {
	event.preventDefault();
	pageOverlay.show();
	var data = $(this).serialize();

	$.post(this.action, data, function (response) {
		if (response.type == 'error') {
			pageOverlay.hide();
			$("input[name=csrf_boostpanel]").val(response.csrf);
			$('.success').attr('style', 'display:none;');
			$('.error').attr('style', 'display:block;');
			$('.error-message').html(response.message);
		} else if (response.type == 'success') {
			pageOverlay.hide();
			$("input[name=csrf_boostpanel]").val(response.csrf);
			$('.error').attr('style', 'display:none;');
			$('.success').attr('style', 'display:block;');
			$('.success-message').html(response.message);
		}
	}, 'json');
});

/**
 * Ajax Update Status Payment
 *
 * @param {*} type
 */
function updateStatusPayment(type) {
	$('#update-payment-status-' + type).on('change', function () {
		var data = $(this).serialize();
		$.post(this.action, data, function () {
			if ($('input[name=payment_status_' + type + ']').is(':checked')) {
				$('input[name=payment_status_' + type + ']').prop("checked", true);
			} else if (!$('input[name=payment_status_' + type + ']').is(':checked')) {
				$('input[name=payment_status_' + type + ']').prop("checked", false);
			}
		});
	});
}

/**
 * Edit FAQ
 *
 * @param {*} id
 */
function editFAQ(id) {
	var title = $('#title_faq' + id).html();
	var contentStrip = $("#strip_tags" + id).html();
	$("#edit-faq").attr("action", base + 'admin/faq/edit/' + id);
	$('input[name=title_edit]').val(title);
	$('textarea[name=description_edit]').val(contentStrip.trim());
}

/**
 * Edit Category
 *
 * @param {*} id
 */
function editCategory(id) {
	var category_name = $('#category_name' + id).html();
	var category_status = $('#status_category' + id).data("status");
	$("#edit-category-panel").attr("action", base + 'admin/category/edit/' + id);
	$('input[name=edit_category_name]').val(category_name);
	if (category_status == 0) {
		$('input[type=checkbox][name=edit_status_category]').bootstrapToggle('off');
	} else if (category_status == 1) {
		$('input[type=checkbox][name=edit_status_category]').bootstrapToggle('on');
	}
}

/**
 * Edit API Provider
 *
 * @param {*} id
 */
function editApi(id) {
	var name_api = $('#name_api' + id).html();
	var url_api = $('#url_api' + id).html();
	var type_parameter = $('#type_parameter' + id).html();
	var api_key = $('#api_key' + id).html();
	var api_status = $('#api_status' + id).data("status");
	$("#edit-api-providers").attr("action", base + 'admin/api/providers/edit/' + id);
	$('input[name=edit_name_api]').val(name_api);
	$('input[name=edit_url_api]').val(url_api);
	$('select[name=edit_type_parameter]').val(type_parameter);
	$('input[name=edit_key_api]').val(api_key);
	if (api_status == 0) {
		$('input[type=checkbox][name=edit_api_status]').bootstrapToggle('off');
	} else if (api_status == 1) {
		$('input[type=checkbox][name=edit_api_status]').bootstrapToggle('on');
	}
}

/**
 * Sync Services API Provider
 *
 * @param {*} id
 */
function syncServicesApi(id) {
	var name_api = $('#name_api' + id).html();
	var url_api = $('#url_api' + id).html();
	var api_key = $('#api_key' + id).html();
	$("#sync-services-api").attr("action", base + 'admin/api/sync/services/' + id);
	$('input[name=sync_name_api]').val(name_api);
	$('input[name=sync_url_api]').val(url_api);
	$('input[name=sync_key_api]').val(api_key);
}

/**
 * Get data via API Provider
 *
 * @param {*} id
 */
function getDataViaApi(id) {
	var data_service_id = $('#data-service-' + id).data("service-id");
	var service_name = $('#data-service-name-' + id).data('title');
	var category_service = $('#data-service-category-' + id).data('category');
	var service_amount_min = $('#data-amount-min-' + id).data('min');
	var service_amount_max = $('#data-amount-max-' + id).data('max');
	var price_service = $('#data-price-' + id).data('price');
	var currency_price = $('#data-price-' + id).data('currency');
	var dripfeed_service = $('#data-' + id).data('dripfeed');
	var data_provider_id = $('#data-' + id).data('provider-id');
	var data_type = $('#data-' + id).data('type');
	var description_service = $('#data-' + id).data('description');
	$("input[type=text][name=name_service]").val(service_name);
	$("input[type=text][name=category_service]").val(category_service);
	$("input[type=hidden][name=min_amount_service]").val(service_amount_min);
	$("input[type=hidden][name=max_amount_service]").val(service_amount_max);
	$("input[type=text][name=price_service]").val(price_service);
	$("input[type=hidden][name=currency_price]").val(currency_price);
	$("input[type=hidden][name=dripfeed]").val(dripfeed_service);
	$("input[type=hidden][name=api_service_id]").val(data_service_id);
	$("input[type=hidden][name=api_provider_id]").val(data_provider_id);
	$("input[type=hidden][name=type]").val(data_type);
	$("#description_service").val(description_service);
}

/**
 * Actions Orders History (Admin)
 *
 * @param {*} action
 * @param {*} id
 */
function actionsOrdersHistory(action, id) {
	if (action == 'edit_link') {
		$("#edit-link-order").attr("action", base + 'admin/orders/actions/edit_link/' + id);
		var link = $('#link-order' + id).data('link');
		$('input[name=edit_link]').val(link);
	} else if (action == 'set_start_count') {
		$("input[name=set_start_count]").on('keypress', function (e) {
			if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
				return false;
			}
		});
		$("#form_set_start_count").attr("action", base + 'admin/orders/actions/set_start_count/' + id);
	} else if (action == 'set_partial') {
		$("input[name=set_partial]").on('keypress', function (e) {
			if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
				return false;
			}
		});
		$("#form_set_partial").attr("action", base + 'admin/orders/actions/set_partial/' + id);
	}
}

/**
 * Status Completed Subs Manual
 *
 * @param {*} id
 */
function statusCompletedSubs(id) {
	$("#update-status-completed-subs").attr("action", base + 'admin/subscriptions/completed/' + id);
}

/**
 * Only numbers inputs
 */
$(document).ready(function () {
	$(".searchTicketsAjax").on('keypress', function (e) {
		if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
			return false;
		}
	});

	$("input[name=edit_api_service_id]").on('keypress', function (e) {
		if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
			return false;
		}
	});

	$("input[name=add_api_service_id]").on('keypress', function (e) {
		if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
			return false;
		}
	});

	$("input[name=quantity]").on('keypress', function (e) {
		if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
			return false;
		}
	});
});
