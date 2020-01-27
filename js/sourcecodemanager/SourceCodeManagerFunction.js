function duplicateFile() {
	// TODO Implement duplicate file function
}
function deleteFile() {
	// TODO Delete file function
}
function editFile() {
	var selectedFile = fileTable.getElementsByClassName('selected');
	var queryString = "?sourcecode=" + selectedFile[0].id;
	var win = window.open('../CreateCode.php' + queryString);
	if (win) { // Browser has allowed it to be opened
		win.focus();
	} else { // Browser has blocked it
		alert('Please allow popups for this website');
	}
}
function exportFile() {
	// TODO Export file
}
function renameFile() {
	// TODO Rename file
}
function showRenameDialog() {
	var selectedValue = $("tr.selected td:eq(1)").html();
	if (selectedValue == null) {
		alert("Please select a file");
		return;
	}
	document.getElementById("filename").value = "";
	renameModal.style.display = "block";
}