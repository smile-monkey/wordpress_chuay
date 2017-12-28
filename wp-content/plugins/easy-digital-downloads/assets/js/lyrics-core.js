(function ($) {
	$(document).ready(function () {
		$('#passage-text span').click(function () {
			$.post(
					LY_Ajax.ajaxurl,
					{
						// wp ajax action
				    	action: 'ajax-inputtitleSubmit',

						// vars
						ID: $(this).attr('id'),
						post_ID: $("#post_ID").val(),
						Time: Math.floor(document.getElementById('passage-audio').currentTime),

						// send the nonce along with the request
						nextNonce: LY_Ajax.nextNonce
					},
					function (response) {
						//console.log(response);
						console.log( document.getElementById('passage-audio').currentTime )
					}
			);
			return false;
			
		});

	});
	})(jQuery);