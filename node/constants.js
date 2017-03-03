var uuid = require('uuid/v1');
var nodes = require('./nodeManager').nodes;

var port = process.argv[2];
var endpoint = 'http://localhost:' + port + '/postmessage';
var uniqueID = uuid();
var userName = nodes[endpoint].user;

module.exports = {
	uuid: uniqueID,
	port: port,
	endpoint: endpoint,
	userName: userName
}