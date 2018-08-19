<?php
	require_once 'adminCommon.php';
	require_once '../common/common.php';
	$tabList = getCakeClassTab();
	
	
	$groupName = "";
	$tabId = "";
	$albumId = "";
	$desc = "";
	$price = "";
	$showPrice = "";
	
	$errors = array('groupName' => '','tab'=> '','time' => '','albumId' => '');
	
	if(isset($_POST['groupName']) && isset($_POST['tab']) && isset($_POST['albumId'])){
		$error = false;
		
		if($_POST['groupName'] == ''){
			$error = true;
			$errors['groupName'] = "Group Name is required";
		}
		else
			$groupName = $_POST['groupName'];
		
		if($_POST['tab'] == ''){
			$error = true;
			$errors['tab'] = "Tab is required";
		}
		else
			$tabId  =  $_POST['tab'];

		if($_POST['albumId'] == ''){
			$error = true;
			$errors['albumId'] = "Album Id is required";
		}
		else
			$albumId = $_POST['albumId'];
			
		$desc = $_POST['desc'];
		$price = $_POST['price'];
		$showPrice = $_POST['showPrice'];
		
		if(!$error){
			createNewCakeClassGroup($groupName,$tabId,$albumId,$desc,$price,$showPrice);
			header('Location: http://' . $_SERVER['HTTP_HOST']."/admin/viewClassGroup.php");
		}
	}
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
</head>
<body>
<?php require_once 'menu_bar.php'; ?>
<div class="formarea">
	<form action='addClassGroup.php' method='POST' id="addClassGroup">
		<h2>Add Class Group</h2>
		<table>
			<tr>
				<td><label for="groupName">Group Name :</label></td>
				<td>
					<input type="text" id="groupName" name="groupName" value="<?php echo $groupName;?>"/>
					<p class="errorMsg"><?php echo $errors['groupName'] ?></p>
				</td>
			</tr>
			<tr>
				<td><label for="tab">Under which tab :</label></td>
				<td>
					<select id="tab" name="tab">
						<?php 
							foreach ($tabList as $tab)
							{
								
								echo "<option value='".$tab['id']."'";
								if($tab['id'] == $tabId)
									echo " selected ";
								echo ">".$tab['name']."</option>";

							}							
						?>
					</select>
					<p class="errorMsg"><?php echo $errors['tab'] ?></p>
				</td>
			</tr>
			<tr>
				<td><label for="albumId">Album Id :</label></td>
				<td>
					<input type="text" id="albumId" name="albumId" value="<?php echo $albumId;?>"/>
					<p class="errorMsg"><?php echo $errors['albumId'] ?></p>
				</td>
			</tr>
			<tr>
				<td><label for="desc">Description:</label></td>
				<td>
					<textarea id="desc" name="desc" cols="40" rows="6"><?php echo $desc;?></textarea>
				</td>
			</tr>				
			<tr>
				<td><label for="price">Price : </label></td>
				<td>
					<input type="text" id="price" name="price" value="<?php echo $price;?>"/>
				</td>
			</tr>	
			<tr>
				<td><label for="price">Show Price : </label></td>
				<td>
					<input type="radio" id="showPrice" name="showPrice" value="A" <?php if($showPrice == 'A' || $showPrice == '' ) echo "checked";?>> Show  <br>
					<input type="radio" id="showPrice" name="showPrice" value="H" <?php if($showPrice == 'H') echo "checked";?>> Hide  
				</td>
			</tr>				
			<tr>
			<td></td>
			<td>
				<div class="buttonsarea">
					<input type="button" value="Submit" onclick="formSubmit('addClassGroup')">
				</div>
			</td>
			</tr>		
		</table>		
	</form>
</div>
</body>
</html>