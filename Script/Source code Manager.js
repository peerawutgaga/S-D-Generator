var fileTable = document.getElementById("fileTable");
var fileSelect = fileTable.getElementsByClassName("selected");
fileTable.onclick = highlight;
window.onload = function(){
    $.post('Page/SourceCodeMgrService.php',{
        'getList': "Sequence",
    },function (returnedData){
        addListToTable(returnedData);
    }),"json";
}
function addListToTable(fileList){
    var table = document.getElementById("fileTable");
    table.innerHTML = "";
    fileList = JSON.parse(fileList);
    for(var i = 0;i<fileList.length;i++){
        var row = table.insertRow(i);
        var cell = row.insertCell(0);
        cell.innerHTML = i+1;
        cell = row.insertCell(1);
        cell.innerHTML = fileList[i][0];
        cell = row.insertCell(2);
        cell.innerHTML = fileList[i][1];
        cell = row.insertCell(3);
        cell.innerHTML = fileList[i][2];
        cell = row.insertCell(4);
        cell.innerHTML = fileList[i][4];
    }
    table.innerHTML += "<thead><tr><th>Item</th><th>File Name</th><th>File Type</th><th>Language</th><th>Create Date</th></tr></thead>";
}
function highlight(e){
    if(e.target.parentNode.parentNode.tagName == "THEAD"){
        return;
    }
    if (fileSelect[0]) fileSelect[0].className = '';
	e.target.parentNode.className = 'selected';  
}
function duplicateFile(){
    var selectedValue = $("tr.selected td:first" ).html();
	if(selectedValue == null){
		alert("Please select a file");
		return;
	}
}
function deleteFile(){
    var selectedValue = $("tr.selected td:first" ).html();
	if(selectedValue == null){
		alert("Please select a file");
		return;
	}
}
function editFile(){
    var selectedValue = $("tr.selected td:first" ).html();
	if(selectedValue == null){
		alert("Please select a file");
		return;
	}
}
function exportFile(){
    var selectedValue = $("tr.selected td:first" ).html();
	if(selectedValue == null){
		alert("Please select a file");
		return;
	}
}
function renameFile(){
    var selectedValue = $("tr.selected td:first" ).html();
	if(selectedValue == null){
		alert("Please select a file");
		return;
	}
}