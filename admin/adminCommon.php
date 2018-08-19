<?php
	session_start();
	require_once '../common/common.php';
	if(!isset($_SESSION['adminLogon'])){
		redirectMainPage();
	}
 ?>