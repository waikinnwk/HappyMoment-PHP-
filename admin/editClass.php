<?php
	require_once 'adminCommon.php';
	require_once '../common/common.php';
	
	
	$eventTitle = "";
	$eventDate = "";
	$startHour = "";
	$startMin = "";
	$endHour = "";
	$endMin = "";
	$eventQuota = "";
	$eventPrice = "";
	$eventEmail = "";
	$eventClassGroup = "";
	
	$eventId = "";
	$gooEventId = "";
	
	$searchParam = "";
	$searchParamOrg = "";
	$orderBy = "";
	
	$classGroup = getAllCakeClassGroup();
	
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
	
	$errors = array('eventTitle' => '','eventDate'=> '','time' => '','eventQuota' => '','eventPrice' => '');
	$summayPage = false;
	if(isset($_POST['action']) && $_POST['action'] == 'editClass' && isset($_POST['eventId']) ){
		$eventId = $_POST['eventId'];
		$eventTitle = $_POST["eventTitle"];
		$eventDate = $_POST["eventDate"];
		$startHour = $_POST["startHour"];
		$startMin = $_POST["startMin"];
		$endHour = $_POST["endHour"];
		$endMin = $_POST["endMin"];
		$eventQuota = $_POST["eventQuota"];
		$eventPrice = $_POST["eventPrice"];
		$eventEmail = $_POST["eventEmail"];
		$eventClassGroup = $_POST["eventClassGroup"];
		
		$isErrors = false;
		
		if(strlen(trim ($eventTitle)) == 0){
			$errors['eventTitle'] = 'Title is required.';
			$isErrors = true;
		}
		
		if(strlen(trim ($eventDate)) == 0){
			$errors['eventDate'] = 'Date is required.';
			$isErrors = true;
		}
		else{
			$date_format = 'Y-m-d';
			$eventDate = trim($eventDate);
			$time = strtotime($eventDate);
			$is_valid = date($date_format, $time) == $eventDate;
			if(!$is_valid){
				$errors['eventDate'] = 'Invalid Date.';
				$isErrors = true;				
			}
		
		}
		
		if($startHour > $endHour){
			$errors['time'] = 'End Time should be later than Start Time.';
			$isErrors = true;		
		}
		else if($startHour == $endHour && $startMin >= $endMin ){
			$errors['time'] = 'End Time should be later than Start Time.';
			$isErrors = true;
		}
		
		if(!is_numeric($eventQuota) && $eventQuota < 1 ){
			$errors['eventQuota'] = 'Invalid Quota.';
			$isErrors = true;
		}
		
		
		if(!is_numeric($eventPrice) && $eventPrice < 0 ){
			$errors['eventPrice'] = 'Invalid Price.';
			$isErrors = true;
		}
		
		if(!$isErrors){
			$summayPage = true;
		}
	
	}
	else if(isset($_GET['eventId']) && $_GET['eventId'] != ''){
		$eventId = $_GET['eventId'];
		//$gooEventId = getGoogleEventIdByClassId($eventId);
		//if(trim($gooEventId) != ''){
			$classDetail = getClassDetail($eventId);
			$time = $classDetail['time'];
			$splitTime= explode('-',$time);
			
			$startTime = $splitTime[0];
			$endTime = $splitTime[1];
			
			
			$splitStartTime= explode(':',$startTime);
			$splitEndTime= explode(':',$endTime);
			

			
			$startHour = trim($splitStartTime[0]);
			$startMin = trim($splitStartTime[1]);
			$endHour = trim($splitEndTime[0]);
			$endMin = trim($splitEndTime[1]);			

			
			$eventTitle = $classDetail['title'];
			$eventDate = $classDetail['date'];
			$eventQuota = $classDetail['quota'];
			$eventPrice = $classDetail['price'];
			$eventEmail = $classDetail['email'];
			$eventClassGroup = $classDetail['eventClassGroup'];
		//}
		//else{
		//	header('Location: http://' . $_SERVER['HTTP_HOST']."/admin/viewClass.php");
		//}
	}
	else if(isset($_POST['action']) && $_POST['action'] == 'back' ){
		$eventTitle = $_POST["eventTitle"];
		$eventDate = $_POST["eventDate"];
		$startHour = $_POST["startHour"];
		$startMin = $_POST["startMin"];
		$endHour = $_POST["endHour"];
		$endMin = $_POST["endMin"];
		$eventQuota = $_POST["eventQuota"];
		$eventPrice = $_POST["eventPrice"];
		$eventEmail = $_POST["eventEmail"];
		$eventClassGroup = $_POST["eventClassGroup"];
	}
	else{
		header('Location: http://' . $_SERVER['HTTP_HOST']."/admin/viewClass.php");
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
	<script type="text/javascript">
	var classGroupName = new Array();
	var classGroupPrice = new Array();
	<?php
		foreach ($classGroup as $gp)
		{						
		echo "classGroupName['".$gp['id']."'] = '".$gp['name']."';\n";		
		echo "classGroupPrice['".$gp['id']."'] = '".$gp['price']."';\n";
		}	
	?>	
	window.addEvent('domready', function(){
		$$('input.DatePicker').each( function(el){
			new DatePicker(el);
		});
		
	
		
	});
	var searchParam = "<?php echo $searchParam ?>";
	function CancelClass(){
		if(confirm("Are you sure to cancel this class?")){
			document.getElementById("editClass").action = "updateClass.php";
			document.getElementById("action").value = "cancelClass";
			formSubmit('editClass');
		}
		return true;
	}	
	
	function back(){
			document.getElementById("editClass").action = "editClass.php";
			document.getElementById("action").value = "back";
			formSubmit('editClass');
		}	
	function backHome(){
			window.location = "http://" + "<?php echo $_SERVER['HTTP_HOST']."/admin/viewClass.php"?>" + "?" + searchParam + "&orderBy=" + "<?php echo $orderBy ?>";
		}	
	function prefillInfo(){
		var e = document.getElementById("eventClassGroup");
		var groupId = e.options[e.selectedIndex].value;
		if(groupId != ""){
			if(confirm('Prefill Info from Group ? ')){

				document.getElementById("eventTitle").value = classGroupName[groupId];
				document.getElementById("eventPrice").value = classGroupPrice[groupId];
			}
		}
		
	}		
	</script>
</head>
<body>
<?php require_once 'menu_bar.php'; ?>
<div class="formarea">
<?php if(!$summayPage) {?>
	<form action='editClass.php' method='POST' id="editClass">
	<input type="hidden" id="action" name="action" value="editClass"/>
	<input type="hidden" id="eventId" name="eventId" value="<?php echo $eventId; ?>"/>
	<input type="hidden" name="searchParam" id="searchParam" value="<?php echo $searchParamOrg ?>">
	<input type="hidden" name="orderBy" id="orderBy" value="<?php echo $orderBy ?>">
	<h2>Edit Class</h2>
	<div class="subfieldsset">
	<table>
		<tr>
			<td><label for="eventTitle">Event Title</label></td>
			<td>
				<input type="text" name="eventTitle" id="eventTitle" value="<?php echo $eventTitle; ?>">
				<p class="errorMsg"><?php echo $errors['eventTitle'] ?></p>
			</td>
		</tr>
		<tr>
			<td><label for="tab">Class Group :</label></td>
			<td>
				<select id="eventClassGroup" name="eventClassGroup" onchange="prefillInfo();">
					<option value="">Nil</option>
					<?php 
						foreach ($classGroup as $gp)
						{
							
							echo "<option value='".$gp['id']."'";
							if($gp['id'] == $eventClassGroup)
								echo " selected ";
							echo ">".$gp['name']."</option>";
							}							
					?>
				</select>
			</td>
		</tr>		
		<tr>
			<td><label for="eventDate">Date</label></td>
			<td>
				<input id="eventDate" name="eventDate" type="text" class="DatePicker" alt="{format:'yyyy-mm-dd'}" value="<?php echo $eventDate; ?>">
				<p class="errorMsg"><?php echo $errors['eventDate'] ?></p>
			</td>
		</tr>
		<tr>
			<td><label>Start Time</label></td>
			<td>
				<select id="startHour" name="startHour">
					<option value="09" <?php if($startHour == '09') echo "selected"; ?> >09</option>
					<option value="10" <?php if($startHour == '10') echo "selected"; ?> >10</option>
					<option value="11" <?php if($startHour == '11') echo "selected"; ?> >11</option>
					<option value="12" <?php if($startHour == '12') echo "selected"; ?> >12</option>
					<option value="13" <?php if($startHour == '13') echo "selected"; ?> >13</option>
					<option value="14" <?php if($startHour == '14') echo "selected"; ?> >14</option>
					<option value="15" <?php if($startHour == '15') echo "selected"; ?> >15</option>
					<option value="16" <?php if($startHour == '16') echo "selected"; ?> >16</option>
					<option value="17" <?php if($startHour == '17') echo "selected"; ?> >17</option>
					<option value="18" <?php if($startHour == '18') echo "selected"; ?> >18</option>
					<option value="19" <?php if($startHour == '19') echo "selected"; ?> >19</option>
					<option value="20" <?php if($startHour == '20') echo "selected"; ?> >20</option>
					<option value="21" <?php if($startHour == '21') echo "selected"; ?> >21</option>
					<option value="22" <?php if($startHour == '22') echo "selected"; ?> >22</option>
					<option value="23" <?php if($startHour == '23') echo "selected"; ?> >23</option>				
				</select>
				<select id="startMin" name="startMin">
					<option value="00" <?php if($startMin == '00') echo "selected"; ?> >00</option>
					<option value="15" <?php if($startMin == '15') echo "selected"; ?> >15</option>
					<option value="30" <?php if($startMin == '30') echo "selected"; ?> >30</option>
					<option value="45" <?php if($startMin == '45') echo "selected"; ?> >45</option>			
				</select>			
			</td>
		</tr>
		<tr>
			<td><label>End Time</label></td>
			<td>
				<select id="endHour" name="endHour">
					<option value="09" <?php if($endHour == '09') echo "selected"; ?> >09</option>
					<option value="10" <?php if($endHour == '10') echo "selected"; ?> >10</option>
					<option value="11" <?php if($endHour == '11') echo "selected"; ?> >11</option>
					<option value="12" <?php if($endHour == '12') echo "selected"; ?> >12</option>
					<option value="13" <?php if($endHour == '13') echo "selected"; ?> >13</option>
					<option value="14" <?php if($endHour == '14') echo "selected"; ?> >14</option>
					<option value="15" <?php if($endHour == '15') echo "selected"; ?> >15</option>
					<option value="16" <?php if($endHour == '16') echo "selected"; ?> >16</option>
					<option value="17" <?php if($endHour == '17') echo "selected"; ?> >17</option>
					<option value="18" <?php if($endHour == '18') echo "selected"; ?> >18</option>
					<option value="19" <?php if($endHour == '19') echo "selected"; ?> >19</option>
					<option value="20" <?php if($endHour == '20') echo "selected"; ?> >20</option>
					<option value="21" <?php if($endHour == '21') echo "selected"; ?> >21</option>
					<option value="22" <?php if($endHour == '22') echo "selected"; ?> >22</option>
					<option value="23" <?php if($endHour == '23') echo "selected"; ?> >23</option>			
				</select>
				<select id="endMin" name="endMin">
					<option value="00" <?php if($endMin == '00') echo "selected"; ?> >00</option>
					<option value="15" <?php if($endMin == '15') echo "selected"; ?> >15</option>
					<option value="30" <?php if($endMin == '30') echo "selected"; ?> >30</option>
					<option value="45" <?php if($endMin == '45') echo "selected"; ?> >45</option>		
				</select>
			</td>
		</tr>
		<tr><td></td><td><p class="errorMsg"><?php echo $errors['time'] ?></p></td></tr>
		<tr>
			<td><label for="eventQuota">Quota</label></td>
			<td><input type="text" name="eventQuota" id="eventQuota" value="<?php echo $eventQuota;?>">
				<p class="errorMsg"><?php echo $errors['eventQuota'] ?></p>
			</td>
		</tr>
		<tr>
			<td><label for="eventPrice">Price</label></td>
			<td><input type="text" name="eventPrice" id="eventPrice" value="<?php echo $eventPrice;?>">
				<p class="errorMsg"><?php echo $errors['eventPrice'] ?></p>
			</td>
		</tr>	
		<tr>
			<td><label for="eventPrice">Confirmation Email Bank Account</label></td>
			<td>
				<select id="eventEmail" name="eventEmail">
					<option value="1" <?php if($eventEmail == '1' || $eventEmail =='' ) echo "selected"; ?> >Happy Moment Limited</option>
					<!--<option value="2" <?php if($eventEmail == '2') echo "selected"; ?> >Cat Society (Hong Kong) Limited</option>-->	
				</select>			
			</td>
		</tr>		
		<tr>
			<td></td>
			<td>
				<div class="buttonsarea">
				<input type="button" value="Submit" onclick="formSubmit('editClass')">
				</div>
				<div class="buttonsarea">
				<input type="button" value="Back" onclick="backHome()">
				</div>				
			</td>
		</tr>
		<tr>
			<td></td>
			<td>
				<div class="buttonsarea">
					<input type="button" value="Cancel Class" onclick="CancelClass();">
				</div>
			</td>
		</tr>		
	</table>
	</div>
	</form>
<?php }  else {?>
	<form action='updateClass.php' method='POST' id="editClass">
	<input type="hidden" id="action" name="action" value="editClass"/>
	<input type="hidden" id="eventId" name="eventId" value="<?php echo $eventId; ?>"/>
	<input type="hidden" name="searchParam" id="searchParam" value="<?php echo $searchParamOrg ?>">
	<input type="hidden" name="orderBy" id="orderBy" value="<?php echo $orderBy ?>">
	<h2>Edit Class</h2>
	<div class="subfieldsset">
	<table>
		<tr>
			<td><label for="eventTitle">Event Title</label></td>
			<td>
				<?php echo $eventTitle; ?>
				<input type="hidden" id="eventTitle" name="eventTitle" value="<?php echo $eventTitle; ?>"/>
			</td>
		</tr>
		<tr>
			<td><label for="tab">Class Group :</label></td>
			<td>
					<?php 
						foreach ($classGroup as $gp)
						{
							

							if($gp['id'] == $eventClassGroup){
								echo "<input name='eventClassGroup' id='eventClassGroup' type='hidden' value='".$gp['id']."' />";
								echo $gp['name'];
							}
						}
						if($eventClassGroup == "")
							echo "Nil";						
					?>
			</td>
		</tr>			
		<tr>
			<td><label for="eventDate">Date</label></td>
			<td>
				<?php echo $eventDate; ?>
				<input type="hidden" id="eventDate" name="eventDate" value="<?php echo $eventDate; ?>"/>
			</td>
		</tr>
		<tr>
			<td><label>Start Time</label></td>
			<td>
				<?php echo $startHour.":".$startMin ?>
				<input type="hidden" id="startHour" name="startHour" value="<?php echo $startHour; ?>"/>
				<input type="hidden" id="startMin" name="startMin" value="<?php echo $startMin; ?>"/>
			</td>
		</tr>
		<tr>
			<td><label>End Time</label></td>
			<td>
				<?php echo $endHour.":".$endMin ?>
				<input type="hidden" id="endHour" name="endHour" value="<?php echo $endHour; ?>"/>
				<input type="hidden" id="endMin" name="endMin" value="<?php echo $endMin; ?>"/>
			</td>
		</tr>
		<tr>
			<td><label for="eventQuota">Quota</label></td>
			<td>
				<?php echo $eventQuota?>
				<input type="hidden" id="eventQuota" name="eventQuota" value="<?php echo $eventQuota; ?>"/>
			</td>
		</tr>
		<tr>
			<td><label for="eventPrice">Price</label></td>
			<td>
				<?php echo $eventPrice?>
				<input type="hidden" id="eventPrice" name="eventPrice" value="<?php echo $eventPrice; ?>"/>
			</td>
		</tr>		
		<tr>
			<td><label for="eventPrice">Confirmation Email Bank Account</label></td>
			<td>
				<?php if($eventEmail == '1' || $eventEmail =='' ) echo "Happy Moment Limited"; ?>
				<?php if($eventEmail == '2') echo "Cat Society (Hong Kong) Limited"; ?>		
				<input type="hidden" id="eventEmail" name="eventEmail" value="<?php echo $eventEmail; ?>"/>
			</td>
		</tr>		
		<tr>
			<td></td>
			<td>
				<div class="buttonsarea">
				<input type="button" value="Submit" onclick="formSubmit('editClass')">
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
<html>