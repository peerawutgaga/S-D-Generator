var filenameArea = document.getElementById("filename");
var fileExtension;
var oldFilename;
window.onload = function(){
    var filename = decodeURIComponent(window.location.search);
    filename = filename.substring(12);
    recordFileInfo(filename);
    openFile();
};
function recordFileInfo(filename){
    var idx = filename.lastIndexOf("-");
    fileExtension = filename.substring(idx+1);
    oldFilename = filename.substring(0,idx);
    console.log(oldFilename);
}
function openFile(){
    var filename = oldFilename+"."+fileExtension;
    filepath = "../Source Code Files/"+filename+".txt";
    var client = new XMLHttpRequest();
    client.open('GET', filepath);
    client.onreadystatechange = function() {
        if(client.readyState === 4)
        {
            if(client.status === 200 || client.status == 0)
            {
                var allText = client.responseText;
                filenameArea.value = filename;
                document.getElementById("codeEditor").value = allText;
            }
        }
    }
    client.send();
}
function rename(){
    var currentFilename = filenameArea.value;
    var idx = currentFilename.lastIndexOf(".");
    var newFilename = currentFilename.substring(0,idx);
    var currentExtension = currentFilename.substring(idx+1);
    if(currentExtension != fileExtension){
        alert("Cannot change file extension");
        return;
    }
    if(newFilename == oldFilename){
        alert("Nothing changed");
        return;
    }
    if(newFilename == ""){
        alert("Filename cannot be empty");
        return;
    }
    $.post('Page/CodeEditorService.php', { 
		'method': "rename",
        'oldFilename' : oldFilename+"."+fileExtension, 
        'newFilename' : currentFilename,
	}, function(returnedData){
         if(returnedData == "failed"){
             alert("Rename failed");
        }else{
            alert("Rename Succeeded");
            refreshCreatCodePage(returnedData);
         }
	});
}
function refreshCreatCodePage(sourceCodePath){
	var queryString = "?sourcecode="+sourceCodePath;
	window.location.href='../Create Code.php'+queryString;
}
