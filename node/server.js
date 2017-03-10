var express = require('express');
var bodyParser = require('body-parser')
var messageHandler = require('./messageHandler');
var nodeManager = require('./nodeManager');
var workQueue = require('./workQueue');
var constants = require('./constants');
var socket = require('./socket');

console.log(`starting server on port ${constants.port}`);


var app = express();
app.set('view engine', 'pug');
app.use(bodyParser.json());
app.use(bodyParser.urlencoded( {extended: true} ));
app.use(express.static(__dirname));

var server = require('http').createServer(app);
// socket.init(server);


app.get('/', function(req, res) {
	res.render('chat', {
		messages: messageHandler.messages,
		name: nodeManager.getMyUsername(),
		id: constants.uuid,
		port: constants.port
	});
});

app.get('/messages', function(req, res) {
	res.status(200).send(messageHandler.messages);
});

app.post('/postmessage', function(req, res) {
	message = req.body;
	messageHandler.handle(message);

	res.status(200).send({'success': 'true'});
});

app.post('/addpeer', function(req, res) {
	if (!req.body.url) {
		res.status(400).send({'error': 'no url provided'});
	}

	nodeManager.addPeer(req.body.url);

	res.status(200).send({'success': 'true'});
});

app.listen(constants.port);

setInterval(function() {
	workQueue.propogateMessage();
}, 500);
