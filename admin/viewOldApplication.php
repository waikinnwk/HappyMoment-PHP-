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
	
	//$appList = getApplicationListSearch($pagenum,"Y");
	$count = getCountApplicationListSearch("Y");
	$p1 = $count / 20;
	
	
	$classId =  "";
	$appId = "";
	$name = "";
	$email = "";
	$tel = "";
	$searchStatus = "";
	
	if(isset($_POST['classId']) && $_POST['classId'] != "")
		$classId = $_POST['classId'];
	else if(isset($_GET['classId']) && $_GET['classId'] != "")
		$classId = $_GET['classId'];
		
	if(isset($_POST['appId']) && $_POST['appId'] != "")
		$appId = $_POST['appId'];
	else if(isset($_GET['appId']) && $_GET['appId'] != "")
		$appId = $_GET['appId'];

	if(isset($_POST['name']) && $_POST['name'] != "")
		$name = $_POST['name'];
	else if(isset($_GET['name']) && $_GET['name'] != "")
		$name = $_GET['name'];

	if(isset($_POST['email']) && $_POST['email'] != "")
		$email = $_POST['email'];
	else if(isset($_GET['email']) && $_GET['email'] != "")
		$email = $_GET['email'];

	if(isset($_POST['tel']) && $_POST['tel'] != "")
		$tel = $_POST['tel'];
	else if(isset($_GET['tel']) && $_GET['tel'] != "")
		$tel = $_GET['tel'];
		
	if(isset($_POST['searchStatus']) && $_POST['searchStatus'] != "")
		$searchStatus = $_POST['searchStatus'];
	else if(isset($_GET['searchStatus']) && $_GET['searchStatus'] != "")
		$searchStatus = $_GET['searchStatus'];			
	
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
				document.getElementById("searchParam").value = concatSearchParam().replace("searchParam=","");
				document.getElementById("listview").action = "updateApplicationStatus.php";
				formSubmit('listview');
			}
		}
		
		function editApplication(applicationId){
			window.location = "http://" + "<?php echo $_SERVER['HTTP_HOST']."/admin/editApplication.php?applicationId=" ?>" + applicationId + "&" + concatSearchParam();
		}
		function changeClass(applicationId){
			window.location = "http://" + "<?php echo $_SERVER['HTTP_HOST']."/admin/changeClass.php?applicationId=" ?>" + applicationId + "&" + concatSearchParam();
		}

		function concatSearchParam(){
			var searchParam = "searchParam=";
			searchParam += "pagenum|" + <?php echo $pagenum?>;
			if(document.getElementById("classId").value != '')
				searchParam += ",classId|" + document.getElementById("classId").value;
			if(document.getElementById("appId").value != '')
				searchParam += ",appId|" + document.getElementById("appId").value;	
			if(document.getElementById("name").value != '')
				searchParam += ",name|" + document.getElementById("name").value;	
			if(document.getElementById("email").value != '')
				searchParam += ",email|" + document.getElementById("email").value;					
			if(document.getElementById("tel").value != '')
				searchParam += ",tel|" + document.getElementById("tel").value;
			if(document.getElementById("searchStatus").value != '')
				searchParam += ",searchStatus|" + document.getElementById("searchStatus").value;				
			return searchParam;
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
<h3>Search Application(Old) : </h3>
<form action='viewOldApplication.php' method='POST' id="listview">
<table>
	<tr>
		<td>Class Id : </td>
		<td><input type="text" name="classId" id="classId" value="<?php echo $classId; ?>" ></td>
	</tr>
	<tr>
		<td>Application Id : </td>
		<td><input type="text" name="appId" id="appId" value="<?php echo $appId; ?>"  ></td>
	</tr>	
	<tr>
		<td>Applicant Name : </td>
		<td><input type="text" name="name" id="name" value="<?php echo $name; ?>"  ></td>
	</tr>
	<tr>
		<td>Email : </td>
		<td><input type="text" name="email" id="email" value="<?php echo $email; ?>"  ></td>
	</tr>
	<tr>
		<td>Tel No : </td>
		<td><input type="text" name="tel" id="tel" value="<?php echo $tel; ?>"  ></td>
	</tr>
	<tr>
		<td>Status : </td>
		<td>
			<select id="searchStatus" name="searchStatus">
				<option value="" <?php if($searchStatus == "") echo "selected"; ?> >All</option>
				<option value="P" <?php if($searchStatus == "P") echo "selected"; ?> >Pending</option>
				<option value="S" <?php if($searchStatus == "S") echo "selected"; ?> >Success</option>
				<option value="F" <?php if($searchStatus == "F") echo "selected"; ?> >Fail</option>		
			</select>		
		</td>
	</tr>	
	<tr>
		<td></td>
		<td><input type="submit" value="Search" onclick="document.getElementById('action').value = 'search';formSubmit('listview');"><input type="reset" value="Clear"></td>
	</tr>	
</table>
<div class="listViewAdmin" >
	<?php getApplicationListSearchTable($pagenum,"Y"); ?>
</div>
	<input type="hidden" id="action" name="action" value=""/>
	<input type="hidden" id="viewOld" name="viewOld" value="Y"/>
	<input type="hidden" id="searchParam" name="searchParam" value=""/>
	<input type="hidden" id="orderBy" name="orderBy" value="<?php echo $orderBy ?>"/>
	<input type="hidden" id="currentPage" name="currentPage" value="viewOldApplication.php"/>
</form>

</body>
</html>