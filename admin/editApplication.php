<?php
	require_once 'adminCommon.php';
	require_once '../common/common.php';
	
	$name = "";
	$tel = "";
	$whatsapp = "";
	$email = "";
	$nop = "";
	$remarks = "";
	
	$searchParam = "";
	$searchParamOrg = "";
	$orderBy = "";
	if(isset($_POST['searchParam'])){
		$searchParam = $_POST['searchParam'];
		$searchParamOrg = $searchParam;
		$searchParam =  str_replace("|","=",$searchParam);
		$searchParam =  str_replace(",","&",$searchParam);
	}	
	else if(isset($_GET['searchParam'])){
		$searchParam = $_GET['searchParam'];
		$searchParamOrg = $searchParam;
		$searchParam =  str_replace("|","=",$searchParam);
		$searchParam =  str_replace(",","&",$searchParam);
	}
	
	if(isset($_POST['orderBy'])){
		$orderBy = $_POST['orderBy'];
	}	
	else if(isset($_GET['orderBy'])){
		$orderBy = $_GET['orderBy'];
	}	
	
	$errors = array('name' => '','tel'=> '','email' => '','nop'=> '');
	$summayPage = false;
	if(isset($_POST['action']) && $_POST['action'] == 'editApplication' ){
		
		$applicationId = $_POST['applicationId'];
		$appDetail = getApplicationDetail($applicationId);
		$classDetail = getClassDetail(getClassIdByAppId($applicationId));
		
		$name = $_POST['name'];
		$tel =  $_POST['tel'];
		if(isset($_POST['whatsapp']))
			$whatsapp = $_POST['whatsapp'];
		else
			$whatsapp = "N";
		$email = $_POST['email'];
		$nop = $_POST['nop'];
		$remarks = $_POST['remarks'];
			
		$isErrors = false;
			
		if (strlen($name) == 0) {
			$errors['name'] = 'name is required';
			$isErrors = true;
		}			
			

			if (strlen($tel) == 0) {
				$errors['tel'] = 'tel is required';
				$isErrors = true;
			} else if (!preg_match("/^[0-9]{8}$/", $tel)) {
				$errors['tel'] = 'invalid tel';
				$isErrors = true;
			}

			if (strlen($email) == 0) {
				$errors['email'] = 'email is required';
				$isErrors = true;
			} else if ( !preg_match('/^(?:[\w\d]+\.?)+@(?:(?:[\w\d]\-?)+\.)+\w{2,4}$/i', $email)) {
				$errors['email'] = 'invalid email';
				$isErrors = true;
			}
			
			if(!is_numeric($nop) || $nop < 1 ){
				$errors['nop'] = 'Invalid No of People.';
				$isErrors = true;
			}
			
			
			if(!$isErrors){
				$summayPage = true;
			}
			
		}
		else if(isset($_POST['action']) && $_POST['action'] == 'back' ){
			$applicationId = $_POST['applicationId'];
			$name = $_POST['name'];
			$tel =  $_POST['tel'];
			if(isset($_POST['whatsapp']))
				$whatsapp = $_POST['whatsapp'];
			else
				$whatsapp = "N";
			$email = $_POST['email'];
			$nop = $_POST['nop'];
			$remarks = $_POST['remarks'];
			
			$classDetail = getClassDetail(getClassIdByAppId($applicationId));
		}
	else if(isset($_GET['applicationId']) && $_GET['applicationId'] != ''){
		$applicationId = $_GET['applicationId'];
		$appDetail = getApplicationDetail($applicationId);
		$classDetail = getClassDetail(getClassIdByAppId($applicationId));

		$name = $appDetail['name'];
		$tel = $appDetail['tel'];
		$whatsapp = $appDetail['whatsapp'];
		$email = $appDetail['email'];
		$nop = $appDetail['nop'];
		$remarks = $appDetail['remarks'];
		
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
	var searchParam = "<?php echo $searchParam ?>";
	function back(){
			document.getElementById("editApplication").action = "editApplication.php";
			document.getElementById("action").value = "back";
			formSubmit('editApplication');
		}	
	function backHome(){
			window.location = "http://" + "<?php echo $_SERVER['HTTP_HOST']."/admin/viewApplication.php"?>" + "?" + searchParam + "&orderBy=" + "<?php echo $orderBy ?>";
		}

	function resendEmail(applicationId){
			window.location = "http://" + "<?php echo $_SERVER['HTTP_HOST']."/admin/resendEmail.php?applicationId="?>" + applicationId + "&searchParam=" + "<?php echo $searchParamOrg?>" + "&orderBy=" + "<?php echo $orderBy ?>"; 
		}			
	</script>
</head>
<body>
<?php require_once 'menu_bar.php'; ?>
<div class="formarea">
<?php if(!$summayPage) {?>
	<form action='editApplication.php' method='POST' id="editApplication">
	<input type="hidden" name="applicationId" id="applicationId" value="<?php echo $applicationId ?>">
	<input type="hidden" name="action" id="action" value="editApplication">
	<input type="hidden" name="searchParam" id="searchParam" value="<?php echo $searchParamOrg ?>">
	<input type="hidden" name="orderBy" id="orderBy" value="<?php echo $orderBy ?>">
	<div class="subfieldsset">
	<table width="100%">
		<tr>
			<td>Class:</td>
			<td><?php echo $classDetail['classId']." ".$classDetail['title'] ?></td>
		</tr>
		<tr>
			<td>Date & Time:</td>
			<td><?php echo $classDetail['date']." ".$classDetail['time'] ?></td>
		</tr>
		<tr>
			<td>Quota:</td>
			<td><?php echo $classDetail['quota'] ?></td>
		</tr>
		<tr>
			<td>Quota Left:</td>
			<td><?php echo $classDetail['quotaLeft'] ?></td>
		</tr>		
		<tr>
			<td></td>
			<td><input type="button" value="Resend Confirmation Email" onclick="resendEmail('<?php echo $applicationId ?>')"></td>
		</tr>		
		<tr>
			<td><label for="name">Applicant Name:</label></td>
			<td>
				<input type="text" name="name" id="name" value="<?php echo $name ?>">
				<p class="errorMsg"><?php echo $errors['name'] ?></p>
			</td>
		</tr>
		<tr>
			<td><label for="tel">Tel No</label></td>
			<td>
				<input type="text" name="tel" id="tel" value="<?php echo $tel ?>">
				<p class="errorMsg"><?php echo $errors['tel'] ?></p>
			</td>
		</tr>
		<tr>
			<td></td>
			<td><input type="checkbox" name="whatsapp" id="whatsapp" value="Y" <?php if($whatsapp =='Y') echo 'checked' ?>>WhatsApp</td>
		</tr>
		<tr>
			<td><label for="email">Email</label></td>
			<td>
				<input type="text" name="email" id="email" value="<?php echo $email ?>">
				<p class="errorMsg"><?php echo $errors['email'] ?></p>
			</td>
		</tr>
		<tr>
			<td><label for="nop">No of Person(s)</label></td>
			<td>
				<input type="text" name="nop" id="nop" value="<?php echo $nop ?>">
				<p class="errorMsg"><?php echo $errors['nop'] ?></p>
			</td>
		</tr>		
		<tr>
			<td><label for="remarks">Remarks</label></td>
			<td><textarea id="remarks" name="remarks" cols="40" rows="6"><?php echo $remarks ?></textarea></td>
		</tr>
		<tr>
			<td></td>
			<td>
				<div class="buttonsarea">
				<input type="button" value="Update" onclick="formSubmit('editApplication')">
				<input type="button" onClick="backHome();" value="Back" />
				</div>
			</td>
		</tr>		
	</table>
	</div>
	</form>
<?php }  else {?>
	<form action='updateApplication.php' method='POST' id="editApplication">
	<input type="hidden" name="applicationId" id="applicationId" value="<?php echo $applicationId ?>">
	<input type="hidden" name="action" id="action" value="editApplication">
	<input type="hidden" name="searchParam" id="searchParam" value="<?php echo $searchParamOrg ?>">
	<input type="hidden" name="orderBy" id="orderBy" value="<?php echo $orderBy ?>">
	<div class="subfieldsset">
	<table width="100%">
		<tr>
			<td>Class:</td>
			<td><?php echo $classDetail['classId']." ".$classDetail['title'] ?></td>
		</tr>
		<tr>
			<td>Date & Time:</td>
			<td><?php echo $classDetail['date']." ".$classDetail['time'] ?></td>
		</tr>
		<tr>
			<td>Quota:</td>
			<td><?php echo $classDetail['quota'] ?></td>
		</tr>
		<tr>
			<td>Quota Left:</td>
			<td><?php echo $classDetail['quotaLeft'] ?></td>
		</tr>	
		<tr>
			<td><label for="name">Applicant Name:</label></td>
			<td>
				<?php echo $name ?>
				<input type="hidden" name="name" id="name" value="<?php echo $name ?>">
			</td>
		</tr>
		<tr>
			<td><label for="tel">Tel No</label></td>
			<td>
				<?php echo $tel ?>
				<input type="hidden" name="tel" id="tel" value="<?php echo $tel ?>">
			</td>
		</tr>
		<tr>
			<td></td>
			<td><input type="checkbox" name="whatsapp" id="whatsapp" value="Y" <?php if($whatsapp =='Y') echo 'checked' ?> disabled="true">WhatsApp
				<input type="hidden" name="whatsapp" id="whatsapp" value="<?php echo $whatsapp ?>">
			</td>
		</tr>
		<tr>
			<td><label for="email">Email</label></td>
			<td>
				<?php echo $email ?>
				<input type="hidden" name="email" id="email" value="<?php echo $email ?>">
			</td>
		</tr>
		<tr>
			<td><label for="nop">No of Person(s)</label></td>
			<td>
				<?php echo $nop ?>
				<input type="hidden" name="nop" id="nop" value="<?php echo $nop ?>">
			</td>
		</tr>		
		<tr>
			<td><label for="remarks">Remarks</label></td>
			<td><?php echo nl2br($remarks) ?>
				<input type="hidden" name="remarks" id="remarks" value="<?php echo $remarks ?>">
			</td>
		</tr>
		<tr>
			<td></td>
			<td>
				<div class="buttonsarea">
					<input type="button" value="Submit" onclick="formSubmit('editApplication')">
					<input type="button" onClick="back();" value="Back" />
				</div>
			</td>
		</tr>		
	</table>
	</div>
	</form>
<?php } ?>
</div>
</body>
</html>