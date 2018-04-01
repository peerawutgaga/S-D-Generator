var isShiftDown = false;
var codeEditor = document.getElementById("codeEditor");
var insertDefault = document.getElementById("insertdefaultBtn");
var insertMax = document.getElementById("insertmaxBtn");
var insertMin = document.getElementById("insertminBtn");
var insertRandom = document.getElementById("insertrandomBtn");
var defaultDataTypeSelect = document.getElementById('defaultDataTypeSelect');
var maxDataTypeSelect = document.getElementById('maxDataTypeSelect');
var minDataTypeSelect = document.getElementById('minDataTypeSelect');
var randomDataTypeSelect = document.getElementById('randomDataTypeSelect');
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
function insert(insertVal){
    var val = codeEditor.value,
    start = codeEditor.selectionStart,
    end = codeEditor.selectionEnd;
    codeEditor.value = val.substring(0, start) + insertVal + val.substring(end);
    codeEditor.selectionStart = codeEditor.selectionEnd = start + 1;
}
insertDefault.onclick = function(){
    var selectedValue = defaultDataTypeSelect.options[defaultDataTypeSelect.selectedIndex].value;
    if(selectedValue==0){
        alert("Please select data type");
        return;
    }
    var defaultValue = getDefault(selectedValue);
    insert(defaultValue);
}
insertMax.onclick = function(){
    var selectedValue = maxDataTypeSelect.options[maxDataTypeSelect.selectedIndex].value;
    if(selectedValue==0){
        alert("Please select data type");
        return;
    }
    var maxValue = getMax(selectedValue);
    insert(maxValue);
}
insertMin.onclick = function(){
    var selectedValue = minDataTypeSelect.options[minDataTypeSelect.selectedIndex].value;
    if(selectedValue==0){
        alert("Please select data type");
        return;
    }
    var minValue = getMin(selectedValue);
    insert(minValue);
}
