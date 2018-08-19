<?php
	require_once '../common/common.php';

	if(isset($_POST['login']) && isset($_POST['pwd'])){
		$logon = $_POST['login'];
		$pwd = $_POST['pwd'];
		if(adminAuthen($logon,$pwd)){
			session_start();
			$_SESSION['adminLogon'] = $logon;
			
			header('Location: http://' . $_SERVER['HTTP_HOST'].'/admin/adminHome.php');
			
		}
		else{
			header('Location: http://' . $_SERVER['HTTP_HOST'].'/admin/logon.php?result=f');
		}
	}
	else{
		redirectMainPage();
	}
?>