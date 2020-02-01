<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Create Code</title>
<link rel="stylesheet" href="css/CreateCode.css">
</head>
<body>
	<header>
		<h1>Stubs and Drivers Generator Tool</h1>
	</header>
	<article>
		<div id="fileInfo">
			Filename: <input type="text" name="filename" id="filenameTextArea">
			<button id="renameBtn" class="UIButton" onclick="rename()">
				<img src="Image/rename.png">Rename
			</button>
			<button id="exportBtn" class="UIButton" onclick="exportFile()">
				<img src="Image/export.png">Export
			</button>
			<button id="saveBtn" class="UIButton" onclick="saveChange()">
				<img src="Image/save.png">Save
			</button>
		</div>
		<div id="editor">
			<textarea id="codeEditorTextArea"></textarea>
		</div>
		<div id="insert">
			<button id="genMaxValueBtn" class="UIButton" onclick="showMaxModal()">Max
				Value</button>
			<button id="genMinValueBtn" class="UIButton" onclick="showMinModal()">Min
				Value</button>
			<button id="genRandomStringBtn" class="UIButton"
				onclick="showRandomStringModal()">Random String</button>
			<button id="genRandomIntegerBtn" class="UIButton"
				onclick="showRandomIntegerModal()">Random Integer</button>
			<button id="genRandomDecimalBtn" class="UIButton"
				onclick="showRandomDecimalModal()">Random Decimal</button>
		</div>
		<div id="generateMaxValueModal" class="modal">
			<div id="generateMaxValueModalContent" class="modal-content">
				<span class="close">&times;</span>
				<h3>Generate Max Value</h3>
				<div align="center">
					<button class="modalButton" onclick="getMaxFloat()">Max Float</button>
					<button class="modalButton" onclick="getMaxDouble()">Max Double</button>
					<button class="modalButton" onclick="getMaxInteger()">Max Integer</button>
					<button class="modalButton" onclick="getMaxByte()">Max Byte</button>
					<button class="modalButton" onclick="getMaxShort()">Max Short</button>
					<button class="modalButton" onclick="getMaxLong()">Max Long</button>
				</div>
			</div>
		</div>
		<div id="generateMinValueModal" class="modal">
			<div id="generateMinValueModalContent" class="modal-content">
				<span class="close">&times;</span>
				<h3>Generate Max Value</h3>
				<div align="center">
					<button class="modalButton" onclick="getMinFloat()">Min Float</button>
					<button class="modalButton" onclick="getMinDouble()">Min Double</button>
					<button class="modalButton" onclick="getMinInteger()">Min Integer</button>
					<button class="modalButton" onclick="getMinByte()">Min Byte</button>
					<button class="modalButton" onclick="getMinShort()">Min Short</button>
					<button class="modalButton" onclick="getMinLong()">Min Long</button>
				</div>
			</div>
		</div>
		<div id="generateRandomStringModal" class="modal">
			<div id="generateRandomStringModalContent"
				class="modal-content">
				<span class="close">&times;</span>
				<h3>Generate Random String</h3>
				<div align="center">
					<h4>Random any string</h4>
					<button class="modalButton" onclick="randomAnyString()">Random</button>
				</div>
				<div align="center">
					<h4>Random string with specific length</h4>
					Length: <input type="text" class="randomCriteriaTextBox"
						name="stringLength" id="stringLengthTextArea">
					<button class="modalButton" onclick="randomStringWithLength()">Random</button>
				</div>
			</div>
		</div>
		<div id="generateRandomIntegerModal" class="modal">
			<div id="generateRandomIntegerModalContent" class="modal-content">
				<span class="close">&times;</span>
				<h3>Generate Random Integer</h3>
				<div align="center">
					<h4>Random integer with bound</h4>
					<p>Leave the bounds blanked for unspecific bound</p>
					Min: <input type="text" class="randomCriteriaTextBox" name="minInt"
						id="minIntTextArea"> Max: <input type="text"
						class="randomCriteriaTextBox" name="maxInt" id="maxIntTextArea">
					<button class="modalButton" onclick="randomIntegerWithBound()">Random</button>
				</div>
				<div align="center">
					<h4>Random integer with specific digits</h4>
					Digit(s): <input type="text" class="randomCriteriaTextBox"
						name="intDigit" id="intDigitTextArea">
					<button class="modalButton" onclick="randomIntegerWithLength()">Random</button>
				</div>
			</div>
		</div>
		<div id="generateRandomDecimalModal" class="modal">
			<div id="generateRandomDecimalModalContent"
				class="modal-content">
				<span class="close">&times;</span>
				<h4>Generate Random Decimal</h4>
				<div align="center">
					<h4>Random decimal with bound</h4>
					<p>Leave the bounds blanked for unspecific bound</p>
					Min: <input type="text" class="randomCriteriaTextBox"
						name="minDecimal" id="minDecimalTextArea"> Max: <input type="text"
						class="randomCriteriaTextBox" name="maxDecimal"
						id="maxDecimalTextArea">
					<button class="modalButton" onclick="randomDecimalWithBound()">Random</button>
				</div>
				<div align="center">
					<h4>Random decimal with specific length</h4>
					Fraction digit(s): <input type="text" class="randomCriteriaTextBox"
						name="fractalLength" id="fractalLengthTextArea"> Decimal digit(s): <input
						type="text" class="randomCriteriaTextBox" name="decimalLength"
						id="decimalLengthTextArea"><br>
					<button class="modalButton" onclick="randomDecimalWithLength()">Random</button>
				</div>
			</div>
		</div>
	</article>
	<footer>
		2018 Copyright &copy; Department of Computer Engineering<br /> Faculty
		of Engineering, Chulalongkorn University
	</footer>
	<script
		src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="js/codeeditor/CodeEditorProperties.js"></script>
	<script src="js/codeeditor/CodeEditorStyle.js"></script>
	<script src="js/codeeditor/CodeEditorFunction.js"></script>
	<script src="js/utilities/Randomizer.js"></script>
	<script src="js/utilities/GetConstant.js"></script>
</body>
</html>
