jQuery(document).ready(function($){

	$( "#test-button" ).on( "click", function() {
		$("#test-button").text("Sending...");
		$("#test-button").attr('disabled', true);

		var data = {
			'action'	: 'test_error'
		};

		$.post(ajaxurl, data, function(response) {
			$("#test-button").text("Send Test");
			$("#test-button").removeAttr('disabled',true);



		});

	});


});