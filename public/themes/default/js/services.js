"use strict";

/**
 * Add New Service
 */
$(".addNewService").on('submit', function () {
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
 * Edit Service
 */
$(".editService").on('submit', function () {
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
 * Select Type Service (Default, Subscriptions, Poll, Package and etcs...)
 */

$('.dripfeed-default').addClass('d-none');
$('.type_service').removeClass('col-6');
$('.type_service').addClass('col-12');

function selectTypeService(type) {
	$(document).on('change', 'select[name=' + type + ']', function () {
		var types = $('option:selected', this).val();

		switch (types) {
			case 'noselect':
				$('.dripfeed-default').addClass('d-none');
				$('.type_service').addClass('col-12');
				$('.type_service').removeClass('col-6');
				break;

			case 'default':
				$('.dripfeed-default').removeClass('d-none');
				$('.type_service').removeClass('col-12');
				$('.type_service').addClass('col-6');
				break;

			case 'subscriptions':
				$('.dripfeed-default').addClass('d-none');
				$('.type_service').removeClass('col-6');
				$('.type_service').addClass('col-12');
				break;

			case 'custom_comments':
				$('.dripfeed-default').addClass('d-none');
				$('.type_service').removeClass('col-6');
				$('.type_service').addClass('col-12');
				break;

			case 'custom_comments_package':
				$('.dripfeed-default').addClass('d-none');
				$('.type_service').removeClass('col-6');
				$('.type_service').addClass('col-12');
				break;

			case 'mentions_with_hashtags':
				$('.dripfeed-default').addClass('d-none');
				$('.type_service').removeClass('col-6');
				$('.type_service').addClass('col-12');
				break;

			case 'mentions_custom_list':
				$('.dripfeed-default').addClass('d-none');
				$('.type_service').removeClass('col-6');
				$('.type_service').addClass('col-12');
				break;

			case 'mentions_hashtag':
				$('.dripfeed-default').addClass('d-none');
				$('.type_service').removeClass('col-6');
				$('.type_service').addClass('col-12');
				break;

			case 'mentions_user_followers':
				$('.dripfeed-default').addClass('d-none');
				$('.type_service').removeClass('col-6');
				$('.type_service').addClass('col-12');
				break;

			case 'mentions_media_likers':
				$('.dripfeed-default').addClass('d-none');
				$('.type_service').removeClass('col-6');
				$('.type_service').addClass('col-12');
				break;

			case 'package':
				$('.dripfeed-default').addClass('d-none');
				$('.type_service').removeClass('col-6');
				$('.type_service').addClass('col-12');
				break;

			case 'comment_likes':
				$('.dripfeed-default').addClass('d-none');
				$('.type_service').removeClass('col-6');
				$('.type_service').addClass('col-12');
				break;

			case 'custom_data':
				$('.dripfeed-default').addClass('d-none');
				$('.type_service').removeClass('col-6');
				$('.type_service').addClass('col-12');
				break;

			case 'poll':
				$('.dripfeed-default').addClass('d-none');
				$('.type_service').removeClass('col-6');
				$('.type_service').addClass('col-12');
				break;

			case 'seo':
				$('.dripfeed-default').addClass('d-none');
				$('.type_service').removeClass('col-6');
				$('.type_service').addClass('col-12');
				break;
		}
	});
}

/**
 * Form Service Edit with data
 */
function serviceEdit(id, categoryID) {
	$(".editService").attr("action", base + 'admin/services/edit/' + id);

	var name_service = $('#name_service' + id).data('name-service');
	var description_service = $('.description_service' + id).html();
	var category_service = $('#service_category_id' + categoryID).data("category-id");
	var api_or_manual = $('#type_service' + id).data('api-or-manual');
	var type_service = $('#type_service' + id).data('type');
	var name_api = $('#type_service' + id).data('name-api');
	var price_service = $('#price_service' + id).data("price");
	var min_service = $('#min_max_service' + id).data("min");
	var max_service = $('#min_max_service' + id).data("max");
	var dripfeed_service = $('#dripfeed_service' + id).data("dripfeed");
	var status_service = $('#status_service' + id).data("status");

	$('input[name=edit_package_name]').val(name_service);
	$('select[name=category_service]').val(category_service);
	$('input[name=edit_min_amount]').val(min_service);
	$('input[name=edit_max_amount]').val(max_service);
	$('select[name=edit_services_type]').val(type_service);
	$('input[name=edit_price_amount]').val(price_service);
	$('#description_service').text(description_service.replace(/(<([^>]+)>)/ig, ""));

	if (api_or_manual == 'manual') {
		$('.dripfeed-default-edit').show();
		$('.type_service_edit').show();
		$('.api_name_form').addClass("d-none");
		$('.edit_min_amount').removeClass('d-none');
		$('.edit_max_amount').removeClass('d-none');
	} else {
		$('.dripfeed-default-edit').hide();
		$('.type_service_edit').hide();
		$('.api_name_form').removeClass("d-none");
		$('.api_name_form').html("(" + name_api + ")");
		$('.edit_min_amount').addClass('d-none');
		$('.edit_max_amount').addClass('d-none');
	}

	switch (type_service) {
		case 'default':
			$('.dripfeed-default-edit').removeClass('d-none');
			$('.type_service_edit').addClass('col-6');
			$('.type_service_edit').removeClass('col-12');
			$('select[name=edit_dripfeed_service]').val(dripfeed_service);
			break;

		case 'custom_data':
			$('.dripfeed-default-edit').addClass('d-none');
			$('.type_service_edit').removeClass('col-6');
			$('.type_service_edit').addClass('col-12');
			break;

		case 'subscriptions':
			$('.dripfeed-default-edit').addClass('d-none');
			$('.type_service_edit').removeClass('col-6');
			$('.type_service_edit').addClass('col-12');
			break;

		case 'custom_comments':
			$('.dripfeed-default-edit').addClass('d-none');
			$('.type_service_edit').removeClass('col-6');
			$('.type_service_edit').addClass('col-12');
			break;

		case 'custom_comments_package':
			$('.dripfeed-default-edit').addClass('d-none');
			$('.type_service_edit').removeClass('col-6');
			$('.type_service_edit').addClass('col-12');
			break;

		case 'mentions_with_hashtags':
			$('.dripfeed-default-edit').addClass('d-none');
			$('.type_service_edit').removeClass('col-6');
			$('.type_service_edit').addClass('col-12');
			break;

		case 'mentions_custom_list':
			$('.dripfeed-default-edit').addClass('d-none');
			$('.type_service_edit').removeClass('col-6');
			$('.type_service_edit').addClass('col-12');
			break;

		case 'mentions_hashtag':
			$('.dripfeed-default-edit').addClass('d-none');
			$('.type_service_edit').removeClass('col-6');
			$('.type_service_edit').addClass('col-12');
			break;

		case 'mentions_user_followers':
			$('.dripfeed-default-edit').addClass('d-none');
			$('.type_service_edit').removeClass('col-6');
			$('.type_service_edit').addClass('col-12');
			break;

		case 'mentions_media_likers':
			$('.dripfeed-default-edit').addClass('d-none');
			$('.type_service_edit').removeClass('col-6');
			$('.type_service_edit').addClass('col-12');
			break;

		case 'package':
			$('.dripfeed-default-edit').addClass('d-none');
			$('.type_service_edit').removeClass('col-6');
			$('.type_service_edit').addClass('col-12');
			break;

		case 'comment_likes':
			$('.dripfeed-default-edit').addClass('d-none');
			$('.type_service_edit').removeClass('col-6');
			$('.type_service_edit').addClass('col-12');
			break;

		case 'poll':
			$('.dripfeed-default-edit').addClass('d-none');
			$('.type_service_edit').removeClass('col-6');
			$('.type_service_edit').addClass('col-12');
			break;

		case 'seo':
			$('.dripfeed-default-edit').addClass('d-none');
			$('.type_service_edit').removeClass('col-6');
			$('.type_service_edit').addClass('col-12');
			break;
	}

	if (status_service == 0) {
		$('input[name=status_service]').bootstrapToggle('off');
	} else if (status_service == 1) {
		$('input[name=status_service]').bootstrapToggle('on');
	}
}

/**
 * Ajax Search Services
 */
$(document).on("input", ".searchServicesAjax", function () {
	event.preventDefault();
	pageOverlay.show();
	var action = $(this).data("url");
	var data = $(this).serialize();
	var field = $(this).val();
	$.post(action, data, function (response) {
		if (field != '') {
			pageOverlay.hide();
			$(".table-search-services").empty().append(response);
		} else {
			pageOverlay.hide();
			location.reload();
		}
	});
});
