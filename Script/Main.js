var uploadModal = document.getElementById("uploadModal");
var createCodeModal = document.getElementById("createCodeModal");
var fileListModal = document.getElementById("fileListModal");
var uploadBtn = document.getElementById("uploadBtn");
var createCodeBtn = document.getElementById("createCodeBtn");
var uploadClose = document.getElementsByClassName("close")[0];
var createCodeClose= document.getElementsByClassName("close")[1];
var fileListClose = document.getElementsByClassName("close")[2];
var uploadSDBtn =document.getElementById("uploadSD");
var uploadCDBtn =document.getElementById("uploadCD");
var ClassSelect = document.getElementById("ClassSelect");
//TODO Refactor
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

fileListClose.onclick = function(){
	fileListModal.style.display = "none";
};
window.onclick = function (event) {
	if (event.target == uploadModal) {
		uploadModal.style.display = "none";
	}
	if(event.target == createCodeModal){
		createCodeModal.style.display = "none";
	}
	if(event.target == fileListModal){
		fileListModal.style.display = "none";
	}
};

uploadSDBtn.onclick = function(){
	document.getElementById("SDFile").click();
};
uploadCDBtn.onclick = function(){
	document.getElementById("CDFile").click();
};

function uploadSDFile()
{
	document.getElementById("SDSubmit").click();
}
function uploadCDFile()
{
	document.getElementById("CDSubmit").click();
}
function selectSD(selected){
	$.post('Page/SetCodeProperties.php', {  
		'CUT' : selected,
	}, function(returnedData){
		 ClassSelect.innerHTML = returnedData;
	});
}

