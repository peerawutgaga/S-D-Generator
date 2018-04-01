<?php
     if(isset($_POST['option'])){
        switch($_POST['option']){
            case 'range':
                addRangeOption();
                break;
            case 'length':
                addLengthOption();
                break;
            case 'clear':
                initialRandomOptionDiv();
                break;
            default:
                initialRandomOptionDiv();
        }
    }
    function initialModal($name){
        echo "<div id=\"".$name."ValueModal\" class=\"modal\">\n";
        echo"<div class=\"modal-content\"> <span class=\"close\">&times;</span>\n";
        echo "<h3 align = 'left'>Insert ".$name." value</h3>\n";
        echo "<h4 align = \"center\">Select Datatype</h4>\n";
        if($name == 'random'){
            initialRandomDataTypeSelect();
            initialRandomOptionDiv();
        }else{
            initialDataTypeSelect($name);     
        }
        initialButton($name);
        closeModalDiv();
    }
    function initialRandomDataTypeSelect(){
        echo "<select id = 'randomDataTypeSelect' onchange = showOption(this.value)>\n";
        initialOptions();
    }
    function initialDataTypeSelect($name){
        echo "<select id = '".$name."DataTypeSelect'>\n";
        initialOptions();   
    }
    function initialOptions(){
        echo "<option value = '0' selected disabled hidden>Please Select Data Type</option>\n";
        echo "<option value = 'byte'>byte</option>\n";
        echo "<option value = 'short'>short</option>\n";
        echo "<option value = 'int'>int</option>\n";
        echo "<option value = 'long'>long</option>\n";
        echo "<option value = 'float'>float</option>\n";
        echo "<option value = 'double'>double</option>\n";
        echo "<option value = 'boolean'>boolean</option>\n";
        echo "<option value = 'char'>char</option>\n";
        echo "<option value = 'string'>string</option>\n";
        echo "<option value = 'object'>object</option>\n";
        echo "</select>\n";
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
        echo "From: ";
        echo "<input type = \"text\" name = \"minimum\" id = \"minimum\" >";
        echo "To: ";
        echo "<input type = \"text\" name = \"maximum\" id = \"maximum\" >";
        echo "</div>\n";
    }
    function addLengthOption(){
        echo "<div id = 'randomOption' text-align = 'left'>\n";
        echo "Length: ";
        echo "<input type = \"text\" name = \"length\" id = \"length\" >";
        echo "</div>\n";
    }
?>