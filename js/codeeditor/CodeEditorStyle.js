window.onload = function(){
    var url = decodeURIComponent(window.location.search);
    var fileId = url.substring(12);//Get file Id after 'sourcecode='
    openFile(fileId);
};
window.onclick = function (event) {
	if (event.target == genMaxValueModal) {
		genMaxValueModal.style.display = "none";
	}
    if (event.target == genMinValueModal) {
    	genMinValueModal.style.display = "none";
    }
    if (event.target == generateRandomStringModal) {
    	generateRandomStringModal.style.display = "none";
	}
    if (event.target == generateRandomIntegerModal) {
    	generateRandomIntegerModal.style.display = "none";
	}
    if (event.target == generateRandomDecimalModal) {
    	generateRandomDecimalModal.style.display = "none";
	}
};

closeMaxModalBtn.onclick = function(){
	genMaxValueModal.style.display = "none";
}
closeMinModalBtn.onclick = function(){
	genMinValueModal.style.display = "none";
}
closeRandomStringModalBtn.onclick = function(){
	generateRandomStringModal.style.display = "none";
}
closeRandomIntegerModalBtn.onclick = function(){
	generateRandomIntegerModal.style.display = "none";
}
closeRandomDecimalModalBtn.onclick = function(){
	generateRandomDecimalModal.style.display = "none";
}
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
