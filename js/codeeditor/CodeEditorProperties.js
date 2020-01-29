//TextArea
var filenameTextArea = document.getElementById("filenameTextArea");
var codeTextArea = document.getElementById("codeEditorTextArea");

//Modal
var genMaxValueModal = document.getElementById("generateMaxValueModal");
var genMinValueModal = document.getElementById("generateMinValueModal");
var generateRandomStringModal = document.getElementById("generateRandomStringModal");
var generateRandomIntegerModal = document.getElementById("generateRandomIntegerModal");
var generateRandomDecimalModal = document.getElementById("generateRandomDecimalModal");

//Button
var closeMaxModalBtn = document.getElementsByClassName("close")[0];
var closeMinModalBtn= document.getElementsByClassName("close")[1];
var closeRandomStringModalBtn= document.getElementsByClassName("close")[2];
var closeRandomIntegerModalBtn= document.getElementsByClassName("close")[3];
var closeRandomDecimalModalBtn= document.getElementsByClassName("close")[4];

//Variable
var isShiftDown = false;
var currentFileName;
var currentExtension;
var currentFileId;
