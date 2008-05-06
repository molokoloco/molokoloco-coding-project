<?php
if(isset($_POST['submit'])){
	require_once('clsMsDocGenerator.php');
		
	$titleFormat = array(
							'text-align' 	=> 'center',
							'font-weight' 	=> 'bold',
							'font-size'		=> '18pt',
							'color'			=> 'blue');
	
	$doc = new clsMsDocGenerator($_POST['pageOrientation'][0], $_POST['pageType'][0], '', $_POST['top'][0], $_POST['right'][0], $_POST['bottom'][0], $_POST['left'][0]);
	
	$doc->isDebugging = $_POST['isDebugging'] == 'true' ? true : false;
	
	$doc->addParagraph('sample I', $titleFormat);
	$doc->addParagraph('this is the first paragraph');
	$doc->addParagraph('this is the paragraph in justified style', array('text-align' => 'justify'));
	
	$doc->newSession($_POST['pageOrientation'][1], $_POST['pageType'][1], $_POST['top'][1], $_POST['right'][1], $_POST['bottom'][1], $_POST['left'][1]);
	$doc->addParagraph('paragraph in second session', array('color' => '#FF5566', 'background-color' => '#DEDEDE'));
	
	$doc->newSession($_POST['pageOrientation'][2], $_POST['pageType'][2], $_POST['top'][2], $_POST['right'][2], $_POST['bottom'][2], $_POST['left'][2]);
	$doc->addParagraph('paragraph in third session', array('color' => '#FF5566', 'background-color' => '#DEDEDE'));
	
	$doc->output();
	exit();
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>sample</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
body{
	text-align: center;
}
form{
	width: 465px;
	margin-left: auto;
	margin-right: auto;
	border: solid 1px #000000;
	padding: 10px;
	font-size: 12px;
	font-family: Arial;
	text-align: left;
}
.fieldContainer{
	margin-bottom: 2em;
}
.fieldInputContainer{
	text-align: center;
}
</style>
</head>

<body>
<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
	<div class="fieldContainer">
		<label for="isDebugging">isDebugging:</label>
		<select name="isDebugging" id="isDebugging">
			<option value="true">true</option>
			<option value="false">false</option>
		</select>
	</div>
	<div class="fieldContainer">
		<label for="pageOrientation[0]">page Orientation:</label>
		<select name="pageOrientation[0]" id="pageOrientation[0]">
			<option value="PORTRAIT">PORTRAIT</option>
			<option value="LANDSCAPE">LANDSCAPE</option>
		</select>
		<label for="pageType[0]">page Type:</label>
		<select name="pageType[0]" id="pageType[0]">
			<option value="A4">A4</option>
			<option value="A5">A5</option>
			<option value="LETTER">LETTER</option>
			<option value="OFFICE">OFFICE</option>
		</select><br />
		<label for="top[0]">top margin:</label>
		<input type="text" name="top[0]" id="top[0]" value="3.0">
		<label for="right[0]">right margin:</label>
		<input type="text" name="right[0]" id="right[0]" value="2.5">
		<label for="bottom[0]">bottom margin:</label>
		<input type="text" name="bottom[0]" id="bottom[0]" value="3.0">
		<label for="left[0]">left margin:</label>
		<input type="text" name="left[0]" id="left[0]" value="2.5">
	</div>
	<div class="fieldContainer">
		<label for="pageOrientation[1]">page Orientation:</label>
		<select name="pageOrientation[1]" id="pageOrientation[1]">
			<option value="LANDSCAPE">LANDSCAPE</option>
			<option value="PORTRAIT">PORTRAIT</option>
		</select>
		<label for="pageType[1]">page Type:</label>
		<select name="pageType[1]" id="pageType[1]">
			<option value="OFFICE">OFFICE</option>
			<option value="A4">A4</option>
			<option value="A5">A5</option>
			<option value="LETTER">LETTER</option>
		</select><br />
		<label for="top[1]">top margin:</label>
		<input type="text" name="top[1]" id="top[1]" value="1.0">
		<label for="right[1]">right margin:</label>
		<input type="text" name="right[1]" id="right[1]" value="1.5">
		<label for="bottom[1]">bottom margin:</label>
		<input type="text" name="bottom[1]" id="bottom[1]" value="1.0">
		<label for="left[1]">left margin:</label>
		<input type="text" name="left[1]" id="left[1]" value="1.5">
	</div>
	<div class="fieldContainer">
		<label for="pageOrientation[2]">page Orientation:</label>
		<select name="pageOrientation[2]" id="pageOrientation[2]">
			<option value="PORTRAIT">PORTRAIT</option>
			<option value="LANDSCAPE">LANDSCAPE</option>
		</select>
		<label for="pageType[2]">page Type:</label>
		<select name="pageType[2]" id="pageType[2]">
			<option value="A5">A5</option>
			<option value="A4">A4</option>
			<option value="LETTER">LETTER</option>
			<option value="OFFICE">OFFICE</option>
		</select><br />
		<label for="top[2]">top margin:</label>
		<input type="text" name="top[2]" id="top[2]" value="1.0">
		<label for="right[2]">right margin:</label>
		<input type="text" name="right[2]" id="right[2]" value="1.5">
		<label for="bottom[2]">bottom margin:</label>
		<input type="text" name="bottom[2]" id="bottom[2]" value="1.0">
		<label for="left[2]">left margin:</label>
		<input type="text" name="left[2]" id="left[2]" value="1.5">
	</div>
	
	<div class="fieldInputContainer">
		<input type="submit" name="submit" value="generate">
	</div>
</form>
</body>
</html>