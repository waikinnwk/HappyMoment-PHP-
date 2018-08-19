<?php
	require_once 'adminCommon.php';
	require_once '../common/common.php';
	if(isset($_GET['id']) && $_GET['id'] != ''){
		$tab = getCakeClassTabById($_GET['id']);
		
	}
	else if(isset($_POST['id']) && $_POST['id'] != '' && isset($_POST['tabName']) && $_POST['tabName'] != ''){
		updateCakeClassTab($_POST['id'],$_POST['tabName']);
		header('Location: http://' . $_SERVER['HTTP_HOST']."/admin/viewClassTab.php");
	}
	
?>
<head>
	<link rel="stylesheet" href="../css/DatePicker.css" media="screen, projection" />
	<link rel="stylesheet" href="../css/addForm.css" media="screen, projection" />
	<script type="text/javascript" src="../js/mootools.v1.11.js"></script>
	<script type="text/javascript" src="../js/DatePicker.js"></script>
	<script type="text/javascript" src="../js/common.js"></script>
	<script type="text/javascript">
		function back(){
			window.location = "http://" + "<?php echo $_SERVER['HTTP_HOST']."/admin/viewClassTab.php";?>";
		}
	</script>
	</script>
</head>
<body>

<?php require_once 'menu_bar.php'; ?>

<br>
<div class="formarea">
	<form action='editClassTab.php' method='POST' id="editClassTab">
		<h2>Edit Class Tab</h2>
		<table>
			<tr>
				<td><label for="eventTitle">Tab Name :</label></td>
				<td>
					<input type="text" id="tabName" name="tabName" value="<?php echo $tab["name"];  ?>"/>
					<input type="hidden" id="id" name="id" value="<?php echo $tab["id"]; ?>"/>
				</td>
			</tr>
			<tr>
			<td></td>
			<td>
				<div class="buttonsarea">
					<input type="button" value="Submit" onclick="formSubmit('editClassTab')">
					<input type="button" value="back" onclick="back();">
				</div>
			</td>
			</tr>		
		</table>		
	</form>
</div>
</body>
</html>