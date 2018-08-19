<?php 
	require_once 'adminCommon.php';
	require_once '../common/common.php';
	
	if(isset($_POST['action'])){
		$eventId = $_POST['eventId'];
		if($_POST['action'] == 'editClass'){
			
			$eventTitle = $_POST["eventTitle"];
			$eventDate = $_POST["eventDate"];
			$startTime =$_POST["startHour"].':'.$_POST["startMin"];
			$endTime =$_POST["endHour"].':'.$_POST["endMin"];
			$eventQuota = $_POST["eventQuota"];
			$eventPrice = $_POST["eventPrice"];
			$eventEmail = $_POST["eventEmail"];
			if(!isset($_POST["eventClassGroup"]) || $_POST["eventClassGroup"] == "")
				$eventClassGroup = "NULL";
			else
				$eventClassGroup = $_POST["eventClassGroup"];
			
			$eventTime = $startTime." - ".$endTime;
			
			updateCakeClass($eventId,$eventTitle,$eventQuota,$eventDate,$eventTime,$eventPrice,$eventEmail,$eventClassGroup);
			
			/*
			$gooEventId = getGoogleEventIdByClassId($eventId);
			
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

			$event->title = $gdataCal->newTitle($eventTitle."(".$eventId.")");

			$when = $gdataCal->newWhen();
			$when->startTime = "{$eventDate}T{$startTime}:00.000+08:00";
			$when->endTime = "{$eventDate}T{$endTime}:00.000+08:00";
			$event->when = array($when);
			$event->content = $gdataCal->newContent(getEventDesc($eventId,$gooEventId));
			$event->save();
			*/
		}
		else if($_POST['action'] == 'cancelClass'){
			/*
			include('google_cal_config.php');

			$service = Zend_Gdata_Calendar::AUTH_SERVICE_NAME; 
			$client = Zend_Gdata_ClientLogin::getHttpClient($googleAccount, $googlePassword, $service);
			$gdataCal = new Zend_Gdata_Calendar($client);
			
			$calendarURL="http://www.google.com/calendar/feeds/".urlencode($calendarID)."/private/full";
			
			$gooEventId = getGoogleEventIdByClassId($eventId);
			*/
			cancelCakeClass($eventId);
			
			/*
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
			
			$event->delete();
			*/
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
	header('Location: http://' . $_SERVER['HTTP_HOST']."/admin/viewClass.php"."?".$searchParam.$orderBy);	

?>