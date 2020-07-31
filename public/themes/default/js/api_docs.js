"use strict";

/**
 * Select type service in API DOCS
 */
$(document).on('change', '#service_type_api_docs', function () {
	var type = $(this).val();

	switch (type) {
		case '0':
			$(".service_type_0").show();
			$(".service_type_1").hide();
			$(".service_type_2").hide();
			$(".service_type_3").hide();
			$(".service_type_4").hide();
			$(".service_type_5").hide();
			$(".service_type_6").hide();
			$(".service_type_7").hide();
			$(".service_type_8").hide();
			$(".service_type_9").hide();
			$(".service_type_10").hide();
			$(".service_type_11").hide();
			$(".service_type_12").hide();
			$(".service_type_13").hide();
			break;

		case '1':
			$(".service_type_0").hide();
			$(".service_type_1").show();
			$(".service_type_2").hide();
			$(".service_type_3").hide();
			$(".service_type_4").hide();
			$(".service_type_5").hide();
			$(".service_type_6").hide();
			$(".service_type_7").hide();
			$(".service_type_8").hide();
			$(".service_type_9").hide();
			$(".service_type_10").hide();
			$(".service_type_11").hide();
			$(".service_type_12").hide();
			$(".service_type_13").hide();
			break;

		case '2':
			$(".service_type_0").hide();
			$(".service_type_1").hide();
			$(".service_type_2").show();
			$(".service_type_3").hide();
			$(".service_type_4").hide();
			$(".service_type_5").hide();
			$(".service_type_6").hide();
			$(".service_type_7").hide();
			$(".service_type_8").hide();
			$(".service_type_9").hide();
			$(".service_type_10").hide();
			$(".service_type_11").hide();
			$(".service_type_12").hide();
			$(".service_type_13").hide();
			break;

		case '3':
			$(".service_type_0").hide();
			$(".service_type_1").hide();
			$(".service_type_2").hide();
			$(".service_type_3").show();
			$(".service_type_4").hide();
			$(".service_type_5").hide();
			$(".service_type_6").hide();
			$(".service_type_7").hide();
			$(".service_type_8").hide();
			$(".service_type_9").hide();
			$(".service_type_10").hide();
			$(".service_type_11").hide();
			$(".service_type_12").hide();
			$(".service_type_13").hide();
			break;

		case '4':
			$(".service_type_0").hide();
			$(".service_type_1").hide();
			$(".service_type_2").hide();
			$(".service_type_3").hide();
			$(".service_type_4").show();
			$(".service_type_5").hide();
			$(".service_type_6").hide();
			$(".service_type_7").hide();
			$(".service_type_8").hide();
			$(".service_type_9").hide();
			$(".service_type_10").hide();
			$(".service_type_11").hide();
			$(".service_type_12").hide();
			$(".service_type_13").hide();
			break;

		case '5':
			$(".service_type_0").hide();
			$(".service_type_1").hide();
			$(".service_type_2").hide();
			$(".service_type_3").hide();
			$(".service_type_4").hide();
			$(".service_type_5").show();
			$(".service_type_6").hide();
			$(".service_type_7").hide();
			$(".service_type_8").hide();
			$(".service_type_9").hide();
			$(".service_type_10").hide();
			$(".service_type_11").hide();
			$(".service_type_12").hide();
			$(".service_type_13").hide();
			break;

		case '6':
			$(".service_type_0").hide();
			$(".service_type_1").hide();
			$(".service_type_2").hide();
			$(".service_type_3").hide();
			$(".service_type_4").hide();
			$(".service_type_5").hide();
			$(".service_type_6").show();
			$(".service_type_7").hide();
			$(".service_type_8").hide();
			$(".service_type_9").hide();
			$(".service_type_10").hide();
			$(".service_type_11").hide();
			$(".service_type_12").hide();
			$(".service_type_13").hide();
			break;

		case '7':
			$(".service_type_0").hide();
			$(".service_type_1").hide();
			$(".service_type_2").hide();
			$(".service_type_3").hide();
			$(".service_type_4").hide();
			$(".service_type_5").hide();
			$(".service_type_6").hide();
			$(".service_type_7").show();
			$(".service_type_8").hide();
			$(".service_type_9").hide();
			$(".service_type_10").hide();
			$(".service_type_11").hide();
			$(".service_type_12").hide();
			$(".service_type_13").hide();
			break;

		case '8':
			$(".service_type_0").hide();
			$(".service_type_1").hide();
			$(".service_type_2").hide();
			$(".service_type_3").hide();
			$(".service_type_4").hide();
			$(".service_type_5").hide();
			$(".service_type_6").hide();
			$(".service_type_7").hide();
			$(".service_type_8").show();
			$(".service_type_9").hide();
			$(".service_type_10").hide();
			$(".service_type_11").hide();
			$(".service_type_12").hide();
			$(".service_type_13").hide();
			break;

		case '9':
			$(".service_type_0").hide();
			$(".service_type_1").hide();
			$(".service_type_2").hide();
			$(".service_type_3").hide();
			$(".service_type_4").hide();
			$(".service_type_5").hide();
			$(".service_type_6").hide();
			$(".service_type_7").hide();
			$(".service_type_8").hide();
			$(".service_type_9").show();
			$(".service_type_10").hide();
			$(".service_type_11").hide();
			$(".service_type_12").hide();
			$(".service_type_13").hide();
			break;

		case '10':
			$(".service_type_0").hide();
			$(".service_type_1").hide();
			$(".service_type_2").hide();
			$(".service_type_3").hide();
			$(".service_type_4").hide();
			$(".service_type_5").hide();
			$(".service_type_6").hide();
			$(".service_type_7").hide();
			$(".service_type_8").hide();
			$(".service_type_9").hide();
			$(".service_type_10").show();
			$(".service_type_11").hide();
			$(".service_type_12").hide();
			$(".service_type_13").hide();
			break;

		case '11':
			$(".service_type_0").hide();
			$(".service_type_1").hide();
			$(".service_type_2").hide();
			$(".service_type_3").hide();
			$(".service_type_4").hide();
			$(".service_type_5").hide();
			$(".service_type_6").hide();
			$(".service_type_7").hide();
			$(".service_type_8").hide();
			$(".service_type_9").hide();
			$(".service_type_10").hide();
			$(".service_type_11").show();
			$(".service_type_12").hide();
			$(".service_type_13").hide();
			break;

		case '12':
			$(".service_type_0").hide();
			$(".service_type_1").hide();
			$(".service_type_2").hide();
			$(".service_type_3").hide();
			$(".service_type_4").hide();
			$(".service_type_5").hide();
			$(".service_type_6").hide();
			$(".service_type_7").hide();
			$(".service_type_8").hide();
			$(".service_type_9").hide();
			$(".service_type_10").hide();
			$(".service_type_11").hide();
			$(".service_type_12").show();
			$(".service_type_13").hide();
			break;

		case '13':
			$(".service_type_0").hide();
			$(".service_type_1").hide();
			$(".service_type_2").hide();
			$(".service_type_3").hide();
			$(".service_type_4").hide();
			$(".service_type_5").hide();
			$(".service_type_6").hide();
			$(".service_type_7").hide();
			$(".service_type_8").hide();
			$(".service_type_9").hide();
			$(".service_type_10").hide();
			$(".service_type_11").hide();
			$(".service_type_12").hide();
			$(".service_type_13").show();
			break;
	}
});
