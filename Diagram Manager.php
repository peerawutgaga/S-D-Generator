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
            <button class = "commandBtn" id = "deleteBtn"><img src = "Image/delete.png">Delete</button>
        </div>
    </article>
    <footer>2018 Copyright &copy; Department of Computer Engineering
        <br/> Faculty of Engineering, Chulalongkorn University</footer>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="Script/Diagram Manager.js"></script>
</body>
</html>
