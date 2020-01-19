//Table
var callGraphTable = document.getElementById("CallGraphTable");
var classDiagramTable = document.getElementById("ClassDiagramTable");

//Selected TR
var callGraphSelected = callGraphTable.getElementsByClassName('selected');
var classDiagramSelected = classDiagramTable.getElementsByClassName('selected');

//Modal
var renameModal = document.getElementById("renameModal");
var closeModalBtn = document.getElementsByClassName("close")[0];

//Variable
var currentTable;
var selectedGraphId;
var selectedDiagramId;