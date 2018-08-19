<?php 
require_once 'adminCommon.php';
require_once '../common/common.php';

$afterSave = false;

if(!isset($_POST['action']) || $_POST['action'] == ''){
	$tabs = getCakeClassTab();
}
else if(isset($_POST['action']) && $_POST['action'] == 'save'){
	$tabs = getCakeClassTab();
	foreach ($tabs as $tempTab)
	{
		$tmp = $tempTab["id"]."_seq";
		if(isset($_POST[$tmp])){
			updateTabSeq($tempTab['id'],$_POST[$tmp]);
		}
	}
	$tabs = getCakeClassTab();
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
<form action='orderTab.php' method='POST' id="orderTab">
<input type="hidden" id="action" name="action" value="save"/>
<div class="formarea">
	<h1>Order Tab</h1>
	<table border="1">

	<tr><td>Tab Name</td><td>Seq</td></tr>
<?php
	foreach ($tabs as $tab)
	{
		echo "<tr><td>".$tab["name"]."</td>";
?>

		<td>
				<select id="<?php echo $tab["id"] ?>_seq" name="<?php echo $tab["id"] ?>_seq">
					<option value="0" <?php if($tab["seq"] == '0') echo "selected"; ?> >0</option>
					<option value="1" <?php if($tab["seq"] == '1') echo "selected"; ?> >1</option>
					<option value="2" <?php if($tab["seq"] == '2') echo "selected"; ?> >2</option>
					<option value="3" <?php if($tab["seq"] == '3') echo "selected"; ?> >3</option>
					<option value="4" <?php if($tab["seq"] == '4') echo "selected"; ?> >4</option>
					<option value="5" <?php if($tab["seq"] == '5') echo "selected"; ?> >5</option>
					<option value="6" <?php if($tab["seq"] == '6') echo "selected"; ?> >6</option>
					<option value="7" <?php if($tab["seq"] == '7') echo "selected"; ?> >7</option>
					<option value="8" <?php if($tab["seq"] == '8') echo "selected"; ?> >8</option>
					<option value="9" <?php if($tab["seq"] == '9') echo "selected"; ?> >9</option>
					<option value="10" <?php if($tab["seq"] == '10') echo "selected"; ?> >10</option>
					<option value="11" <?php if($tab["seq"] == '11') echo "selected"; ?> >11</option>
					<option value="12" <?php if($tab["seq"] == '12') echo "selected"; ?> >12</option>
					<option value="13" <?php if($tab["seq"] == '13') echo "selected"; ?> >13</option>
					<option value="14" <?php if($tab["seq"] == '14') echo "selected"; ?> >14</option>
					<option value="15" <?php if($tab["seq"] == '15') echo "selected"; ?> >15</option>					
				</select>		
		</td>
<?php echo "</tr>";
	}
?>	
</table>
<input type="button" value="Submit" onclick="formSubmit('orderTab')">
</div>
</form>
</body>
</html>