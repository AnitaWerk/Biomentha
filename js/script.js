var $ = jQuery.noConflict();

function sendContact() {
	event.preventDefault();
	var form = $('#contactform').serialize();
	$.ajax({
		url: "send-form.php",
		data: form,
		type: "POST",
		success: function (data){
			if (data == "OK") {
				showMessage('Su mensaje ha sido enviado.', 'success', '¡Gracias!');
				$('#contactform').find("input, textarea").attr("disabled", true);
			}
		},
		error: function (error){
			console.log(error);
		},
	}).fail( function( jqXHR, textStatus, errorThrown ) {
		if (jqXHR.status === 0) {
			showMessage('Not connect: Verify Network.', 'error');
		} else if (jqXHR.status == 404) {
			showMessage('Requested page not found [404]', 'error');
		} else if (jqXHR.status == 500) {
			showMessage('Internal Server Error [500].', 'error');
		} else if (textStatus === 'parsererror') {
			showMessage('Requested JSON parse failed.', 'error');
		} else if (textStatus === 'timeout') {
			showMessage('Time out error.', 'error');
		} else if (textStatus === 'abort') {
			showMessage('Ajax request aborted.', 'error');
		} else {
			showMessage(jqXHR.responseText, 'error');
		}
	});
}

function showMessage(message, type = 'success', title = 'Oops..') {
	swal({
		type: type,
		title: title,
		text: message
	})
}