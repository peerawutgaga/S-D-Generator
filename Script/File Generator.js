var table = document.getElementById("fileListTable");
var selected = table.getElementsByClassName('selected');
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
			addFileList(returnedData);
		 }
	});
}
function addFileList(returnedData){
	var fileList = returnedData.split(",");
	table.innerHTML = "";
	for(var i =0;i<fileList.length;i++){
		var row = table.insertRow(i);
		var cell = row.insertCell(0);
		cell.innerHTML = fileList[i];
	}
	fileListModal.style.display = "block";
}
function navigateToCreatCodePage(sourceCodePath){
	var queryString = "?sourcecode="+sourceCodePath;
	window.location.href='../Create Code.php'+queryString;
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
	navigateToCreatCodePage(selectedValue);
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
	
}
