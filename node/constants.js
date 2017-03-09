var uuid = require('uuid/v1');

var urlBase = 'http://localhost';
var postRoute = '/postmessage';
var port = process.argv[2];
var endpoint = urlBase + ':' + port + postRoute;
var uniqueID = uuid();

module.exports = {
	uuid: uniqueID,
	urlBase: urlBase,
	postRoute: postRoute,
	port: port,
	endpoint: endpoint
}