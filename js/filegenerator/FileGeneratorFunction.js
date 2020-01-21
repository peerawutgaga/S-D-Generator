function createSourceCode(){
	graphId = SDSelect.options[SDSelect.selectedIndex].id;
	diagramId = CDSelect.options[CDSelect.selectedIndex].id;
	classList = getClassUnderTestList();
	sourceLang = "JAVA";
	if(classList.length == 0){
		alert("Please select at least one class");
		return;
	}
	checkClassesRelation(graphId,classList);
}
function getClassUnderTestList(){
	var classListTable = document.getElementById("classListTable");
	var rows = classListTable.rows;
	var classList = "";
	for(var i=0;i<rows.length;i++){
		if(rows[i].className == "selected"){
			var classId = rows[i].id;
			classList += classId + ",";
		}	
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
	if(returnedObject["isSuccess"] =="false"){
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
	$result = $.post('php/pages/DiagramSelectionPage.php', { 
		'functionName' : 'checkClassesRelation',
		'callGraphId' : graphId,
		'objectList':classList
	}, function(returnedData){
		var result = JSON.parse(returnedData);
		if(result[0]["isSuccess"] == "success"){
			generateSourceCode(diagramId,classList,"JAVA");
		}else if(result[0]["isSuccess"] == "warning"){
			if(!confirm(result[0]["errorMessage"])){
		        return;
		    }
			generateSourceCode(diagramId,classList,"JAVA");
		}else if(result[0]["isSuccess"] == "error"){
			alert(result[0]["errorMessage"]);
		}
	});	
}
function generateSourceCode(diagramId,classList,sourceLang){
	
	$.post('php/sourcecode/SourceCodeGenerator.php', { 
		'diagramId' : diagramId,
		'objectList':classList,
		'sourceLang':sourceLang
	}, function(returnedData){
		var fileList = getFileList(returnedData);
		addFileListToTable(fileList);	
	});
}
function editCode(){
	var selectedFile = fileListTable.getElementsByClassName('selected');
	  var queryString = "?sourcecode="+selectedFile[0].id; 
	  var win = window.open('../CreateCode.php'+queryString); 
	  if (win) { // Browser has allowed it to be opened
		  win.focus(); 
	  } else { // Browser has blocked it
	  alert('Please allow popups for this website'); 
  }
	 
}
function exportSelected(){
	var selectedFile = fileListTable.getElementsByClassName('selected'); 
	if(selectedFile.length ==0){
		alert("Please select a file");
		return; 
	} 
	var queryString = "?sourcecode="+selectedFile[0].id;
	window.location.href='../../php/utilities/Download.php'+queryString; 
}
function exportAll(){
	var fileList = fileListTable.getElementsByTagName("tr");
	var fileListStr = "";
	for(var i=0;i<fileList.length;i++){
		fileListStr += fileList[i].id+",";
	}
	fileListStr = fileListStr.substring(0, fileListStr.length - 1);
	$.post('php/utilities/LocalFileManager.php', { 
		'function' : 'zip',
		'fileList':fileListStr
	}, function(returnedData){
		console.log(returnedData);
		if(returnedData == "success"){
			window.location.href='../../php/utilities/Download.php?sourcecode=zip'; 
		}else{
			alert("Error while exporting file");
		}
	});
}
