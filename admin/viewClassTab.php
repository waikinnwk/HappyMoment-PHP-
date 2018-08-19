<?php 
require_once 'adminCommon.php';
require_once '../common/common.php';

$tabList = getCakeClassTab();
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
		function editClassTab(id){
			window.location = "http://" + "<?php echo $_SERVER['HTTP_HOST']."/admin/editClassTab.php?id="?>" +  id;
		}
		function deleteClassTab(id){
			if(confirm("Are you sure ?"))
				window.location = "http://" + "<?php echo $_SERVER['HTTP_HOST']."/admin/deleteClassTab.php?id="?>" +  id;
		}	
		function orderTabGroup(id){
			window.location = "http://" + "<?php echo $_SERVER['HTTP_HOST']."/admin/orderGroup.php?id="?>" +  id;
		}		
	</script>
</head>
<body>
<?php require_once 'menu_bar.php'; ?>
<h3>Class Tab List : </h3>
<br>
<div class="listViewAdmin" >
<table>

	<tr><td>Tab Name</td><td></td></tr>
<?php
	foreach ($tabList as $tab)
	{
		echo "<tr><td>".$tab["name"]."</td>";
		echo "<td><input type=\"button\" value=\"Edit\" onclick=\"editClassTab('".$tab["id"]."'); \" />";
		echo "<input type=\"button\" value=\"Delete\" onclick=\"deleteClassTab('".$tab["id"]."'); \" />";
		echo "<input type=\"button\" value=\"Order Tab Group\" onclick=\"orderTabGroup('".$tab["id"]."'); \" />";
		echo "</td></tr>"; 
	}
?>
</table>
</div>
</body>
</html>