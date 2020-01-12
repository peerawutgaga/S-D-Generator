<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Create Code</title>
	<link rel = "stylesheet" href = "css/CreateCode.css">
</head>
<body>
	<header>
		<h1>Stubs and Drivers Generator Tool</h1>
	</header>
<article>
	<div id = "fileInfo">
		Filename: <input type = "text" name = "filename" id = "filenameTextArea" >
		<button id = "renameBtn" class = "UIButton" onclick = "rename()"><img src = "Image/rename.png">Rename</button>
		<button id = "exportBtn" class = "UIButton" onclick = "exportFile()"><img src = "Image/export.png">Export</button>
		<button id = "saveBtn" class = "UIButton" onclick = "saveChange()"><img src = "Image/save.png">Save</button>
	</div>
	<div id = "editor">
		<textarea id = "codeEditorTextArea"></textarea>
	</div>
	<div id = "insert">
		<button id = "defaultBtn" class = "UIButton" onclick = "showDefaultModal()">Default Value</button>
		<button id = "maxBtn" class = "UIButton" onclick = "showMaxModal()">Max Value</button>
		<button id = "minBtn" class = "UIButton" onclick = "showMinModal()">Min Value</button>
		<button id = "randomBtn" class = "UIButton" onclick = "showRandomModal()">Random Value</button>
	</div>
</article>
<footer>2018 Copyright &copy; Department of Computer Engineering<br/>
  Faculty of Engineering, Chulalongkorn University</footer>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src = "js/codeeditor/CodeEditorProperties.js"></script>
<script src = "js/codeeditor/CodeEditorStyle.js"></script>
<script src = "js/codeeditor/CodeEditorFunction.js"></script>
<script src = "js/Random.js"></script>
<script src = "js/GetValue.js"></script>
</body>
</html>
