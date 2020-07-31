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
 * Ajax Install Boostrap
 */
$("#install-form").on('submit', function () {
	event.preventDefault();
	pageOverlay.show();

	var data = $(this).serialize();
	$.post(this.action, data, function (response) {
		if (response.type == 'error') {
			pageOverlay.hide();
			$("input[name=csrf_boostpanel]").val(response.csrf);
			Swal.fire({
				title: 'ERROR',
				text: response.message,
				icon: response.type
			});
		} else if (response.type == 'success') {
			$("input[name=csrf_boostpanel]").val(response.csrf);
			$('#button-install').val(response.language).attr('disabled', 'disabled');
			setTimeout(function () {
				pageOverlay.hide();
				Swal.fire({
					title: response.title,
					html: response.html,
					icon: response.type,
					showConfirmButton: false,
					timer: 3000,
					onBeforeOpen: function onBeforeOpen() {
						Swal.showLoading();
					}
				}).then(function () {
					window.location.href = response.base_url;
				});
				$('#install-form')[0].reset();
			}, 1000);
		}
	}, 'json');
});
