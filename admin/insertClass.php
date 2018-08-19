<?php
	require_once 'adminCommon.php';
	require_once '../common/common.php';
	
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
	$location = "Unit 5,10/F, NEW CITY CENTRE, 2 LEI YUE MUN ROAD, KUNG TONG,HONG KONG";
	
	//include('google_cal_config.php');
	
		 
	
	
	//$service = Zend_Gdata_Calendar::AUTH_SERVICE_NAME; 
	//$client = Zend_Gdata_ClientLogin::getHttpClient($googleAccount, $googlePassword, $service);
	//$gdataCal = new Zend_Gdata_Calendar($client);

	
	//$calendarURL="http://www.google.com/calendar/feeds/".urlencode($calendarID)."/private/full";
	//$calendarURL="https://www.google.com/calendar/feeds/waikinnwkapi%40gmail.com/private-26bf960f3e5fa470e7dfd84670fa344f/basic";
	
	//$newEvent = $gdataCal->newEventEntry();
	//$newEvent->title = $gdataCal->newTitle($eventTitle);
	//$newEvent->where = array($gdataCal->newWhere($location));

	//$when = $gdataCal->newWhen();
	//$when->startTime = "{$eventDate}T{$startTime}:00.000+08:00";
	//$when->endTime = "{$eventDate}T{$endTime}:00.000+08:00";
	//$newEvent->when = array($when);

	//$createdEvent = $gdataCal->insertEvent($newEvent, $calendarURL);
	

	
	$eventTime = $startTime." - ".$endTime;
	
	//$eventId = substr($createdEvent->id, strrpos($createdEvent->id, '/')+1); // trim off everything but the id
	
	$eventId = "";
	
	$classId = createNewCakeClass($eventId,$eventTitle,$eventQuota, $eventDate, $eventTime, $location,$eventPrice,$eventEmail,$eventClassGroup);
	
	
	
		
	/*	
	$query = $gdataCal->newEventQuery();
	$query->setUser($calendarID);
	$query->setVisibility('private');
	$query->setProjection('full');
	$query->setEvent($eventId);
	

	try {
		$event = $gdataCal->getCalendarEventEntry($query);

	} catch (Zend_Gdata_App_Exception $e) {
		var_dump($e);
	}

	$event->content = $gdataCal->newContent(getEventDesc($classId,$eventId));
	$event->title = $gdataCal->newTitle($eventTitle."(".$classId.")");
	$event->save();
	*/
	header('Location: http://' . $_SERVER['HTTP_HOST']."/admin/viewClass.php");
	

?>