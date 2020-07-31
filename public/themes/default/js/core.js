"use strict";

var pageOverlay = pageOverlay || (function ($) {
	return {
		show: function (message, options) {
			if (!$('#page-overlay').hasClass('active')) {
				$('#page-overlay').addClass('active');
				$('#page-overlay .balls').removeClass('d-none');
			}
		},

		hide: function () {
			if ($('#page-overlay').hasClass('active')) {
				$('#page-overlay').removeClass('active');
				$('#page-overlay .balls').addClass('d-none');
			}
		}
	};
})(jQuery);

/**
 * Preloader
 */
$(window).on('load', function () {
	$('.preloader').delay(200).fadeOut(300);
});

$(document).ready(function () {
	// Active pageOverlay
	$('.loadingOverlay').on('click', function () {
		pageOverlay.show();
	});

	// Counter Numbers
	$('.counter').counterUp({
		time: 1000
	});

	// Title Tooltip Bootstrap
	$('[data-toggle="tooltip"]').tooltip({
		boundary: 'viewport'
	});

	// Click close alert
	$('.close').on('click', function () {
		$('.alert').hide();
	});

	// Scroll Effect menu
	$(window).scroll(function () {
		var window_top = $(window).scrollTop() + 1;
		if (window_top > 50) {
			$('.main_menu').addClass('menu_fixed animated fadeInDown');
		} else {
			$('.main_menu').removeClass('menu_fixed animated fadeInDown');
		}
	});

	// Effect Plus and Minus in FAQ
	$('.collapse').on('shown.bs.collapse', function () {
		$(this).parent().find(".fa-plus").removeClass("fa-plus").addClass("fa-minus");
	}).on('hidden.bs.collapse', function () {
		$(this).parent().find(".fa-minus").removeClass("fa-minus").addClass("fa-plus");
	});

	/**
	 * DataPicker Bootstrap
	 */
	$('.datepicker').datepicker();

	/*
	 * Tags Input Effect
	 */
	$('#tagsinput_comments').tagsInput({
		'placeholder': 'Comments',
	});

	$('#tagsinput_hashtags').tagsInput({
		'placeholder': '#',
	});

	$('#tagsinput_usernames_hashtags').tagsInput({
		'placeholder': '#',
	});

	$('#tagsinput_usernames').tagsInput({
		'placeholder': '#',
	});

	$('#tagsinput_keywords').tagsInput();
});

/**
 * Ajax Select Change Language
 */
$(document).on("change", ".ajaxChangeLanguage", function () {
	event.preventDefault();
	var ids = $(this).val();
	var action = $(this).data("url") + ids;
	var redirect = $(this).data("redirect");
	var data = $.param({
		redirect: redirect
	});
	if (ids == 'noselect') {
		window.location.href = base + 'language/no-select/idiom';
	} else {
		$.post(action, data, function () {
			window.location.href = redirect;
		});
	}
});

// Show/Hide Change Language
$('.change-language-button').on('click', function () {
	$('.change-language-show').css("display", "block");
	$('.change-language-button').hide();
});

$('.change-language-show').on('mouseleave', function () {
	$('.change-language-show').css("display", "none");
	$('.change-language-button').show();
});

// Show/Hide Change Language (Mobile)
$('.change-language-button-mobile').on('click', function () {
	$('.change-language-show-mobile').toggle();
});

$('.change-language-show-mobile').on('mouseleave', function () {
	$('.change-language-show-mobile').css("display", "none");
});

// Remove Bug Modal Bootstrap
$(document).on({
	'show.bs.modal': function () {
		$(this).removeAttr('tabindex');
	}
}, '.modal');

/**
 * Alerts SWAL
 *
 */
function alert_swal(url, title, confirmText, cancelText) {
	Swal.fire({
		title: title,
		icon: 'warning',
		showCancelButton: true,
		confirmButtonText: confirmText,
		cancelButtonText: cancelText,
		customClass: {
			confirmButton: 'btn btn-success',
			cancelButton: 'btn btn-danger ml-1'
		},
		buttonsStyling: false
	}).then(function (result) {
		if (result.value) {
			window.location.href = url;
		}
	});
}

function alert_confirm(url, title, confirmText, cancelText, msgSuccess) {
	Swal.fire({
		title: title,
		icon: 'error',
		showCancelButton: true,
		confirmButtonText: confirmText,
		cancelButtonText: cancelText,
		customClass: {
			confirmButton: 'btn btn-success',
			cancelButton: 'btn btn-danger ml-1'
		},
		buttonsStyling: false
	}).then(function (result) {
		if (result.value) {
			Swal.fire({
				title: msgSuccess,
				icon: 'success'
			}).then(function () {
				window.location.href = url;
			});
		}
	});
}

function alert_confirm_notice(url, title, confirmText, cancelText, text, msgSuccess) {
	Swal.fire({
		title: title,
		icon: 'error',
		html: '<div class="bg-warning text-white p-1 rounded fs-15"><i class="fa fa-exclamation-circle"></i> ' + text + '</div>',
		showCancelButton: true,
		confirmButtonText: confirmText,
		cancelButtonText: cancelText,
		customClass: {
			confirmButton: 'btn btn-success',
			cancelButton: 'btn btn-danger ml-1',
		},
		buttonsStyling: false
	}).then(function (result) {
		if (result.value) {
			Swal.fire({
				title: msgSuccess,
				icon: 'success'
			}).then(function () {
				window.location.href = url;
			});
		}
	});
}

function alert_normal(type, title, sub_title) {
	Swal.fire({
		title: title,
		text: sub_title,
		icon: type
	});
}
