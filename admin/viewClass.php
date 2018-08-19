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
	
	$orderBy = "";
	
	if(isset($_POST['orderBy'])){
		$orderBy = $_POST['orderBy'];
	}
	else if(isset($_GET['orderBy'])){
		$orderBy = $_GET['orderBy'];
	}		
	
	//$classList = getCakeClassList($pagenum,"N");
	$count = getCountCakeClassList("N");
	$p1 = $count / 10;
	
	
	$classId =  "";
	$title = "";
	$date = "";

	
	if(isset($_POST['classId']) && $_POST['classId'] != "")
		$classId = $_POST['classId'];
	else if(isset($_GET['classId']) && $_GET['classId'] != "")
		$classId = $_GET['classId'];
		
	if(isset($_POST['title']) && $_POST['title'] != "")
		$title = $_POST['title'];
	else if(isset($_GET['title']) && $_GET['title'] != "")
		$title = $_GET['title'];
		
	if(isset($_POST['date']) && $_POST['date'] != "")
		$date = $_POST['date'];
	else if(isset($_GET['date']) && $_GET['date'] != "")
		$date = $_GET['date'];		

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
		window.location = "http://" + "<?php echo $_SERVER['HTTP_HOST']."/admin/editClass.php?eventId=" ?>" + classId + "&" + concatSearchOrderByParam();
	}
	function clearForm(){
		document.getElementById("classId").value = "";
		document.getElementById("title").value = "";
		document.getElementById("date").value = "";
	}
	function concatSearchOrderByParam(){
			var param = "";
			var searchParam = "searchParam=";
			searchParam += "pagenum|" + <?php echo $pagenum?>;
			if(document.getElementById("classId").value != '')
				searchParam += ",classId|" + document.getElementById("classId").value;
			if(document.getElementById("title").value != '')
				searchParam += ",title|" + document.getElementById("title").value;	
			if(document.getElementById("date").value != '')
				searchParam += ",date|" + document.getElementById("date").value;	
			param = searchParam + "&orderBy=" + document.getElementById("orderBy").value;
			return param;
	}
	function setOrderBy(colName){
			var orderBy = document.getElementById("orderBy").value;
			if(orderBy !=""){
				var finalOrderBy = "";
				var a1 = orderBy.split(",");
				var existCol = false;
				for(var i = 0 ; i < a1.length; i++){
					var a2 = a1[i].split("|");
					if(a2.length > 1){
						if(a2[0] == colName){
							if(a2[1] == 'A'){
								if( finalOrderBy !="")
									finalOrderBy += ",";
								finalOrderBy += colName + "|D";
							}
							existCol = true;
						}
						else{
							if( finalOrderBy !="")
									finalOrderBy += ",";
							finalOrderBy += a2[0] + "|" + a2[1];
						}
					}
				}
				if(!existCol){
					if( finalOrderBy !="")
						finalOrderBy += ",";
					finalOrderBy += colName + "|A";				
				}
				document.getElementById("orderBy").value = finalOrderBy;
			}
			else
				document.getElementById("orderBy").value = colName + "|A";
			formSubmit('listview');
		}	
	</script>
</head>
<body>
<?php require_once 'menu_bar.php'; ?>
<br><br>
<h3>Cake Class : </h3>
<form action='viewClass.php' method='POST' id="listview">
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
		<td><input type="submit" value="Search" onclick="document.getElementById('action').value = 'search';formSubmit('listview');"><input type="button" value="Clear" onclick="clearForm()";></td>
	</tr>	
</table>
<div class="listViewAdmin" >
	<?php getCakeClassListTable($pagenum ,"N"); ?>
</div>
	<input type="hidden" id="action" name="action" value=""/>
	<input type="hidden" id="searchParam" name="searchParam" value=""/>
	<input type="hidden" id="orderBy" name="orderBy" value="<?php echo $orderBy ?>"/>
	<input type="hidden" id="currentPage" name="currentPage" value="viewClass.php"/>
</form>

</body>
</html>