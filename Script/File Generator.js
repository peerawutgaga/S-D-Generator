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
	for(var i =0;i<fileList.length;i++){

	}
	fileListModal.style.display = "block";
}
function navigateToCreatCodePage(sourceCodePath){
	var queryString = "?sourcecode="+sourceCodePath;
	window.location.href='../Create Code.php'+queryString;
}