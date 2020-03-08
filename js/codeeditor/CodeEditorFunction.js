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
function randomAnyString(){
	var length = randomInt(1,100);
	var str = randomString(length);
	insertCharacterToCodeEditor(str);
}
function randomStringWithLength(){
	var length = document.getElementById("stringLengthTextArea").value;
	if(isNaN(length)){
		alert("Length is not a number.");
		return;
	}
	if(length==""){
		alert("Please specify length.");
		return;
	}
	var str = randomString(Math.floor(length));
	insertCharacterToCodeEditor(str);
}
function randomIntegerWithBound(){
	var minBound = document.getElementById("minIntTextArea").value;
	var maxBound = document.getElementById("maxIntTextArea").value;
	if(isNaN(minBound)){
		alert("Min value is not a number.");
		return;
	}
	if(isNaN(maxBound)){
		alert("Max value is not a number.");
		return;
	}
	if(minBound==""){
		minBound = -2000000000;
	}
	if(maxBound==""){
		maxBound = 2000000000;
	}
	var integer = randomInt(Math.floor(minBound),Math.floor(maxBound));
	insertCharacterToCodeEditor(integer);
}
function randomIntegerWithLength(){
	var length = document.getElementById("intDigitTextArea").value;
	if(isNaN(length)){
		alert("Length is not a number.");
	}
	if(length==""){
		alert("Please specify length.");
		return;
	}
	var integer = randomNumberByDigit(Math.floor(length));
	insertCharacterToCodeEditor(integer);
}
function randomDecimalWithBound(){
	var minBound = document.getElementById("minDecimalTextArea").value;
	var maxBound = document.getElementById("maxDecimalTextArea").value;
	if(isNaN(minBound)){
		alert("Min value is not a number.");
		return;
	}
	if(isNaN(maxBound)){
		alert("Max value is not a number.");
		return;
	}
	if(minBound==""){
		minBound = -2000000000;
	}
	if(maxBound==""){
		maxBound = 2000000000;
	}
	var decimal = randomDecimal(minBound,maxBound);
	insertCharacterToCodeEditor(decimal);
}
function randomDecimalWithLength(){
	var fractalLength = document.getElementById("fractalLengthTextArea").value;
	var decimalLength = document.getElementById("decimalLengthTextArea").value;
	if(isNaN(fractalLength)||isNaN(decimalLength)){
		alert("Length is not a number.");
	}
	if(fractalLength==""||decimalLength==""){
		alert("Please specify length.");
		return;
	}
	var decimal = randomDecimalByDigit(Math.floor(fractalLength),Math.floor(decimalLength));
	insertCharacterToCodeEditor(decimal);
}
