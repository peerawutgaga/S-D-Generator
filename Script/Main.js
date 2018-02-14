var uploadModal = document.getElementById("uploadModal");
var createCodeModal = document.getElementById("createCodeModal");
var uploadBtn = document.getElementById("uploadBtn");
var createCodeBtn = document.getElementById("createCodeBtn");
var uploadClose = document.getElementsByClassName("close")[0];
var createCodeClose= document.getElementsByClassName("close")[1];
var uploadSDBtn =document.getElementById("uploadSD");
var SDuploader = document.getElementById("SDuploader");
var uploadCDBtn =document.getElementById("uploadCD");
var CDuploader = document.getElementById("CDuploader");

uploadBtn.style.cursor = "pointer";
uploadBtn.onclick = function () {
	uploadModal.style.display = "block";
};

createCodeBtn.style.cursor = "pointer";
createCodeBtn.onclick = function () {
	createCodeModal.style.display = "block";
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
	SDuploader.click();
};
uploadCDBtn.onclick = function(){
	CDuploader.click();
};

function uploadSDFile()
{
	
}
function uploadCDFile()
{
	
}