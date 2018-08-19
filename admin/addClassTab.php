<?php
require_once 'adminCommon.php';
require_once '../common/common.php';

if(isset($_POST['tabName'])){
	createNewCakeClassTab($_POST['tabName']);
	header('Location: http://' . $_SERVER['HTTP_HOST']."/admin/viewClassTab.php");
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<link rel="stylesheet" href="../css/DatePicker.css" media="screen, projection" />
	<link rel="stylesheet" href="../css/addForm.css" media="screen, projection" />
	<script type="text/javascript" src="../js/mootools.v1.11.js"></script>
	<script type="text/javascript" src="../js/DatePicker.js"></script>
	<script type="text/javascript" src="../js/common.js"></script>
</head>
<body>
<?php require_once 'menu_bar.php'; ?>

<br>
<div class="formarea">
	<form action='addClassTab.php' method='POST' id="addClassTab">
		<h2>Add Class Tab</h2>
		<table>
			<tr>
				<td><label for="tabName">Tab Name :</label></td>
				<td>
					<input type="text" id="tabName" name="tabName"/>
				</td>
			</tr>
			<tr>
			<td></td>
			<td>
				<div class="buttonsarea">
					<input type="button" value="Submit" onclick="formSubmit('addClassTab')">
				</div>
			</td>
			</tr>		
		</table>		
	</form>
</div>
</body>
</html>