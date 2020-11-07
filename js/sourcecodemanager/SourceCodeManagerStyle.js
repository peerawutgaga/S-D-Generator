fileTable.onclick = function(e) {
	var selectedFile = fileTable.getElementsByClassName('selected');
	if (e.target.parentNode.nodeName == "TR"
			&& e.target.parentNode.id != "fileListTableHeader") {
		if (selectedFile[0]) {
			selectedFile[0].className = '';
			if (selectedFileId != e.target.parentNode.id) {
				e.target.parentNode.className = 'selected';
				selectedFileId = e.target.parentNode.id;
			}
		} else {
			e.target.parentNode.className = 'selected';
			selectedFileId = e.target.parentNode.id;
		}
	}
}
window.onload = function() {
	renameModal.style.display = "none";
	$
			.post(
					'php/pages/SourceCodeManagerPage.php',
					{
						'functionName' : "getFileList"
					},
					function(returnedData) {
						var table = document.getElementById("fileTable");
						table.innerHTML = "";
						var fileList = JSON.parse(returnedData);
						for (var i = 0; i < fileList.length; i++) {
							var row = table.insertRow(i);
							row.id = fileList[i]["fileId"];
							var cell = row.insertCell(0);
							cell.innerHTML = i + 1;
							cell = row.insertCell(1);
							cell.innerHTML = fileList[i]["filename"];
							cell = row.insertCell(2);
							cell.innerHTML = fileList[i]["language"];
							cell = row.insertCell(3);
							cell.innerHTML = fileList[i]["sourceType"];
							cell = row.insertCell(4);
							cell.innerHTML = fileList[i]["createTimeStamp"];
							cell = row.insertCell(5);
							cell.innerHTML = fileList[i]["lastUpdateTimeStamp"];
						}
						table.innerHTML += '<thead><tr id="fileListTableHeader"><th>Item</th><th>File Name</th><th>Language</th><th>File Type</th><th>Create Date</th><th>Last Update Timestamp</th></tr></thead>';
					});
}
window.onclick = function(event) {
	if (event.target == renameModal) {
		renameModal.style.display = "none";
	}
};
renameModalClostBtn.onclick = function() {
	renameModal.style.display = "none";
}