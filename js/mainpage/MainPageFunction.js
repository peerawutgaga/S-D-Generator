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
function refreshSDList() {
	$.ajax({
		url : 'php/pages/DiagramSelection.php',
		data : {
			functionName : 'getCallGraphList'
		},
		type : 'post',
		success : function(output) {
			var sdList = JSON.parse(output);
			sdList.forEach(function(item,index){
				var option = document.createElement("option");
				option.id = "sd"+item[0];
				option.text = item[1];
				SDSelect.add(option);
			});
		}
	});
}
function refreshCDList() {
	$.ajax({
		url : 'php/pages/DiagramSelection.php',
		data : {
			functionName : 'getClassDiagramList'
		},
		type : 'post',
		success : function(output) {
			var cdList = JSON.parse(output);
			cdList.forEach(function(item,index){
				var option = document.createElement("option");
				option.id = "cd"+item[0];
				option.text = item[1];
				CDSelect.add(option);
			});
		}
	});
}


