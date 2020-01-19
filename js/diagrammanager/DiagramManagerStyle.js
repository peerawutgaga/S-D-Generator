window.onclick = function(event) {
	if (event.target == renameModal) {
		renameModal.style.display = "none";
	}
};
callGraphTable.onclick = function(e) {
	var selectedDiagram = callGraphTable.getElementsByClassName('selected');
	if (e.target.parentNode.nodeName == "TR"
			&& e.target.parentNode.id != "graphHeader") {
		if (selectedDiagram[0]){
			selectedDiagram[0].className = '';							
			if(selectedGraphId != e.target.parentNode.id){
				e.target.parentNode.className = 'selected';
				selectedGraphId = e.target.parentNode.id;
			}
		}
		else{
			e.target.parentNode.className = 'selected';
			selectedGraphId = e.target.parentNode.id;
		}
	}
};
classDiagramTable.onclick = function(e) {
	var selectedDiagram = classDiagramTable.getElementsByClassName('selected');
	if (e.target.parentNode.nodeName == "TR"
			&& e.target.parentNode.id != "diagramHeader") {
		if (selectedDiagram[0]){
			selectedDiagram[0].className = '';	
			if(selectedDiagramId != e.target.parentNode.id){
				e.target.parentNode.className = 'selected';
				selectedDiagramId = e.target.parentNode.id;
			}
		}
		else{
			e.target.parentNode.className = 'selected';	
			selectedDiagramId = e.target.parentNode.id;
		}
	}
};
// Get the element with id="defaultOpen" and click on it
document.getElementById("SDContent").click();

closeModalBtn.onclick = function() {
	renameModal.style.display = "none";
};