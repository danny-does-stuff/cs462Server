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

		addMessage(userName, messageText, time);
		$('#message-input').val('');
	});

	$('#message-input').keypress(function(event){
		if(event.keyCode == 13) {
			$('#send-message').click();
		}
	});

	function addMessage(name, text, time) {
		$('#conversation').append(`
			<div class='message'>
				<div class='message-row'>
					<div class='user-name'>${name}:</div>
					<div class='message-text'>${text}</div>
				</div>
				<div class='message-time'>${time}</div>
			</div>
		`);
	}

	setInterval(function() {
		$.ajax({
			url: 'http://localhost:' + port + '/messages',
			type: 'get',
			dataType: 'json',
			success: function(result) {
				$('#conversation').empty();
				result.forEach(function(message) {
					addMessage(message.user, message.text, message.time);
				});
			}
		})
	}, 3000);

	// var socket = io('http://localhost:' + port);

	// socket.on('connect', function() {
	// 	console.log('connected on client');
	// });

	// socket.on('message', function(msg){
	// 	console.log('received', msg);
	// });

	// socket.send('Hello world!');

});