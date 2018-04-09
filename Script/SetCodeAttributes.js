function createCode(){
	fileListModal.style.display = "block";
	createCodeModal.style.display = "none";
	return;
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
	$.post('Page/CreateSourceCode.php', { 
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
			//navigateToCreatCodePage(returnedData);
		 }
	});
}
function navigateToCreatCodePage(sourceCodePath){
	var queryString = "?sourcecode="+sourceCodePath;
	window.location.href='../Create Code.php'+queryString;
}