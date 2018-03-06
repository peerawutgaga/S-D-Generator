var uploadModal = document.getElementById("uploadModal");
var createCodeModal = document.getElementById("createCodeModal");
var uploadBtn = document.getElementById("uploadBtn");
var createCodeBtn = document.getElementById("createCodeBtn");
var uploadClose = document.getElementsByClassName("close")[0];
var createCodeClose= document.getElementsByClassName("close")[1];
var uploadSDBtn =document.getElementById("uploadSD");
var SDFile = document.getElementById("SDFile");
var uploadCDBtn =document.getElementById("uploadCD");
var CDFile = document.getElementById("CDFile");
var SDSubmit = document.getElementById("SDSubmit");
var CDSubmit = document.getElementById("CDSubmit");
var SDSelect = document.getElementById("SDSelect");
var CDSelect = document.getElementById("CDSelect");

uploadBtn.style.cursor = "pointer";
uploadBtn.onclick = function () {
	uploadModal.style.display = "block";
};

createCodeBtn.style.cursor = "pointer";
createCodeBtn.onclick = function () {
	createCodeModal.style.display = "block";
	addItemToDropDown();
};

uploadClose.onclick = function () {
	uploadModal.style.display = "none";
};

createCodeClose.onclick =function(){
	createCodeModal.style.display = "none";
};

window.onclick = function (event) {
	console.log(event.target);
	if (event.target == uploadModal) {
		uploadModal.style.display = "none";
	}
	if(event.target == createCodeModal){
		createCodeModal.style.display = "none";
	}
};

uploadSDBtn.onclick = function(){
	SDFile.click();
};
uploadCDBtn.onclick = function(){
	CDFile.click();
};

function uploadSDFile()
{
	SDSubmit.click();
}
function uploadCDFile()
{
	CDSubmit.click();
}
function createCode(){
	var form = document.forms["codeProperties"];
	console.log(form["Filename"].value);
	window.location.href = "Create Code.html";
}
function addItemToDropDown(){
	console.log("call");
	
}