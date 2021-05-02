jQuery(function ($) {
    $('#locked_btn').on('click', function (param) {
		param.preventDefault();
		$.ajax({
			type: "get",
			url: public_ajax_action.ajaxurl,
			data: {
				action: 'get_payment_form'
			},
			success: function (response) {
				$('#payment_form').show().append(response);
			}
		});
	});
});