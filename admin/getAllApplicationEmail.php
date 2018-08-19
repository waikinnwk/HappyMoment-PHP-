<?php
	require_once 'adminCommon.php';
	require_once '../common/common.php';
	
	$emailList = getAllApplicationEmail();
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
	<script type="text/javascript">
	</script>
</head>
<body>
<?php require_once 'menu_bar.php'; ?>
<div>
<h1>Email List:</h1>
<textarea rows="30" cols="250">
<?php
	$j = 0;
	foreach ($emailList as $email)
	{
	    if($j > 0)
			echo ",";
		echo $email;
		$j++;
	}
?>
</textarea>
</div>
</body>
<html>