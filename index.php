<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Main</title>
<link rel = "stylesheet" href = "CSS/Main.css">
</head>

<body>
<header>
  <h1>Stub and Driver Generator Online Tool</h1>
</header>
<article>
  <!-- Information Side Bar -->
  <div class = "description">
    <p id="tool-info"><b>Stub and Driver Generator Online Tool</b> is a tool for creating stubs or drivers for Object-Oriented program testing from sequence and class diagrams</p>
    <h3>Instruction</h3>
    <ol>
      <li>Upload UML diagram as XML files.</li>
      <li>Select class under test.</li>
      <li>Choose source code type (Stub or Driver).</li>
      <li>Choose languag.e</li>
      <li>Edit the source codes.</li>
      <li>Export.</li>
    </ol>
  </div>
  <!-- Button Table -->
  <div class = "table">
    <table>
      <tbody>
        <tr>
          <td><div id = "uploadBtn"><img src="Image/Upload.png" width="170" height="170" alt=""/></br>
              Upload XML</div></td>
          <td><div id = "createCodeBtn"><img src="Image/Create Code.png" width="170" height="170" alt=""/></br>
            Create Source Code</div></td>
        </tr>
        <tr>
          <td><a href="Diagram Manager.php"><img src="Image/Diagram Manager.png" width="170" height="170" alt=""/></br>
            Diagram Manager</a></td>
          <td><a href="Source Code Manager.php"><img src="Image/File Manager.png" width="170" height="170" alt=""/></br>
            Source Code Manager</a></td>
        </tr>
      </tbody>
    </table>
  </div>
</article>
<!-- Upload Modal -->
<div id="uploadModal" class="uploadModal">
  <div class="uploadModal-content"> <span class="close">&times;</span>
    <h3>Upload XML File</h3>
    <form id = "uploadForm" action = "./PHP/Uploader.php" method = "post" enctype="multipart/form-data">
	    <input type = "file" id = "SDFile" name = "SDFile" style = "display: none" onchange="uploadSDFile()" multiple size= "1">
      <input type = "file" id = "CDFile" name = "CDFile" style = "display: none" onchange="uploadCDFile()" multiple size= "1">
      <input type = "submit" id = "SDSubmit" name = "SDSubmit" style = "display: none">
      <input type = "submit" id = "CDSubmit" name = "CDSubmit" style = "display: none">
    </form>
    <table>
      <tbody>
        <tr>
          <td id = "uploadSD" class = "uploadButton" width="50%">
			    <img src="Image/Sequence Diagram Upload.png" width="100" height="100" alt=""/></br>
            Sequence Diagram</td>
          <td id = "uploadCD" class = "uploadButton"><img src="Image/Class Diagram Upload.png" width="100" height="100" alt=""/></br>
            Class Diagram</td>
        </tr>
      </tbody>
    </table>
	<p align="center">XML must be generated from Visual Paradigm</p>
  </div>
</div>
<!-- Create Code Modal -->
<div id="createCodeModal" class="createCodeModal">
  <div class = "createCodeModal-header"><span class="close">&times;</span>
    <h3>Set Code Property</h3>
    <div id = "SelectDiagram">
          <?php
            require "Page/SetCodeProperties.php";
            initialDatabase();
            initialSDSelect();
            initialCDSelect();
            initialClassSelect();
          ?>  
      </div>
      <div id = "SetProperties">
        <form id = "codeProperties">
          <h4>Filename</h4>
          <input type = "text" name = "filename" id = "filename">
          <h4>Select Source Code Type</h4>
          <input type = "radio" name = "sourceCodeType" id = "stub" value = "stub" checked = "checked"> Stub</br>
          <input type = "radio" name = "sourceCodeType" id = "driver" value = "driver"> Driver</br>
          <h4>Select Source Code Language</h4>
          <input type = "radio" name = "sourceCodeLang" id = "Java" value = "Java" checked = "checked"> Java</br>
          <input type = "radio" name = "sourceCodeLang" id = "PHP" value = "PHP"> PHP</br></br>
        </form>
        <button id = "createBtn" onclick="createCode()">Create Code</button>
      </div> 
  </div>
</div>
<footer>2018 Copyright &copy; Department of Computer Engineering<br/>
  Faculty of Engineering, Chulalongkorn University</footer>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="Script/Main.js"></script>
</body>
</html>
