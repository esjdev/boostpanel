"use strict";

/**
 * List Services for Category
 */
$(document).on('change', 'select[name=category]', function () {
	var servID = $('option:selected', this).val();
	var data = $(this).serialize();
	$.post(base + 'service_list_order', data, function (response) {
		if (servID == 'noselect' || response == '') {
			$('.services-input').addClass('d-none');
			$('.link-no').addClass('d-none');
			$('.quantity-no').addClass('d-none');
			$('.text-min-max').addClass('d-none');
			$('.total-price-new-order').addClass('d-none');
			$('.comments-custom').addClass('d-none');
			$('.usernames').addClass('d-none');
			$('.usernames_hashtags').addClass('d-none');
			$('.mentions_with_hashtags').addClass('d-none');
			$('.username-follower').addClass('d-none');
			$('.media_url').addClass('d-none');
			$('.dripfeed').addClass('d-none');
			$('.subscriptions').addClass('d-none');
			$('.answer_number_poll').addClass('d-none');
		} else {
			$('.services-input').removeClass('d-none');
		}

		if (servID != 'noselect' && response == '') {
			$('#no_service_category').removeClass('d-none');
			$('#submit-order').addClass('d-none');
		} else {
			$('#no_service_category').addClass('d-none');
			$('#submit-order').removeClass('d-none');
		}

		$('.description-service').addClass('d-none');
		$('#description_service').val('');

		$('#select_services').empty().append(response);
	});
});

/**
 * "Select" with all services of category
 */
var price = 0;
var quantity = 0;

