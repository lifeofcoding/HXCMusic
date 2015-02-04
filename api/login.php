<?php
include('headers.php');
header('Access-Control-Allow-Headers: X-Requested-With');
header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Credentials: false');
header('Access-Control-Max-Age: 86400'); // 24 hours
header('Access-Control-Allow-Headers: X-Requested-With, X-HTTP-Method-Override, Content-Type, Accept');

include('../includes/database.php');

if(isset($_REQUEST['email'])){
		$isEmail = true;
    	$paramEmail = $_REQUEST['email'];
	}else{
		$isEmail = false;
		$paramUsername = $_REQUEST['username'];
	}
    $paramPassword = $_REQUEST['password']; // Getting parameter with names
/* echo $paramPassword. ' - ' .md5($paramPassword);
die; */
	if($isEmail){
		$sql = "SELECT * FROM users WHERE email='".$paramEmail."' AND password='".md5($paramPassword)."'";
	}else{
		$sql = "SELECT * FROM users WHERE username='".$paramUsername."' AND password='".md5($paramPassword)."'";
	}

	$conn = mysql_connect($db_config['server'], $db_config['username'], $db_config['password']) or die('Could not connect: ' . mysql_error());
	mysql_select_db($db_config['database']) or die('Could not select database');

	// Performing SQL query
	$result = mysql_query($sql) or die('Query failed: ' . mysql_error());
	//$result = mysql_fetch_row($result);
	$result = mysql_fetch_array($result);

		if($result['userId']){
			session_start();
			$_SESSION['user_id'] = $result['userId'];
			$_SESSION['isLoggedIn'] = true;
			echo json_encode($result);
		}else{
			echo '{"error":{"text":"incorrect"}}';
		}
?>