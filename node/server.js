var express = require('express');
var bodyParser = require('body-parser')
var messageHandler = require('./messageHandler');
var workQueue = require('./workQueue');
var constants = require('./constants');

console.log(`starting server on port ${constants.port}`);

var app = express();
app.set('view engine', 'pug');
app.use(bodyParser.json());
app.use(bodyParser.urlencoded( {extended: true} ));

var server = require('http').createServer(app);
var io = require('socket.io')(server);
io.on('connection', function(){
	client.on('event', function(data){
		console.log('from socket', data);
	});
	client.on('disconnect', function(){
		// nothing?
	});
});


app.get('/', function(req, res) {
	res.render('chat', {
		messages: messageHandler.messages,
		name: constants.userName,
		id: constants.uuid
	});
});

app.post('/postmessage', function(req, res) {
	message = req.body;
	// console.log('receiving post:', req.body);
	messageHandler.handle(message);
});

app.listen(constants.port);

setInterval(function() {
	workQueue.propogateMessage();
}, 500);
