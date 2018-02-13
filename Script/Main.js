var modal = document.getElementById("uploadModal");
var uploadBtn = document.getElementById("uploadBtn");
var span = document.getElementsByClassName("close")[0];
var uploadSDBtn =document.getElementById("uploadSD");
var SDuploader = document.getElementById("SDuploader");
var uploadCDBtn =document.getElementById("uploadCD");
var CDuploader = document.getElementById("CDuploader");

uploadBtn.style.cursor = "pointer";
uploadBtn.onclick = function () {
	modal.style.display = "block";
};

span.onclick = function () {
	modal.style.display = "none";
};

window.onclick = function (event) {
	if (event.target == modal) {
		modal.style.display = "none";
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