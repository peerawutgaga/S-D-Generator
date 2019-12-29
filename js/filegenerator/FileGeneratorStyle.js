fileListTable.onclick = function(e) {
	if (e.target.parentNode.nodeName == "TR") {
		if (e.target.parentNode.className == 'selected') {
			e.target.parentNode.className = '';
		} else {
			e.target.parentNode.className = 'selected';
		}
	}
};