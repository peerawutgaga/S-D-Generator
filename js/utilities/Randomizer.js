function randomInt(from, to) {
	from = Number(from);
	to=Number(to);
	return Math.floor(Math.random() * (to - from + 1)) + from;
}
function randomDecimal(from, to,digits) {
	from = Number(from);
	to = Number(to);
	var data = (Math.random() * (to - from)) + from;
	if(digits != ""){
		data = data.toFixed(digits);
	}
	return data;
}
function randomChar() {
	var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
	return possible.charAt(Math.floor(Math.random() * possible.length));
}
function randomNumber() {
	var possible = "0123456789";
	return possible.charAt(Math.floor(Math.random() * possible.length));
}
function randomString(length) {
	var rndString = "";
	for (var i = 0; i < length; i++) {
		rndString += randomChar();
	}
	return rndString;
}
function randomNumberByDigit(digits) {
	var rndString = "";
	for (var i = 0; i < digits; i++) {
		rndString += randomNumber();
	}
	return rndString;
}
function randomDecimalByDigit(fraction, decimal) {
	var rndString = "";
	if (fraction == 0) {
		var rndString = "0";
	} else {
		for (var i = 0; i < fraction; i++) {
			rndString += randomNumber();
		}
	}
	if (decimal > 0) {
		rndString += ".";
		for (var i = 0; i < decimal; i++) {
			rndString += randomNumber();
		}
	}
	return rndString;
}