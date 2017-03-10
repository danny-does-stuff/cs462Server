$(document).ready(function() {
	scrollConvoToBottom();

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

		scrollConvoToBottom();
	});

	$('#add-friend').click(function() {
		var newURL = $('#other-url').val();
		$('#other-url').val('');

		$.ajax({
			url: '/addpeer',
			type: 'POST',
			dataType: 'json',
			data: {
				url: newURL
			},
			success: function() {
				// probably nothing
			},
			error: function(jqXHR, error) {
				alert("There was a problem on the server. The friend may not have been added");
			}
		});
	})

	$('#message-input').keypress(function(event){
		if (event.keyCode == 13) {
			$('#send-message').click();
		}
	});

	$('#other-url').keypress(function(event){
		if (event.keyCode == 13) {
			$('#add-friend').click();
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

	function scrollConvoToBottom() {
		var convo = document.getElementById('conversation');
		convo.scrollTop = convo.scrollHeight;
	}

	setInterval(function() {
		$.ajax({
			url: '/messages',
			type: 'get',
			dataType: 'json',
			success: function(result) {
				var newMessages = result.length > $('#conversation .message').length;
				$('#conversation').empty();

				result.forEach(function(message) {
					addMessage(message.user, message.text, message.time);
				});

				if (newMessages) {
					scrollConvoToBottom();
				}
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
