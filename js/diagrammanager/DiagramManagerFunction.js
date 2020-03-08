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
		linkBtn.disabled = false;
		getCallGraphList();
	} else if (tableName == "ClassDiagram") {
		linkBtn.disabled = true;
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
function getSelectedFile() {
	var selectedFile;
	if (currentTable == "CallGraph") {
		selectedFile = callGraphTable.getElementsByClassName('selected');
	} else if (currentTable == "ClassDiagram") {
		selectedFile = classDiagramTable.getElementsByClassName('selected');
	}
	if (selectedFile.length == 0) {
		alert("Please select a graph or a diagram");
		return null;
	}
	return selectedFile[0];
}
function deleteDiagram() {
	var selectedFile = getSelectedFile();
	if (selectedFile == null) {
		return;
	}
	var confirmMsg = "Delete " + selectedFile.cells[1].innerHTML + "?";
	if (!confirm(confirmMsg)) {
		return;
	}
	if (currentTable == "CallGraph") {
		deleteCallGraph(selectedFile.id);
	} else if (currentTable == "ClassDiagram") {
		deleteClassDiagram(selectedFile.id);
	}

}
function deleteCallGraph(callGraphId) {
	$.post('php/pages/DiagramManagerPage.php', {
		'functionName' : 'deleteCallGraph',
		'callGraphId' : callGraphId,
	}, function(returnedData) {
		if (returnedData.trim() == "success") {
			alert("Deleted");
		} else {
			alert("Delete failed");
		}
		refreshPage();
	});
}
function deleteClassDiagram(diagramId) {
	$.post('php/pages/DiagramManagerPage.php', {
		'functionName' : 'deleteClassDiagram',
		'diagramId' : diagramId,
	}, function(returnedData) {
		if (returnedData.trim() == "success") {
			alert("Deleted");
		} else {
			alert("Delete failed");
		}
		refreshPage();
	});
}
function refreshPage() {
	if (currentTable == "CallGraph") {
		document.getElementById("SDContent").click();
	} else {
		document.getElementById("CDContent").click();
	}
}
function showRenameDialog() {
	var selectedFile = getSelectedFile();
	document.getElementById("newFilenameTextArea").value = selectedFile.cells[1].innerHTML;
	if (selectedFile == null) {
		return;
	}
	renameModal.style.display = "block";
}
function rename() {
	var selectedFile = getSelectedFile();
	var newFilename = document.getElementById("newFilenameTextArea").value;
	if (currentTable == "CallGraph") {
		renameCallGraph(selectedFile.id,newFilename);
	} else if (currentTable == "ClassDiagram") {
		renameClassDiagram(selectedFile.id,newFilename);
	}
}
function renameCallGraph(callGraphId,newFilename){
	$.post('php/pages/DiagramManagerPage.php', {
		'functionName' : 'renameCallGraph',
		'callGraphId' : callGraphId,
		'newFilename': newFilename
	}, function(returnedData) {
		if (returnedData.trim() == "success") {
			alert("Renamed to "+newFilename);
		} else {
			alert("Rename failed");
		}
		refreshPage();
	});
}
function renameClassDiagram(diagramId,newFilename){
	$.post('php/pages/DiagramManagerPage.php', {
		'functionName' : 'renameClassDiagram',
		'diagramId' : diagramId,
		'newFilename': newFilename
	}, function(returnedData) {
		if (returnedData.trim() == "success") {
			alert("Renamed to "+newFilename);
		} else {
			alert("Rename failed");
		}
		refreshPage();
	});
}
function showLinkingDialog() {
	var selectedFile = callGraphTable.getElementsByClassName('selected')[0];
	if (selectedFile.length == 0) {
		alert("Please select a graph or a diagram");
		return;
	}
	resetCallGraphSelector();
	$.post('php/pages/DiagramManagerPage.php', {
		'functionName' : 'getCallGraphList'
	}, function(returnedData) {
		var sdList = JSON.parse(returnedData);

		sdList.forEach(function(sd, index) {
			var option = document.createElement("option");
			option.id = sd["callGraphId"];
			option.text = sd["callGraphName"];
			callGraphSelector.add(option);
		});
	});
	$.post('php/pages/DiagramManagerPage.php', {
		'functionName' : 'getReferenceObjectList',
		'callGraphId' : selectedFile.id
	}, function(returnedData) {
		if (returnedData.trim() == "NONE") {
			alert("The selected call graph does not refer to other diagrams");
		} else {
			var refObjList = JSON.parse(returnedData);
			refObjList.forEach(function(refObj, index) {
				var option = document.createElement("option");
				option.id = refObj["objectId"];
				option.text = refObj["objectName"];
				refObjectSelector.add(option);
			});
			linkingModal.style.display = "block";
		}
	});
}
function resetCallGraphSelector() {
	var defaultOption = '<option value="0" selected disabled hidden>Please Select Destination Call Graph</option>';
	document.getElementById('callGraphSelector').innerHTML = defaultOption;
	document.getElementById('referenceSelector').innerHTML = defaultOption;
}
function linkDiagram() {
	// Get current selector stage
	callGraphSelector = document.getElementById('callGraphSelector');
	refObjectSelector = document.getElementById('referenceSelector');
	var sourceCallGraphId = callGraphTable.getElementsByClassName('selected')[0].id;
	var destinationCallGraphId = callGraphSelector.options[callGraphSelector.selectedIndex].id;
	var refObjectId = refObjectSelector.options[refObjectSelector.selectedIndex].id;
	$
			.post(
					'php/pages/DiagramManagerPage.php',
					{
						'functionName' : 'connectReferenceDiagram',
						'sourceCallGraphId' : sourceCallGraphId,
						'destinationCallGraphId' : destinationCallGraphId,
						'referenceObjectId' : refObjectId
					},
					function(returnedData) {
						if (returnedData.trim() == "INSERT") {
							var message = "Linked with "
									+ callGraphSelector.options[callGraphSelector.selectedIndex].value;
							alert(message);
						} else if (returnedData.trim() == "UPDATE") {
							var message = "The linked destination graph with "
									+ refObjectSelector.options[refObjectSelector.selectedIndex].value
									+ " object has been changed to "
									+ callGraphSelector.options[callGraphSelector.selectedIndex].value;
							alert(message);
						}
						linkingModal.style.display = "none";
					});
}
