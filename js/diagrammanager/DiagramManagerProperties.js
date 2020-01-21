//Table
var callGraphTable = document.getElementById("CallGraphTable");
var classDiagramTable = document.getElementById("ClassDiagramTable");

//Selected TR
var callGraphSelected = callGraphTable.getElementsByClassName('selected');
var classDiagramSelected = classDiagramTable.getElementsByClassName('selected');

//Modal
var renameModal = document.getElementById("renameModal");
var closeRenameModalBtn = document.getElementsByClassName("close")[0]
var linkingModal = document.getElementById("linkingModal");
var closeLinkingModalBtn = document.getElementsByClassName("close")[1];

//Selector
var callGraphSelector = document.getElementById('callGraphSelector');
var refObjectSelector = document.getElementById('referenceSelector');

//Button
var linkBtn = document.getElementById("linkBtn");
//Variable
var currentTable;
var selectedGraphId;
var selectedDiagramId;