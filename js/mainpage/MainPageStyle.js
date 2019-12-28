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