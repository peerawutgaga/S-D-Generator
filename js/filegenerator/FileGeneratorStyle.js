fileListTable.onclick = function(e) {
	var selectedFile = fileListTable.getElementsByClassName('selected');
	 if (selectedFile[0]) selectedFile[0].className = '';
		e.target.parentNode.className = 'selected'; 
};