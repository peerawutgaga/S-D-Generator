function createSourceCode(){
	
}
function addFileList(fileList){
	/*var files = fileList.split(",");
	table.innerHTML = "";
	for(var i =0;i<files.length;i++){
		var row = table.insertRow(i);
		var cell = row.insertCell(0);
		cell.innerHTML = files[i];
	}
	fileListModal.style.display = "block";*/
}

function editCode(){
	/*var selectedValue = $("tr.selected td:first" ).html();
	if(selectedValue == null){
		alert("Please select a file");
		return;
	}
	selectedValue = selectedValue.replace(".","-");
	var queryString = "?sourcecode="+selectedValue;
	var win = window.open('../CreateCode.php'+queryString);
	if (win) {
		//Browser has allowed it to be opened
		win.focus();
	} else {
		//Browser has blocked it
		alert('Please allow popups for this website');
	}*/
}
function exportSelected(){
	/*var selectedValue = $("tr.selected td:first" ).html();
	if(selectedValue == null){
		alert("Please select a file");
		return;
	}
	var confirmMsg = "Export file: "+selectedValue+"?";
    if(!confirm(confirmMsg)){
        return;
    }
	selectedValue = selectedValue.replace(".","-");
	var queryString = "?sourcecode="+selectedValue; 
	window.location.href='../PHP/Download.php'+queryString;*/
}
function exportAll(){
	/*var confirmMsg = "Export all generated files?";
    if(!confirm(confirmMsg)){
        return;
    }
	var files = fileList.split(",");
	if(files.length == 1){
		var file = files[0].replace(".","-");
		var queryString = "?sourcecode="+file; 
		window.location.href='../php/Download.php'+queryString;
		return;
	}
	$.post('php/pages/CodeEditorService.php', { 
		'method': "exportAll",
        'filepath' : fileList, 
	}, function(returnedData){
        var queryString = "?sourcecode=Source_Code_Files-zip"; 
		window.location.href='..php/Download.php'+queryString;
	});*/
}
