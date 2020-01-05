function createSourceCode(){
	var graphId = SDSelect.options[SDSelect.selectedIndex].id;
	diagramId = CDSelect.options[CDSelect.selectedIndex].id;
	classList = getClassUnderTestList();
	sourceLang = "JAVA";
	if(classList.length == 0){
		alert("Please select at least one class");
		return;
	}
	if(stubCheckBox.checked){
		sourceType = "STUB";
	}else if(driverCheckBox.checked){
		sourceType = "DRIVER";
	}else{
		alert("Please select source code type either Stub or Driver.");
	}
	checkClassesRelation(graphId,classList);
}
function getClassUnderTestList(){
	
	var rows =  $(".selected").map(function() {
	    return this.outerHTML;
	}).get();
	var classList = "";
	for(var i=0;i<rows.length;i++){
		// Split <tr id="xx"><td>...</td></tr> by \" and get the second value
		// which is id.
		var classId = rows[i].split('"')[1];
		classList += classId + ",";
	}
	classList = classList.substring(0, classList.length - 1);// Remove last
																// comma
	return classList;
}
function getFileList(returnedData){
	var fileList = [];
	if(returnedData == null || returnedData.length ==0){
		alert("No data returned from source code generator. Error might be occured.");
		return;
	}
	var returnedObject = JSON.parse(returnedData);
	if(returnedObject["isSuccess"]=="false"){
		alert("Error occured in source code generator: "+returnedObject["errorMessage"]);
		return;
	}
	for (var [key, value] of Object.entries(returnedObject)) {
		 if(key != "isSuccess"){
			 fileList[key] = value;
		 }
	}
	return fileList;
}
function addFileListToTable(fileList){
	fileListTable.innerHTML = "";
	var i=0;
	fileList.forEach(function(item,index){
		var row = fileListTable.insertRow(i);
		row.id = index;
		var cell = row.insertCell(0);
		cell.innerHTML = item;
		i++;
	});
	classSelectionModal.style.display = "none";
	fileListModal.style.display = "block";
}
function checkClassesRelation(graphId,classList){
	$result = $.post('php/pages/DiagramSelection.php', { 
		'functionName' : 'checkClassesRelation',
		'callGraphId' : graphId,
		'objectList':classList
	}, function(returnedData){
		var result = JSON.parse(returnedData);
		console.log(result[0]["isSuccess"]);
		if(result[0]["isSuccess"] == "success"){
			generateSourceCode(diagramId,classList,sourceType,"JAVA");
		}else if(result[0]["isSuccess"] == "warning"){
			if(!confirm(result[0]["errorMessage"])){
		        return;
		    }
			generateSourceCode(diagramId,classList,sourceType,"JAVA");
		}else if(result[0]["isSuccess"] == "error"){
			alert(result[0]["errorMessage"]);
		}
	});	
}
function generateSourceCode(diagramId,classList,sourceType,sourceLang){
	
	$.post('php/sourcecode/SourceCodeGenerator.php', { 
		'diagramId' : diagramId,
		'objectList':classList,
		'sourceType':sourceType,
		'sourceLang':sourceLang
	}, function(returnedData){
		var fileList = getFileList(returnedData);
		addFileListToTable(fileList);
	});
}
function editCode(){
	/*
	 * var selectedValue = $("tr.selected td:first" ).html(); if(selectedValue ==
	 * null){ alert("Please select a file"); return; } selectedValue =
	 * selectedValue.replace(".","-"); var queryString =
	 * "?sourcecode="+selectedValue; var win =
	 * window.open('../CreateCode.php'+queryString); if (win) { //Browser has
	 * allowed it to be opened win.focus(); } else { //Browser has blocked it
	 * alert('Please allow popups for this website'); }
	 */
}
function exportSelected(){
	/*
	 * var selectedValue = $("tr.selected td:first" ).html(); if(selectedValue ==
	 * null){ alert("Please select a file"); return; } var confirmMsg = "Export
	 * file: "+selectedValue+"?"; if(!confirm(confirmMsg)){ return; }
	 * selectedValue = selectedValue.replace(".","-"); var queryString =
	 * "?sourcecode="+selectedValue;
	 * window.location.href='../PHP/Download.php'+queryString;
	 */
}
function exportAll(){
	/*
	 * var confirmMsg = "Export all generated files?"; if(!confirm(confirmMsg)){
	 * return; } var files = fileList.split(","); if(files.length == 1){ var
	 * file = files[0].replace(".","-"); var queryString = "?sourcecode="+file;
	 * window.location.href='../php/Download.php'+queryString; return; }
	 * $.post('php/pages/CodeEditorService.php', { 'method': "exportAll",
	 * 'filepath' : fileList, }, function(returnedData){ var queryString =
	 * "?sourcecode=Source_Code_Files-zip";
	 * window.location.href='..php/Download.php'+queryString; });
	 */
}
