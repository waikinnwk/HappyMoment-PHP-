<?php
	require_once 'adminCommon.php';
	require_once '../common/common.php';
	$pagenum = 1;
	if(isset($_POST['action']) && $_POST['action'] == 'search'){
		$pagenum = 1;
	}	
	else if(isset($_POST['pagenum'])){
		$pagenum = $_POST['pagenum'];
	}
	else if(isset($_GET['pagenum'])){
		$pagenum = $_GET['pagenum'];
	}		
	
	//$classList = getCakeClassList($pagenum,"Y");
	$count = getCountCakeClassList("Y");
	$p1 = $count / 10;
	
	
	$classId =  "";
	$title = "";
	$date = "";

	
	if(isset($_POST['classId']) && $_POST['classId'] != "")
		$classId = $_POST['classId'];
		
	if(isset($_POST['title']) && $_POST['title'] != "")
		$title = $_POST['title'];

	if(isset($_POST['date']) && $_POST['date'] != "")
		$date = $_POST['date'];

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<link rel="stylesheet" href="../css/DatePicker.css" media="screen, projection" />
	<link rel="stylesheet" href="../css/addForm.css" media="screen, projection" />
	<script type="text/javascript" src="../js/mootools.v1.11.js"></script>
	<script type="text/javascript" src="../js/DatePicker.js"></script>
	<script type="text/javascript" src="../js/common.js"></script>
	<script type="text/javascript">
	window.addEvent('domready', function(){
		$$('input.DatePicker').each( function(el){
			new DatePicker(el);
		});
		
	
		
	});
	function prevPage(){
		document.getElementById("pagenum").value = parseInt(document.getElementById("pagenum").value) - 1;
		formSubmit('listview');
	}
	function nextPage(){
	document.getElementById("pagenum").value = parseInt(document.getElementById("pagenum").value) + 1;
		formSubmit('listview');
	}
	function editClass(classId){
		window.location = "http://" + "<?php echo $_SERVER['HTTP_HOST']."/admin/editClass.php?eventId=" ?>" + classId;
	}
	function clearForm(){
		document.getElementById("classId").value = "";
		document.getElementById("title").value = "";
		document.getElementById("date").value = "";
	}
	</script>
</head>
<body>
<?php require_once 'menu_bar.php'; ?>
<br><br>
<h3>Cake Class(Old) : </h3>
<form action='viewOldClass.php' method='POST' id="listview">
<table>
	<tr>
		<td>Class Id : </td>
		<td><input type="text" name="classId" id="classId" value="<?php echo $classId; ?>" ></td>
	</tr>
	<tr>
		<td>Class Title</td>
		<td><input type="text" name="title" id="title" value="<?php echo $title; ?>"  ></td>
	</tr>	
	<tr>
		<td>Date : </td>
		<td><input id="date" name="date" type="text" class="DatePicker" alt="{format:'yyyy-mm-dd'}" value="<?php echo $date; ?>"></td>
	</tr>
	<tr>
		<td></td>
		<td><input type="button" value="Search" onclick="document.getElementById('action').value = 'search';formSubmit('listview');"><input type="button" value="Clear" onclick="clearForm()";></td>
	</tr>	
</table>
<div class="listViewAdmin" >
	<?php getCakeClassListTable($pagenum ,"Y"); ?>
</div>
	<input type="hidden" id="action" name="action" value=""/>
	

	<input type="hidden" id="currentPage" name="currentPage" value="viewOldClass.php"/>
</form>

</body>
</html>