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
