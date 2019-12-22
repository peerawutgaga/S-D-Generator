function uploadSDFile()
{
	document.getElementById("SDSubmit").click();
}
function uploadCDFile()
{
	document.getElementById("CDSubmit").click();
}
function selectSD(selected){
	$.post('php/pages/SetCodeProperties.php', {  
		'CUT' : selected,
	}, function(returnedData){
		 ClassSelect.innerHTML = returnedData;
	});
}

