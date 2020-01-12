window.onload = function(){
    var url = decodeURIComponent(window.location.search);
    var fileId = url.substring(12);//Get file Id after 'sourcecode='
    openFile(fileId);
};
/*
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
*/
codeTextArea.onkeydown = function(e) {
    if (e.keyCode === 9) {
    	insertCharacterToCodeEditor('\t');//Insert Tab
        return false;
    }
    else if(e.keyCode === 16){
        isShiftDown = true;//Mark as shift key is down
        return false;
    }
    else if(e.keyCode === 57 && isShiftDown){
    	insertCharacterToCodeEditor('()');//Insert () when ( is pressed.
        return false;
    }
    else if(e.keyCode === 219 && isShiftDown){
    	insertCharacterToCodeEditor('{}');//Insert {} when { is pressed.
        return false;
    }
    else if(e.keyCode === 219 && !isShiftDown){
    	insertCharacterToCodeEditor('[]');//Insert [] when [ is pressed.
        return false;
    }
    else if(e.keyCode === 222 && isShiftDown){
    	insertCharacterToCodeEditor('\"\"');//Insert "" when " is pressed.
        return false;
    }
    else if(e.keyCode === 222 && !isShiftDown){
    	insertCharacterToCodeEditor('\'\'');//Insert '' when ' is pressed.
        return false;
    }
};
codeTextArea.onkeyup = function(e){
    if(e.keyCode === 16){
        isShiftDown = false;
    } 
};
