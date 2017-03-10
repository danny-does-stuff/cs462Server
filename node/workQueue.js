var request = require('request');
var nodeManager = require('./nodeManager');
var constants = require('./constants');
var messageChecker = require('./messages');

var queue = [];

function addToQueue(wantMessage) {
	queue.push(wantMessage);
}

function processQueue() {
	queue.forEach(function(wantMessage) {
		var message = prepareMessage(wantMessage.Want);
		if (message !== false) {
			sendMessage(message, wantMessage.EndPoint);
		}

		// foreach w work_queue {
		// 	s = prepareMsg(state, w)                        
		// 	<url> = getUrl(w)
		// 	send(<url>, s)
		// 	state = update(state, s) // put inside of sendMessage()
		// }
	});
	queue = [];
}

function prepareMessage(want) {
	if (Math.floor(Math.random() * 2)) {
		return generateRumor(want);
	} else {
		return generateWant();
	}
}

function generateRumor(want) {
	var iKnowMore = {};
	var mySeen = nodeManager.nodes[constants.endpoint].seen;

	var mySeenIDs = Object.keys(mySeen);
	for (var i = 0; i < mySeenIDs.length; i++) {
		if (!want.hasOwnProperty(mySeenIDs[i])) {
			iKnowMore[mySeenIDs[i]] = 0
		} else if (mySeen[mySeenIDs[i]] > want[mySeenIDs[i]]) {
			iKnowMore[mySeenIDs[i]] = Number(want[mySeenIDs[i]]) + 1;
		}

		/*if (i == mySeenIDs.length - 1 && Object.keys(iKnowMore).length == 0) {
			iKnowMore[mySeenIDs[i]] = mySeen[mySeenIDs[i]];
		}*/
	}

	var messageHandler = require('./messageHandler');
	if (Object.keys(iKnowMore).length == 0) {
		return false;
	}
	var message = messageHandler.getMessageToSend(iKnowMore);

	return {
		Rumor: {
			MessageID: message.id,
			Originator: message.user,
			Text: message.text
		},
		EndPoint: constants.endpoint
	}
}

function generateWant() {
	var wantMessage = {
		EndPoint: constants.endpoint
	}

	wantMessage.Want = nodeManager.nodes[constants.endpoint].seen;
	return wantMessage;
}

function sendMessage(message, url) {
	var options = {
		method: 'post',
		body: message,
		json: true,
		url: url
	}

	request(options, function(error, response, body) {
		if (error) {
			console.log('other server gave an error', error);
		}
		
		if (!error && response.statusCode == 200) {
			if (messageChecker.isRumor(message)) {
				var messageData = message.Rumor.MessageID.split(':');
				nodeManager.updateNode(url, messageData[0], messageData[1]);
			}
		}
	});
}

function propogateMessage() {
	var url = nodeManager.getPeer(constants.endpoint);
	if (url) {
		var message = prepareMessage(nodeManager.nodes[url].seen);
		if (message !== false) {
			sendMessage(message, url);
		}
	}
}

module.exports = {
	processQueue: processQueue,
	addToQueue: addToQueue,
	propogateMessage: propogateMessage
}

 // Rumor Format
 //	{
 //		"Rumor" : {
 //			"MessageID": "ABCD-1234-ABCD-1234-ABCD-1234:5" ,
 //			"Originator": "Phil",
 //			"Text": "Hello World!"
 //		},
 //		"EndPoint": "https://example.com/gossip/13244"
 //	}

//	Want Format
//		{"Want": {
//			"ABCD-1234-ABCD-1234-ABCD-125A": 3,
//			"ABCD-1234-ABCD-1234-ABCD-129B": 5,
//			"ABCD-1234-ABCD-1234-ABCD-123C": 10
//		} ,
//	"EndPoint": "https://example.com/gossip/asff3"
// }