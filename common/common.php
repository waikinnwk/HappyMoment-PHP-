<?php
if (!headers_sent()) {
  header("Content-Type:text/html; charset=utf-8");
}
// Constant Start
//$calendarId = 'tbt5ehqdk3g8stbnuirfrkjq44@group.calendar.google.com'; // Dev test
$calendarId = 'sgfubucdfumjr2q2v665g0t8bs@group.calendar.google.com';
// Constant End

function connectDB(){
		

	$dbhost = 'localhost';
	$username = 'cm_user';
	$password = '9VP3KDs9cjCsEj3J';
	$dbname = 'CakeClass';

	/*
	$dbhost = 'localhost';
	$username = 'happtcom_cake';
	$password = '?k[q=6lAVAuz';
	$dbname = 'happtcom_cakeclass';
	*/


	$con=mysqli_connect($dbhost,$username,$password,$dbname);
	// Check connection
	if (mysqli_connect_errno($con))
	{
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	return $con;
}

function closeConn($con){
	mysqli_close($con);
}

function createNewCakeClass($gooEventId,$eventTitle,$quota,$date,$time,$loc,$price,$email,$classGroup){
	
	$con = connectDB();
	
	$selectSql = "SELECT COUNT(1) AS COUNT FROM CakeEvent WHERE  CE_DATE='".$date."';";
	$result = mysqli_query($con,$selectSql);
	while($row = mysqli_fetch_array($result)){
		 $countNum = $row['COUNT'];
	}
	
	$validClassId = false;
	
	while(!$validClassId){
		$classId = $date.chr($countNum + 65) ;

		$classId = str_replace("-", "", $classId);
		
		$selectSql = "SELECT COUNT(1) AS COUNT FROM CakeEvent WHERE  CE_ID ='".$classId."';";
		$result = mysqli_query($con,$selectSql);
		while($row = mysqli_fetch_array($result)){
			 $classIdCountNum = $row['COUNT'];
		}
		if($classIdCountNum > 0){
			$countNum = $countNum + 1;
		}
		else
			$validClassId = true;
		
	}
	
	$sql="INSERT INTO CakeEvent(CE_ID,CE_GOOGLE_EVENT_ID,CE_TITLE, CE_QUOTA, CE_DATE, CE_TIME, CE_LOC, CE_PRICE,CE_STATUS,CE_EMAIL,CE_CCG_ID) VALUES('".$classId."','".$gooEventId."','".$eventTitle."','".$quota."','".$date."','".$time."','".$loc."',".$price.",'A','".$email."',".$classGroup.");";
	if (!mysqli_query($con,$sql))
	{
		die('Error: ' . mysqli_error($con));
	}
	closeConn($con);
	return $classId;
}

function updateCakeClass($eventId,$eventTitle,$quota,$date,$time,$price,$email,$classGroup){
	
	$con = connectDB();
	
	$sql="UPDATE CakeEvent SET CE_TITLE = '".$eventTitle."', CE_QUOTA = '".$quota."', CE_DATE = '".$date."', CE_TIME = '".$time."', CE_PRICE = '".$price."', CE_EMAIL = '".$email."' , CE_CCG_ID = ".$classGroup." where CE_ID = '".$eventId."'";
	
	if (!mysqli_query($con,$sql))
	{
		die('Error: ' . mysqli_error($con));
	}
	closeConn($con);
}

function cancelCakeClass($eventId){
	
	$con = connectDB();
	
	$sql = "DELETE From CakeEvent where CE_ID = '".$eventId."'";
	
	if (!mysqli_query($con,$sql))
	{
		die('Error: ' . mysqli_error($con));
	}
	closeConn($con);
}

function updateApplication($applicationId,$name,$tel,$email,$nop,$remarks,$whatsapp){
	$con = connectDB();

	$sql="UPDATE CakeApplication SET CA_NAME = '".$name."', CA_TEL = '".$tel."', CA_EMAIL = '".$email."', CA_REMARKS = '".$remarks."', CA_NOP = '".$nop."', CA_WHATSAPP = '".$whatsapp."' where CA_ID = '".$applicationId."'";
	
	if (!mysqli_query($con,$sql))
	{
		die('Error: ' . mysqli_error($con));
	}
	
	closeConn($con);
}



function getClassDetail($eventId){
	$con = connectDB();
	$classDetail = array();
	$sql = "SELECT CE_ID, CE_TITLE, CE_QUOTA, CE_DATE, CE_TIME, CE_LOC, CE_PRICE, (SELECT SUM(CA_NOP) FROM CakeApplication WHERE CA_CE_ID = CE_ID AND (CA_STATUS = 'S' or CA_STATUS = 'P') ) AS CE_QUOTA_USED,CE_EMAIL,CE_CCG_ID FROM CakeEvent WHERE  CE_ID='".$eventId."';";
	$result = mysqli_query($con,$sql);
	while($row = mysqli_fetch_array($result)){
		$usedQuota = $row['CE_QUOTA_USED'];
		$quotaLeft = $row['CE_QUOTA'] - $usedQuota;
		 $classDetail = array('classId' => $row['CE_ID'] ,'title' => $row['CE_TITLE'],'quota' => $row['CE_QUOTA'], 'date' => $row['CE_DATE'],'time' => $row['CE_TIME'],'location' => $row['CE_LOC'],'price' => $row['CE_PRICE'],'quotaLeft'=> $quotaLeft,'email'=>$row['CE_EMAIL'],'eventClassGroup'=>$row['CE_CCG_ID']);
	}
	closeConn($con);
	return $classDetail;
	
}

function getClassCalendar($year,$month,$day){
	$con = connectDB();
	
	$sql = "SELECT CE_ID, CE_TITLE, DAY(CE_DATE) AS CE_DAY , CE_TIME FROM CakeEvent WHERE YEAR(CE_DATE) = ".$year." and MONTH(CE_DATE) = ".$month." and DAY(CE_DATE) = ".$day." ORDER BY DAY(CE_DATE), CE_TIME;";
	$result = mysqli_query($con,$sql);
	closeConn($con);
	return $result;
}

function getApplicationDetail($applicationId){
	$con = connectDB();
	$appDetail = array();
	$sql = "SELECT CA_ID , CA_CE_ID, CA_NAME , CA_EMAIL , CA_TEL , CA_REMARKS , CA_NOP, CA_WHATSAPP   FROM  CakeApplication WHERE  CA_ID ='".$applicationId."';";
	$result = mysqli_query($con,$sql);
	while($row = mysqli_fetch_array($result)){
		 $appDetail = array('classId' => $row['CA_CE_ID'] ,'appId' => $row['CA_ID'],'name' => $row['CA_NAME'], 'email' => $row['CA_EMAIL'],'tel' => $row['CA_TEL'],'remarks' => $row['CA_REMARKS'],'nop' => $row['CA_NOP'],'whatsapp'=> $row['CA_WHATSAPP']);
	}
	closeConn($con);
	return $appDetail;
	
}

function createNewApplication($eventId,$name,$tel,$email,$nop,$whatsapp,$remarks,$sendConfirmEmail,$admin){
	$con = connectDB();
	$classDetail = getClassDetail($eventId);
	$classId = $classDetail['classId'];
		
	if($classDetail['quotaLeft'] >= $nop  ){
	
		$selectSql = "SELECT COUNT(1) AS COUNT FROM CakeApplication WHERE  CA_CE_ID ='".$classId."';";
		$result = mysqli_query($con,$selectSql);
		while($row = mysqli_fetch_array($result)){
			 $countNum = $row['COUNT'] + 1;
		}
		
		if(strlen($countNum)<=1){
			$countNum = '0'.$countNum;
		}
		$applicationId = $classId.$countNum;
		
		
		
		$sql = "INSERT INTO CakeApplication(CA_ID,CA_CE_ID,CA_NAME,CA_TEL,CA_EMAIL,CA_NOP,CA_REMARKS,CA_WHATSAPP,CA_STATUS) SELECT '".$applicationId."', CE_ID,'".$name."','".$tel."','".$email."',".$nop.",'".$remarks."','".$whatsapp."','P' FROM CakeEvent WHERE CE_ID ='".$eventId."' ";
		if (!mysqli_query($con,$sql))
		{
			die('Error: ' . mysqli_error($con));
		}	
		closeConn($con);
		
		if($sendConfirmEmail == "Y")
			$result = sendConfirmationEMail($email,$name,$applicationId,$classDetail['title'],$nop,$classDetail['price'],$classDetail['classId'],$classDetail['date'],$classDetail['time'],$classDetail['email'],$admin,false);
				
		if($admin == "Y"){
			updateAdminGooEventDesc($classId,$eventId);
		}
		else{
			updateCommonGooEventDesc($classId,$eventId);
		}
	}
	else 
		$applicationId = "-1";

	
	return $applicationId;
}

function updateCommonGooEventDesc($eventId,$gooEventId){
	/*
	include('admin/google_cal_config.php');
	$service = Zend_Gdata_Calendar::AUTH_SERVICE_NAME; 
	$client = Zend_Gdata_ClientLogin::getHttpClient($googleAccount, $googlePassword, $service);
	$gdataCal = new Zend_Gdata_Calendar($client);
			
	$calendarURL="http://www.google.com/calendar/feeds/".urlencode($calendarID)."/private/full";
			
	$query = $gdataCal->newEventQuery();
	$query->setUser($calendarID);
	$query->setVisibility('private');
	$query->setProjection('full');
	$query->setEvent($gooEventId);
			

	try {
		$event = $gdataCal->getCalendarEventEntry($query);

	} catch (Zend_Gdata_App_Exception $e) {
		var_dump($e);
	}	
	
	$event->content = $gdataCal->newContent(getEventDesc($eventId,$gooEventId));
	$event->save();
	*/
}

function updateAdminGooEventDesc($eventId,$gooEventId){
	/*
	include('google_cal_config.php');
	$service = Zend_Gdata_Calendar::AUTH_SERVICE_NAME; 
	$client = Zend_Gdata_ClientLogin::getHttpClient($googleAccount, $googlePassword, $service);
	$gdataCal = new Zend_Gdata_Calendar($client);
			
	$calendarURL="http://www.google.com/calendar/feeds/".urlencode($calendarID)."/private/full";
			
	$query = $gdataCal->newEventQuery();
	$query->setUser($calendarID);
	$query->setVisibility('private');
	$query->setProjection('full');
	$query->setEvent($gooEventId);
			

	try {
		$event = $gdataCal->getCalendarEventEntry($query);

	} catch (Zend_Gdata_App_Exception $e) {
		var_dump($e);
	}	
	
	$event->content = $gdataCal->newContent(getEventDesc($eventId,$gooEventId));
	$event->save();
	*/
}


function getApplicationListHome($pageNum){
	$con = connectDB();
	$min = ($pageNum - 1) * 10;
	$max = ($pageNum) * 10;
	$rowNumWhere = "row_number > ".$min." and row_number <= ".$max." ";

	$orderbyString = getOrderByString();
	if($orderbyString == "")
		$orderbyString = " ORDER BY CA_CREATE_DATE DESC  ";
	else
		$orderbyString = " ORDER BY ".$orderbyString;	
	
	$selectSql = "SELECT * FROM (SELECT *,@curRow := @curRow + 1 AS row_number FROM (SELECT * FROM CakeApplication WHERE CA_STATUS <> 'D' ".$orderbyString." ) CakeApp,CakeEvent JOIN    (SELECT @curRow := 0) r WHERE CA_CE_ID = CE_ID ) App WHERE ".$rowNumWhere." ;";
	$result = mysqli_query($con,$selectSql);
	closeConn($con);
	return $result;
	
}

function getCountApplicationListHome(){
	$con = connectDB();
	$selectSql = "SELECT COUNT(1) AS COUNT FROM CakeApplication WHERE CA_STATUS <> 'D';";
	$result = mysqli_query($con,$selectSql);
	$count = 0;
	while($row = mysqli_fetch_array($result)){
		 $count = $row['COUNT'];
	}
	closeConn($con);
	return $count;
}

function getApplicationListSearch($pageNum,$viewOld){


	$con = connectDB();
	$min = ($pageNum - 1) * 20;
	$max = ($pageNum) * 20;
	$rowNumWhere = "row_number > ".$min." and row_number <= ".$max." ";
	$searchWhere = getApplicationSearchWhere();	
	$cakeEvent = "Select * from CakeEvent WHERE ";
	if($viewOld == "Y"){
		$cakeEvent .=  "  CE_DATE < CURDATE()  ";
	}
	else {
		$cakeEvent .=  "  CE_DATE >= CURDATE()  ";
	}
	
	$orderbyString = getOrderByString();
	if($orderbyString == "")
		$orderbyString = " ORDER BY CA_CREATE_DATE DESC  ";
	else
		$orderbyString = " ORDER BY ".$orderbyString;
	$selectSql = "SELECT * FROM (SELECT *,@curRow := @curRow + 1 AS row_number FROM (SELECT * FROM CakeApplication ".$searchWhere." ".$orderbyString." ) CakeApp,(".$cakeEvent.") class JOIN    (SELECT @curRow := 0) r WHERE CA_CE_ID = CE_ID ) App WHERE ".$rowNumWhere;

	$result = mysqli_query($con,$selectSql);
	closeConn($con);
	return $result;
	
}

function getCountApplicationListSearch($viewOld){
	$con = connectDB();
	
	$searchWhere = getApplicationSearchWhere();
	
			
	
	$selectSql = "SELECT COUNT(1) AS COUNT FROM CakeApplication, CakeEvent ".$searchWhere;
	if($searchWhere != ""){
		$selectSql .= " AND CA_CE_ID = CE_ID ";
	}
	else{
		$selectSql .= " WHERE CA_CE_ID = CE_ID ";
	}
	
	if($viewOld == "Y"){
		$selectSql .=  " AND CE_DATE < CURDATE() ; ";
	}
	else {
		$selectSql .=  " AND CE_DATE >= CURDATE() ; ";
	}

	$result = mysqli_query($con,$selectSql);
	$count = 0;
	while($row = mysqli_fetch_array($result)){
		 $count = $row['COUNT'];
	}
	closeConn($con);
	return $count;
}

function getApplicationSearchWhere(){

	$searchWhere = " WHERE CA_STATUS <> 'D' ";
	
	if(isset($_POST['classId']) && $_POST['classId'] != ""){
		$searchWhere .= "AND CA_CE_ID like '%".$_POST['classId']."%'";
	}
	else if(isset($_GET['classId']) && $_GET['classId'] != ""){
		$searchWhere .= "AND CA_CE_ID like '%".$_GET['classId']."%'";
	}
	
	if(isset($_POST['appId']) && $_POST['appId'] != ""){
		$searchWhere.= " AND CA_ID like '%".$_POST['appId']."%'";
	}	
	else if(isset($_GET['appId']) && $_GET['appId'] != ""){
		$searchWhere.= " AND CA_ID like '%".$_GET['appId']."%'";
	}		
	
	if(isset($_POST['oldAppId']) && $_POST['oldAppId'] != ""){
		$searchWhere.= " AND CA_PREV_ID like '%".$_POST['oldAppId']."%'";
	}	
	else if(isset($_GET['oldAppId']) && $_GET['oldAppId'] != ""){
		$searchWhere.= " AND CA_PREV_ID like '%".$_GET['oldAppId']."%'";
	}		
	
	if(isset($_POST['name']) && $_POST['name'] != ""){
		$searchWhere.= " AND CA_NAME like '%".$_POST['name']."%'";
	}
	else if(isset($_GET['name']) && $_GET['name'] != ""){
		$searchWhere.= " AND CA_NAME like '%".$_GET['name']."%'";
	}

	if(isset($_POST['email']) && $_POST['email'] != ""){
		$searchWhere.= " AND CA_EMAIL like '%".$_POST['email']."%'";
	}	
	else if(isset($_GET['email']) && $_GET['email'] != ""){
		$searchWhere.= " AND CA_EMAIL like '%".$_GET['email']."%'";
	}	

	if(isset($_POST['tel']) && $_POST['tel'] != ""){
		$searchWhere.= " AND CA_TEL like '%".$_POST['tel']."%'";
	}
	else if(isset($_GET['tel']) && $_GET['tel'] != ""){
		$searchWhere.= " AND CA_TEL like '%".$_GET['tel']."%'";
	}
	
	if(isset($_POST['searchStatus']) && $_POST['searchStatus'] != ""){
		$searchWhere.= " AND CA_STATUS = '".$_POST['searchStatus']."'";
	}
	else if(isset($_GET['searchStatus']) && $_GET['searchStatus'] != ""){
		$searchWhere.= " AND CA_STATUS = '".$_GET['searchStatus']."'";
	}	

	return $searchWhere;
}


function getApplicationListTable(){
	$pagenum = 1;
	if(isset($_POST['pagenum'])){
		$pagenum = $_POST['pagenum'];
	}
	
	$appList = getApplicationListHome($pagenum);
	$count = getCountApplicationListHome();
	$p1 = $count / 10;


	echo "<table><tr>";
	echo "<td onclick=\"setOrderBy('CA_CE_ID');\">Cake Class ".getOrderByImg("CA_CE_ID")."</td>";
	echo "<td onclick=\"setOrderBy('CA_ID');\">Ref No ".getOrderByImg("CA_ID")."</td>";
	echo "<td onclick=\"setOrderBy('CA_NAME');\">Name ".getOrderByImg("CA_NAME")."</td>";
	echo "<td onclick=\"setOrderBy('CA_EMAIL');\">E-Mail ".getOrderByImg("CA_EMAIL")."</td>";
	echo "<td onclick=\"setOrderBy('CA_TEL');\">Tel No. ".getOrderByImg("CA_TEL")."</td>";
	echo "<td onclick=\"setOrderBy('CA_NOP');\">No of Person(s) ".getOrderByImg("CA_NOP")."</td>";
	echo "<td>Total($)</td>";
	echo "<td onclick=\"setOrderBy('CA_STATUS');\">Status ".getOrderByImg("CA_STATUS")."</td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "</tr>";
	
	while($row=mysqli_fetch_array($appList)){
		$totalPrice = $row['CE_PRICE'] * $row['CA_NOP'];
		if( $row['CA_WHATSAPP'] == 'Y'){
			$tel_content = $row['CA_TEL']."<img src=\"whatsapp.jpg\" alt=\"WhatsApp\" height=\"15\" width=\"15\">";
		}
		else{
			$tel_content = $row['CA_TEL'];
		}
			
		if( $row['CA_STATUS'] == 'P'){
			$status = 'Pending';
		}
		else if( $row['CA_STATUS'] == 'S'){
			$status = 'Success';
		}
		else if( $row['CA_STATUS'] == 'F'){
			$status = 'Fail';
		}
			
		$remarks_conent= nl2br($row['CA_REMARKS']);
		/*	
		if(strlen( preg_replace("/[\n\r]/","",$row['CA_REMARKS'])) > 0){
			$remarks_conent = "<img src=\"remarks.jpg\" alt=\"remarks\" height=\"15\" width=\"15\"  onmouseover=\"ShowContent('".$row['CA_ID']."_remarks'); return true;\"  onmouseout=\"HideContent('".$row['CA_ID']."_remarks'); return true;\">";
			$remarks_conent .= "<div id=\"".$row['CA_ID']."_remarks\" style=\"display:none;position:absolute; border-style: solid;background-color: white; padding: 5px;\">Remarks:<br>".nl2br($row['CA_REMARKS'])."</div>";
		}
		*/
			
		$rowString = "<tr><td>".$row['CE_ID']." - ".$row['CE_TITLE']."</td><td>".$row['CA_ID']."</td><td>".$row['CA_NAME']."</td><td>".$row['CA_EMAIL']."</td>";
		$rowString .= "<td>".$tel_content."</td><td>".$row['CA_NOP']."</td><td>$".$totalPrice."</td><td>".$status."</td>";
		$rowString .= "<td>".$remarks_conent."</td>";
		
		$editBtn = "<input type=\"button\" value=\"Edit\" onclick=\"editApplication('".$row['CA_ID']."'); \" />";
		$changeBtn = "<input type=\"button\" value=\"Change Class\" onclick=\"changeClass('".$row['CA_ID']."'); \" />";
		$rowString .= "<td>".$editBtn.$changeBtn."</td>";
		if($row['CA_STATUS'] != 'S')
			$rowString .= "<td><input type=\"button\" value=\"Mark Success\" onclick=\"markStatus('".$row['CA_ID']."','S')\" /></td>";
		if($row['CA_STATUS'] != 'F')
			$rowString .= "<td><input type=\"button\" value=\"Mark Fail\" onclick=\"markStatus('".$row['CA_ID']."','F')\" /></td>";
		if($row['CA_STATUS'] != 'P')
			$rowString .= "<td><input type=\"button\" value=\"Mark Pending\" onclick=\"markStatus('".$row['CA_ID']."','P')\" /></td>";	
		$rowString .= "</tr>";
		echo $rowString;
	}
	
	echo "</table>";
	echo "<div style=\"float: right;\">";
	if($pagenum > 1){ 
		echo "<input type=\"button\" value=\"<<Prev\" onclick=\"prevPage()\" />";
	}
	if($pagenum < $p1){ 
		echo "<input type=\"button\" value=\"Next>>\" onclick=\"nextPage()\" />";  
	}
	echo "</div>";	
	echo "<br>";
	echo "<div align=\"right\">Page :".$pagenum."</div>";
	echo "<div align=\"right\">Total Record : ".$count."</div>";
	echo "<input type=\"hidden\" id=\"pagenum\" name=\"pagenum\" value=\"".$pagenum."\"/>";
	echo "<input type=\"hidden\" id=\"status\" name=\"status\" value=\"\"/>";
	echo "<input type=\"hidden\" id=\"id\" name=\"id\" value=\"\"/>";

}


function getApplicationListSearchTable($pagenum,$viewOld){
	
	$appList = getApplicationListSearch($pagenum,$viewOld);
	$count = getCountApplicationListSearch($viewOld);
	$p1 = $count / 20;

	$totalSuccessP = 0;
	
	echo "<table><tr>";
	echo "<td onclick=\"setOrderBy('CA_CE_ID');\">Cake Class ".getOrderByImg("CA_CE_ID")."</td>";
	echo "<td onclick=\"setOrderBy('CA_ID');\">Ref No ".getOrderByImg("CA_ID")."</td>";
	echo "<td onclick=\"setOrderBy('CA_NAME');\">Name ".getOrderByImg("CA_NAME")."</td>";
	echo "<td onclick=\"setOrderBy('CA_EMAIL');\">E-Mail ".getOrderByImg("CA_EMAIL")."</td>";
	echo "<td onclick=\"setOrderBy('CA_TEL');\">Tel No. ".getOrderByImg("CA_TEL")."</td>";
	echo "<td onclick=\"setOrderBy('CA_NOP');\">No of Person(s) ".getOrderByImg("CA_NOP")."</td>";
	echo "<td>Total($)</td>";
	echo "<td onclick=\"setOrderBy('CA_STATUS');\">Status ".getOrderByImg("CA_STATUS")."</td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "</tr>";
	
	while($row=mysqli_fetch_array($appList)){
		$totalPrice = $row['CE_PRICE'] * $row['CA_NOP'];
		if( $row['CA_WHATSAPP'] == 'Y'){
			$tel_content = $row['CA_TEL']."<img src=\"whatsapp.jpg\" alt=\"WhatsApp\" height=\"15\" width=\"15\">";
		}
		else{
			$tel_content = $row['CA_TEL'];
		}
			
		if( $row['CA_STATUS'] == 'P'){
			$status = 'Pending';
		}
		else if( $row['CA_STATUS'] == 'S'){
			$status = 'Success';
			$totalSuccessP += $row['CA_NOP'];
		}
		else if( $row['CA_STATUS'] == 'F'){
			$status = 'Fail';
		}
			
		$remarks_conent= nl2br($row['CA_REMARKS']);
		/*	
		if(strlen( preg_replace("/[\n\r]/","",$row['CA_REMARKS'])) > 0){
			$remarks_conent = "<img src=\"remarks.jpg\" alt=\"remarks\" height=\"15\" width=\"15\"  onmouseover=\"ShowContent('".$row['CA_ID']."_remarks'); return true;\"  onmouseout=\"HideContent('".$row['CA_ID']."_remarks'); return true;\">";
			$remarks_conent .= "<div id=\"".$row['CA_ID']."_remarks\" style=\"display:none;position:absolute; border-style: solid;background-color: white; padding: 5px;\">Remarks:<br>".nl2br($row['CA_REMARKS'])."</div>";
		}
		*/

		$prevIdStr = "";
		if($row['CA_PREV_ID'] != "")
			$prevIdStr="<br>(".$row['CA_PREV_ID'].")";
		
		$rowString = "<tr><td>".$row['CE_ID']." - ".$row['CE_TITLE']."</td><td>".$row['CA_ID'].$prevIdStr."</td><td>".$row['CA_NAME']."</td><td>".$row['CA_EMAIL']."</td>";
		$rowString .= "<td>".$tel_content."</td><td>".$row['CA_NOP']."</td><td>$".$totalPrice."</td><td>".$status."</td>";
		$rowString .= "<td>".$remarks_conent."</td>";
		
		$editBtn = "<input type=\"button\" value=\"Edit\" onclick=\"editApplication('".$row['CA_ID']."'); \" />";
		$changeBtn = "<input type=\"button\" value=\"Change Class\" onclick=\"changeClass('".$row['CA_ID']."'); \" />";
		$rowString .= "<td>".$editBtn.$changeBtn."</td>";
		
		if($row['CA_STATUS'] != 'S')
			$rowString .= "<td><input type=\"button\" value=\"Mark Success\" onclick=\"markStatus('".$row['CA_ID']."','S')\" /></td>";
		if($row['CA_STATUS'] != 'F')
			$rowString .= "<td><input type=\"button\" value=\"Mark Fail\" onclick=\"markStatus('".$row['CA_ID']."','F')\" /></td>";
		if($row['CA_STATUS'] != 'P')
			$rowString .= "<td><input type=\"button\" value=\"Mark Pending\" onclick=\"markStatus('".$row['CA_ID']."','P')\" /></td>";	
		$rowString .= "<td><input type=\"button\" value=\"Delete\" onclick=\"markStatus('".$row['CA_ID']."','D')\" /></td>";		
		$rowString .= "</tr>";
		echo $rowString;
	}
	
	echo "</table>";
	echo "<div style=\"float: right;\">";
	if($pagenum > 1){ 
		echo "<input type=\"button\" value=\"<<Prev\" onclick=\"prevPage()\" />";
	}
	if($pagenum < $p1){ 
		echo "<input type=\"button\" value=\"Next>>\" onclick=\"nextPage()\" />";  
	}
	echo "</div>";	
	echo "<br>";
	echo "<div align=\"left\">Total Success Person(s) :".$totalSuccessP."</div>";
	echo "<div align=\"right\">Page :".$pagenum."</div>";
	echo "<div align=\"right\">Total Record : ".$count."</div>";
	echo "<input type=\"hidden\" id=\"pagenum\" name=\"pagenum\" value=\"".$pagenum."\"/>";
	echo "<input type=\"hidden\" id=\"status\" name=\"status\" value=\"\"/>";
	echo "<input type=\"hidden\" id=\"id\" name=\"id\" value=\"\"/>";

}

function getApplicationListPendingMoreThanThreeDay($pageNum){
	$con = connectDB();
	$min = ($pageNum - 1) * 20;
	$max = ($pageNum) * 20;
	$rowNumWhere = "row_number > ".$min." and row_number <= ".$max." ";

	$orderbyString = getOrderByString();
	if($orderbyString == "")
		$orderbyString = " ORDER BY CA_CREATE_DATE DESC  ";
	else
		$orderbyString = " ORDER BY ".$orderbyString;	
	
	$selectSql = "SELECT * FROM (SELECT *,@curRow := @curRow + 1 AS row_number FROM (SELECT * FROM CakeApplication WHERE CA_STATUS <> 'D' and CA_STATUS = 'P' AND DATEDIFF(NOW(),CA_CREATE_DATE) > 3 ".$orderbyString." ) CakeApp,CakeEvent JOIN    (SELECT @curRow := 0) r WHERE CA_CE_ID = CE_ID ) App WHERE ".$rowNumWhere." ;";
	$result = mysqli_query($con,$selectSql);
	closeConn($con);
	return $result;
	
}

function getCountApplicationListPendingMoreThanThreeDay(){
	$con = connectDB();
	$selectSql = "SELECT COUNT(1) AS COUNT FROM CakeApplication WHERE CA_STATUS <> 'D' and CA_STATUS = 'P' AND DATEDIFF(NOW(),CA_CREATE_DATE) > 3  ;";
	$result = mysqli_query($con,$selectSql);
	$count = 0;
	while($row = mysqli_fetch_array($result)){
		 $count = $row['COUNT'];
	}
	closeConn($con);
	return $count;
}

function getApplicationListTablePendingMoreThanThreeDay(){
	$pagenum = 1;
	if(isset($_POST['pagenum'])){
		$pagenum = $_POST['pagenum'];
	}
	
	$appList = getApplicationListPendingMoreThanThreeDay($pagenum);
	$count = getCountApplicationListPendingMoreThanThreeDay();
	$p1 = $count / 20;


	echo "<table><tr>";
	echo "<td><input type=\"checkbox\" name=\"checkAll\" value=\"\" onclick=\"toggle(this)\"></td>";
	echo "<td onclick=\"setOrderBy('CA_CE_ID');\">Cake Class ".getOrderByImg("CA_CE_ID")."</td>";
	echo "<td onclick=\"setOrderBy('CA_ID');\">Ref No ".getOrderByImg("CA_ID")."</td>";
	echo "<td onclick=\"setOrderBy('CA_NAME');\">Name ".getOrderByImg("CA_NAME")."</td>";
	echo "<td onclick=\"setOrderBy('CA_EMAIL');\">E-Mail ".getOrderByImg("CA_EMAIL")."</td>";
	echo "<td onclick=\"setOrderBy('CA_TEL');\">Tel No. ".getOrderByImg("CA_TEL")."</td>";
	echo "<td onclick=\"setOrderBy('CA_NOP');\">No of Person(s) ".getOrderByImg("CA_NOP")."</td>";
	echo "<td>Total($)</td>";
	echo "<td onclick=\"setOrderBy('CA_STATUS');\">Status ".getOrderByImg("CA_STATUS")."</td>";
	echo "<td onclick=\"setOrderBy('CA_CREATE_DATE');\">Apply Date ".getOrderByImg("CA_CREATE_DATE")."</td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "</tr>";
	
	while($row=mysqli_fetch_array($appList)){
		$totalPrice = $row['CE_PRICE'] * $row['CA_NOP'];
		if( $row['CA_WHATSAPP'] == 'Y'){
			$tel_content = $row['CA_TEL']."<img src=\"whatsapp.jpg\" alt=\"WhatsApp\" height=\"15\" width=\"15\">";
		}
		else{
			$tel_content = $row['CA_TEL'];
		}
			
		if( $row['CA_STATUS'] == 'P'){
			$status = 'Pending';
		}
		else if( $row['CA_STATUS'] == 'S'){
			$status = 'Success';
		}
		else if( $row['CA_STATUS'] == 'F'){
			$status = 'Fail';
		}
			
		$remarks_conent= nl2br($row['CA_REMARKS']);
		/*	
		if(strlen( preg_replace("/[\n\r]/","",$row['CA_REMARKS'])) > 0){
			$remarks_conent = "<img src=\"remarks.jpg\" alt=\"remarks\" height=\"15\" width=\"15\"  onmouseover=\"ShowContent('".$row['CA_ID']."_remarks'); return true;\"  onmouseout=\"HideContent('".$row['CA_ID']."_remarks'); return true;\">";
			$remarks_conent .= "<div id=\"".$row['CA_ID']."_remarks\" style=\"display:none;position:absolute; border-style: solid;background-color: white; padding: 5px;\">Remarks:<br>".nl2br($row['CA_REMARKS'])."</div>";
		}
		*/
		
		$prevIdStr = "";
		if($row['CA_PREV_ID'] != "")
			$prevIdStr="<br>(".$row['CA_PREV_ID'].")";
			
		$rowString = "<tr><td><input type=\"checkbox\" id=\"appIds[]\" name=\"appIds[]\" value=\"".$row['CA_ID'].$prevIdStr."\"></td><td>".$row['CE_ID']." - ".$row['CE_TITLE']."</td><td>".$row['CA_ID']."</td><td>".$row['CA_NAME']."</td><td>".$row['CA_EMAIL']."</td>";
		$rowString .= "<td>".$tel_content."</td><td>".$row['CA_NOP']."</td><td>$".$totalPrice."</td><td>".$status."</td><td>".$row['CA_CREATE_DATE']."</td>";
		$rowString .= "<td>".$remarks_conent."</td>";
		
		if($row['CA_STATUS'] != 'S')
			$rowString .= "<td><input type=\"button\" value=\"Mark Success\" onclick=\"markStatus('".$row['CA_ID']."','S')\" /></td>";
		if($row['CA_STATUS'] != 'F')
			$rowString .= "<td><input type=\"button\" value=\"Mark Fail\" onclick=\"markStatus('".$row['CA_ID']."','F')\" /></td>";
		if($row['CA_STATUS'] != 'P')
			$rowString .= "<td><input type=\"button\" value=\"Mark Pending\" onclick=\"markStatus('".$row['CA_ID']."','P')\" /></td>";
		$rowString .= "<td><input type=\"button\" value=\"Delete\" onclick=\"markStatus('".$row['CA_ID']."','D')\" /></td>";	
		$rowString .= "</tr>";
		echo $rowString;
	}
	
	echo "</table>";
	echo "<div style=\"float: right;\">";
	if($pagenum > 1){ 
		echo "<input type=\"button\" value=\"<<Prev\" onclick=\"prevPage()\" />";
	}
	if($pagenum < $p1){ 
		echo "<input type=\"button\" value=\"Next>>\" onclick=\"nextPage()\" />";  
	}
	echo "</div>";	
	echo "<br>";
	echo "<div align=\"right\">Page :".$pagenum."</div>";
	echo "<div align=\"right\">Total Record : ".$count."</div>";
	echo "<input type=\"hidden\" id=\"pagenum\" name=\"pagenum\" value=\"".$pagenum."\"/>";
	echo "<input type=\"hidden\" id=\"status\" name=\"status\" value=\"\"/>";
	echo "<input type=\"hidden\" id=\"id\" name=\"id\" value=\"\"/>";

}

function getCakeClassList($pageNum,$viewOld){
	$con = connectDB();
	$min = ($pageNum - 1) * 10;
	$max = ($pageNum) * 10;
	$rowNumWhere = "row_number > ".$min." and row_number <= ".$max." ";
	$searchWhere = getCakeClassSearchWhere($viewOld);
	
	$orderbyString = getOrderByString();
	if($orderbyString == "")
		$orderbyString = " ORDER BY CE_DATE  ";
	else
		$orderbyString = " ORDER BY ".$orderbyString;	

	$selectSql = "SELECT * FROM (SELECT *,@curRow := @curRow + 1 AS row_number FROM (SELECT *,(SELECT SUM(CA_NOP) FROM CakeApplication WHERE CA_CE_ID = CE_ID AND CA_STATUS = 'S' ) AS CE_QUOTA_USED,(SELECT SUM(CA_NOP) FROM CakeApplication    WHERE CA_CE_ID = CE_ID AND CA_STATUS = 'P' ) AS CE_QUOTA_USED_P FROM CakeEvent LEFT JOIN CakeClassGroup ON CE_CCG_ID = CCG_ID ".$searchWhere." ".$orderbyString." ) CakeEvt JOIN    (SELECT @curRow := 0) r) Evt WHERE ".$rowNumWhere." ;";
	$result = mysqli_query($con,$selectSql);
	closeConn($con);
	return $result;
	
}

function getCountCakeClassList($viewOld){
	$con = connectDB();
	$searchWhere = getCakeClassSearchWhere($viewOld);
	$selectSql = "SELECT COUNT(1) AS COUNT FROM CakeEvent ".$searchWhere.";";
	$result = mysqli_query($con,$selectSql);
	$count = 0;
	while($row = mysqli_fetch_array($result)){
		 $count = $row['COUNT'];
	}
	closeConn($con);
	return $count;
}


function getCakeClassSearchWhere($viewOld){

	$searchWhere = "WHERE CE_STATUS <> 'D' ";
	if(isset($_POST['classId']) && $_POST['classId'] != ""){
		$searchWhere .= "AND CE_ID like '%".$_POST['classId']."%'";
	}
	else if(isset($_GET['classId']) && $_GET['classId'] != ""){
		$searchWhere .= "AND CE_ID like '%".$_GET['classId']."%'";
	}
	
	if(isset($_POST['title']) && $_POST['title'] != ""){
		$searchWhere.= " AND CE_TITLE like '%".$_POST['title']."%'";
	}
	else if(isset($_GET['title']) && $_GET['title'] != ""){
		$searchWhere.= " AND CE_TITLE like '%".$_GET['title']."%'";
	}
	
	if(isset($_POST['date']) && $_POST['date'] != ""){
		$searchWhere.= " AND CE_DATE = '".$_POST['date']."'";
	}
	else if(isset($_GET['date']) && $_GET['date'] != ""){
		$searchWhere.= " AND CE_DATE = '".$_GET['date']."'";
	}
	
	if($viewOld == "Y"){
		$searchWhere .=  " AND CE_DATE < CURDATE()  ";
	}
	else {
		$searchWhere .=  " AND CE_DATE >= CURDATE() ";
	}


	return $searchWhere;
}


function getCakeClassListTable($pagenum,$viewOld){
	
	$classList = getCakeClassList($pagenum,$viewOld);
	$count = getCountCakeClassList($viewOld);
	$p1 = $count / 10;


	echo "<table><tr>";
	echo "<td onclick=\"setOrderBy('CE_ID');\">Class ID".getOrderByImg("CE_ID")."</td>";
	echo "<td onclick=\"setOrderBy('CE_TITLE');\">Title".getOrderByImg("CE_TITLE")."</td>";
	echo "<td onclick=\"setOrderBy('CCG_NAME');\">Group".getOrderByImg("CCG_NAME")."</td>";
	echo "<td onclick=\"setOrderBy('CE_DATE');\">Date".getOrderByImg("CE_DATE")."</td>";
	echo "<td onclick=\"setOrderBy('CE_TIME');\">Time".getOrderByImg("CE_TIME")."</td>";
	echo "<td onclick=\"setOrderBy('CE_PRICE');\">Price".getOrderByImg("CE_PRICE")."</td>";
	echo "<td onclick=\"setOrderBy('CE_QUOTA');\">Quota".getOrderByImg("CE_QUOTA")."</td>";
	echo "<td onclick=\"setOrderBy('CE_QUOTA_USED');\">Total PPL".getOrderByImg("CE_QUOTA_USED")."</td>";
	echo "<td onclick=\"setOrderBy('CE_QUOTA_USED_P');\">PPL(Pending)".getOrderByImg("CE_QUOTA_USED")."</td>";
	
	echo "<td></td>";
	echo "</tr>";
	
	while($row=mysqli_fetch_array($classList)){
		
		$editBtn = "<input type=\"button\" value=\"Edit\" onclick=\"editClass('".$row['CE_ID']."'); \" />";
	
		$rowString = "<tr>";
		$rowString .= "<td>".$row['CE_ID']."</td>";
		$rowString .= "<td>".$row['CE_TITLE']."</td>";
		$rowString .= "<td>".$row['CCG_NAME']."</td>";
		$rowString .= "<td>".$row['CE_DATE']."</td>";
		$rowString .= "<td>".$row['CE_TIME']."</td>";
		$rowString .= "<td>".$row['CE_PRICE']."</td>";
		$rowString .= "<td>".$row['CE_QUOTA']."</td>";
		if($row['CE_QUOTA_USED'] == "" || $row['CE_QUOTA_USED'] == null)
			$rowString .= "<td>0</td>";
		else
			$rowString .= "<td>".$row['CE_QUOTA_USED']."</td>";
		if($row['CE_QUOTA_USED_P'] == "" || $row['CE_QUOTA_USED_P'] == null)
			$rowString .= "<td>0</td>";
		else			
			$rowString .= "<td>".$row['CE_QUOTA_USED_P']."</td>";
		$rowString .= "<td>".$editBtn."</td>";
		$rowString .= "</tr>";
		echo $rowString;
	}
	
	echo "</table>";
	echo "<div style=\"float: right;\">";
	if($pagenum > 1){ 
		echo "<input type=\"button\" value=\"<<Prev\" onclick=\"prevPage()\" />";
	}
	if($pagenum < $p1){ 
		echo "<input type=\"button\" value=\"Next>>\" onclick=\"nextPage()\" />";  
	}
	echo "</div>";	
	echo "<br>";
	echo "<div align=\"right\">Page :".$pagenum."</div>";
	echo "<div align=\"right\">Total Record : ".$count."</div>";
	echo "<input type=\"hidden\" id=\"pagenum\" name=\"pagenum\" value=\"".$pagenum."\"/>";
	echo "<input type=\"hidden\" id=\"status\" name=\"status\" value=\"\"/>";
	echo "<input type=\"hidden\" id=\"id\" name=\"id\" value=\"\"/>";

}


function updateApplicationStatus($id,$status,$emailClassDeclare){
	$con = connectDB();
	$sql = "UPDATE CakeApplication SET CA_STATUS ='".$status."' WHERE CA_ID = '".$id."'";
	$emailStatus = false;
	if (!mysqli_query($con,$sql))
	{
		die('Error: ' . mysqli_error($con));
	}	
	if($status == 'S') {
		$selectSql = "SELECT CA_ID,CA_NAME,CA_EMAIL,CA_CE_ID,CA_NOP FROM CakeApplication WHERE  CA_ID ='".$id."';";
		$result = mysqli_query($con,$selectSql);
		while($row = mysqli_fetch_array($result)){
			 $email = $row['CA_EMAIL'];
			 $name = $row['CA_NAME'];
			 $classDetail = getClassDetail($row['CA_CE_ID']);
			 $emailStatus = sendConfirmationSuccessEMail($email,$name,$row['CA_CE_ID'],$classDetail['title'],$row['CA_NOP'],$classDetail['price'],$classDetail['classId'],$classDetail['date'],$classDetail['time'],$emailClassDeclare);
		}
	}
	else if($status == 'F'){
		$selectSql = "SELECT CA_ID,CA_NAME,CA_EMAIL FROM CakeApplication WHERE  CA_ID ='".$id."';";
		$result = mysqli_query($con,$selectSql);
		while($row = mysqli_fetch_array($result)){
			 $email = $row['CA_EMAIL'];
			 $name = $row['CA_NAME'];
			 $emailStatus = sendConfirmationFailEMail($email,$name,$id,$emailClassDeclare);
		}	
		
	}
	else{
		$emailStatus = true;
	}
	closeConn($con);
	return $emailStatus;
}

function updateApplicationPrevId($id,$prev_id){
	$con = connectDB();
	$sql = "UPDATE CakeApplication SET CA_PREV_ID ='".$prev_id."' WHERE CA_ID = '".$id."'";
	$emailStatus = false;
	if (!mysqli_query($con,$sql))
	{
		die('Error: ' . mysqli_error($con));
	}	
}
/*
function getGoogleEventIdByAppId($id){
	$con = connectDB();
	$selectSql = "SELECT CE_GOOGLE_EVENT_ID FROM CakeApplication,CakeEvent WHERE CA_CE_ID = CE_ID AND  CA_ID ='".$id."';";
	$result = mysqli_query($con,$selectSql);
	$id = "";
	while($row = mysqli_fetch_array($result)){
		 $id = $row['CE_GOOGLE_EVENT_ID'];
	}
	
	closeConn($con);
	return $id;
}

function getGoogleEventIdByClassId($id){
	$con = connectDB();
	$selectSql = "SELECT CE_GOOGLE_EVENT_ID FROM CakeEvent WHERE  CE_ID ='".$id."';";
	$result = mysqli_query($con,$selectSql);
	$id = "";
	while($row = mysqli_fetch_array($result)){
		 $id = $row['CE_GOOGLE_EVENT_ID'];
	}
	
	closeConn($con);
	return $id;
}
*/

function getClassIdByAppId($id){
	$con = connectDB();
	$selectSql = "SELECT CE_ID FROM CakeApplication,CakeEvent WHERE CA_CE_ID = CE_ID AND  CA_ID ='".$id."';";
	$result = mysqli_query($con,$selectSql);
	$id = "";
	while($row = mysqli_fetch_array($result)){
		 $id = $row['CE_ID'];
	}
	
	closeConn($con);
	return $id;
}
	
function getChangeCakeClassList($currentClassId,$nop){
	$listHTML = "<select id=\"classList\" name=\"classList\">";
	$con = connectDB();
	$selectSql = "SELECT CE_ID, CE_TITLE, CE_QUOTA, CE_DATE, CE_TIME, CE_PRICE, (SELECT SUM(CA_NOP) FROM CakeApplication WHERE CA_CE_ID = CE_ID AND (CA_STATUS = 'S' or CA_STATUS = 'P') ) AS CE_QUOTA_USED FROM CakeEvent where  CE_DATE >= CURDATE() and CE_ID <> '".$currentClassId."' order by CE_DATE,CE_TIME";
	$result = mysqli_query($con,$selectSql);
	while($row = mysqli_fetch_array($result)){
		$usedQuota = $row['CE_QUOTA_USED'];
		$quotaLeft = $row['CE_QUOTA'] - $usedQuota;
		
		
		if($nop > $quotaLeft)
			$style = "style=\"background-color:red\"";
		else 
			$style = "style=\"background-color:white\"";
		
		
		$listHTML.= "<option ".$style."  value=\"".$row['CE_ID']."\">";

		$title = $row['CE_TITLE'];
		$listHTML.= $row['CE_ID']."&emsp;".$row['CE_DATE']."&emsp;".$row['CE_TIME']." Quota Left : ".$quotaLeft."&emsp;&emsp;".$title;
		$listHTML.="</option>";
	}

	closeConn($con);
	$listHTML.= "</select>";
	return $listHTML;
}
	


function sendConfirmationEMail($email,$name,$applicationId,$title,$nop,$price,$classId,$date,$time,$classEmail,$admin,$emailClassDeclare){
	if(!$emailClassDeclare){
	   if($admin == "Y")
			require("../mail/class.phpmailer.php");
		else
			require("mail/class.phpmailer.php");
	}

    $mail = new PHPMailer();
	/*
    $mail->IsSMTP();  
    $mail->SMTPAuth   = true; 
	$mail->SMTPDebug = 1;
    $mail->Host       = "smtp.gmail.com"; 
	$mail->SMTPSecure = "ssl"; 
    $mail->Port       = 465; 
    $mail->Username   = "waikinnwkapi@gmail.com"; 
    $mail->Password   = "nwk+9218";     
	
	$mail->CharSet = "utf-8";
	$mail->Encoding = "base64";
	
    $mail->SetFrom('waikinnwkapi@gmail.com', 'Cake'); 
    $mail->AddReplyTo('waikinnwkapi@gmail.com', 'Cake'); 
	*/
	try {
		$mail->IsSMTP();  
		$mail->SMTPAuth   = true; 
		$mail->SMTPDebug = 1;
		
		/*
		$mail->Host       = "mail.happymoment.com.hk"; 
		//$mail->SMTPSecure = "ssl"; 
		$mail->Port       = 1025; 
		$mail->Username   = "autoreply@happymoment.com.hk"; 
		$mail->Password   = "0PfqR2F@IFcV"; 
		*/
		$mail->Host       = "smtp.gmail.com"; 
		$mail->SMTPSecure = "ssl"; 
		$mail->Port       = 465; 
		$mail->Username   = "happymoment2013@gmail.com"; 
		$mail->Password   = "lodaikung";  		
		
		$mail->CharSet = "utf-8";
		$mail->Encoding = "base64";
		$mail->IsHTML(true);
		
		$mail->SetFrom('autoreply@happymoment.com.hk', 'Happy Moment'); 
		$mail->AddReplyTo('autoreply@happymoment.com.hk', 'Happy Moment'); 	
		

		$mail->AddAddress($email, $name); // recipient email

		$mail->Subject    = "歡迎報讀 Happy Moment ".$title." ".$date." ".$time." ".$applicationId; // email subject
		
		$bankAcc = "";
		if($classEmail == '1'){
			$bankAcc = "香港恆生銀行戶口 363 517418 883 Happy Moment Limited";
		}
		else if($classEmail == '2') {
			$bankAcc = "匯豐銀行戶口 640-030011-292 Cat Society (Hong Kong) Limited<br>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;渣打戶口 413-10647-752 Cat Society (Hong Kong) Limited";
		}
		
		$totalPrice = $price * $nop;
		$body ="<font size=\"4\" style=\"line-height:200%\">";
		$body .= "親愛的 ".$name."<br>";
		$body .="&emsp;&emsp;&emsp;&emsp;歡迎你報讀HappyMoment的烘培課程！<br>";
		$body .="&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;你的課程資料：<br>";
		$body .="&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;班名：<font color=\"red\">（".$title."）</font>&emsp;課程編號：<font color=\"red\">".$classId."</font><br>";
		$body .="&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;日期：<font color=\"red\"> ".$date."</font>&emsp;時間： <font color=\"red\">".$time."</font><br>";
		$body .="&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;報名人數： <font color=\"red\">".$nop."</font>&emsp;報名編號： <font color=\"red\">".$applicationId."</font><br>";
		$body .="&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;費用： <font color=\"red\">$".$totalPrice."</font><br>";
		$body .="&emsp;&emsp;&emsp;&emsp;請確定課程日期，時間及報名人數正確，核對無誤後，請以下列方法繳付學費：<br>";
		$body .="&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;請將學費以現金形式存入以下戶口：<br>";
		$body .="&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;".$bankAcc."<br>";
		$body .="&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;敬請保留收據，請於入數紙寫上報名編號及登記電話號碼<br>";
		$body .="&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;1)	以whatsapp 將收據連同你的報名編號傳送到5118 5576<br>";
		$body .="&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;2)	以E-mail 方式將收據連同你的報名編號傳送 <font color=\"blue\">application@happymoment.com.hk</font><br>";
		$body .="&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;3)	致電5118 5576 並提供你的入數時間，銀碼，報名編號<br>";
		$body .="&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;課程名額有限，為確保報名成功，請於收到電郵後3日內繳付學費<br>";
		$body .="&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;<font size=\"6\" color=\"red\">由於課程名額有限，請各同學盡快入數，如遇有名額不足，將以入數時間作準（先入數者優先），敬請留意！</font><br>";
		$body .="&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;確認學費收妥後，系統會自動發出一封確認電郵到您登記的電郵<br>";
		$body .="&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;<font size=\"6\" color=\"red\">上課日敬請攜同並出示入數收據，如未能出示收據，本公司有權拒絕貴客上課，敬請留意</font><br>";
		$body .="&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;如有任何疑問，可:<br>";
		$body .="&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;1)	電郵到<font color=\"blue\">enquiry@happymoment.com.hk</font><br>";
		$body .="&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;2)	於Facebook專頁<font color=\"blue\">www.facebook.com/happymoment2013</font> 內inbox留言<br>";
		$body .="&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;3)	於辦公時間內致電 5118 5576<br>";
		$body .= "<br>祝你享受我們為您帶來的 HappyMoment !";
		if($classEmail == '2') {
			$body .= "<br>並謹代表所有受助流浪貓貓，多謝你的捐助";
		}
		$body .= "<br><br>Happy Moment 敬上";
		$body .="</font>";
		
		$mail->Body = $body;

		if(!$mail->Send()) {
		  echo 'Message was not sent.';
		  echo 'Mailer error: ' . $mail->ErrorInfo;
		  return false;
		}
	}
	catch (phpmailerException $e) {
		return false;
	} catch (Exception $e) {
		return false;
	}	
	return true;
}



function sendConfirmationSuccessEMail($email,$name,$applicationId,$title,$nop,$price,$classId,$date,$time,$emailClassDeclare){
	if(!$emailClassDeclare){
		require("../mail/class.phpmailer.php");
	}

    $mail = new PHPMailer();
	try{
		/*
		$mail->IsSMTP();  
		$mail->SMTPAuth   = true; 
		$mail->SMTPDebug = 1;
		$mail->Host       = "smtp.gmail.com"; 
		$mail->SMTPSecure = "ssl"; 
		$mail->Port       = 465; 
		$mail->Username   = "waikinnwkapi@gmail.com"; 
		$mail->Password   = "nwk+9218";     
		
		$mail->CharSet = "utf-8";
		$mail->Encoding = "base64";
		
		$mail->SetFrom('waikinnwkapi@gmail.com', 'Cake'); 
		$mail->AddReplyTo('waikinnwkapi@gmail.com', 'Cake'); 
		*/
		$mail->IsSMTP();  
		$mail->SMTPAuth   = true; 
		$mail->SMTPDebug = 1;
		
		/*
		$mail->Host       = "mail.happymoment.com.hk"; 
		//$mail->SMTPSecure = "ssl"; 
		$mail->Port       = 1025; 
		$mail->Username   = "autoreply@happymoment.com.hk"; 
		$mail->Password   = "0PfqR2F@IFcV";     
		*/
		$mail->Host       = "smtp.gmail.com"; 
		$mail->SMTPSecure = "ssl"; 
		$mail->Port       = 465; 
		$mail->Username   = "happymoment2013@gmail.com"; 
		$mail->Password   = "lodaikung";  			
		
		$mail->CharSet = "utf-8";
		$mail->Encoding = "base64";
		$mail->IsHTML(true);
		
		$mail->SetFrom('autoreply@happymoment.com.hk', 'Happy Moment'); 
		$mail->AddReplyTo('autoreply@happymoment.com.hk', 'Happy Moment'); 

		$mail->AddAddress($email, $name); // recipient email

		$mail->Subject    = "Happy Moment  報名成功確認電郵 ".$title." ".$date." ".$time." ".$applicationId; // email subject
		
		
		$body ="<font size=\"4\" style=\"line-height:200%\">";
		$body .= "親愛的 ".$name."<br>";
		$body .="&emsp;&emsp;&emsp;&emsp;歡迎你報讀HappyMoment的烘培課程！<br>";
		$body .="&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;你的課程資料：<br>";
		$body .="&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;班名：<font color=\"red\">（".$title."）</font>&emsp;課程編號：<font color=\"red\">".$classId."</font><br>";
		$body .="&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;日期：<font color=\"red\"> ".$date."</font>&emsp;時間： <font color=\"red\">".$time."</font><br>";
		$body .="&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;報名人數： <font color=\"red\">".$nop."</font>&emsp;報名編號： <font color=\"red\">".$applicationId."</font><br>";
		$body .="&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;你的報名經已確認！<br>";
		$body .="&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;上課地點:<br>";
		$body .="&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;九龍觀塘鯉魚門道2號 新城工商中心 10樓 5室, 觀塘地鐵站D4 出口直行(約4分鐘)<br>";
		$body .="&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;<font size=\"6\" color=\"red\">上課日敬請攜同並出示入數收據，如未能出示收據，本公司有權拒絕貴客上課，敬請留意</font><br>";	
		$body .="&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;並請留意及遵守 http://www.happymoment.com.hk/rule.php &emsp; 所示之課堂守則<br>";
		$body .="&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;如有任何疑問，可:<br>";
		$body .="&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;1)	電郵到<font color=\"blue\">enquiry@happymoment.com.hk</font><br>";
		$body .="&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;2)	於Facebook專頁<font color=\"blue\">www.facebook.com/happymoment2013</font> 內inbox留言<br>";
		$body .="&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;3)	於辦公時間內致電 5118 5576<br>";
		$body .= "<br>祝你享受我們為您帶來的 HappyMoment !<br><br>Happy Moment 敬上";
		$body .="</font>";
		
		
		
		
		$mail->Body = $body;

		if(!$mail->Send()) {
		  echo 'Message was not sent.';
		  echo 'Mailer error: ' . $mail->ErrorInfo;
		  return false;
		}
	}
	catch (phpmailerException $e) {
		return false;
	} catch (Exception $e) {
		return false;
	}		
	return true;
}


function sendConfirmationFailEMail($email,$name,$applicationId,$emailClassDeclare){
	if(!$emailClassDeclare){
		require("../mail/class.phpmailer.php");
	}
	$appDetail = getApplicationDetail($applicationId);
	$classDetail = getClassDetail(getClassIdByAppId($applicationId));
    $mail = new PHPMailer();
	try{
		/*
		$mail->IsSMTP();  
		$mail->SMTPAuth   = true; 
		$mail->SMTPDebug = 1;
		$mail->Host       = "smtp.gmail.com"; 
		$mail->SMTPSecure = "ssl"; 
		$mail->Port       = 465; 
		$mail->Username   = "waikinnwkapi@gmail.com"; 
		$mail->Password   = "nwk+9218";     
		
		$mail->CharSet = "utf-8";
		$mail->Encoding = "base64";
		
		$mail->SetFrom('waikinnwkapi@gmail.com', 'Cake'); 
		$mail->AddReplyTo('waikinnwkapi@gmail.com', 'Cake'); 
		*/
		$mail->IsSMTP();  
		$mail->SMTPAuth   = true; 
		$mail->SMTPDebug = 1;
		
		/*
		$mail->Host       = "mail.happymoment.com.hk"; 
		//$mail->SMTPSecure = "ssl"; 
		$mail->Port       = 1025; 
		$mail->Username   = "autoreply@happymoment.com.hk"; 
		$mail->Password   = "0PfqR2F@IFcV";      
		*/
		$mail->Host       = "smtp.gmail.com"; 
		$mail->SMTPSecure = "ssl"; 
		$mail->Port       = 465; 
		$mail->Username   = "happymoment2013@gmail.com"; 
		$mail->Password   = "lodaikung";  	
		
		$mail->CharSet = "utf-8";
		$mail->Encoding = "base64";
		$mail->IsHTML(true);
		
		$mail->SetFrom('autoreply@happymoment.com.hk', 'Happy Moment'); 
		$mail->AddReplyTo('autoreply@happymoment.com.hk', 'Happy Moment'); 

		$mail->AddAddress($email, $name); // recipient email

		$mail->Subject    = "報名不成功HAPPYMOMENT ".$classDetail['title']." ".$classDetail['date']."  ".$classDetail['time']; // email subject

		$totalPrice = $classDetail['price'] * $appDetail['nop'];
		$body ="<font size=\"4\" style=\"line-height:200%\">";
		$body .= "親愛的 ".$name."<br>";
		$body .="&emsp;&emsp;&emsp;&emsp;歡迎你報讀HappyMoment的烘培課程！<br>";
		$body .="&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;你的課程資料：<br>";
		$body .="&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;班名：<font color=\"red\">（".$classDetail['title']."）</font>&emsp;課程編號：<font color=\"red\">".$classDetail['classId']."</font><br>";
		$body .="&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;日期：<font color=\"red\"> ".$classDetail['date']."</font>&emsp;時間： <font color=\"red\">".$classDetail['time']."</font><br>";
		$body .="&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;報名人數： <font color=\"red\">".$appDetail['nop']."</font>&emsp;報名編號： <font color=\"red\">".$applicationId."</font><br>";
		$body .="&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;費用： <font color=\"red\">$".$totalPrice."</font><br>";
		$body .="&emsp;&emsp;&emsp;&emsp;很抱歉，但你的報名不成功<br>";
		$body .="&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;可能基於以下原因：<br>";
		$body .="&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;1） 課堂名額已滿 或<br>";
		$body .="&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;2） 我們未能於三日內收到你的報名入數收據<br>";
		$body .="&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;如你經已入數，請到<font color=\"blue\">www.happymoment.com.hk/application.php</font>&emsp;選擇另一時段後與我們聯絡，我們將優先處理你的報名<br>";
		$body .="&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;如有任何疑問，可:<br>";
		$body .="&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;1)	電郵到<font color=\"blue\">enquiry@happymoment.com.hk</font><br>";
		$body .="&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;2)	於Facebook專頁<font color=\"blue\">www.facebook.com/happymoment2013</font> 內inbox留言<br>";
		$body .="&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;3)	於辦公時間內致電 5118 5576<br>";
		$body .= "<br>祝你享受我們為您帶來的 HappyMoment !<br><br>Happy Moment 敬上";



		$mail->Body = $body;
		
		
		

		if(!$mail->Send()) {
		  echo 'Message was not sent.';
		  echo 'Mailer error: ' . $mail->ErrorInfo;
		  return false;
		}
	}
	catch (phpmailerException $e) {
		return false;
	} catch (Exception $e) {
		return false;
	}	
	return true;
}

function getEventDesc($classId,$gooEventId){
	$classDetail = getClassDetail($classId);
	


	$classIdDesc = "<p>課程編號: ".$classId."</p>";
	$price = "<p>價錢 : 每位 $".$classDetail['price']."</p>";
		
	$quotaDesc = "<p>課程名額: ".$classDetail['quota']."&emsp;&emsp;尚餘名額: ".$classDetail['quotaLeft']."</p>";
	$appLink = '<a href=\'http://' . $_SERVER['HTTP_HOST'].'/applyClass.php?eventId='.$gooEventId.'\'>報名</a>';
	if($classDetail['quotaLeft'] > 0){
		$desc = $classIdDesc.$price.$quotaDesc.$appLink;
	}
	else{
		$desc = $classIdDesc.$price.$quotaDesc;
	}
	return $desc;
}


function redirectMainPage(){
	header('Location: http://' . $_SERVER['HTTP_HOST']);
}

function adminAuthen($logon,$pwd){
	$con = connectDB();
	$selectSql = "SELECT 1 FROM AdminUser WHERE  USR_ID ='".$logon."' and USR_PWD = md5('".$pwd."') ;";
	$result = mysqli_query($con,$selectSql);
	$logonResult = false;
	while($row = mysqli_fetch_array($result)){
		$logonResult = true;
	}
	closeConn($con);
	return $logonResult;
}
function getOrderByImg($colName){
	$orderBy = "";
	$imgString = "";
	if(isset($_POST['orderBy']) && $_POST['orderBy'] != "")
		$orderBy = $_POST['orderBy'];
	else if(isset($_GET['orderBy']) && $_GET['orderBy'] != "")
		$orderBy = $_GET['orderBy'];
	else 
		return "";
	$a1 = array();
	$a1 = preg_split("/,/",$orderBy);

	for ($i = 0; $i < sizeof($a1); $i++) {
	  if($a1[$i] == $colName."|A")
		$imgString =  "<img src=\"arrow_a.png\" alt=\"ASE\" height=\"15\" width=\"15\">";
	  else if($a1[$i] == $colName."|D")
		$imgString =  "<img src=\"arrow_d.png\" alt=\"DESC\" height=\"15\" width=\"15\">";
	}
	return $imgString;
}

function getOrderByString(){
	$orderBy = "";
	$orderByString = "";
	if(isset($_POST['orderBy']) && $_POST['orderBy'] != "")
		$orderBy = $_POST['orderBy'];
	else if(isset($_GET['orderBy']) && $_GET['orderBy'] != "")
		$orderBy = $_GET['orderBy'];
	else 
		return "";
	$a1 = array();
	$a1 = preg_split("/,/",$orderBy);

	for ($i = 0; $i < sizeof($a1); $i++) {
		$a2 = array();
		$a2 = preg_split("/\|/",$a1[$i]);
		
		if(sizeof($a2) > 1){
			if($a2[1] == 'A'){
				if($orderByString == "")
					$orderByString .= " ".$a2[0]." ASC ";
				else
					$orderByString .= ", ".$a2[0]." ASC ";
			}
			else if($a2[1] == 'D'){
				if($orderByString == "")
					$orderByString .= " ".$a2[0]." DESC ";
				else
					$orderByString .= ", ".$a2[0]." DESC ";
			}
				
		}
	}
	return $orderByString;
}


function createNewCakeClassTab($name){
	
	$con = connectDB();
	
	
	$sql="INSERT INTO CakeTab(CT_NAME) VALUES('".$name."');";
	if (!mysqli_query($con,$sql))
	{
		die('Error: ' . mysqli_error($con));
	}
	closeConn($con);
}

function getCakeClassTab(){
	$con = connectDB();
	$selectSql = "SELECT CT_ID,CT_NAME,CT_SEQ FROM CakeTab WHERE CT_STATUS IS NULL OR CT_STATUS  <> 'D' order by CT_SEQ";
	$result = mysqli_query($con,$selectSql);
	$i = 0;
	$tabList = array();
	while($row = mysqli_fetch_array($result)){
		$tabId = $row['CT_ID'];
		$tabName = $row['CT_NAME'];
		$tabSeq = $row['CT_SEQ'];
		
		$tabInfo = array("id"=> $tabId,"name"=> $tabName,"seq" => $tabSeq); 
		$tabList[$i] = $tabInfo;
		$i++;
	}

	closeConn($con);
	return $tabList;
}

function getCakeClassTabById($id){
	$con = connectDB();
	$selectSql = "SELECT CT_ID,CT_NAME FROM CakeTab WHERE CT_ID = '".$id."'";
	$result = mysqli_query($con,$selectSql);
	$tab = array();
	while($row = mysqli_fetch_array($result)){
		$tabId = $row['CT_ID'];
		$tabName = $row['CT_NAME'];
		
		$tab = array("id"=> $tabId,"name"=> $tabName); 
	}

	closeConn($con);
	return $tab;
}

function updateCakeClassTab($id,$tabName){
	$con = connectDB();
	$sql="UPDATE CakeTab SET CT_NAME = '".$tabName."' WHERE CT_ID = '".$id."'";
	if (!mysqli_query($con,$sql))
	{
		die('Error: ' . mysqli_error($con));
	}	
	closeConn($con);
}

function markDeleteCakeClassTab($id){
	$con = connectDB();
	$sql="UPDATE CakeTab SET CT_STATUS = 'D' WHERE CT_ID = '".$id."'";
	if (!mysqli_query($con,$sql))
	{
		die('Error: ' . mysqli_error($con));
	}	
	closeConn($con);
}

function createNewCakeClassGroup($groupName,$tabId,$albumId,$desc,$price,$showPrice){
	
	$con = connectDB();
	
	
	$sql="INSERT INTO CakeClassGroup(CCG_CT_ID,CCG_NAME,CCG_ALBUM_ID,CCG_DESC,CCG_PRICE,CCG_STATUS,CCG_SHOW_PRICE) VALUES(".$tabId.",'".$groupName."','".$albumId."','".$desc."','".$price."','A','".$showPrice."');";
	if (!mysqli_query($con,$sql))
	{
		die('Error: ' . mysqli_error($con));
	}
	closeConn($con);
}

function getCakeClassGroup($tab){
	$con = connectDB();
	$selectSql = "SELECT CCG_ID,CCG_NAME,CCG_ALBUM_ID,CCG_DESC,CCG_PRICE,CCG_STATUS,CCG_SEQ,CCG_SHOW_PRICE,(SELECT COUNT(1) FROM CakeEvent WHERE  CE_CCG_ID= CCG_ID AND CE_DATE >= sysdate()) AS noc  FROM CakeClassGroup WHERE CCG_CT_ID = ".$tab." and CCG_STATUS  <> 'D' order by CCG_SEQ";
	$result = mysqli_query($con,$selectSql);
	$i = 0;
	$groupList = array();
	while($row = mysqli_fetch_array($result)){
		$groupId = $row['CCG_ID'];
		$groupName = $row['CCG_NAME'];
		$albumId = $row['CCG_ALBUM_ID'];
		$desc = $row['CCG_DESC'];
		$price = $row['CCG_PRICE'];
		$status = $row['CCG_STATUS'];
		$seq = $row['CCG_SEQ'];
		$showPrice = $row['CCG_SHOW_PRICE'];
		$noc = $row['noc'];
		
		$groupInfo = array("id"=> $groupId,"name"=> $groupName,"albumId" => $albumId,'desc' => $desc, 'price' => $price,'status' => $status,'seq' => $seq,'showPrice' => $showPrice,'noc' => $noc); 
		$groupList[$i] = $groupInfo;
		$i++;
	}

	closeConn($con);
	return $groupList;
}

function getAllCakeClassGroup(){
	$con = connectDB();
	$selectSql = "SELECT CCG_ID,CCG_NAME,CCG_ALBUM_ID,CT_NAME,CCG_DESC,CCG_PRICE,CCG_STATUS,CCG_SEQ,CCG_SHOW_PRICE,(SELECT COUNT(1) FROM CakeEvent WHERE  CE_CCG_ID= CCG_ID AND CE_DATE >= sysdate()) AS noc  FROM CakeClassGroup,CakeTab WHERE CCG_CT_ID = CT_ID and CCG_STATUS  <> 'D'";
	$result = mysqli_query($con,$selectSql);
	$i = 0;
	$groupList = array();
	while($row = mysqli_fetch_array($result)){
		$groupId = $row['CCG_ID'];
		$groupName = $row['CCG_NAME'];
		$albumId = $row['CCG_ALBUM_ID'];
		$tabName = $row['CT_NAME'];
		$desc = $row['CCG_DESC'];
		$price = $row['CCG_PRICE'];
		$status = $row['CCG_STATUS'];
		$seq = $row['CCG_SEQ'];
		$showPrice = $row['CCG_SHOW_PRICE'];
		$noc = $row['noc'];
		
		$groupInfo = array("id"=> $groupId,"name"=> $groupName,"albumId" => $albumId,'tabName' => $tabName,'desc' => $desc, 'price' => $price,'status' => $status,'seq' => $seq,'showPrice' => $showPrice,'noc' => $noc); 
		$groupList[$i] = $groupInfo;
		$i++;
	}

	closeConn($con);
	return $groupList;
}

function getAllCakeClassGroupById($id){
	$con = connectDB();
	$selectSql = "SELECT CCG_ID,CCG_NAME,CCG_ALBUM_ID,CCG_CT_ID,CCG_DESC,CCG_PRICE,CCG_STATUS,CCG_SEQ,CCG_SHOW_PRICE, (SELECT COUNT(1) FROM CakeEvent WHERE  CE_CCG_ID= CCG_ID AND CE_DATE >= sysdate()) AS noc FROM CakeClassGroup WHERE CCG_ID = '".$id."'";
	$result = mysqli_query($con,$selectSql);
	$i = 0;
	$groupInfo = array();
	while($row = mysqli_fetch_array($result)){
		$groupId = $row['CCG_ID'];
		$groupName = $row['CCG_NAME'];
		$albumId = $row['CCG_ALBUM_ID'];
		$tab = $row['CCG_CT_ID'];
		$desc = $row['CCG_DESC'];
		$price = $row['CCG_PRICE'];
		$status = $row['CCG_STATUS'];
		$seq = $row['CCG_SEQ'];
		$showPrice = $row['CCG_SHOW_PRICE'];
		$noc = $row['noc'];
		
		$groupInfo = array("id"=> $groupId,"name"=> $groupName,"albumId" => $albumId,'tab' => $tab,'desc' => $desc,'price' => $price,'status' => $status,'seq' => $seq,'showPrice' => $showPrice,'noc' => $noc); 
	}

	closeConn($con);
	return $groupInfo;
}

function updateCakeClassGroup($id,$groupName,$tabId,$albumId,$desc,$price,$showPrice){
	
	$con = connectDB();
	
	
	$sql="UPDATE CakeClassGroup SET CCG_CT_ID = ".$tabId.",CCG_NAME = '".$groupName."',CCG_ALBUM_ID = '".$albumId."' ,CCG_DESC = '".$desc."',CCG_PRICE = '".$price."', CCG_SHOW_PRICE = '".$showPrice."' WHERE CCG_ID = ".$id.";";
	if (!mysqli_query($con,$sql))
	{
		die('Error: ' . mysqli_error($con));
	}
	closeConn($con);
}

function markDeleteCakeClassGroup($id){
	$con = connectDB();
	$sql="UPDATE CakeClassGroup SET CCG_STATUS = 'D' WHERE CCG_ID = '".$id."'";
	if (!mysqli_query($con,$sql))
	{
		die('Error: ' . mysqli_error($con));
	}	
	closeConn($con);
}


function getClassListByGroupId($groupId){
	$con = connectDB();
	$i = 0;
	$classList = array();
	
	$sql = "SELECT CE_ID, CE_TITLE, CE_QUOTA, CE_DATE, CE_TIME, CE_LOC, CE_PRICE, (SELECT SUM(CA_NOP) FROM CakeApplication WHERE CA_CE_ID = CE_ID AND (CA_STATUS = 'S' or CA_STATUS = 'P') ) AS CE_QUOTA_USED,CE_EMAIL,CE_CCG_ID,CE_GOOGLE_EVENT_ID FROM CakeEvent WHERE  CE_CCG_ID='".$groupId."' AND CE_DATE >= sysdate() order by CE_DATE,CE_TIME;";
	$result = mysqli_query($con,$sql);
	while($row = mysqli_fetch_array($result)){
		$classDetail = array();
		$usedQuota = $row['CE_QUOTA_USED'];
		$quotaLeft = $row['CE_QUOTA'] - $usedQuota;
		$classDetail = array('classId' => $row['CE_ID'] ,'title' => $row['CE_TITLE'],'quota' => $row['CE_QUOTA'], 'date' => $row['CE_DATE'],'time' => $row['CE_TIME'],'location' => $row['CE_LOC'],'price' => $row['CE_PRICE'],'quotaLeft'=> $quotaLeft,'email'=>$row['CE_EMAIL'],'eventClassGroup'=>$row['CE_CCG_ID'],'gooEventId'=>$row['CE_GOOGLE_EVENT_ID']);
		$classList[$i] = $classDetail;
		$i++;
	}
	closeConn($con);
	return $classList;
	
}

function getAllClassList(){
	$con = connectDB();
	$i = 0;
	$classList = array();
	
	$sql = "SELECT CE_ID, CE_TITLE, CE_QUOTA, CE_DATE, CE_TIME, CE_LOC, CE_PRICE, (SELECT SUM(CA_NOP) FROM CakeApplication WHERE CA_CE_ID = CE_ID AND (CA_STATUS = 'S' or CA_STATUS = 'P') ) AS CE_QUOTA_USED,CE_EMAIL,CE_CCG_ID,CE_GOOGLE_EVENT_ID FROM CakeEvent WHERE CE_DATE >= sysdate() order by CE_DATE,CE_TIME;";
	$result = mysqli_query($con,$sql);
	while($row = mysqli_fetch_array($result)){
		$classDetail = array();
		$usedQuota = $row['CE_QUOTA_USED'];
		$quotaLeft = $row['CE_QUOTA'] - $usedQuota;
		$classDetail = array('classId' => $row['CE_ID'] ,'title' => $row['CE_TITLE'],'quota' => $row['CE_QUOTA'], 'date' => $row['CE_DATE'],'time' => $row['CE_TIME'],'location' => $row['CE_LOC'],'price' => $row['CE_PRICE'],'quotaLeft'=> $quotaLeft,'email'=>$row['CE_EMAIL'],'eventClassGroup'=>$row['CE_CCG_ID'],'gooEventId'=>$row['CE_GOOGLE_EVENT_ID']);
		$classList[$i] = $classDetail;
		$i++;
	}
	closeConn($con);
	return $classList;
	
}

function updateClassGroupStatus($id,$status){
	$con = connectDB();
	$sql="UPDATE CakeClassGroup SET CCG_STATUS = '".$status."' WHERE CCG_ID = '".$id."'";
	if (!mysqli_query($con,$sql))
	{
		die('Error: ' . mysqli_error($con));
	}	
	closeConn($con);
}	

function updateClassGroupSeq($id,$seq){
	$con = connectDB();
	$sql="UPDATE CakeClassGroup SET CCG_SEQ = ".$seq." WHERE CCG_ID = '".$id."'";
	if (!mysqli_query($con,$sql))
	{
		die('Error: ' . mysqli_error($con));
	}	
	closeConn($con);
}

function updateTabSeq($id,$seq){
	$con = connectDB();
	$sql="UPDATE CakeTab SET CT_SEQ = ".$seq." WHERE CT_ID = '".$id."'";
	if (!mysqli_query($con,$sql))
	{
		die('Error: ' . mysqli_error($con));
	}	
	closeConn($con);
}

function getSysValue($id){
	$con = connectDB();
	$selectSql = "SELECT SYS_VALUE,SYS_STATUS FROM SYS_TABLE WHERE SYS_ID = '".$id."' ";
	$result = mysqli_query($con,$selectSql);
	$sysValue = array();
	while($row = mysqli_fetch_array($result)){
		$value = $row['SYS_VALUE'];
		$status = $row['SYS_STATUS'];	
		$sysValue = array("value"=> $value,"status"=> $status); 
	}

	closeConn($con);
	return $sysValue;
}

function updateSysValue($id,$value,$status){
	$con = connectDB();
	$sql="UPDATE SYS_TABLE SET SYS_VALUE = '".$value."', SYS_STATUS = '".$status."' WHERE SYS_ID = '".$id."'";
	if (!mysqli_query($con,$sql))
	{
		die('Error: ' . mysqli_error($con));
	}	
	closeConn($con);
}


function getAllApplicationEmail(){
	$con = connectDB();
	$i = 0;
	$emailList = array();
	$sql = "SELECT DISTINCT CA_EMAIL FROM  CakeApplication ";
	$result = mysqli_query($con,$sql);
	while($row = mysqli_fetch_array($result)){
		$emailList[$i] = $row['CA_EMAIL'];
		$i++;
	}
	closeConn($con);
	return $emailList;
	
}

?>