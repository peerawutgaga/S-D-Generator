var uploadModal = document.getElementById("uploadModal");
var createCodeModal = document.getElementById("createCodeModal");
var uploadBtn = document.getElementById("uploadBtn");
var createCodeBtn = document.getElementById("createCodeBtn");
var uploadClose = document.getElementsByClassName("close")[0];
var createCodeClose= document.getElementsByClassName("close")[1];
var uploadSDBtn =document.getElementById("uploadSD");
var SDFile = document.getElementById("SDFile");
var uploadCDBtn =document.getElementById("uploadCD");
var CDFile = document.getElementById("CDFile");
var SDSubmit = document.getElementById("SDSubmit");
var CDSubmit = document.getElementById("CDSubmit");
var SDSelect = document.getElementById("SDSelect");
var CDSelect = document.getElementById("CDSelect");
var ClassSelect = document.getElementById("ClassSelect");

uploadBtn.style.cursor = "pointer";
uploadBtn.onclick = function () {
	uploadModal.style.display = "block";
};

createCodeBtn.style.cursor = "pointer";
createCodeBtn.onclick = function () {
	createCodeModal.style.display = "block";
};

uploadClose.onclick = function () {
	uploadModal.style.display = "none";
};

createCodeClose.onclick =function(){
	createCodeModal.style.display = "none";
};

window.onclick = function (event) {
	console.log(event.target);
	if (event.target == uploadModal) {
		uploadModal.style.display = "none";
	}
	if(event.target == createCodeModal){
		createCodeModal.style.display = "none";
	}
};

uploadSDBtn.onclick = function(){
	SDFile.click();
};
uploadCDBtn.onclick = function(){
	CDFile.click();
};

function uploadSDFile()
{
	SDSubmit.click();
}
function uploadCDFile()
{
	CDSubmit.click();
}
function selectSD(selected){
	$.post('Page/SetCodeProperties.php', {  
		'CUT' : selected,
	}, function(returnedData){
		 ClassSelect.innerHTML = returnedData;
	});
}
function createCode(){
	var form = document.getElementById('codeProperties');
	var filename = form.elements.namedItem('filename');
	var sourceType;
	var sourceLang;
	if(!isFormValid(filename)){
		return;
	}
	if(form.elements.namedItem('sourceCodeType')[0].checked){
		sourceType = 'stub';
	}else{
		sourceType = 'driver';
	}
	if(form.elements.namedItem('sourceCodeLang')[0].checked){
		sourceLang = 'Java';
	}else{
		sourceLang = 'PHP';
	}
	$.post('Page/CreateSourceCode.php', { 
		'graphID': SDSelect.options[SDSelect.selectedIndex].value,
		'diagramID' : CDSelect.options[CDSelect.selectedIndex].value, 
		'CUT' : ClassSelect.options[ClassSelect.selectedIndex].value,
		'filename' : filename.value,
		'sourceType' : sourceType,
		'sourceLang' : sourceLang
	}, function(returnedData){
         if(returnedData == "stub error"){
			alert("Cannot create stub for this class because this class does not call any methods in other classes");
		 }else if(returnedData == "driver error"){
			alert("Cannot create driver for this class because this class does not be called by other classes");
		 }else if(returnedData == "file exist"){
			alert("This filename is already exist");
		 }else{
			navigateToCreatCodePage(returnedData);
		 }
	});
}
function navigateToCreatCodePage(sourceCodePath){
	var queryString = "?sourcecode="+sourceCodePath;
	window.location.href='../Create Code.php'+queryString;
}
function isFormValid(filename){
	if(SDSelect.options[SDSelect.selectedIndex].value==0){
		alert("Please Select Call Graph");
		return false;
	}
	if(CDSelect.options[CDSelect.selectedIndex].value==0){
		alert("Please Select Class Diagram");
		return false;
	}
	if(ClassSelect.options[ClassSelect.selectedIndex].value==0){
		alert("Please Select Class Under Test");
		return false;
	}
	if(filename.value === ""){
		alert("Filename cannot be blanked");
		return false;
	}
	return true;
}
