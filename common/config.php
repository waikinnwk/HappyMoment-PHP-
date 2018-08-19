<?php
// Constant Start

$dbhost = 'localhost';
$username = 'cm_user';
$password = 'abc123';
$dbname = 'CakeClass';

/*
$dbhost = 'happymoment.com.hk';
$username = 'happtcom_cake';
$password = '?k[q=6lAVAuz';
$dbname = 'happtcom_cakeclass';
*/
$calendarId = 'tbt5ehqdk3g8stbnuirfrkjq44@group.calendar.google.com';
// Constant End

public function connectDB(){
	$con=mysqli_connect($dbhost,$username,$password,$dbname);
	// Check connection
	if (mysqli_connect_errno($con))
	{
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	
	return $con;
}
?>