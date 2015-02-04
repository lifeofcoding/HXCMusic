<?php
	include('headers.php');
	//header('Content-Type: application/json');
	header('Access-Control-Allow-Headers: X-Requested-With');
	header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS');
	header('Access-Control-Allow-Credentials: false');
	header('Access-Control-Max-Age: 86400'); // 24 hours
	header('Access-Control-Allow-Headers: X-Requested-With, X-HTTP-Method-Override, Content-Type, Accept');

	include('../includes/database.php');

	///fix cloudflare IP problem
	if ($_SERVER["HTTP_CF_CONNECTING_IP"]) {
		$_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"] ? $_SERVER["HTTP_CF_CONNECTING_IP"] : $_SERVER["REMOTE_ADDR"];
	}

	class User
	{
		public $username;
		public $email;
		public $image;
		public $password;
		public $ip;
		public $userid;
	}

	function sendWelcomeEmail($username, $password, $email){
		// message lines should not exceed 70 characters (PHP rule), so wrap it
		//$message = wordwrap($message, 70);
		ob_start();
		include('../includes/config.php');
		include($website_config['web_root'].'includes/email-tpl.php');
		$content = ob_get_contents();

		// send mail
		$subject = 'Welcome to HXCMusic.com';
		$from = 'no-reply@hxcmusic.com';

		$headers = "From: HXCMusic.com\r\n";
		$headers .= "Reply-To: no-reply@hxcmusic.com\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
		mail($email,$subject,$content,$headers);
	}

	//echo '{"error":{"text":'. $e->getMessage() .'}}';

	$variables = new User();

	$variables->username = $_POST['username'];
	$variables->image = $_POST['image'];
	$variables->email = $_POST['email'];
	if(!isset($_POST['isGoogle'])){
		$variables->password = $_POST['password'];
	}else{
		$variables->password = 'hxc-'.$_POST['email'];
	}
	$variables->ip = $_SERVER['REMOTE_ADDR'];
	$variables->userid = md5($variables->username);
	$recaptcha_challenge_field = $_POST['recaptcha_challenge_field'];
	$recaptcha_response_field = $_POST['recaptcha_response_field'];

	$fields = array(
		'privatekey' => '6LdPXPUSAAAAAA7vxydFDcHgR5rl-mITTU8GALAG',
		'remoteip' => $variables->ip,
		'challenge' => $recaptcha_challenge_field,
		'response' => $recaptcha_response_field
	);

	//url-ify the data for the POST
	foreach($fields as $key=>$value) {
		$fields_string .= $key.'='.$value.'&';
	}
	rtrim($fields_string, '&');

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'http://www.google.com/recaptcha/api/verify');
	curl_setopt($ch,CURLOPT_POST, count($fields));
	curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, "10");
	curl_setopt($ch, CURLOPT_TIMEOUT, "10");
	$gurl = curl_exec($ch);
	curl_close($ch);

	if (strpos($gurl,'false') !== false && !isset($_POST['isGoogle'])) {
    	die('{"error":{"text":"Captcha Incorrect"}}');
	}

	$conn = mysql_connect($db_config['server'], $db_config['username'], $db_config['password']) or die('Could not connect: ' . mysql_error());
	mysql_select_db($db_config['database']) or die('Could not select database');

	$sql = "SELECT * FROM users WHERE ip='".$variables->ip."' OR username='".$variables->username."'";

	// Performing SQL query
	$preCheckResult = mysql_query($sql) or die('Query failed: ' . mysql_error());
	//$result = mysql_fetch_row($result);
	$preCheckResult = mysql_fetch_array($preCheckResult);

	if($preCheckResult['userId'] && !isset($_POST['isGoogle'])){
		die('{"error":{"text":"Username or IP already in use."}}');
	}else{
		if(!$preCheckResult['userId']){
			if(isset($_POST['isGoogle'])){
				mail("lifeofcoding@gmail.com", "New User Signup Via Google!", "Username: $variables->username\nEmail: $variables->email","From: no-reply@hxcmusic.com\n");
			}
			sendWelcomeEmail($variables->username, $variables->password, $variables->email);
			$sql = "INSERT INTO users (`username`,`email`,`image`,`password`,`userlevel`,`ip`,`userid`) VALUES ('".$variables->username."','".$variables->email."','".$variables->image."','".md5($variables->password)."','1','".$variables->ip."','".$variables->userid."')";

			// Performing SQL query
			$createResult = mysql_query($sql) or die('Query failed: ' . mysql_error());
		}
		$fields = array(
			'email' => $variables->email,
			'password' => $variables->password
		);

		//url-ify the data for the POST
		foreach($fields as $key=>$value) {
			$fields_string .= $key.'='.$value.'&';
		}
		rtrim($fields_string, '&');

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $_SERVER['HTTP_HOST'].'/login.php');
		curl_setopt($ch,CURLOPT_POST, count($fields));
		curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, "10");
		curl_setopt($ch, CURLOPT_TIMEOUT, "10");
		$gurl = curl_exec($ch);
		curl_close($ch);
		echo $gurl;
	}

?>