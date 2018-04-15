var table = document.getElementById("fileListTable");
var selected = table.getElementsByClassName('selected');
var fileList;
table.onclick = highlight;
function createCode(){
	var form = document.getElementById('codeProperties');
	var sourceType;
	var sourceLang;
	if(form.elements.namedItem('sourceCodeType')[0].checked){
		sourceType = 'stub';
	}else{
		sourceType = 'driver';
	}
	if(form.elements.namedItem('sourceCodeLang')[0].checked){
		sourceLang = 'Java';
	}else{
		sourceLang = 'PHP';
	}
	$.post('Page/SourceCodeGenerator.php', { 
		'graphID': SDSelect.options[SDSelect.selectedIndex].value,
		'diagramID' : CDSelect.options[CDSelect.selectedIndex].value, 
		'CUT' : ClassSelect.options[ClassSelect.selectedIndex].value,
		'sourceType' : sourceType,
		'sourceLang' : sourceLang
	}, function(returnedData){
         if(returnedData == "stub error"){
			alert("Cannot create stub for this class because this class does not call any methods in other classes");
		 }else if(returnedData == "driver error"){
			alert("Cannot create driver for this class because this class does not be called by other classes");
		 }else{
			createCodeModal.style.display = "none";
			fileList = returnedData
			addFileList(fileList);
		 }
	});
}
function addFileList(fileList){
	var files = fileList.split(",");
	table.innerHTML = "";
	for(var i =0;i<files.length;i++){
		var row = table.insertRow(i);
		var cell = row.insertCell(0);
		cell.innerHTML = files[i];
	}
	fileListModal.style.display = "block";
}
function highlight(e) {
	if (selected[0]) selected[0].className = '';
	e.target.parentNode.className = 'selected';  
}
function editCode(){
	var selectedValue = $("tr.selected td:first" ).html();
	if(selectedValue == null){
		alert("Please select a file");
		return;
	}
	selectedValue = selectedValue.replace(".","-");
	var queryString = "?sourcecode="+selectedValue;
	var win = window.open('../Create Code.php'+queryString);
	if (win) {
		//Browser has allowed it to be opened
		win.focus();
	} else {
		//Browser has blocked it
		alert('Please allow popups for this website');
	}
}
function exportSelected(){
	var selectedValue = $("tr.selected td:first" ).html();
	if(selectedValue == null){
		alert("Please select a file");
		return;
	}
	selectedValue = selectedValue.replace(".","-");
	var queryString = "?sourcecode="+selectedValue; 
	window.location.href='../PHP/Download.php'+queryString;
}
function exportAll(){
	var files = fileList.split(",");
	if(files.length == 1){
		var file = files[0].replace(".","-");
		var queryString = "?sourcecode="+file; 
		window.location.href='../PHP/Download.php'+queryString;
		return;
	}
	$.post('Page/CodeEditorService.php', { 
		'method': "exportAll",
        'filepath' : fileList, 
	}, function(returnedData){
        var queryString = "?sourcecode=Source_Code_Files-zip"; 
		window.location.href='../PHP/Download.php'+queryString;
	});
}
