<?php
	require_once 'adminCommon.php';
	require_once '../common/common.php';
	if(isset($_GET['id']) && $_GET['id'] != ''){
		markDeleteCakeClassTab($_GET['id']);		
	}
	header('Location: http://' . $_SERVER['HTTP_HOST']."/admin/viewClassTab.php");
?>