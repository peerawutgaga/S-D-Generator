function openTable(evt, tableName) {
	var i, tabcontent, tablinks;
	currentTable = tableName;
	// Get all elements with class="tabcontent" and hide them
	tabcontent = document.getElementsByClassName("tabcontent");
	for (i = 0; i < tabcontent.length; i++) {
		tabcontent[i].style.display = "none";
	}
	// Get all elements with class="tablinks" and remove the class "active"
	tablinks = document.getElementsByClassName("tablinks");
	for (i = 0; i < tablinks.length; i++) {
		tablinks[i].className = tablinks[i].className.replace(" active", "");
	}
	if (tableName == "CallGraph") {
		getCallGraphList();
	} else {
		getClassDiagramList();
	}
	// Show the current tab, and add an "active" class to the button that opened
	// the tab
	document.getElementById(tableName).style.display = "block";
	evt.currentTarget.className += " active";
}
function getCallGraphList() {
	$
			.post(
					'php/pages/DiagramManagerPage.php',
					{
						'functionName' : "getCallGraphList",
					},
					function(returnedData) {
						callGraphTable.innerHTML = "";
						var itemList = JSON.parse(returnedData);
						for (var i = 0; i < itemList.length; i++) {
							var row = callGraphTable.insertRow(i);
							var cell = row.insertCell(0);
							row.id = itemList[i]["callGraphId"];
							cell.innerHTML = i + 1;
							cell = row.insertCell(1);
							cell.innerHTML = itemList[i]["callGraphName"];
							cell = row.insertCell(2);
							cell.innerHTML = itemList[i]["createTimeStamp"];
						}
						callGraphTable.innerHTML += '<thead><tr id="graphHeader"><th>Item</th><th>File Name</th><th>Create Date</th></tr></thead>';
					});
}
function getClassDiagramList() {
	$
			.post(
					'php/pages/DiagramManagerPage.php',
					{
						'functionName' : "getClassDiagramList",
					},
					function(returnedData) {
						classDiagramTable.innerHTML = "";
						var itemList = JSON.parse(returnedData);
						for (var i = 0; i < itemList.length; i++) {
							var row = classDiagramTable.insertRow(i);
							var cell = row.insertCell(0);
							row.id = itemList[i]["diagramId"];
							cell.innerHTML = i + 1;
							cell = row.insertCell(1);
							cell.innerHTML = itemList[i]["diagramName"];
							cell = row.insertCell(2);
							cell.innerHTML = itemList[i]["createTimeStamp"];
						}
						classDiagramTable.innerHTML += '<thead><tr id="diagramHeader"><th>Item</th><th>File Name</th><th>Create Date</th></tr></thead>';
					});
}

function deleteDiagram() {
	var selectedFile;
	if (currentTable == "CallGraph") {
		selectedFile = callGraphTable.getElementsByClassName('selected');
	} else if (currentTable == "ClassDiagram") {
		selectedFile = classDiagramTable.getElementsByClassName('selected');
	}
	if (selectedFile.length == 0) {
		alert("Please select a graph or a diagram");
		return;
	}
	var confirmMsg = "Delete " + selectedFile[0].cells[1].innerHTML + "?";
	if (!confirm(confirmMsg)) {
		return;
	}
	if (currentTable == "CallGraph") {
		deleteCallGraph(selectedFile[0].id);
	} else if (currentTable == "ClassDiagram") {
		deleteClassDiagram(selectedFile[0].id);
	}

}
function deleteCallGraph(callGraphId) {
	$.post('php/pages/DiagramManagerPage.php', {
		'functionName' : 'deleteCallGraph',
		'callGraphId' : callGraphId,
	}, function(returnedData) {
		if (returnedData == "success") {
			alert("Deleted");
			refreshPage();
		} else {
			alert("Delete failed");
		}
	});
}
function deleteClassDiagram(diagramId) {
	$.post('php/pages/DiagramManagerPage.php', {
		'functionName' : 'deleteClassDiagram',
		'diagramId' : diagramId,
	}, function(returnedData) {
		if (returnedData == "success") {
			alert("Deleted");
			refreshPage();
		} else {
			alert("Delete failed");
		}
	});
}
/*
function showRenameDialog() {
	var selectedValue = $("tr.selected td:eq(1)").html();
	if (selectedValue == null) {
		alert("Please select a file");
		return;
	}
	document.getElementById("filename").value = "";
	renameModal.style.display = "block";
}
*/
function refreshPage() {
	if (currentTable == "ClassDiagramTable") {
		document.getElementById("CDContent").click();
	} else {
		document.getElementById("SDContent").click();
	}
}
/*
 * function rename(){ var selectedValue = $("tr.selected td:eq(1)" ).html(); var
 * newFilename = document.getElementById("filename").value; if(newFilename ==
 * ""){ alert("New filename cannot be blanked."); } var confirmMsg = "Rename
 * from "+selectedValue+" to "+newFilename+".xml"; if(!confirm(confirmMsg)){
 * return; } $.post('php/pages/DiagramManagerPage.php',{ 'rename':
 * selectedValue, 'table':currentTable, 'newName':newFilename + ".xml",
 * },function (returnedData){ if(returnedData == "Exist"){ alert("Filename
 * "+newFilename+" is already existed."); }else if(returnedData == "success"){
 * renameModal.style.display = "none"; alert("Renamed"); refreshPage(); }else{
 * alert("Rename fail"); } }); }
 */