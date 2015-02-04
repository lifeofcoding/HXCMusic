<?php
    //$req = $app->request(); // Getting parameter with names
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
    try {
        $dbCon = getConnection();
        $stmt = $dbCon->prepare($sql);  
        //$stmt->bindParam("id", $id);
		if($isEmail){
			$stmt->bindParam("email", $paramEmail);
		}else{
			$stmt->bindParam("username", $paramUsername);
		}
		$stmt->bindParam("password", md5($paramPassword));
        $stmt->execute();
        $user = $stmt->fetchObject();  
        $dbCon = null;
		if(isset($user->userId)){
			session_start();
			$_SESSION['user_id'] = $user->userId;
			$_SESSION['isLoggedIn'] = true;
			echo json_encode($user);
		}else{
			echo '{"error":{"text":"incorrect"}}';
		}
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
?>