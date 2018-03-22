<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Create Code</title>
	<link rel = "stylesheet" href = "CSS/Create Code.css">
</head>
<body>
	<header>
		<h1>Stub and Driver Generator Online Tool</h1>
	</header>
<article>
	<div id = "fileInfo">
		Filename: <input type = "text" name = "filename" id = "filename" >
		<button id = "renameBtn" onclick = "rename()"><img src = "Image/rename.png">Rename</button>
		<button id = "exportBtn" onclick = "exportFile()"><img src = "Image/export.png">Export</button>
		<button id = "saveBtn" onclick = "saveChange()"><img src = "Image/save.png">Save</button>
	</div>
	<div id = "editor">
		<textarea id = "codeEditor"></textarea>
	</div>
	<div id = "insert">
		<button id = "defaultBtn">Default Value</button>
		<button id = "maxBtn">Max Value</button>
		<button id = "minBtn">Min Value</button>
		<button id = "randomBtn">Random Value</button>
	</div>
</article>
<footer>2018 Copyright &copy; Department of Computer Engineering<br/>
  Faculty of Engineering, Chulalongkorn University</footer>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src = "Script/Create Code.js"></script>
<script src = "Script/Code Editor.js"></script>
</body>
</html>
