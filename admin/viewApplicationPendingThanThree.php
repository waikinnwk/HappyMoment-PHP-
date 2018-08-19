<?php 
	require_once 'adminCommon.php';
	require_once '../common/common.php';
	
	$errorMsg = "";
	
	if(isset($_POST['action'])){
		if(isset($_POST['action']) && $_POST['action'] == 'markMFail'){
			$Ids = $_POST['appIds']; 
			$emailClassDeclare = false;
			foreach ($Ids as $markStatusId)
			{
				$e_result = updateApplicationStatus($markStatusId,'F',$emailClassDeclare);
				$emailClassDeclare = true;
				if($e_result){
					$gooEventId = getGoogleEventIdByAppId($markStatusId);
					$classId = getClassIdByAppId($markStatusId);
					
					include('google_cal_config.php');

					$service = Zend_Gdata_Calendar::AUTH_SERVICE_NAME; 
					$client = Zend_Gdata_ClientLogin::getHttpClient($googleAccount, $googlePassword, $service);
					$gdataCal = new Zend_Gdata_Calendar($client);

					
					$calendarURL="http://www.google.com/calendar/feeds/".urlencode($calendarID)."/private/full";
					
					$query = $gdataCal->newEventQuery();
					$query->setUser($calendarID);
					$query->setVisibility('private');
					$query->setProjection('full');
					$query->setEvent($gooEventId);
					

					try {
						$event = $gdataCal->getCalendarEventEntry($query);

					} catch (Zend_Gdata_App_Exception $e) {
						var_dump($e);
					}

					$event->content = $gdataCal->newContent(getEventDesc($classId,$gooEventId));
					$event->save();		
				}
				else{
					 updateApplicationStatus($markStatusId,'P',$emailClassDeclare);
					 $errorMsg .= "Application : ".$markStatusId." send fail email Fail.\n" ;
				}
			}
		}
		else if(isset($_POST['action']) && $_POST['action'] == 'resendMEmail'){
			$Ids = $_POST['appIds']; 
			$emailClassDeclare = false;
			foreach ($Ids as $emailId)
			{		
				$appDetail = getApplicationDetail($emailId);
				$classDetail = getClassDetail(getGoogleEventIdByAppId($emailId));
				$e_result = sendConfirmationEMail($appDetail['email'],$appDetail['name'],$emailId,$classDetail['title'],$appDetail['nop'],$classDetail['price'],$appDetail['classId'],$classDetail['date'],$classDetail['time'],$classDetail['email'],"Y",$emailClassDeclare);	
				$emailClassDeclare = true;
				if(!$e_result){
					$errorMsg .= "Application : ".$emailId." confirmation email Fail.\n" ;
				}
			}		
		}
	}
	
	
	$pagenum = 1;
	if(isset($_POST['pagenum'])){
		$pagenum = $_POST['pagenum'];
	}

	$orderBy = "";
	
	if(isset($_POST['orderBy'])){
		$orderBy = $_POST['orderBy'];
	}
	else if(isset($_GET['orderBy'])){
		$orderBy = $_GET['orderBy'];
	}		
	
	//$appList = getApplicationListHome($pagenum);
	$count = getCountApplicationListPendingMoreThanThreeDay();
	$p1 = $count / 20;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<link rel="stylesheet" href="../css/addForm.css" media="screen, projection" />
	<script type="text/javascript" src="../js/common.js"></script>
	<script type="text/javascript">
		function prevPage(){
			document.getElementById("pagenum").value = parseInt(document.getElementById("pagenum").value) - 1;
			formSubmit('listview');
		}
		function nextPage(){
		document.getElementById("pagenum").value = parseInt(document.getElementById("pagenum").value) + 1;
			formSubmit('listview');
		}
		
		function markStatus(id,status){
			var confirmMsg = "Are you sure to mark " + id + " to ";
			if(status == 'S')
				confirmMsg += "Success";
			else if(status == 'F')
				confirmMsg += "Fail";
			else if(status == 'P')
				confirmMsg += "Pending";
			else if(status == 'D')
				confirmMsg = "Are you sure to delete " + id;				
				
			
			confirmMsg += "?";
			if(confirm(confirmMsg)){
				document.getElementById("id").value = id;
				document.getElementById("status").value = status;
				document.getElementById("listview").action = "updateApplicationStatus.php";
				formSubmit('listview');
			}
		}
		
		function markMStatus(status){
			if(status == 'F')
				document.getElementById("action").value = "markMFail";
			formSubmit('listview');
		}
		function resendMEmail(){
			document.getElementById("action").value = "resendMEmail";
			formSubmit('listview');
		}		

		function setOrderBy(colName){
			var orderBy = document.getElementById("orderBy").value;
			if(orderBy !=""){
				var finalOrderBy = "";
				var a1 = orderBy.split(",");
				var existCol = false;
				for(var i = 0 ; i < a1.length; i++){
					var a2 = a1[i].split("|");
					if(a2.length > 1){
						if(a2[0] == colName){
							if(a2[1] == 'A'){
								if( finalOrderBy !="")
									finalOrderBy += ",";
								finalOrderBy += colName + "|D";
							}
							existCol = true;
						}
						else{
							if( finalOrderBy !="")
									finalOrderBy += ",";
							finalOrderBy += a2[0] + "|" + a2[1];
						}
					}
				}
				if(!existCol){
					if( finalOrderBy !="")
						finalOrderBy += ",";
					finalOrderBy += colName + "|A";				
				}
				document.getElementById("orderBy").value = finalOrderBy;
			}
			else
				document.getElementById("orderBy").value = colName + "|A";
			formSubmit('listview');
		}	

		function toggle(source) {
			checkboxes = document.getElementsByName('appIds[]');
			for(var i=0, n=checkboxes.length;i<n;i++) {
				checkboxes[i].checked = source.checked;
			}
		 }		
	</script>
</head>
<body>
<?php require_once 'menu_bar.php'; ?>
<br><br>
<h3>Application Pending More Than Three Days: </h3>
<form action='viewApplicationPendingThanThree.php' method='POST' id="listview">
<p><font color="red"><?php echo $errorMsg ?></font></p>
<table>
<tr>
	<td><input type="button" value="Mark Selected Application(s) to Fail" onclick="markMStatus('F')"></td>
	<td><input type="button" value="Resend Confirmation Email of Selected Application(s)" onclick="resendMEmail()"></td>
</tr>
</table>
<div class="listViewAdmin" >
	<?php getApplicationListTablePendingMoreThanThreeDay(); ?>
</div>
	<input type="hidden" id="action" name="action" value=""/>
	<input type="hidden" id="orderBy" name="orderBy" value="<?php echo $orderBy ?>"/>
	<input type="hidden" id="currentPage" name="currentPage" value="viewApplicationPendingThanThree.php"/>
</form>

</body>
</html>