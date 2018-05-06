<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Source Code Manager</title>
<link rel = "stylesheet" href = "CSS/Source code Manager.css">
</head>

<body>
    <header>
        <h1>Stubs and Drivers Generator Tool</h1>
    </header>
    <article>
         <!-- File List -->
        <table id = "fileTable">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>File Name</th>
                    <th>File Type</th>
                    <th>Created Date</th>
                </tr>
            </thead>
        </table>
        <!-- File Management Panel -->
        <div id = "fileMgr">
                <button id = "copyBtn"><img src = "Image/copy.png">Duplicate</button>
                <button id = "deleteBtn"><img src = "Image/delete.png">Delete</button>
                <button id = "renameBtn"><img src = "Image/rename.png">Rename</button>
                <button id = "editBtn"><img src = "Image/edit.png">Edit</button>
		        <button id = "exportBtn"><img src = "Image/export.png">Export</button>
		       
            </div>
    </article>
    <footer>2018 Copyright &copy; Department of Computer Engineering<br/>
        Faculty of Engineering, Chulalongkorn University</footer>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="Script/Source code Manager.js"></script>
</body>
</html>
