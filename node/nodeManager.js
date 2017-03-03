var nodes = {
	'http://localhost:4040/postmessage': {
		user: 'Danny Harding',
		peers: ['http://localhost:4141/postmessage', 'http://localhost:8080/postmessage'],
		seen: {}
	},
	'http://localhost:4141/postmessage': {
		user: 'Ashley Harding',
		peers: ['http://localhost:4040/postmessage', 'http://localhost:8181/postmessage'],
		seen: {}
	},
	'http://localhost:8080/postmessage': {
		user: 'Paisley Grace',
		peers: ['http://localhost:4141/postmessage', 'http://localhost:4040/postmessage', 'http://localhost:8181/postmessage'],
		seen: {}
	},
	'http://localhost:8181/postmessage': {
		user: 'Joe Mama',
		peers: ['http://localhost:8080/postmessage'],
		seen: {}
	}
}

function updateNode(endpoint, id, lastMessage) {
	nodes[endpoint].seen[id] = lastMessage;
}

function getPeer(endpoint) {
	var peers = nodes[endpoint].peers;
	return peers[Math.floor(Math.random() * peers.length)];
}

module.exports = {
	nodes: nodes,
	updateNode: updateNode,
	getPeer: getPeer
}