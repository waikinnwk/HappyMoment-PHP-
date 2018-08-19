<?php
require_once '../google/Google_Client.php';
require_once '../google/contrib/Google_CalendarService.php';
require_once '../common/common.php';

session_start();
$client = new Google_Client();
$client->setApplicationName("Google Calendar PHP Starter Application");
$cal = new Google_CalendarService($client);


if (isset($_SESSION['token'])) {
  $client->setAccessToken($_SESSION['token']);
}


if ($client->getAccessToken()) {

	$eventTitle = $_POST["eventTitle"];
	$eventDate = $_POST["eventDate"];
	$startTime =$_POST["startHour"].':'.$_POST["startMin"];
	$endTime =$_POST["endHour"].':'.$_POST["endMin"];
	$eventQuota = $_POST["eventQuota"];
	$eventPrice = $_POST["eventPrice"];
	$location = "Shau Kei Wan";

    $event = new Google_Event();

	$event->setSummary($eventTitle);
	$event->setLocation($location);

	$start = new Google_EventDateTime();
	
	
	$start->setDateTime($eventDate.'T'.$startTime.':00.000+08:00');
	$event->setStart($start);

	$end = new Google_EventDateTime(); 
	
	
	
	$end->setDateTime($eventDate.'T'.$endTime.':00.000+08:00');
	$event->setEnd($end);
	
	$createdEvent = $cal->events->insert($calendarId, $event);

	
	
	$createdEvent = $cal->events->GET($calendarId, $createdEvent->getId());
	
	$eventTime = $startTime." - ".$endTime;
	
	$classId = createNewCakeClass($createdEvent->getId(),$eventTitle,$eventQuota, $eventDate, $eventTime, $location,$eventPrice);
	
	$createdEvent->setDescription(getEventDesc($classId,$createdEvent->getId()));
	
	$cal->events->update($calendarId, $createdEvent->getId(),$createdEvent);
	
	
	
	
	//echo $createdEvent->getId()."<br>";
	header('Location: http://' . $_SERVER['HTTP_HOST']."/admin/adminHome.php");


	$_SESSION['token'] = $client->getAccessToken();
} else {
  $authUrl = $client->createAuthUrl();
  print "<a class='login' href='$authUrl'>Insert!</a>";
}

?>