$(document).on('change', '#services', function () {
	var serviceId = $('option:selected', this).val();

	if (serviceId != 'noselect') {
		$('.link-no').removeClass('d-none');
		$('.quantity-no').removeClass('d-none');
		$('.text-min-max').removeClass('d-none');
		$('.total-price-new-order').removeClass('d-none');
		$('.comments-custom').addClass('d-none');
		$('.usernames').addClass('d-none');
		$('.usernames_hashtags').addClass('d-none');
		$('.mentions_with_hashtags').addClass('d-none');
		$('.mentions_with_hashtag').addClass('d-none');
		$('.username-follower').addClass('d-none');
		$('.media_url').addClass('d-none');
		$('.dripfeed').addClass('d-none');
		$('.subscriptions').addClass('d-none');
		$('.answer_number_poll').addClass('d-none');
		$('.seo_keywords').addClass('d-none');
	} else {
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
		$('.answer_number_poll').addClass('d-none');
		$('.seo_keywords').addClass('d-none');
		$('.text-min-max').addClass('d-none');
	}

	if (!isNaN(serviceId)) {
		var data = $(this).serialize();

		$.post(base + 'service_list_order', data, function (response) {
			price = response.price_per_1k;
			var total = (price * quantity) / 1000;

			switch (response.service_type) {
				case 'default':
					$('.subscriptions').addClass('d-none');
					$('.link-no').removeClass('d-none');
					$('.quantity-no').removeClass('d-none');
					$('.text-min-max').removeClass('d-none');
					$('#quantityService').attr('readonly', false);
					$('.usernames').addClass('d-none');
					$('.usernames_hashtags').addClass('d-none');
					$('.mentions_with_hashtags').addClass('d-none');
					$('.mentions_with_hashtag').addClass('d-none');
					$('.username-follower').addClass('d-none');
					$('.media_url').addClass('d-none');
					$('.answer_number_poll').addClass('d-none');
					$('.seo_keywords').addClass('d-none');

					// Dripfeed
					if (response.dripfeed == 1) {
						$('.dripfeed').removeClass('d-none');

						$('#dripfeedCheck').on('click', function () {
							if ($(this).prop("checked") == true) {
								var runs = $("input[name=runs]").val(),
									total_quantity_dripfeed = runs * quantity;
								$('.forms-dripfeed').removeClass('d-none');

								var totalDefault = (total_quantity_dripfeed * price) / 1000;

								$(document).on('input', '#quantityService', function () {
									var runs = $("input[name=runs]").val(),
										total_quantity_dripfeed = runs * quantity,
										total = (total_quantity_dripfeed * price) / 1000;

									if (total_quantity_dripfeed != "") {
										$('input[name=total_quantity_dripfeed]').val(total_quantity_dripfeed);
									}
									$('#priceTotal').text(total.toFixed(2));
								});

								$(document).on('input', '#quantityRuns', function () {
									var runs = $("input[name=runs]").val(),
										total_quantity_dripfeed = runs * quantity,
										total = (total_quantity_dripfeed * price) / 1000;

									if (total_quantity_dripfeed != "" && runs != "") {
										$('input[name=total_quantity_dripfeed]').val(total_quantity_dripfeed);
										$('#priceTotal').text(total.toFixed(2));
									} else {
										$('input[name=total_quantity_dripfeed]').val(total_quantity_dripfeed);
										$('#priceTotal').text(total.toFixed(2));
									}
								});

								$('#priceTotal').text(totalDefault.toFixed(2));
							} else {
								$('.forms-dripfeed').addClass('d-none');
								$('#priceTotal').text(parseFloat(price).toFixed(2));
							}
						});
					} else {
						$('.dripfeed').addClass('d-none');
						$('#priceTotal').text(total.toFixed(2));
					}
					break;

				case 'custom_data':
					$('.subscriptions').addClass('d-none');
					$('.link-no').removeClass('d-none');
					$('.quantity-no').removeClass('d-none');
					$('.text-min-max').removeClass('d-none');
					$('#quantityService').attr('readonly', true);
					$('.usernames').addClass('d-none');
					$('.usernames_hashtags').addClass('d-none');
					$('.mentions_with_hashtags').addClass('d-none');
					$('.mentions_with_hashtag').addClass('d-none');
					$('.username-follower').addClass('d-none');
					$('.media_url').addClass('d-none');
					$('.comments-custom').removeClass('d-none');
					$('.answer_number_poll').addClass('d-none');
					$('#priceTotal').text(total.toFixed(2));
					$('.seo_keywords').addClass('d-none');

					$("#tagsinput_comments_tag").on("blur focus change", function () {
						var quantityComments = $('#tagsinput_comments').val();
						var count_tags = $('.tag').length;

						if (quantityComments == "") {
							quantityComments = 0;
						} else {
							quantityComments = quantityComments.split(",").length;
						}

						$("#quantityService").val(quantityComments);
						var service_price = $("#priceTotal").text();

						var total_charge = (quantityComments != "" && quantityComments != "0") ? (price * quantityComments) / 1000 : 0;
						$('#priceTotal').text(total_charge.toFixed(2));

						var timer = setTimeout(update(), 100);

						function update() {
							$("#quantityService").val(count_tags);
						}
					});
					break;

				case 'subscriptions':
					$('.subscriptions').removeClass('d-none');
					$('.link-no').addClass('d-none');
					$('.quantity-no').addClass('d-none');
					$('.comments-custom').addClass('d-none');
					$('.text-min-max').removeClass('d-none');
					$('.usernames').addClass('d-none');
					$('.usernames_hashtags').addClass('d-none');
					$('.mentions_with_hashtags').addClass('d-none');
					$('.mentions_with_hashtag').addClass('d-none');
					$('.username-follower').addClass('d-none');
					$('.media_url').addClass('d-none');
					$('.answer_number_poll').addClass('d-none');
					$('.total-price-new-order').addClass('d-none');
					$('.seo_keywords').addClass('d-none');
					break;

				case 'custom_comments':
					$('.subscriptions').addClass('d-none');
					$('.link-no').removeClass('d-none');
					$('.quantity-no').removeClass('d-none');
					$('#quantityService').attr('readonly', true);
					$('.comments-custom').removeClass('d-none');
					$('.usernames').addClass('d-none');
					$('.usernames_hashtags').addClass('d-none');
					$('.mentions_with_hashtags').addClass('d-none');
					$('.mentions_with_hashtag').addClass('d-none');
					$('.text-min-max').removeClass('d-none');
					$('.username-follower').addClass('d-none');
					$('.media_url').addClass('d-none');
					$('#priceTotal').text("0.00");
					$('.answer_number_poll').addClass('d-none');
					$('.seo_keywords').addClass('d-none');

					$("#tagsinput_comments_tag").on("blur focus change", function () {
						var quantityComments = $('#tagsinput_comments').val();
						var count_tags = $('.tag').length;

						if (quantityComments == "") {
							quantityComments = 0;
						} else {
							quantityComments = quantityComments.split(",").length;
						}

						$("#quantityService").val(quantityComments);
						var service_price = $("#priceTotal").text();

						var total_charge = (quantityComments != "" && quantityComments != "0") ? (price * quantityComments) / 1000 : 0;
						$('#priceTotal').text(total_charge.toFixed(2));

						var timer = setTimeout(update(), 100);

						function update() {
							$("#quantityService").val(count_tags);
						}
					});
					break;

				case 'custom_comments_package':
					$('.subscriptions').addClass('d-none');
					$('.quantity-no').addClass('d-none');
					$('.comments-custom').removeClass('d-none');
					$('.usernames_hashtags').addClass('d-none');
					$('.usernames').addClass('d-none');
					$('.mentions_with_hashtags').addClass('d-none');
					$('.mentions_with_hashtag').addClass('d-none');
					$('.text-min-max').addClass('d-none');
					$('.username-follower').addClass('d-none');
					$('.media_url').addClass('d-none');
					$('#priceTotal').text(parseFloat(price).toFixed(2));
					$('.answer_number_poll').addClass('d-none');
					$('.seo_keywords').addClass('d-none');
					break;

				case 'mentions_with_hashtags':
					$('.subscriptions').addClass('d-none');
					$('.link-no').removeClass('d-none');
					$('.quantity-no').removeClass('d-none');
					$('#quantityService').attr('readonly', false);
					$('.usernames_hashtags').addClass('d-none');
					$('.usernames').removeClass('d-none');
					$('.mentions_with_hashtags').removeClass('d-none');
					$('.mentions_with_hashtag').addClass('d-none');
					$('.text-min-max').removeClass('d-none');
					$('.username-follower').addClass('d-none');
					$('.media_url').addClass('d-none');
					$('#priceTotal').text(total.toFixed(2));
					$('.answer_number_poll').addClass('d-none');
					$('.seo_keywords').addClass('d-none');
					break;

				case 'mentions_custom_list':
					$('.subscriptions').addClass('d-none');
					$('.link-no').removeClass('d-none');
					$('.quantity-no').removeClass('d-none');
					$('#quantityService').attr('readonly', true);
					$('.usernames').addClass('d-none');
					$('.usernames_hashtags').removeClass('d-none');
					$('.mentions_with_hashtags').addClass('d-none');
					$('.mentions_with_hashtag').addClass('d-none');
					$('.text-min-max').removeClass('d-none');
					$('.username-follower').addClass('d-none');
					$('.media_url').addClass('d-none');
					$('#priceTotal').text("0.00");
					$('.answer_number_poll').addClass('d-none');
					$('.seo_keywords').addClass('d-none');

					$("#tagsinput_usernames_hashtags_tag").bind("blur focus change", function () {
						var quantityComments = $('#tagsinput_usernames_hashtags').val();
						var count_tags = $('.tag').length;

						if (quantityComments == "") {
							quantityComments = 0;
						} else {
							quantityComments = quantityComments.split(",").length;
						}

						$("#quantityService").val(quantityComments);
						var service_price = $("#priceTotal").text();

						var total_charge = (quantityComments != "" && quantityComments != "0") ? (price * quantityComments) / 1000 : 0;
						$('#priceTotal').text(total_charge.toFixed(2));

						var timer = setTimeout(update(), 100);

						function update() {
							$("#quantityService").val(count_tags);
						}
					});
					break;

				case 'mentions_hashtag':
					$('.subscriptions').addClass('d-none');
					$('.link-no').removeClass('d-none');
					$('.quantity-no').removeClass('d-none');
					$('#quantityService').attr('readonly', false);
					$('.usernames').addClass('d-none');
					$('.usernames_hashtags').addClass('d-none');
					$('.mentions_with_hashtags').addClass('d-none');
					$('.mentions_with_hashtag').removeClass('d-none');
					$('.text-min-max').removeClass('d-none');
					$('.username-follower').addClass('d-none');
					$('.media_url').addClass('d-none');
					$('#priceTotal').text(total.toFixed(2));
					$('.answer_number_poll').addClass('d-none');
					$('.seo_keywords').addClass('d-none');
					break;

				case 'mentions_user_followers':
					$('.subscriptions').addClass('d-none');
					$('.link-no').removeClass('d-none');
					$('.quantity-no').removeClass('d-none');
					$('#quantityService').attr('readonly', false);
					$('.usernames').addClass('d-none');
					$('.username-follower').removeClass('d-none');
					$('.usernames_hashtags').addClass('d-none');
					$('.mentions_with_hashtags').addClass('d-none');
					$('.mentions_with_hashtag').addClass('d-none');
					$('.media_url').addClass('d-none');
					$('.text-min-max').removeClass('d-none');
					$('#priceTotal').text(total.toFixed(2));
					$('.answer_number_poll').addClass('d-none');
					$('.seo_keywords').addClass('d-none');
					break;

				case 'mentions_media_likers':
					$('.subscriptions').addClass('d-none');
					$('.link-no').removeClass('d-none');
					$('.quantity-no').removeClass('d-none');
					$('#quantityService').attr('readonly', false);
					$('.usernames').addClass('d-none');
					$('.username-follower').addClass('d-none');
					$('.usernames_hashtags').addClass('d-none');
					$('.mentions_with_hashtags').addClass('d-none');
					$('.mentions_with_hashtag').addClass('d-none');
					$('.media_url').removeClass('d-none');
					$('#priceTotal').text(total.toFixed(2));
					$('.answer_number_poll').addClass('d-none');
					$('.seo_keywords').addClass('d-none');
					break;

				case 'package':
					$('.subscriptions').addClass('d-none');
					$('.quantity-no').addClass('d-none');
					$('.comments-custom').addClass('d-none');
					$('.usernames').addClass('d-none');
					$('.usernames_hashtags').addClass('d-none');
					$('.mentions_with_hashtags').addClass('d-none');
					$('.mentions_with_hashtag').addClass('d-none');
					$('.text-min-max').addClass('d-none');
					$('.username-follower').addClass('d-none');
					$('.media_url').addClass('d-none');
					$('#priceTotal').text(parseFloat(price).toFixed(2));
					$('.answer_number_poll').addClass('d-none');
					$('.seo_keywords').addClass('d-none');
					break;

				case 'comment_likes':
					$('.subscriptions').addClass('d-none');
					$('.link-no').removeClass('d-none');
					$('.quantity-no').removeClass('d-none');
					$('.usernames').addClass('d-none');
					$('.username-follower').removeClass('d-none');
					$('.usernames_hashtags').addClass('d-none');
					$('.mentions_with_hashtags').addClass('d-none');
					$('.mentions_with_hashtag').addClass('d-none');
					$('.media_url').addClass('d-none');
					$('#quantityService').attr('readonly', false);
					$('#priceTotal').text(total.toFixed(2));
					$('.answer_number_poll').addClass('d-none');
					$('.seo_keywords').addClass('d-none');
					break;

				case 'poll':
					$('.subscriptions').addClass('d-none');
					$('.link-no').removeClass('d-none');
					$('.quantity-no').removeClass('d-none');
					$('.usernames').addClass('d-none');
					$('.username-follower').addClass('d-none');
					$('.usernames_hashtags').addClass('d-none');
					$('.mentions_with_hashtags').addClass('d-none');
					$('.mentions_with_hashtag').addClass('d-none');
					$('.media_url').addClass('d-none');
					$('#quantityService').attr('readonly', false);
					$('#priceTotal').text(total.toFixed(2));
					$('.answer_number_poll').removeClass('d-none');
					$('.seo_keywords').addClass('d-none');
					break;

				case 'seo':
					$('.subscriptions').addClass('d-none');
					$('.link-no').removeClass('d-none');
					$('.quantity-no').removeClass('d-none');
					$('.usernames').addClass('d-none');
					$('.username-follower').addClass('d-none');
					$('.usernames_hashtags').addClass('d-none');
					$('.mentions_with_hashtags').addClass('d-none');
					$('.mentions_with_hashtag').addClass('d-none');
					$('.media_url').addClass('d-none');
					$('#quantityService').attr('readonly', false);
					$('#priceTotal').text(total.toFixed(2));
					$('.answer_number_poll').addClass('d-none');
					$('.seo_keywords').removeClass('d-none');
					break;
			}

			if (response.description != '') {
				$('.description-service').removeClass('d-none');
				$('#description_service').val(response.description);
			} else {
				$('.description-service').addClass('d-none');
				$('#description_service').val('');
			}

			$('#min').text(response.min);
			$('#max').text(response.max);

			if (response.service_type == 'custom_comments' || response.service_type == 'mentions_custom_list') {
				$('#priceTotal').text("0.00");
			} else {
				$('#priceTotal').text(parseFloat(price).toFixed(2));
			}
		}, 'json');
	} else {
		$('.description-service').addClass('d-none');
		$('#description_service').val('');
		$('#min').text(0);
		$('#max').text(0);
		price = 0;
		quantity = 0;
		var total = 0;
		$('#priceTotal').text(total.toFixed(2))
	}
});

/**
 * Calculate Quantity Service
 */
$(document).on('input', '#quantityService', function () {
	quantity = $(this).val();
	var total = (price * quantity) / 1000;

	if (quantity == '') {
		$('#priceTotal').text(parseFloat(price).toFixed(2));
	} else {
		$('#priceTotal').text(total.toFixed(2));
	}
});
