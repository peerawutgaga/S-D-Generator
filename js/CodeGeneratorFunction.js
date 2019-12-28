function recordFileInfo(filename){
    var idx = filename.lastIndexOf("-");
    fileExtension = filename.substring(idx+1);
    oldFilename = filename.substring(0,idx);
}
function initialDefaultModal(){
    if(fileExtension == "php"){
        $.post('php/pages/InsertValueModal.php', { 
            'language': "php",
            'modal': "default",
        }, function(returnedData){
           document.getElementById("defaultDataTypeSelect").innerHTML = returnedData;
        });
    }else{
        $.post('Page/InsertValueModal.php', { 
            'language': "java",
            'modal': "default",
        }, function(returnedData){
            document.getElementById("defaultDataTypeSelect").innerHTML = returnedData;
        });
    }
}
function initialMaxModal(){
    if(fileExtension == "php"){
        $.post('php/pages/InsertValueModal.php', { 
            'language': "php",
            'modal': "max",
        }, function(returnedData){
           document.getElementById("maxDataTypeSelect").innerHTML = returnedData;
        });
    }else{
        $.post('Page/InsertValueModal.php', { 
            'language': "java",
            'modal': "max",
        }, function(returnedData){
            document.getElementById("maxDataTypeSelect").innerHTML = returnedData;
        });
    }
}
function initialMinModal(){
    if(fileExtension == "php"){
        $.post('php/pages/InsertValueModal.php', { 
            'language': "php",
            'modal': "min",
        }, function(returnedData){
           document.getElementById("minDataTypeSelect").innerHTML = returnedData;
        });
    }else{
        $.post('php/pages/InsertValueModal.php', { 
            'language': "java",
            'modal': "min",
        }, function(returnedData){
            document.getElementById("minDataTypeSelect").innerHTML = returnedData;
        });
    }
}
function initialRandomModal(){
    if(fileExtension == "php"){
        $.post('php/pages/InsertValueModal.php', { 
            'language': "php",
            'modal': "random",
        }, function(returnedData){
           document.getElementById("randomDataTypeSelect").innerHTML = returnedData;
        });
    }else{
        $.post('php/pages/InsertValueModal.php', { 
            'language': "java",
            'modal': "random",
        }, function(returnedData){
            document.getElementById("randomDataTypeSelect").innerHTML = returnedData;
        });
    }
}
function openFile(){
	//TODO rewrite the function
   /* var filename = oldFilename+"."+fileExtension;
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
    client.send();*/
}
function rename(){
	//TODO Rewrite the function
    /*var currentFilename = filenameArea.value;
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
    $.post('php/pages/CodeEditorService.php', { 
		'method': "rename",
        'oldFilename' : oldFilename+"."+fileExtension, 
        'newFilename' : currentFilename,
	}, function(returnedData){
        if(returnedData == "failed"){
             alert("Rename failed");
        }else if(returnedData =="Exist"){
            alert("Filename: "+currentFilename+" is exists.");
        }else{
            alert("Rename Succeeded");
            refreshCreatCodePage(returnedData);
         }
	});*/
}
function refreshCreatCodePage(sourceCodePath){
	var queryString = "?sourcecode="+sourceCodePath; 
	window.location.href='../CreateCode.php'+queryString;
}
function saveChange(){
	//TODO rewrite the function
    /*if(!confirm("Save Change ?")){
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
	});*/
}
function exportFile(){
	//TODO Rewrite the function
   /* var confirmMsg = "Export file: "+oldFilename+"."+fileExtension+"?";
    if(!confirm(confirmMsg)){
        return;
    }
    var filepath = oldFilename+"-"+fileExtension;
    var queryString = "?sourcecode="+filepath; 
	window.location.href='../php/Download.php'+queryString;*/
}


function showOption(value){
    if(value == "byte" || value == "short" || value == "int"||value == "long"){
        showRangeOption();
    }else if(value == "float"||value == "double"){
        showBothOption();
    }else if(value == "string"){
        showLengthOption();
    }else{
        clearOption();
    }
}
function showLengthOption(){
    $.post('php/pages/InsertValueModal.php', { 
		'option': "length",
	}, function(returnedData){
        document.getElementById('randomOption').innerHTML = returnedData;
	});
}
function showRangeOption(){
    $.post('php/pages/InsertValueModal.php', { 
		'option': "range",
	}, function(returnedData){
        document.getElementById('randomOption').innerHTML = returnedData;
	});
}
function showBothOption(){
    $.post('php/pages/InsertValueModal.php', { 
		'option': "both",
	}, function(returnedData){
        document.getElementById('randomOption').innerHTML = returnedData;
	});
}
function clearOption(){
    $.post('php/pages/InsertValueModal.php', { 
		'option': "clear",
	}, function(returnedData){
        document.getElementById('randomOption').innerHTML = returnedData;
	});
}