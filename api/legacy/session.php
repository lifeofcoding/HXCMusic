<?php
	class User
	{
		public $username;
		public $userId;
		public $userLevel;
		public $isLoggedIn;
		public $email;
		public $totalDownloads;
		public $dailyDownloads;
		public $downloadLimit;
		public $ip;
		public $image;
	}

	$user = new User();
	session_start();

	if(!isset($_SESSION['isLoggedIn'])){
		$user->isLoggedIn = false;
		$user->userLevel = 0;
		$user->dailyDownloads = 0;
		$user->downloadLimit = 1;
	}else{
		$sql = "SELECT * FROM users WHERE userid='".$_SESSION['user_id']."'";
		$dbCon = getConnection();
        $stmt = $dbCon->prepare($sql);  
		$stmt->bindParam("userid", $_SESSION['user_id']);
        $stmt->execute();
        $userData = $stmt->fetchObject();  
        $dbCon = null;
		if($userData){
			if($userData->last_downloaded < (time() - 86400)){
				$user->dailyDownloads = 0;
			}else{
				$user->dailyDownloads = $userData->dailydownloads;
			}
			$user->downloadLimit = $userData->userlevel === '5' ? null : 2;
			$user->email = $userData->email;
			$user->ip = $userData->ip;
			$user->isLoggedIn = true;
			$user->totalDownloads = $userData->downloads;
			$user->userId = $userData->userid;
			$user->userLevel = $userData->userlevel;
			$user->username = $userData->username;
			$user->image = $userData->image;
		}
	}
	echo json_encode($user);
?>