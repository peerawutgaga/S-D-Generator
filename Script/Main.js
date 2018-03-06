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
	if (window.XMLHttpRequest) {
		// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp = new XMLHttpRequest();
	} else {
		// code for IE6, IE5
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			document.getElementById("ClassSelect").innerHTML = this.responseText;
		}
	};
	xmlhttp.open("GET","Page/RefreshSelectClass.php?q="+selected,true);
	xmlhttp.send(); 
}
function createCode(){
	// if(!isAllSelected()){
	// 	return;
	// }
	$.ajax({
		url: "Create Code.php",
		type: "Post",
		data:{},
		success:function(msg){
			
		}
	});
}
function isAllSelected(){
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
	return true;
}
