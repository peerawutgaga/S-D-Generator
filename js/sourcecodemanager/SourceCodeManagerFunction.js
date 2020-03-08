function getSelectedFile() {
	var selectedFile;
	selectedFile = fileTable.getElementsByClassName('selected');
	if (selectedFile.length == 0) {
		alert("Please select a file");
		return null;
	}
	return selectedFile[0];
}
function deleteFile() {
	var selectedFile = getSelectedFile();
	if (selectedFile == null) {
		return;
	}
	var confirmMsg = "Delete " + selectedFile.cells[1].innerHTML + "?";
	if (!confirm(confirmMsg)) {
		return;
	}
	var fileId = selectedFile.id;
	$.post('php/pages/SourceCodeManagerPage.php', {
		'functionName' : 'deleteFile',
		'fileId' : fileId,
	}, function(returnedData) {
		if (returnedData.trim() == "success") {
			alert("Deleted");
		} else {
			alert("Delete failed");
		}
		location.reload(true);
	});
}
function editFile() {
	var selectedFile = getSelectedFile();
	var queryString = "?sourcecode=" + selectedFile.id;
	var win = window.open('../CreateCode.php' + queryString);
	if (win) { // Browser has allowed it to be opened
		win.focus();
	} else { // Browser has blocked it
		alert('Please allow popups for this website');
	}
}
function exportFile() {
	var selectedFile = getSelectedFile();
	var currentFilename = selectedFile.cells[1].innerHTML;
	var confirmMsg = "Export file: "+currentFilename+"?";
    if(!confirm(confirmMsg)){
        return;
    }
    var queryString = "?sourcecode="+selectedFile.id;; 
	window.location.href='../php/utilities/Download.php'+queryString;
}
function renameFile() {
	var selectedFile = getSelectedFile();
	var currentFilename = selectedFile.cells[1].innerHTML;
	currentExtension = currentFilename.substring(currentFilename.lastIndexOf(".")+1);//Get Extension
	var newFilename = filenameTextArea.value;
    var newExtension = newFilename.substring(newFilename.lastIndexOf(".")+1);//Get Extension
    if(newExtension != currentExtension){
        alert("Cannot change file extension");
        return;
    }
    if(newFilename == ""){
        alert("Filename cannot be empty");
        return;
    }
    var fileId = selectedFile.id;
    $.post('php/pages/SourceCodeManagerPage.php', { 
		'functionName': "renameFile",
        'fileId' : fileId, 
        'newFilename' : newFilename,
	}, function(returnedData){
        if(returnedData.trim() == "success"){
        	alert("Renamed");           
        }else{
        	alert("Rename failed");
        }
        location.reload(true);
	});
}
function showRenameDialog() {
	var selectedFile = getSelectedFile();
	document.getElementById("filename").value = selectedFile.cells[1].innerHTML;
	renameModal.style.display = "block";
}