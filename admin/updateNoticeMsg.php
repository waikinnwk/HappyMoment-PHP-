<?php 
require_once 'adminCommon.php';
require_once '../common/common.php';
$sysValue = getSysValue('NoticeMsg');
$afterSave = false;
if(isset($_POST['value']) && $_POST['value'] != '' && isset($_POST['status']) && $_POST['status'] != ''){
	updateSysValue('NoticeMsg',$_POST['value'],$_POST['status']);
	$sysValue = getSysValue('NoticeMsg');
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
<form action='updateNoticeMsg.php' method='POST' id="updateNoticeMsg">
<div class="formarea">
	<h1>Notice Msg</h1>
	<table border="1">

	<tr><td>Notice Msg</td><td><input id="value" name="value" type="text" maxlength="300" style="width:600px" value="<?php echo $sysValue['value']; ?>" /></td></tr>
	<tr><td>status</td><td><input type="radio" id="status" name="status" value="A" <?php if($sysValue['status'] == 'A') echo "checked";?>> Show  <br>
						<input type="radio" id="status" name="status" value="H" <?php if($sysValue['status'] == 'H') echo "checked";?>> Hide<br></td></tr>
</table>
<input type="button" value="Submit" onclick="formSubmit('updateNoticeMsg')">
</div>
</form>
</body>
</html>