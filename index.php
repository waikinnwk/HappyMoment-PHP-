<?php 
require_once 'common/common.php';
$mainPageSysValue = getSysValue('MainPage');
if($mainPageSysValue['status'] == 'H'){
	header('Location: http://' . $_SERVER['HTTP_HOST']."/cake.php");
}
else{
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<?php require_once 'commonHeader.php'; ?>
</head>
<body>
<?php require_once 'cMenuBar.php'; ?>
<div class="notebox">
<table>
<tr>
<td>
	<table>
		<tr>
		<td><img src="/images/MainPage.jpg"/></td>
	</tr>
	</table>
</td>
</tr>
</table>
</div>


</body>
</html>
<?php } ?>