$(document).ready(function() {
	$('#send-message').click(function() {
		var messageText = $('#message-input').val();
		var time = new Date();
		$.ajax({
			url: '/postmessage',
			type: 'POST',
			dataType: 'json',
			data: {
				userID: $('#user-id').val(),
				text: messageText,
				time: time
			},
			success: function() {
				// probably nothing
			},
			error: function() {
				// remove something?
			}
		});

		addMessage(messageText, time);
		$('#message-input').val('');
	});

	$('#message-input').keypress(function(event){
		if(event.keyCode == 13) {
			$('#send-message').click();
		}
	});

	function addMessage(text, time) {
		$('#conversation').append(`
			<div class='message'>
				<div class='message-row'>
					<div class='user-name'>${userName}:</div>
					<div class='message-text'>${text}</div>
				</div>
				<div class='message-time'>${time}</div>
			</div>
		`);
	}

});