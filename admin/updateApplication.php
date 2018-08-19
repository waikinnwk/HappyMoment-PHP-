<?php 
	require_once 'adminCommon.php';
	require_once '../common/common.php';
	
	if(isset($_POST['action'])){
		$applicationId = $_POST['applicationId'];
		if($_POST['action'] == 'editApplication'){
			
			$name = $_POST['name'];
			$tel =  $_POST['tel'];
			$email = $_POST['email'];
			$nop =  $_POST['nop'];
			$remarks = $_POST['remarks'];
			if(isset($_POST['whatsapp'])){
				$whatsapp = $_POST['whatsapp'];
			}
			else{
				$whatsapp = 'N';
			}
			
			updateApplication($applicationId,$name,$tel,$email,$nop,$remarks,$whatsapp);
			
			//$gooEventId = getGoogleEventIdByAppId($applicationId);
			$classDetail = getClassDetail(getClassIdByAppId($applicationId));
			$eventId = $classDetail['classId'];
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
			$event->content = $gdataCal->newContent(getEventDesc($eventId,$gooEventId));
			$event->save();
			*/
		}
		else if($_POST['action'] == 'changeClass'){
		
		}
	}
	
	$searchParam = "";
	if(isset($_POST['searchParam'])){
		$searchParam = $_POST['searchParam'];
		$searchParam =  str_replace("|","=",$searchParam);
		$searchParam =  str_replace(",","&",$searchParam);
	}	
	
	$orderBy = "";
	if(isset($_POST['orderBy'])){
		if($searchParam == "")
			$orderBy = "orderBy=".$_POST['orderBy'];
		else
			$orderBy = "&orderBy=".$_POST['orderBy'];
	}
	
	header('Location: http://' . $_SERVER['HTTP_HOST']."/admin/viewApplication.php"."?".$searchParam.$orderBy);	

?>