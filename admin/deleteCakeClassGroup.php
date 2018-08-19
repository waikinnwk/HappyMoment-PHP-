<?php
	require_once 'adminCommon.php';
	require_once '../common/common.php';
	if(isset($_GET['id']) && $_GET['id'] != ''){
		markDeleteCakeClassGroup($_GET['id']);		
	}
	header('Location: http://' . $_SERVER['HTTP_HOST']."/admin/viewClassGroup.php");
?>