var socket = require('socket.io');
var constants = require('./constants');

function init(server) {
	var io = require('socket.io')(server);

	io.sockets.on('connection', function(client) {

		console.log('A client is connected!');

		client.on('event', function(data) {
			console.log('from socket', data);
		});

		client.on('disconnect', function() {
			// nothing?
		});
	});

	// io.listen(constants.port);
}

module.exports = {
	init: init
}