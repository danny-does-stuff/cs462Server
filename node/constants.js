var uuid = require('uuid/v1');

var port = process.argv[2];
var endpoint = 'http://localhost:' + port + '/postmessage';
var uniqueID = uuid();

module.exports = {
	uuid: uniqueID,
	port: port,
	endpoint: endpoint
}