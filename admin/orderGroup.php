<?php 
require_once 'adminCommon.php';
require_once '../common/common.php';


$afterSave = false;
if(isset($_GET['id']) && $_GET['id'] != ''){
	$tabId = $_GET['id'];
	$tab = getCakeClassTabById($tabId);
	$groupList = getCakeClassGroup($tabId);

}
else if(isset($_POST['id']) && $_POST['id'] != ''){
	$tabId = $_POST['id'];
	$tab = getCakeClassTabById($tabId);
	$groupList = getCakeClassGroup($tabId);
	foreach ($groupList as $gp)
	{
		$tmp = $gp["id"]."_seq";
		if(isset($_POST[$tmp])){
			updateClassGroupSeq($gp['id'],$_POST[$tmp]);
		}
	}
	$groupList = getCakeClassGroup($tabId);
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
<form action='orderGroup.php' method='POST' id="orderGroup">
<input type="hidden" id="id" name="id" value="<?php echo $tabId; ?>"/>
<div class="formarea">
	<h1>Order Group of Tab <?php echo $tab['name']; ?></h1>
	<table border="1">

	<tr><td>Group Name</td><td>status</td><td>Seq</td></tr>
<?php
	foreach ($groupList as $gp)
	{
		echo "<tr><td>".$gp["name"]."</td>";
		if($gp['status'] == 'H')
			echo "<td>Hide</td>";
		else
			echo "<td>Visible</td>";
?>

		<td>
				<select id="<?php echo $gp["id"] ?>_seq" name="<?php echo $gp["id"] ?>_seq">
					<option value="0" <?php if($gp["seq"] == '0') echo "selected"; ?> >0</option>
					<option value="1" <?php if($gp["seq"] == '1') echo "selected"; ?> >1</option>
					<option value="2" <?php if($gp["seq"] == '2') echo "selected"; ?> >2</option>
					<option value="3" <?php if($gp["seq"] == '3') echo "selected"; ?> >3</option>
					<option value="4" <?php if($gp["seq"] == '4') echo "selected"; ?> >4</option>
					<option value="5" <?php if($gp["seq"] == '5') echo "selected"; ?> >5</option>
					<option value="6" <?php if($gp["seq"] == '6') echo "selected"; ?> >6</option>
					<option value="7" <?php if($gp["seq"] == '7') echo "selected"; ?> >7</option>
					<option value="8" <?php if($gp["seq"] == '8') echo "selected"; ?> >8</option>
					<option value="9" <?php if($gp["seq"] == '9') echo "selected"; ?> >9</option>
					<option value="10" <?php if($gp["seq"] == '10') echo "selected"; ?> >10</option>
					<option value="11" <?php if($gp["seq"] == '11') echo "selected"; ?> >11</option>
					<option value="12" <?php if($gp["seq"] == '12') echo "selected"; ?> >12</option>
					<option value="13" <?php if($gp["seq"] == '13') echo "selected"; ?> >13</option>
					<option value="14" <?php if($gp["seq"] == '14') echo "selected"; ?> >14</option>
					<option value="15" <?php if($gp["seq"] == '15') echo "selected"; ?> >15</option>					
				</select>		
		</td>
<?php echo "</tr>";
	}
?>	
</table>
<input type="button" value="Submit" onclick="formSubmit('orderGroup')">
</div>
</form>
</body>
</html>