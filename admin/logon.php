<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<link rel="stylesheet" href="../css/addForm.css" media="screen, projection" />
	<script type="text/javascript" src="../js/common.js"></script>
</head>
<body>
<?php
	if(isset($_GET['result']) && $_GET['result'] == 'f'){
		echo "<script type=\"text/javascript\">alert('Logon Fail')</script>";
	}
 ?>
<form action='authentication.php' method='POST' id="logon">
	<div align="center">
	<h1>Admin Page</h1>
	<table>
		<tr><td>Login :</td><td><input type="text" name="login" id="login"></td></tr>
		<tr><td>Password :</td><td><input type="password" name="pwd" id="pwd"></td></tr>
		<tr>
			<td></td>
			<td><input type="button" value="Logon" onclick="formSubmit('logon');"><input type="reset" value="Clear"></td>
		</tr>
	</table>
	</div>
</form>	
</body>
</html>