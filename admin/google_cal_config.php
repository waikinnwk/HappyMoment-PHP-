<?php 
		
	
	$googleAccount = 'waikinnwkapi@gmail.com'; 
	$googlePassword = 'nwk+9218'; 
	$calendarID = 'tbt5ehqdk3g8stbnuirfrkjq44@group.calendar.google.com'; 
	
	/*	
	$googleAccount = 'happymoment2013@gmail.com'; 
	$googlePassword = 'lodaikung'; 
	$calendarID = 'sgfubucdfumjr2q2v665g0t8bs@group.calendar.google.com'; 
	*/
	
	$includePath = dirname(__FILE__).'/library';
	ini_set('include_path', $includePath); 
	
	require_once 'Zend/Loader.php';
	Zend_Loader::loadClass('Zend_Gdata');
	Zend_Loader::loadClass('Zend_Gdata_AuthSub');
	Zend_Loader::loadClass('Zend_Gdata_ClientLogin');
	Zend_Loader::loadClass('Zend_Gdata_Calendar');
?>