function getMinInteger(){
	insertCharacterToCodeEditor("-2147483648");
	genMinValueModal.style.display = "none";
}
function getMinFloat(){
	insertCharacterToCodeEditor( "(float)1.4E-45");
	genMinValueModal.style.display = "none";
}
function getMinDouble(){
	insertCharacterToCodeEditor( "4.9E-324");
	genMinValueModal.style.display = "none";
}
function getMinLong(){
	insertCharacterToCodeEditor( "-9223372036854775808");
	genMinValueModal.style.display = "none";
}
function getMinShort(){
	insertCharacterToCodeEditor( "-32768");
	genMinValueModal.style.display = "none";
}
function getMinByte(){
	insertCharacterToCodeEditor( "-128");
	genMinValueModal.style.display = "none";
}
function getMaxInteger(){
	insertCharacterToCodeEditor("2147483648");
	genMaxValueModal.style.display = "none";
}
function getMaxFloat(){
	insertCharacterToCodeEditor( "(float)3.4028235E38");
	genMaxValueModal.style.display = "none";
}
function getMaxDouble(){
	insertCharacterToCodeEditor( "1.7976931348623157E308");
	genMaxValueModal.style.display = "none";
}
function getMaxLong(){
	insertCharacterToCodeEditor( "9223372036854775807");
	genMaxValueModal.style.display = "none";
}
function getMaxShort(){
	insertCharacterToCodeEditor( "3276");
	genMaxValueModal.style.display = "none";
}
function getMaxByte(){
	insertCharacterToCodeEditor( "127");
	genMaxValueModal.style.display = "none";
}