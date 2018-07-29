var fileTable = document.getElementById("fileTable");
var fileSelect = fileTable.getElementsByClassName("selected");
var renameModal = document.getElementById("renameModal");
var modalClose = document.getElementsByClassName("close")[0];
fileTable.onclick = highlight;
window.onload = function(){
    renameModal.style.display = "none";
    $.post('Page/SourceCodeMgrService.php',{
        'method': "getList",
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
    var selectedValue = $("tr.selected td:eq(1)" ).html();
	if(selectedValue == null){
		alert("Please select a file");
		return;
    }
    $.post('Page/SourceCodeMgrService.php',{
        'method': "duplicate",
        'file':selectedValue,
    },function (returnedData){
        if(returnedData == "success"){
            location.reload(true);
        }else{
            alert("Duplicate fail");
        }
    });
}
function deleteFile(){
    var selectedValue = $("tr.selected td:eq(1)" ).html();
	if(selectedValue == null){
		alert("Please select a file");
		return;
    }
    var confirmMsg = "Delete file: "+selectedValue+"?";
    if(!confirm(confirmMsg)){
        return;
    }
    $.post('Page/SourceCodeMgrService.php',{
        'method': "delete",
        'file':selectedValue,
    },function (returnedData){
        if(returnedData == "success"){
            location.reload(true);
            alert("Deleted");
        }else{
            alert("Delete failed");
        }
    });
}
function editFile(){
    var selectedValue = $("tr.selected td:eq(1)" ).html();
	if(selectedValue == null){
		alert("Please select a file");
		return;
    }
    selectedValue = selectedValue.replace(".","-");
	var queryString = "?sourcecode="+selectedValue;
	var win = window.open('../Create Code.php'+queryString);
	if (win) {
		//Browser has allowed it to be opened
		win.focus();
	} else {
		//Browser has blocked it
		alert('Please allow popups for this website');
	}
}
function exportFile(){
    var selectedValue = $("tr.selected td:eq(1)" ).html();
	if(selectedValue == null){
		alert("Please select a file");
		return;
    }
    var confirmMsg = "Export file: "+selectedValue+"?";
    if(!confirm(confirmMsg)){
        return;
    }
    var idx = selectedValue.lastIndexOf(".");
    var filename = selectedValue.substring(0,idx);
    var extension = selectedValue.substring(idx+1);
    var queryString = "?sourcecode="+filename+"-"+extension; 
	window.location.href='../PHP/Download.php'+queryString;
}
function renameFile(){
    var selectedValue = $("tr.selected td:eq(1)" ).html();
	if(selectedValue == null){
		alert("Please select a file");
		return;
    }
    var newFilename = document.getElementById("filename").value;
    if(newFilename == ""){
        alert("New filename cannot be blanked.");
    }
    var idx = selectedValue.lastIndexOf(".");
    var extension = selectedValue.substring(idx+1);
    var confirmMsg = "Rename from "+selectedValue+" to "+newFilename+"."+extension;
    if(!confirm(confirmMsg)){
        return;
    }
    $.post('Page/SourceCodeMgrService.php',{
        'method': "rename",
        'oldname': selectedValue,
        'newname':newFilename+"."+extension,
    },function (returnedData){
        console.log(returnedData);
        if(returnedData == "Exist"){
            alert("Filename "+newFilename+" is already existed.");
        }else if(returnedData == "success"){
            renameModal.style.display = "none";
            alert("Renamed");
            location.reload(true);
        }else{
            alert("Rename fail");
        }
    });
}
function showRenameDialog(){
    var selectedValue = $("tr.selected td:eq(1)" ).html();
	if(selectedValue == null){
		alert("Please select a file");
		return;
    }
    document.getElementById("filename").value = "";
    renameModal.style.display = "block";
}
window.onclick = function (event) {
	if (event.target == renameModal) {
		renameModal.style.display = "none";
    }
};
modalClose.onclick = function(){
    renameModal.style.display = "none";
}