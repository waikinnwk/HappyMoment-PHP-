<?php 
	require_once 'adminCommon.php';
	require_once '../common/common.php';
	$pagenum = 1;
	if(isset($_POST['pagenum'])){
		$pagenum = $_POST['pagenum'];
	}

	$orderBy = "";
	
	if(isset($_POST['orderBy'])){
		$orderBy = $_POST['orderBy'];
	}
	else if(isset($_GET['orderBy'])){
		$orderBy = $_GET['orderBy'];
	}		
	
	$appList = getApplicationListHome($pagenum);
	$count = getCountApplicationListHome();
	$p1 = $count / 10;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<link rel="stylesheet" href="../css/addForm.css" media="screen, projection" />
	<script type="text/javascript" src="../js/common.js"></script>
	<script type="text/javascript">
		function prevPage(){
			document.getElementById("pagenum").value = parseInt(document.getElementById("pagenum").value) - 1;
			formSubmit('listview');
		}
		function nextPage(){
		document.getElementById("pagenum").value = parseInt(document.getElementById("pagenum").value) + 1;
			formSubmit('listview');
		}
		
		function markStatus(id,status){
			var confirmMsg = "Are you sure to mark " + id + " to ";
			if(status == 'S')
				confirmMsg += "Success";
			else if(status == 'F')
				confirmMsg += "Fail";
			else if(status == 'P')
				confirmMsg += "Pending";
				
			
			confirmMsg += "?";
			if(confirm(confirmMsg)){
				document.getElementById("id").value = id;
				document.getElementById("status").value = status;
				document.getElementById("listview").action = "updateApplicationStatus.php";
				formSubmit('listview');
			}
		}

		function editApplication(applicationId){
			window.location = "http://" + "<?php echo $_SERVER['HTTP_HOST']."/admin/editApplication.php?applicationId=" ?>" + applicationId;
		}				
		function changeClass(applicationId){
			window.location = "http://" + "<?php echo $_SERVER['HTTP_HOST']."/admin/changeClass.php?applicationId=" ?>" + applicationId;
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
<h3>The latest Application : </h3>
<form action='adminHome.php' method='POST' id="listview">
<div class="listViewAdmin" >
	<?php getApplicationListTable(); ?>
</div>
	<input type="hidden" id="action" name="action" value=""/>
	<input type="hidden" id="orderBy" name="orderBy" value="<?php echo $orderBy ?>"/>
	<input type="hidden" id="currentPage" name="currentPage" value="adminHome.php"/>
</form>

</body>
</html>