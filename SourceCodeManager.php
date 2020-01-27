<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Source Code Manager</title>
<link rel = "stylesheet" href = "css/SourceCodeManager.css">
</head>

<body>
    <header>
        <h1>Stubs and Drivers Generator Tool</h1>
    </header>
    <article>
         <!-- File List -->
        <table id = "fileTable">
            <thead>
                <tr id="fileListTableHeader">
                    <th>Item</th>
                    <th>File Name</th>
                    <th>File Type</th>
                    <th>Language</th>
                    <th>Created Timestamp</th>
                    <th>Last Update Timestamp</th>
                </tr>
            </thead>
        </table>
        <!-- File Management Panel -->
        <div id = "fileMgr">
                <button id = "copyBtn" onclick = "duplicateFile()"><img src = "Image/copy.png">Duplicate</button>
                <button id = "deleteBtn" onclick = "deleteFile()"><img src = "Image/delete.png">Delete</button>
                <button id = "renameBtn" onclick = "showRenameDialog()"><img src = "Image/rename.png">Rename</button>
                <button id = "editBtn" onclick = "editFile()"><img src = "Image/edit.png">Edit</button>
		        <button id = "exportBtn" onclick = "exportFile()"><img src = "Image/export.png">Export</button>
		       
            </div>
    </article>
<div id = "renameModal" class = "modal">
  <div class = "modal-content"><span class="close">&times;</span>
    <h4>Rename</h4>
    <div align ="center">
        Please enter new filename<input type = "text" name = "filename" id = "filename" >
        <button id = "renameConfirm" onclick = "renameFile()">Rename</button>
    </div>
  </div>
</div>
    <footer>2018 Copyright &copy; Department of Computer Engineering<br/>
        Faculty of Engineering, Chulalongkorn University</footer>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="js/sourcecodemanager/SourceCodeManagerProperties.js"></script>
    <script src="js/sourcecodemanager/SourceCodeManagerStyle.js"></script>
    <script src="js/sourcecodemanager/SourceCodeManagerFunction.js"></script>
</body>
</html>
