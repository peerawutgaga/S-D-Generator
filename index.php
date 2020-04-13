<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Main</title>
<link rel="stylesheet" href="css/Main.css">
<script
	src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>
<body>
	<header>
		<h1>Stubs and Drivers Generation Tool</h1>
	</header>
	<article>
		<!-- Information Side Bar -->
		<div class="descriptionSideBar">
			<p id="tool-info">
				<b>Stub and Driver Generator Online Tool</b> is a tool for creating
				stubs or drivers for class integration testing from sequence and
				class diagrams
			</p>
			<h3>Instruction</h3>
			<ol>
				<li>Upload UML diagram as XML files.</li>
				<li>Select class(es) under test.</li>
				<li>Edit the source codes.</li>
				<li>Export.</li>
			</ol>
		</div>
		<!-- Button Table -->
		<div class="table">
			<table>
				<tbody>
					<tr>
						<td class="UI-td"><div id="uploadBtn">
								<img src="Image/Upload.png" width="170" height="170" alt="" /><br>
								Upload XML
							</div></td>
						<td class="UI-td"><div id="createCodeBtn">
								<img src="Image/Create Code.png" width="170" height="170" alt="" /><br>
								Create Source Code
							</div></td>
					</tr>
					<tr>
						<td class="UI-td"><a href="DiagramManager.php"><img
								src="Image/Diagram Manager.png" width="170" height="170" alt="" /><br>
								XML Manager</a></td>
						<td class="UI-td"><a href="SourceCodeManager.php"><img
								src="Image/File Manager.png" width="170" height="170" alt="" /><br>
								Source Code Manager</a></td>
					</tr>
				</tbody>
			</table>
		</div>
	</article>
	<!-- Upload Modal -->
	<div id="uploadModal" class="modal">
		<div class="uploadModal-content">
			<span class="close">&times;</span>
			<h3>Upload XML File</h3>
			<form id="uploadForm" action="./php/utilities/Uploader.php"
				method="post" enctype="multipart/form-data">
				<input type="file" id="SDFile" name="SDFile" style="display: none"
					onchange="uploadSDFile()" multiple size="1"> <input type="file"
					id="CDFile" name="CDFile" style="display: none"
					onchange="uploadCDFile()" multiple size="1"> <input type="submit"
					id="SDSubmit" name="SDSubmit" style="display: none"> <input
					type="submit" id="CDSubmit" name="CDSubmit" style="display: none">
			</form>
			<table>
				<tbody>
					<tr>
						<td id="uploadSDBtn" class="uploadButton" width="50%"><img
							src="Image/Sequence Diagram Upload.png" width="100" height="100"
							alt="" /><br> Sequence Diagram</td>
						<td id="uploadCDBtn" class="uploadButton"><img
							src="Image/Class Diagram Upload.png" width="100" height="100"
							alt="" /><br> Class Diagram</td>
					</tr>
				</tbody>
			</table>
			<p align="center">XML must be generated from Visual Paradigm</p>
		</div>
	</div>
	<!-- Diagram selection Modal -->
	<div id="diagramSelectionModal" class="modal">
		<div class="diagramSelectionModal-content">
			<span class="close">&times;</span>
			<h3>Select Diagram</h3>
			<h4>Select Call Graph</h4>
			<select id='SDSelect'>
				<option value='0' selected disabled hidden>Please Select Call Graph</option>
			</select>
			<h4>Select Class Diagram</h4>
			<select id='CDSelect'>
				<option value='0' selected disabled hidden>Please Select Class
					Diagram</option>
			</select>
			<button id="nextBtn" class="navigateButton"
				onclick="transitToClassSelection()">Next</button>
		</div>
	</div>
	<!-- Class under test selection Modal -->
	<div id="classSelectionModal" class="modal">
		<div class="classSelectionModal-content">
			<span class="close">&times;</span>
			<h3>Select Class(es) Under Test</h3>
			<div id="classSelectionDiv">
				<div id="classTableDiv">
					<table id="classListTable" border=1>
					</table>
				</div>
			</div>
			<button id="backBtn" class="navigateButton">Back</button>
			<button id="createBtn" class="navigateButton"
				onclick="createSourceCode()">CreateCode</button>
		</div>
	</div>
	<!-- File List-->
	<div id="fileListModal" class="modal">
		<div class="fileListModal-content">
			<span class="close">&times;</span>
			<h4>Generated Files</h4>
			<div id="fileTableDiv">
				<table id="fileListTable" border=1>
				</table>
			</div>
			<div align="center">
			<button id="backToClassBtn" class="fileListButton" onclick="backToClassSelection()">Back</button>
			<button id="exportBtn" class="fileListButton" onclick="exportSelected()">Export</button>
			<button id="exportAllBtn" class="fileListButton"onclick="exportAll()">Export All</button>
			<button id="EditBtn" class="fileListButton" onclick="editCode()">Edit</button>
			</div>
		</div>
	</div>
	<footer>
		2018 Copyright &copy; Department of Computer Engineering<br /> Faculty
		of Engineering, Chulalongkorn University
	</footer>
</body>

<script src="js/mainpage/MainPageProperties.js"></script>
<script src="js/mainpage/MainPageStyle.js"></script>
<script src="js/mainpage/MainPageFunction.js"></script>
<script src="js/filegenerator/FileGeneratorProperties.js"></script>
<script src="js/filegenerator/FileGeneratorStyle.js"></script>
<script src="js/filegenerator/FileGeneratorFunction.js"></script>
<script>refreshSDList();</script>
<script>refreshCDList();</script>
</html>
