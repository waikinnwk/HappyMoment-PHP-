<?php 
require_once 'adminCommon.php';
require_once '../common/common.php';

$groupList = getAllCakeClassGroup();
?>
<html>
<head>
	<link rel="stylesheet" href="../css/DatePicker.css" media="screen, projection" />
	<link rel="stylesheet" href="../css/addForm.css" media="screen, projection" />
	<script type="text/javascript" src="../js/mootools.v1.11.js"></script>
	<script type="text/javascript" src="../js/DatePicker.js"></script>
	<script type="text/javascript" src="../js/common.js"></script>
	<script type="text/javascript">
		function editClassGroup(id){
			window.location = "http://" + "<?php echo $_SERVER['HTTP_HOST']."/admin/editCakeClassGroup.php?id="?>" +  id;
		}
		function deleteClassGroup(id){
			if(confirm("Are you sure ?"))
				window.location = "http://" + "<?php echo $_SERVER['HTTP_HOST']."/admin/deleteCakeClassGroup.php?id="?>" +  id;
		}
		function updateClassGroupStatus(id,status){
			if(confirm("Are you sure ?"))
				window.location = "http://" + "<?php echo $_SERVER['HTTP_HOST']."/admin/updateClassGroupStatus.php?id="?>" +  id + "&status=" + status;
		}		
	</script>
</head>
<body>
<?php require_once 'menu_bar.php'; ?>
<h3>Class Group List : </h3>
<br>
<div class="listViewAdmin" >
<table>

	<tr><td>Group Name</td><td>Tab Name</td><td>Description</td><td>Price</td><td></td><td></td></tr>
<?php
	foreach ($groupList as $gp)
	{
		echo "<tr><td>".$gp["name"]."</td><td>".$gp["tabName"]."</td><td>".$gp["desc"]."</td><td>".$gp["price"]."</td>";
		if($gp['status'] == 'H')
			echo "<td>Hide</td>";
		else
			echo "<td>Visible</td>";
		echo "<td><input type=\"button\" value=\"Edit\" onclick=\"editClassGroup('".$gp["id"]."'); \" /><input type=\"button\" value=\"Delete\" onclick=\"deleteClassGroup('".$gp["id"]."'); \" />";
		if($gp['status'] == 'H')
			echo "<input type=\"button\" value=\"Show\" onclick=\"updateClassGroupStatus('".$gp["id"]."','V'); \" />";
		else
			echo "<input type=\"button\" value=\"Hide\" onclick=\"updateClassGroupStatus('".$gp["id"]."','H'); \" />";		
		echo "</td></tr>"; 
	}
?>	
</table>
</body>
</html>