var uuid = require('./constants').uuid;

function isRumor(message) {
	return message.hasOwnProperty('Rumor');
}

function isWant(message) {
	return message.hasOwnProperty('Want');
}

function isFromSelf(message) {
	return message.userID === uuid;
}

module.exports = {
	isRumor: isRumor,
	isWant: isWant,
	isFromSelf: isFromSelf
}