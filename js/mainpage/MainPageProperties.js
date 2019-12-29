//Modal Frame
var uploadModal = document.getElementById("uploadModal");
var diagramSelectionModal = document.getElementById("diagramSelectionModal");
var classSelectionModal = document.getElementById("classSelectionModal");
var fileListModal = document.getElementById("fileListModal");

//Button
var uploadBtn = document.getElementById("uploadBtn");
var createCodeBtn = document.getElementById("createCodeBtn");
var uploadSDBtn =document.getElementById("uploadSD");
var uploadCDBtn =document.getElementById("uploadCD");
var backBtn = document.getElementById("backBtn");

//Close Button
var uploadClose = document.getElementsByClassName("close")[0];
var diagramSelectionClose= document.getElementsByClassName("close")[1];
var classSelectionClose= document.getElementsByClassName("close")[2];
var fileListClose = document.getElementsByClassName("close")[3];

//Selector
var ClassSelect = document.getElementById("ClassSelect");
var SDSelect = document.getElementById("SDSelect");
var CDSelect = document.getElementById("CDSelect");

//Table
var classListTable = document.getElementById("classListTable");

//Variable
var selectedSDId;
var selectedCDId;