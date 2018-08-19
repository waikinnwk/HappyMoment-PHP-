<?php
	require_once 'adminCommon.php';
	require_once '../common/common.php';
	
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
		if($searchParam == "")
			$orderBy = "orderBy=".$_POST['orderBy'];
		else
			$orderBy = "&orderBy=".$_POST['orderBy'];
	}	
	else if(isset($_GET['orderBy'])){
		if($searchParam == "")
			$orderBy = "orderBy=".$_GET['orderBy'];
		else
			$orderBy = "&orderBy=".$_GET['orderBy'];
	}		
	
	if(isset($_GET['applicationId']) && $_GET['applicationId'] != ''){
		$applicationId = $_GET['applicationId'];
		$appDetail = getApplicationDetail($applicationId);
		$classDetail = getClassDetail(getClassIdByAppId($applicationId));
	}
	else if(isset($_POST['applicationId']) && $_POST['applicationId'] != ''
	   && isset($_POST['classList']) && $_POST['classList'] != '') 
	 {
	 
		$applicationId = $_POST['applicationId'];
		$newClassId = $_POST['classList'];
		$appDetail = getApplicationDetail($applicationId);
		$classDetail = getClassDetail(getClassIdByAppId($applicationId));	
		
		if(isset($_POST['sendConfirmEmail']) && $_POST['sendConfirmEmail'] == 'Y')
			$newApplicationId = createNewApplication($newClassId,$appDetail['name'],$appDetail['tel'],$appDetail['email'],$appDetail['nop'],$appDetail['whatsapp'],$appDetail['remarks'],"Y","Y");
		else
			$newApplicationId = createNewApplication($newClassId,$appDetail['name'],$appDetail['tel'],$appDetail['email'],$appDetail['nop'],$appDetail['whatsapp'],$appDetail['remarks'],"N","Y");
		
		updateApplicationPrevId($newApplicationId,$applicationId);
		updateApplicationStatus($applicationId,"D",false);
		updateAdminGooEventDesc($classDetail['classId'],getClassIdByAppId($applicationId));
		header('Location: http://' . $_SERVER['HTTP_HOST']."/admin/viewApplication.php"."?".$searchParam.$orderBy );	
	}
	else{
		header('Location: http://' . $_SERVER['HTTP_HOST']."/admin/viewApplication.php"."?".$searchParam.$orderBy );	
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
	function back(){
		window.location = "http://" + "<?php echo $_SERVER['HTTP_HOST']."/admin/viewApplication.php"."?".$searchParam.$orderBy?>";
	}
	function changeClass(){
		var x = document.getElementById("classList").selectedIndex;
		var classId = document.getElementById("classList").options[x].value;	

		if(confirm('Are you sure to Change of this Application (<?php echo $applicationId ?>) to Class - ' + classId+ ' ?'))
			formSubmit('changeClass') 
		else
			return false;	
	}
	</script>
</head>
<body>
<?php require_once 'menu_bar.php'; ?>
<div class="formarea">
	<form action='changeClass.php' method='POST' id="changeClass">
	<input type="hidden" name="applicationId" id="applicationId" value="<?php echo $applicationId ?>">
	<input type="hidden" name="searchParam" id="searchParam" value="<?php echo $searchParamOrg ?>">
	<input type="hidden" name="orderBy" id="orderBy" value="<?php echo $orderBy ?>">
	<input type="hidden" name="action" id="action" value="changeClass">
	<div class="subfieldsset">
	<table width="100%">
		<tr>
			<td>Cuurent Class:</td>
			<td><?php echo $classDetail['classId']." ".$classDetail['title'] ?></td>
		</tr>
		<tr>
			<td>Date & Time:</td>
			<td><?php echo $classDetail['date']." ".$classDetail['time'] ?></td>
		</tr>
		<tr>
			<td><label for="name">Applicant Id:</label></td>
			<td>
				<?php echo $appDetail['appId'] ?>
			</td>
		</tr>		
		<tr>
			<td><label for="name">Applicant Name:</label></td>
			<td>
				<?php echo $appDetail['name'] ?>
			</td>
		</tr>
		<tr>
			<td><label for="nop">No of Person(s)</label></td>
			<td>
				<?php echo $appDetail['nop'] ?>
			</td>
		</tr>
		<tr><td>Change to : </td>
		<td><?php echo getChangeCakeClassList($appDetail['classId'],$appDetail['nop']) ?></td>
		</tr>
		<tr>
			<td></td>
			<td><input type="checkbox" name="sendConfirmEmail" id="sendConfirmEmail" value="Y">Send Confirmation Email</td>
		</tr>		
		<tr>
			<td></td>
			<td>
				<div class="buttonsarea">
					<input type="button" value="Submit" onclick="changeClass();">
					<input type="button" onClick="back();" value="Back" />
				</div>
			</td>
		</tr>		
	</table>
	</div>
	</form>
</div>
</body>
</html>