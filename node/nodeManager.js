var constants = require('./constants');
var urlBase = constants.urlBase;
var postRoute = constants.postRoute;

var nodeURLs = [urlBase + ':8000' + postRoute, urlBase + ':8001' + postRoute, urlBase + ':8002' + postRoute, urlBase + ':8003' + postRoute];

var nodes = {};

nodes[nodeURLs[0]] = {
	user: 'Danny Harding',
	peers: [nodeURLs[1], nodeURLs[2]],
	seen: {}
};

nodes[nodeURLs[1]] = {
	user: 'Ashley Harding',
	peers: [nodeURLs[0], /*nodeURLs[3]*/],
	seen: {}
};

nodes[nodeURLs[2]] = {
	user: 'Paisley Grace',
	peers: [nodeURLs[1], nodeURLs[0], /*nodeURLs[3]*/],
	seen: {}
};

nodes[nodeURLs[3]] = {
	user: 'Joe Mama',
	peers: [/*nodeURLs[2]*/],
	seen: {}
};

function updateNode(endpoint, id, lastMessage) {
	// only update lastMessage if it is greater than what we already know about node with given id.
	if (!nodes[endpoint]) {
		nodes[endpoint] = {
			seen: {}
		};
	}

	if (!nodes[endpoint].seen[id] || nodes[endpoint].seen[id] < lastMessage) {
		nodes[endpoint].seen[id] = lastMessage;
	}
}

function getPeer(endpoint) {
	var peers = nodes[endpoint].peers;
	return peers ? peers[Math.floor(Math.random() * peers.length)] : false;
}

function addPeer(endpoint, name) {
	var currPeers = nodes[constants.endpoint].peers;
	if (!currPeers.includes(endpoint)) {
		nodes[constants.endpoint].peers.push(endpoint);
		console.log('added peer');
		console.log(nodes[constants.endpoint].peers);
	}

	if (!nodes.hasOwnProperty(endpoint)) {
		nodes[endpoint] = {
			user: name ? name : 'No Name',
			peers: [constants.endpoint],
			seen: {}
		}
	}
}

function isPeer(endpoint) {
	return (nodes[constants.endpoint].peers.includes(endpoint));
}

function getMyUsername() {
	return nodes[constants.endpoint].user;
}

module.exports = {
	nodes: nodes,
	updateNode: updateNode,
	getPeer: getPeer,
	addPeer: addPeer,
	isPeer: isPeer,
	getMyUsername: getMyUsername
}
