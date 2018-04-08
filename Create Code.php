<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Create Code</title>
	<link rel = "stylesheet" href = "CSS/Create Code.css">
</head>
<body>
	<header>
		<h1>Stubs and Drivers Generator Tool</h1>
	</header>
<article>
	<div id = "fileInfo">
		Filename: <input type = "text" name = "filename" id = "filename" >
		<button id = "renameBtn" class = "UIButton" onclick = "rename()"><img src = "Image/rename.png">Rename</button>
		<button id = "exportBtn" class = "UIButton" onclick = "exportFile()"><img src = "Image/export.png">Export</button>
		<button id = "saveBtn" class = "UIButton" onclick = "saveChange()"><img src = "Image/save.png">Save</button>
	</div>
	<div id = "editor">
		<textarea id = "codeEditor"></textarea>
	</div>
	<div id = "insert">
		<button id = "defaultBtn" class = "UIButton" onclick = "showDefaultModal()">Default Value</button>
		<button id = "maxBtn" class = "UIButton" onclick = "showMaxModal()">Max Value</button>
		<button id = "minBtn" class = "UIButton" onclick = "showMinModal()">Min Value</button>
		<button id = "randomBtn" class = "UIButton" onclick = "showRandomModal()">Random Value</button>
	</div>
</article>
<?php
	require_once "Page/InsertValueModal.php";
	initialModal("java","default");
	initialModal("java","max");
	initialModal("java","min");
	initialModal("java","random");
?>
<footer>2018 Copyright &copy; Department of Computer Engineering<br/>
  Faculty of Engineering, Chulalongkorn University</footer>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src = "Script/Code Editor.js"></script>
<script src = "Script/Create Code.js"></script>
<script src = "Script/Random.js"></script>
<script src = "Script/GetValue.js"></script>
</body>
</html>
