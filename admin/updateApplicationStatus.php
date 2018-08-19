<?php
	require_once '../common/common.php';

	$id = "";
	$status = "";
	$emailStatus = "";
	
	if(ISSET($_POST['id']) && ISSET($_POST['status'])){
		$id = $_POST['id'];
		$status = $_POST['status'];
		$emailClassDeclare = false;
		$emailStatus = updateApplicationStatus($id,$status,false);
		//$gooEventId = getGoogleEventIdByAppId($id);
		$classId = getClassIdByAppId($id);
		
		/*
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
		*/
		
		
	}
	
	if(isset($_POST['currentPage'])){
		$searchParam = "";
		if(isset($_POST['searchParam'])){
			$searchParam = $_POST['searchParam'];
			$searchParam =  str_replace("|","=",$searchParam);
			$searchParam =  str_replace(",","&",$searchParam);
		}
		$emailStatus = "emailStatus=";
		if(!$emailStatus)
			$emailStatus.= "F";
		else
			$emailStatus.= "T";
		if($searchParam != "")
			$emailStatus= "&".$emailStatus;
		header('Location: http://' . $_SERVER['HTTP_HOST'].'/admin/'.$_POST['currentPage']."?".$searchParam.$emailStatus);
	}
	else
		header('Location: http://' . $_SERVER['HTTP_HOST'].'/admin/adminHome.php');
	
?>