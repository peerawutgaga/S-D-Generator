window.onload = function(){
    var filename = decodeURIComponent(window.location.search);
    filename = filename.substring(12);
    recordFileInfo(filename);
    initialDefaultModal();
    initialMinModal();
    initialMaxModal();
    initialRandomModal();
    openFile();
};
window.onclick = function (event) {
	if (event.target == defaultModal) {
		defaultModal.style.display = "none";
    }
    if (event.target == minModal) {
		minModal.style.display = "none";
    }
    if (event.target == maxModal) {
		maxModal.style.display = "none";
    }
    if (event.target == randomModal) {
		randomModal.style.display = "none";
	}
};

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
insertDefault.onclick = function(){
    var selectedValue = defaultDataTypeSelect.options[defaultDataTypeSelect.selectedIndex].value;
    if(selectedValue==0){
        alert("Please select data type");
        return;
    }
    var defaultValue = getDefault(selectedValue);
    insert(defaultValue);
    defaultModal.style.display = "none";
}
insertMax.onclick = function(){
    var selectedValue = maxDataTypeSelect.options[maxDataTypeSelect.selectedIndex].value;
    if(selectedValue==0){
        alert("Please select data type");
        return;
    }
    if(fileExtension == "java"){
        var maxValue = getJavaMax(selectedValue);
    }else{
        var maxValue = getPHPMax(selectedValue);
    }
    insert(maxValue);
    maxModal.style.display = "none";
}
insertMin.onclick = function(){
    var selectedValue = minDataTypeSelect.options[minDataTypeSelect.selectedIndex].value;
    if(selectedValue==0){
        alert("Please select data type");
        return;
    }
    if(fileExtension == "java"){
        var minValue = getJavaMin(selectedValue);
    }else{
        var minValue = getPHPMin(selectedValue);
    }
    insert(minValue);
    minModal.style.display = "none";
}
insertRandom.onclick = function(){
    var selectedValue = randomDataTypeSelect.options[randomDataTypeSelect.selectedIndex].value;
    if(selectedValue==0){
        alert("Please select data type");
        return;
    }
    if(selectedValue == "byte" || selectedValue == "short" || selectedValue == "int"||selectedValue == "long"){
        var form = document.getElementById("rangeForm");
        var rndFrom = parseInt(form.elements.namedItem("minimumBox").value);
        var rndTo = parseInt(form.elements.namedItem("maximumBox").value);
        if(isNaN(rndFrom)){
            rndFrom = parseInt(getMin(selectedValue));
        }
        if(isNaN(rndTo)){
            rndTo = parseInt(getMax(selectedValue));
        }
        var rndVal = randomInt(rndFrom,rndTo);
        insert(rndVal);
    }else if(selectedValue == "float" || selectedValue == "double"){
        var form = document.getElementById("bothForm");
        var rndFrom = parseFloat(form.elements.namedItem("minimumBox").value);
        var rndTo = parseFloat(form.elements.namedItem("maximumBox").value);
        var fixLength = parseInt(form.elements.namedItem("lengthBox").value)
        if(isNaN(rndFrom)){
            rndFrom = parseFloat(getMin(selectedValue));
        }
        if(isNaN(rndTo)){
            rndTo = parseFloat(getMax(selectedValue));
        }
        var rndVal = randomFloat(rndFrom,rndTo);
        if(!isNaN(fixLength)){
            rndVal = rndVal.toFixed(fixLength);
        }
        insert(rndVal);
    }else if(selectedValue == "boolean"){
        insert(randomBoolean());
    }else if(selectedValue == "string"){
        var form = document.getElementById("lengthForm");
        var fixLength = parseInt(form.elements.namedItem("lengthBox").value);
        if(isNaN(fixLength)){
            fixLength = randomInt(1,100);
        }
        var rndVal = randomString(fixLength);
        insert(rndVal);
        
    }else if(selectedValue == "char"){
        insert(randomChar());
    }
    randomModal.style.display = "none";
}
