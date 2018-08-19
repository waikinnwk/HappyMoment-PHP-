<?php 
require_once 'adminCommon.php';
require_once '../common/common.php';
$sysValue = getSysValue('MainPage');
$afterSave = false;

if (isset($_FILES["file"]) && $_FILES["file"]["error"] == 0)  {
	if (file_exists("../images/MainPage.jpg")) {
      unlink("../images/MainPage.jpg");
    } 
     move_uploaded_file($_FILES["file"]["tmp_name"],"../images/MainPage.jpg");
    
}

if(isset($_POST['status']) && $_POST['status'] != ''){
	updateSysValue('MainPage','',$_POST['status']);
	$sysValue = getSysValue('MainPage');
	$afterSave = true;
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<link rel="stylesheet" href="../css/addForm.css" media="screen, projection" />
	<script type="text/javascript" src="../js/mootools.v1.11.js"></script>
	<script type="text/javascript" src="../js/common.js"></script>
	<script type="text/javascript">			
	<?php 
	if($afterSave){
		echo "alert(\"Save Sucessfully\")";
	}
	?>
	</script>
</head>
<body>
<?php require_once 'menu_bar.php'; ?>
<form action='updateMainPage.php' method='POST' id="updateMainPage" enctype="multipart/form-data">
<div class="formarea">
	<h1>Main Page</h1>
	<table border="1">
	<tr><td>Poster</td><td><input type="file" name="file" id="file"></td></tr>
	<tr><td>status</td><td><input type="radio" id="status" name="status" value="A" <?php if($sysValue['status'] == 'A') echo "checked";?>> Show  <br>
						<input type="radio" id="status" name="status" value="H" <?php if($sysValue['status'] == 'H') echo "checked";?>> Hide<br></td></tr>
</table>
<input type="button" value="Submit" onclick="formSubmit('updateMainPage')">
</div>
</form>
</body>
</html>