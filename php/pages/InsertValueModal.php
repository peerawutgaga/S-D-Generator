<?php
     if(isset($_POST['option'])){
        switch($_POST['option']){
            case 'range':
                addRangeOption();
                break;
            case 'length':
                addLengthOption();
                break;
            case 'both':
                addBothOption();
                break;
            case 'clear':
                initialRandomOptionDiv();
                break;
            default:
                initialRandomOptionDiv();
        }
    }
    if(isset($_POST['language'])&&isset($_POST['modal'])){
        if($_POST['language'] == 'java'){
            initialDataTypeSelect($_POST['modal']);
            initialOptionsJava($_POST['modal']);
            echo "</select>"; 
        }else{
            initialDataTypeSelect($_POST['modal']);
            initialOptionsPHP($_POST['modal']);
            echo "</select>"; 
        }
    }
    function initialModal($language,$name){
        echo "<div id=\"".$name."ValueModal\" class=\"modal\">\n";
        echo"<div class=\"modal-content\"> <span class=\"close\">&times;</span>\n";
        echo "<h3 align = 'left'>Insert ".$name." value</h3>\n";
        echo "<h4 align = \"center\">Select Datatype</h4>\n";
        initialDataTypeSelect($name);
        if($language == 'java'){
            initialOptionsJava($name);
        }else{
            //TODO Echoo warning when called. This will be disable
            //initialOptionsPHP($name);
        }
        echo "</select>"; 
        if($name == "random"){
            initialRandomOptionDiv();
        }
        initialButton($name);
        closeModalDiv();       
    }
    
    function initialDataTypeSelect($name){
        if($name == "random"){
            echo "<select id = 'randomDataTypeSelect' onchange = showOption(this.value)>\n";
        }else{
            echo "<select id = '".$name."DataTypeSelect'>\n";
        }
    }
    function initialOptionsJava($name){
        echo "<option value = '0' selected disabled hidden>Please Select Data Type</option>\n";
        echo "<option value = 'byte'>byte</option>\n";
        echo "<option value = 'short'>short</option>\n";
        echo "<option value = 'int'>int</option>\n";
        echo "<option value = 'long'>long</option>\n";
        echo "<option value = 'float'>float</option>\n";
        echo "<option value = 'double'>double</option>\n";
        echo "<option value = 'char'>char</option>\n";
        if($name == "random"){
            echo "<option value = 'string'>string</option>\n";
        }
        else if($name == "default"){
            echo "<option value = 'string'>string</option>\n";
            echo "<option value = 'boolean'>boolean</option>\n";
            echo "<option value = 'object'>object</option>\n";
        }
    }
    function initialOptionsPHP($name){
        echo "<option value = '0' selected disabled hidden>Please Select Data Type</option>\n";
        echo "<option value = 'int'>int</option>\n";
        echo "<option value = 'float'>float</option>\n";
        if($name == "random"){
            echo "<option value = 'string'>string</option>\n";
        }
        else if($name == "default"){
            echo "<option value = 'string'>string</option>\n";
            echo "<option value = 'boolean'>boolean</option>\n";
            echo "<option value = 'object'>object</option>\n";
        }
    }
    function initialRandomOptionDiv(){
        echo "<div id = 'randomOption'>\n";
        echo "</div>\n";
    }
    function initialButton($name){
        echo "<button id = 'insert".$name."Btn' class=\"modalButton\">Insert</button>\n";
    }
    function closeModalDiv(){
        echo "</div>\n";
        echo "</div>\n";
    }
    function addRangeOption(){
        echo "<div id = 'randomOption' text-align = 'left'>\n";
        echo "<form id = 'rangeForm'>\n";
        echo "From: ";
        echo "<input type = \"text\" name = \"minimumBox\" id = \"minimumBox\" >";
        echo "To: ";
        echo "<input type = \"text\" name = \"maximumBox\" id = \"maximumBox\" >";
        echo "</form>\n";
        echo "</div>\n";
    }
    function addLengthOption(){
        echo "<div id = 'randomOption' text-align = 'left'>\n";
        echo "<form id = 'lengthForm'>\n";
        echo "Length: ";
        echo "<input type = \"text\" name = \"lengthBox\" id = \"lengthBox\" >";
        echo "</form>\n";
        echo "</div>\n";
    }
    function addBothOption(){
        echo "<div id = 'randomOption' text-align = 'left'>\n";
        echo "<form id = 'bothForm'>\n";
        echo "From: ";
        echo "<input type = \"text\" name = \"minimumBox\" id = \"minimumBox\" >";
        echo "To: ";
        echo "<input type = \"text\" name = \"maximumBox\" id = \"maximumBox\" >";
        echo "Float length: ";
        echo "<input type = \"text\" name = \"lengthBox\" id = \"lengthBox\" >";
        echo "</form>\n";
        echo "</div>\n";
    }
?>