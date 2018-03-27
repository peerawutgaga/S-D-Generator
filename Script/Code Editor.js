var isShiftDown = false;
var codeEditor = document.getElementById("codeEditor");
var lines;
codeEditor.onkeydown = function(e) {
    if (e.keyCode === 9) {
        insert('\t');
        return false;
    }
    else if(e.keyCode === 16){
        isShiftDown = true;
        return false;
    }
    else if(e.keyCode === 57 && isShiftDown){
        insert('()');
        return false;
    }
    else if(e.keyCode === 219 && isShiftDown){
        insert('{}');
        return false;
    }
    else if(e.keyCode === 219 && !isShiftDown){
        insert('[]');
        return false;
    }
    else if(e.keyCode === 222 && isShiftDown){
        insert('\"\"');
        return false;
    }
    else if(e.keyCode === 222 && !isShiftDown){
        insert('\'\'');
        return false;
    }
};
codeEditor.onkeyup = function(e){
    if(e.keyCode === 16){
        isShiftDown = false;
    } 
};
codeEditor.onchange = function(e){
    updateLine();
}
function updateLine(){
    var text = codeEditor.value;
    lines = text.split("\n");   
}
function insert(insertVal){
    var val = codeEditor.value,
    start = codeEditor.selectionStart,
    end = codeEditor.selectionEnd;
    codeEditor.value = val.substring(0, start) + insertVal + val.substring(end);
    codeEditor.selectionStart = codeEditor.selectionEnd = start + 1;
}

