function openFile(fileId){
	$.post('php/pages/CodeEditorPage.php', { 
		'function': "openFile",
        'fileId' : fileId 
	}, function(returnedData){
		var fileInfo = JSON.parse(returnedData);
		currentFileId = fileInfo[0]["fileId"];
		currentFilename = fileInfo[0]["filename"];
		currentExtension = currentFilename.substring(currentFilename.lastIndexOf(".")+1);
		filenameTextArea.value = fileInfo[0]["filename"];
		codeTextArea.value = fileInfo[0]["filePayload"]
	});
}
function rename(){
    var newFilename = filenameTextArea.value;
    var confirmMsg = "Rename from "+currentFilename+" to "+newFilename;
    if(!confirm(confirmMsg)){
        return;
    }
    var newExtension = newFilename.substring(newFilename.lastIndexOf(".")+1);//Get Extension
    if(newExtension != currentExtension){
        alert("Cannot change file extension");
        return;
    }
    if(newFilename == currentFilename){
        alert("Nothing changed");
        return;
    }
    if(currentFilename == ""){
        alert("Filename cannot be empty");
        return;
    }
    $.post('php/pages/CodeEditorPage.php', { 
		'function': "rename",
        'fileId' : currentFileId, 
        'newFilename' : newFilename,
	}, function(returnedData){
        if(returnedData == "success"){
        	alert("Rename Succeeded");
            refreshCreatCodePage();            
        }else{
        	alert("Rename failed");
        }
	});
}

function refreshCreatCodePage(){
	var queryString = "?sourcecode="+currentFileId; 
	window.location.href='../CreateCode.php'+queryString;
}
function saveChange(){
    if(!confirm("Save Change ?")){
        return;
    }
    var filePayload = codeTextArea.value;
    $.post('php/pages/CodeEditorPage.php', { 
		'function': "saveFile",
        'fileId' : currentFileId, 
        'filePayload' : filePayload,
	}, function(returnedData){
		if(returnedData == "success"){
        	alert("Saved");
            refreshCreatCodePage();            
        }else{
        	alert("Save failed");
        }
	});
}
function exportFile(){
   var confirmMsg = "Export file: "+currentFilename+"?";
    if(!confirm(confirmMsg)){
        return;
    }
    var queryString = "?sourcecode="+currentFileId; 
	window.location.href='../php/utilities/Download.php'+queryString;
}

function showMaxModal(){
	genMaxValueModal.style.display = "block";
}
function showMinModal(){
	genMinValueModal.style.display = "block";
}
function showRandomStringModal(){
	generateRandomStringModal.style.display = "block";
}
function showRandomIntegerModal(){
	generateRandomIntegerModal.style.display = "block";
}

function showRandomDecimalModal(){
	generateRandomDecimalModal.style.display = "block";
}


function insertCharacterToCodeEditor(insertValue){
    var currentValue = codeTextArea.value,
    start = codeTextArea.selectionStart,
    end = codeTextArea.selectionEnd;
    codeTextArea.value = currentValue.substring(0, start) + insertValue + currentValue.substring(end);
    codeTextArea.selectionStart = codeTextArea.selectionEnd = start + 1;
}
