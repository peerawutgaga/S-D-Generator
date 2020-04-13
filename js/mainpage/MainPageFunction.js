//Trigger submit button when sequence diagram file is uploaded
function uploadSDFile() {
	document.getElementById("SDSubmit").click();
}
// Trigger submit button when class diagram file is uploaded
function uploadCDFile() {
	document.getElementById("CDSubmit").click();
}
function refreshSDList() {
	$.post('php/pages/DiagramSelectionPage.php', { 
		'functionName' : 'getCallGraphList'
	}, function(returnedData){
		var sdList = JSON.parse(returnedData);
		sdList.forEach(function(sd, index) {
			var option = document.createElement("option");
			option.id = sd["callGraphId"];
			option.text = sd["callGraphName"];
			SDSelect.add(option);
		});
	});
}
function refreshCDList() {
	$.post('php/pages/DiagramSelectionPage.php', { 
		'functionName' : 'getClassDiagramList'
	}, function(returnedData){
		var cdList = JSON.parse(returnedData);
		cdList.forEach(function(cd, index) {
			var option = document.createElement("option");
			option.id = cd["diagramId"];
			option.text = cd["diagramName"];
			CDSelect.add(option);
		});
	});
}
function transitToClassSelection() {
	if(SDSelect.selectedIndex==0){
		alert("Please select call graph");
		return;
	}
	if(CDSelect.selectedIndex==0){
		alert("Please select class diagram");
		return;
	}
	selectedSD = SDSelect.options[SDSelect.selectedIndex].id;
	selectedCD = CDSelect.options[CDSelect.selectedIndex].id;
	diagramSelectionModal.style.display = "none";
	getObjectList(selectedSD);
	classSelectionModal.style.display = "block";
}
function getObjectList(callGraphId) {
	$.post('php/pages/DiagramSelectionPage.php', { 
		'functionName' : 'getObjectListByCallGraphId',
		'callGraphId' : callGraphId
	}, function(returnedData){
		var objectList = JSON.parse(returnedData);
		classListTable.innerHTML = "";
		objectList.forEach(function(objectNode, index) {
			var row = classListTable.insertRow(index);
			row.id = objectNode["objectId"];
			var cell = row.insertCell(0);
			cell.innerHTML = objectNode["baseIdentifier"];
		});
	});
}
function backToClassSelection(){
	classSelectionModal.style.display = "block";
	fileListModal.style.display = "none";
}
