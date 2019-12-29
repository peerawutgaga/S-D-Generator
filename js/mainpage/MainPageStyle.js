//Close modal when click outside
window.onclick = function(event) {
	if (event.target == uploadModal) {
		uploadModal.style.display = "none";
	}
	if (event.target == diagramSelectionModal) {
		diagramSelectionModal.style.display = "none";
	}
	if (event.target == classSelectionModal) {
		classSelectionModal.style.display = "none";
	}
	if (event.target == fileListModal) {
		fileListModal.style.display = "none";
	}
};
// Upload button style
uploadBtn.style.cursor = "pointer";
uploadBtn.onclick = function() {
	uploadModal.style.display = "block";
};
// Create Code button style
createCodeBtn.style.cursor = "pointer";
createCodeBtn.onclick = function() {
	diagramSelectionModal.style.display = "block";
};
// Close upload modal
uploadClose.onclick = function() {
	uploadModal.style.display = "none";
};
// Close diagram selection modal
diagramSelectionClose.onclick = function() {
	diagramSelectionModal.style.display = "none";
};
// Close class selection modal
classSelectionClose.onclick = function() {
	classSelectionModal.style.display = "none";
};
// Close file list modal
fileListClose.onclick = function() {
	fileListModal.style.display = "none";
};
// Submit the form to process the uploaded sequence diagram file
uploadSDBtn.onclick = function() {
	document.getElementById("SDFile").click();
};
// Submit the form to process the uploaded class diagram file
uploadCDBtn.onclick = function() {
	document.getElementById("CDFile").click();
};
// Hightlight class list table row onclick
classListTable.onclick = function(e) {
	if (e.target.parentNode.nodeName == "TR") {
		if (e.target.parentNode.className == 'selected') {
			e.target.parentNode.className = '';
		} else {
			e.target.parentNode.className = 'selected';
		}
	}
};
// Back Button
backBtn.onclick = function(){
	classSelectionModal.style.display = "none";
	diagramSelectionModal.style.display = "block";
}