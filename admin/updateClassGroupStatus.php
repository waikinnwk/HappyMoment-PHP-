<?php
	require_once 'adminCommon.php';
	require_once '../common/common.php';
	if(isset($_GET['id']) && $_GET['id'] != '' && isset($_GET['status']) && $_GET['status'] != ''){
		updateClassGroupStatus($_GET['id'],$_GET['status']);		
	}
	header('Location: http://' . $_SERVER['HTTP_HOST']."/admin/viewClassGroup.php");
?>