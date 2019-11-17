function uploadFile()
{
	document.getElementById("submitFile").click();
}
function selectSD(selected){
	$.post('php/pages/SetCodeProperties.php', {  
		'CUT' : selected,
	}, function(returnedData){
		 ClassSelect.innerHTML = returnedData;
	});
}

