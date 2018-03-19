var isShiftDown = false;
var codeEditor = document.getElementById("codeEditor");
var filenameArea = document.getElementById("filename");
window.onload = function(){
    var filename = decodeURIComponent(window.location.search);
    filename = filename.substring(12);
    openFile(filename);
};
function openFile(filename){
    filepath = "../Source Code Files/"+filename;
    var client = new XMLHttpRequest();
    client.open('GET', filepath);
    client.onreadystatechange = function() {
        if(client.readyState === 4)
        {
            if(client.status === 200 || rawFile.status == 0)
            {
                var allText = client.responseText;
                filenameArea.value = filename;
                codeEditor.value = allText;
            }
        }
    }
    client.send();
}
codeEditor.onkeydown = function(e) {
    if (e.keyCode === 9) {
        var val = this.value,
        start = this.selectionStart,
        end = this.selectionEnd;
        this.value = val.substring(0, start) + '\t' + val.substring(end);
        this.selectionStart = this.selectionEnd = start + 1;
        return false;
    }
    else if(e.keyCode === 16){
        isShiftDown = true;
        return false;
    }
    else if(e.keyCode === 57 && isShiftDown){
        var val = this.value,
        start = this.selectionStart,
        end = this.selectionEnd;
        this.value = val.substring(0, start) + '()' + val.substring(end);
        this.selectionStart = this.selectionEnd = start + 1;
        return false;
    }
    else if(e.keyCode === 219 && isShiftDown){
        var val = this.value,
        start = this.selectionStart,
        end = this.selectionEnd;
        this.value = val.substring(0, start) + '{}' + val.substring(end);
        this.selectionStart = this.selectionEnd = start + 1;
        return false;
    }
    else if(e.keyCode === 219 && !isShiftDown){
        var val = this.value,
        start = this.selectionStart,
        end = this.selectionEnd;
        this.value = val.substring(0, start) + '[]' + val.substring(end);
        this.selectionStart = this.selectionEnd = start + 1;
        return false;
    }
    else if(e.keyCode === 222 && isShiftDown){
        var val = this.value,
        start = this.selectionStart,
        end = this.selectionEnd;
        this.value = val.substring(0, start) + '\"\"' + val.substring(end);
        this.selectionStart = this.selectionEnd = start + 1;
        return false;
    }
    else if(e.keyCode === 222 && !isShiftDown){
        var val = this.value,
        start = this.selectionStart,
        end = this.selectionEnd;
        this.value = val.substring(0, start) + '\'\'' + val.substring(end);
        this.selectionStart = this.selectionEnd = start + 1;
        return false;
    }
};
codeEditor.onkeyup = function(e){
    if(e.keyCode === 16){
        isShiftDown = false;
    } 
};
