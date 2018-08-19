<?php 
	require_once 'adminCommon.php';

	unset($_SESSION['token']); 
	unset($_SESSION['adminLogon']); 
	session_unset(); 
	session_destroy();
	header('Location: http://' . $_SERVER['HTTP_HOST'].'/admin/logon.php');
?>