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
	
	$sendEmail = false;
	if(isset($_GET['applicationId']) && $_GET['applicationId'] != ''){
		$applicationId = $_GET['applicationId'];
		$appDetail = getApplicationDetail($applicationId);
		$classDetail = getClassDetail(getGoogleEventIdByAppId($applicationId));
		sendConfirmationEMail($appDetail['email'],$appDetail['name'],$applicationId,$classDetail['title'],$appDetail['nop'],$classDetail['price'],$appDetail['classId'],$classDetail['date'],$classDetail['time'],$classDetail['email'],"Y",false);
		$sendEmail = true;
	}
	if(!$sendEmail){
		header('Location: http://' . $_SERVER['HTTP_HOST']."/admin/viewApplication.php");
	}
	else{
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
			window.location = "http://" + "<?php echo $_SERVER['HTTP_HOST']."/admin/viewApplication.php"."?".$searchParam.$orderBy ?>";
		}
		
	</script>
</head>
<body>
<?php require_once 'menu_bar.php'; ?>
<div class="formarea">
<div class="subfieldsset">
	<h3><?php echo $applicationId ?></h3>
	<h3>Confirmation Email Sent</h3>
	<input type="button" onClick="back();" value="Back" />
</div>
</div>
</body>
</html>


<?php } ?>
