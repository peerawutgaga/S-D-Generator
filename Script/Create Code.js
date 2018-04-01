var filenameArea = document.getElementById("filename");
var downloadDiv = document.getElementById("downloadDiv");
var defaultModal = document.getElementById("defaultValueModal");
var maxModal = document.getElementById("maxValueModal");
var minModal = document.getElementById("minValueModal");
var randomModal = document.getElementById("randomValueModal");
var closeDefaultBtn = document.getElementsByClassName("close")[0];
var closeMaxBtn = document.getElementsByClassName("close")[1];
var closeMinBtn = document.getElementsByClassName("close")[2];
var closeRandomBtn = document.getElementsByClassName("close")[3];
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
    var confirmMsg = "Rename from "+oldFilename+"."+fileExtension+" to "+currentFilename;
    if(!confirm(confirmMsg)){
        return;
    }
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
            console.log(returnedData);
            refreshCreatCodePage(returnedData);
         }
	});
}
function refreshCreatCodePage(sourceCodePath){
	var queryString = "?sourcecode="+sourceCodePath; 
	window.location.href='../Create Code.php'+queryString;
}
function saveChange(){
    if(!confirm("Save Change ?")){
        return;
    }
    var filepath = "/Source Code Files/"+oldFilename+"."+fileExtension+".txt";
    var content = document.getElementById("codeEditor").value;
    $.post('Page/CodeEditorService.php', { 
		'method': "saveFile",
        'filepath' : filepath, 
        'content' : content,
	}, function(returnedData){
        alert("Saved");
	});
}
function exportFile(){
    var filepath = oldFilename+"-"+fileExtension;
    var queryString = "?sourcecode="+filepath; 
	window.location.href='../PHP/Download.php'+queryString;
}
function showDefaultModal(){
    defaultModal.style.display = "block";
}
function showMaxModal(){
    maxModal.style.display = "block";
}
function showMinModal(){
    minModal.style.display = "block";
}
function showRandomModal(){
    randomModal.style.display = "block";
}
closeDefaultBtn.onclick = function(){
    defaultModal.style.display = "none";
}
closeMaxBtn.onclick = function(){
    maxModal.style.display = "none";
}
closeMinBtn.onclick = function(){
    minModal.style.display = "none";
}
closeRandomBtn.onclick = function(){
    randomModal.style.display = "none";
}
function showOption(value){
    switch(value){
        case 'byte':
            showRangeOption();
            break;
        case 'short':
            showRangeOption();
            break;
        case 'int':
            showRangeOption();
            break;
        case 'long':
            showRangeOption();
            break;
        case 'float':
            showRangeOption();
            break;
        case 'double':
            showRangeOption();
            break;
        case 'char':
            showLengthOption();
            break;
        case 'string':
            showLengthOption();
            break;
        default:
            clearOption();
    }
}
function showLengthOption(){
    $.post('Page/InsertValueModal.php', { 
		'option': "length",
	}, function(returnedData){
        document.getElementById('randomOption').innerHTML = returnedData;
	});
}
function showRangeOption(){
    $.post('Page/InsertValueModal.php', { 
		'option': "range",
	}, function(returnedData){
        document.getElementById('randomOption').innerHTML = returnedData;
	});
}
function clearOption(){
    $.post('Page/InsertValueModal.php', { 
		'option': "clear",
	}, function(returnedData){
        document.getElementById('randomOption').innerHTML = returnedData;
	});
}