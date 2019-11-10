var callGraphTable = document.getElementById("CallGraphTable");
var classDiagramTable = document.getElementById("ClassDiagramTable");
var callGraphSelected = callGraphTable.getElementsByClassName('selected');
var classDiagramSelected = classDiagramTable.getElementsByClassName('selected');
var renameModal = document.getElementById("renameModal");
var modalClose = document.getElementsByClassName("close")[0];
var currentTable;
//TODO Refactor
callGraphTable.onclick = highlightCallGraph;
classDiagramTable.onclick = highlightClassDiagram;
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
    if(tableName=="CallGraph"){
        getCallGraphList();
    }else{
        getClassDiagramList();
    }
    // Show the current tab, and add an "active" class to the button that opened the tab
    document.getElementById(tableName).style.display = "block";
    evt.currentTarget.className += " active";
}

// Get the element with id="defaultOpen" and click on it
document.getElementById("SDContent").click();
function getCallGraphList(){
    $.post('php/pages/DiagramMgrService.php',{
        'getList': "Sequence",
    },function (returnedData){
        addItemToTable("CallGraphTable",returnedData);
    }),"json";
}
function getClassDiagramList(){
    $.post('php/pages/DiagramMgrService.php',{
        'getList': "ClassDiagram",
    },function (returnedData){
        addItemToTable("ClassDiagramTable",returnedData);
    }),"json";
}
function addItemToTable(tableID, itemList){
    var table = document.getElementById(tableID);
    table.innerHTML = "";
    itemList = JSON.parse(itemList);
    for(var i =0;i<itemList.length;i++){
        var row = table.insertRow(i);
        var cell = row.insertCell(0);
        cell.innerHTML = i+1;
        cell = row.insertCell(1);
        cell.innerHTML = itemList[i][1];
        cell = row.insertCell(2);
        cell.innerHTML = itemList[i][3];
    }
    table.innerHTML += "<thead><tr><th>Item</th><th>File Name</th><th>Create Date</th></tr></thead>";
}

function highlightCallGraph(e) {
    if(e.target.parentNode.parentNode.tagName == "THEAD"){
        return;
    }
    if (callGraphSelected[0]) callGraphSelected[0].className = '';
	e.target.parentNode.className = 'selected';  
}
function highlightClassDiagram(e) {
    if(e.target.parentNode.parentNode.tagName == "THEAD"){
        return;
    }
	if (classDiagramSelected[0]) classDiagramSelected[0].className = '';
	e.target.parentNode.className = 'selected';  
}
function deleteDiagram(){
    var selectedValue = $("tr.selected td:eq(1)" ).html();
	if(selectedValue == null){
        alert("Please select a file");
		return;
    }
    var confirmMsg = "Delete this diagram, "+selectedValue+"?";
    if(!confirm(confirmMsg)){
        return;
    }
    $.post('php/pages/DiagramMgrService.php',{
        'delete': selectedValue,
        'table':currentTable,
    },function (returnedData){
        if(returnedData == "success"){
            alert("Deleted");
            refreshPage();
        }else{
            alert("Delete failed");
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
function refreshPage(){
    if(currentTable == "ClassDiagramTable"){
        document.getElementById("CDContent").click();
    }else{
        document.getElementById("SDContent").click();
    }
}
window.onclick = function (event) {
	if (event.target == renameModal) {
		renameModal.style.display = "none";
    }
};
modalClose.onclick = function(){
    renameModal.style.display = "none";
}
function rename(){
    var selectedValue = $("tr.selected td:eq(1)" ).html();
    var newFilename = document.getElementById("filename").value;
    if(newFilename == ""){
        alert("New filename cannot be blanked.");
    }
    var confirmMsg = "Rename from "+selectedValue+" to "+newFilename+".xml";
    if(!confirm(confirmMsg)){
        return;
    }
    $.post('php/pages/DiagramMgrService.php',{
        'rename': selectedValue,
        'table':currentTable,
        'newName':newFilename + ".xml",
    },function (returnedData){
        if(returnedData == "Exist"){
            alert("Filename "+newFilename+" is already existed.");
        }else if(returnedData == "success"){
            renameModal.style.display = "none";
            alert("Renamed");
            refreshPage();
        }else{
            alert("Rename fail");
        }
    });

}