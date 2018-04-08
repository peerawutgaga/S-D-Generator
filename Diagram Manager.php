<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Diagram Manager</title>
<link rel = "stylesheet" href = "CSS/Diagram Manager.css">
</head>

<body>
    <header>
        <h1>Stubs and Drivers Generator Tool</h1>
    </header>
    <article>
        <!-- Tab links -->
        <div class="tab">
            <button class="tablinks" onclick="openTable(event, 'CallGraph')" id="defaultOpen">Call Graph</button>
            <button class="tablinks" onclick="openTable(event, 'ClassDiagram')">Class Diagram</button>
        </div>
        <div id="CallGraph" class="tabcontent">
            <table>
                <thead>
                    <tr>
                        <th>File Name</th>
                        <th>Created Time</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div id="ClassDiagram" class="tabcontent">
            <table>
                <tr>
                    <th>File Name</th>
                    <th>File Size</th>
                    <th>Created Time</th>
                </tr>
            </table>
        </div>
        <!-- File Management Panel -->
        <div id = "fileMgr">
            <button class = "commandBtn" id = "cutBtn"><img src = "Image/cut.png">Cut</button>
		    <button class = "commandBtn" id = "copyBtn"><img src = "Image/copy.png">Copy</button>
		    <button class = "commandBtn" id = "pasteBtn"><img src = "Image/paste.png">Paste</button>
            <button class = "commandBtn" id = "deleteBtn"><img src = "Image/delete.png">Delete</button>
        </div>
    </article>
    <footer>2018 Copyright &copy; Department of Computer Engineering
        <br/> Faculty of Engineering, Chulalongkorn University</footer>
    <script src="Script/Diagram Manager.js"></script>
</body>
</html>
