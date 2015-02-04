<?php
	session_start();

	class User
	{
		public $username;
		public $email;
		public $image;
		public $password;
		public $ip;
		public $userid;
	}

	//echo '{"error":{"text":'. $e->getMessage() .'}}';

	$variables = new User();

	$variables->username = $_POST['username'];
	$variables->image = $_POST['image'];
	$variables->email = $_POST['email'];
	$variables->password = $_POST['password'];
	$variables->ip = $_SERVER['REMOTE_ADDR'];
	$variables->userid = md5($variables->username);

	$sql = "SELECT userid FROM users WHERE ip='".$variables->ip."'";
	try {
		$dbCon = getConnection();
		$stmt = $dbCon->prepare($sql);  
		$stmt->bindParam("ip", $variables->ip);
		$stmt->execute();
		$user = $stmt->fetchObject();  
		$dbCon = null;
		if(isset($user->userid)){
			die('{"error":{"text":"One account per ip"}}');
		}
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}';
	}
 
    $sql = "INSERT INTO users (`username`,`email`,`image`,`password`,`userlevel`,`ip`,`userid`) VALUES ('".$variables->username."','".$variables->email."','".$variables->image."','".md5($variables->password)."','1','".$variables->ip."','".$variables->userid."')";
    try {
        $dbCon = getConnection();
        $stmt = $dbCon->prepare($sql);  
        $stmt->bindParam("username", $variables->username);
        $stmt->bindParam("email", $variables->email);
        $stmt->bindParam("image", $variables->image);
		$stmt->bindParam("password", $variables->password);
		//$stmt->bindParam("userlevel", "1");
		$stmt->bindParam("ip", $variables->ip);
		$stmt->bindParam("userid", $variables->userid);
        $stmt->execute();
        $results->id = $dbCon->lastInsertId();
		$dbCon = null;
		if($results->id !== null){
			$sql = "SELECT * FROM users WHERE email='".$variables->email."' AND password='".md5($variables->password)."'";
			try {
				$dbCon = getConnection();
				$stmt = $dbCon->prepare($sql);  
				//$stmt->bindParam("id", $id);
				$stmt->bindParam("email", $paramEmail);
				$stmt->bindParam("password", md5($paramPassword));
				$stmt->execute();
				$user = $stmt->fetchObject();  
				$dbCon = null;
				if(isset($user->userid)){
					session_start();
					$_SESSION['user_id'] = $user->userid;
					$_SESSION['isLoggedIn'] = true;
					echo json_encode($user);
				}else{
					echo '{"error":{"text":"incorrect"}}';
				}
			} catch(PDOException $e) {
				echo '{"error":{"text":'. $e->getMessage() .'}}';
			}
		}
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
?>