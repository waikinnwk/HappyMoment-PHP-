<?php require_once 'common/common.php'; ?>

<?php 
	$classList = getAllClassList();
		

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<link rel="stylesheet" href="css/common.css" media="screen, projection" />
	<script type="text/javascript" src="js/common.js"></script>
	<script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
	<script type="text/javascript" src="js/tabcontent.js"></script>
	<link href="css/tabcontent.css" rel="stylesheet" type="text/css" />
	<link rel="shortcut icon" href="images/hm_pink03.png"/>
	<title>Happy Moment</title>
	<script type="text/javascript">
	

	 
	 function back(){
		window.location = "http://" + "<?php echo $_SERVER['HTTP_HOST']."/cake.php";?>";
	 }
	 function openForm(classId){
		window.open("http://" + "<?php echo $_SERVER['HTTP_HOST']."/applyClass.php?eventId=";?>" + classId);
	 }

	</script>
</head>
<body>
<?php require_once 'cMenuBar.php'; ?>
<div class="notebox">
	<font size="6" face="chaoganghei">報名</font>
	<table border="1">
	<tr>
		<td><font size="4" face="chaoganghei">課程編號</font></td>
		<td><font size="4" face="chaoganghei">名稱</font></td>
		<td><font size="4" face="chaoganghei">日期</font></td>
		<td><font size="4" face="chaoganghei">課堂名額</font></td>
		<td><font size="4" face="chaoganghei">尚餘名額</font></td>
		<td><font size="4" face="chaoganghei">費用(每人)</font></td>
		<td></td>
	</tr>
	<?php
	foreach ($classList as $class)
	{
		echo "<tr><td><font size=\"4\" face=\"chaoganghei\">".$class['classId']."</font></td>";
		echo "<td><font size=\"4\" face=\"chaoganghei\">".$class['title']."</font></td>";
		echo "<td><font size=\"4\" face=\"chaoganghei\">".$class['date']."  ".$class['time']."</font></td>";
		echo "<td><font size=\"4\" face=\"chaoganghei\">".$class['quota']."</font></td>";
		echo "<td><font size=\"4\" face=\"chaoganghei\">".$class['quotaLeft']."</font></td>";
		echo "<td><font size=\"4\" face=\"chaoganghei\">$ ".$class['price']."</font></td>";
		echo "<td>";
		if( $class['quotaLeft'] > 0){
			echo "<div class=\"buttonsarea\"><input style=\"font-family: chaoganghei;\" type=\"button\" value=\"報名\" onclick=\"openForm('".$class['classId']."')\"/></div>";
		}
		echo "</td>";
		echo "</tr>";
	}
	?>
	</table>
	<div class="buttonsarea">
		<input style="font-family: chaoganghei;" value="返回!" type="button" onclick="back();"/>
	</div>
</div>
</body>
</html>