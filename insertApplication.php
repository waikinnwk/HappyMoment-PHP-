<?php
require_once 'common/common.php';
if (isset($_POST['eventId'])
		&&	isset($_POST['name'])  
		&&	isset($_POST['tel'])
		&&	isset($_POST['email'])
		&&	isset($_POST['nop'])		
		&&	isset($_POST['remarks'])) {
	$eventId = $_POST['eventId'];
	$name = $_POST['name'];
	$tel =  $_POST['tel'];
	$email = $_POST['email'];
	$nop =  $_POST['nop'];
	$remarks = $_POST['remarks'];
	if(isset($_POST['whatsapp'])){
		$whatsapp = $_POST['whatsapp'];
	}
	else{
		$whatsapp = 'N';
	}
	
	$applicationId = createNewApplication($eventId,$name,$tel,$email,$nop,$whatsapp,$remarks,"Y","N");
		
	 
}
else{
	header('Location: http://' . $_SERVER['HTTP_HOST']);
	
}
if($applicationId != ''){ ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<link rel="stylesheet" href="css/addForm.css" media="screen, projection" />
	<link rel="stylesheet" href="css/common.css" media="screen, projection" />
	<script type="text/javascript" src="js/common.js"></script>
</head>
<body>
<div class="formarea">
<table>
<?php if($applicationId != "-1"){ ?>
<tr>
	<td>
		你的申請已經成功提交，你的申請編號為: <?php echo $applicationId ?>
	</td>
</tr>
<tr>
	<td>
		系統電郵將於5分鐘內傳送到你所登記之電郵地址，
		<br>
		如5分鐘內未有收到，請檢查垃圾郵件或雜件箱，謝謝。
	</td>
</tr>
<?php } else { ?>
<tr>
	<td>
		課程尚餘名額不足，請選擇其他課堂日子，謝謝。
	</td>
</tr>
<?php } ?>
<tr>
	<td><a href=''>返回</a></td>
</tr>
</table>
</div>
</body>
</html>

<?php }
?>