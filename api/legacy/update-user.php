<?php
	global $app;
	$requestBody = $app->request()->getBody();  // <- getBody() of http request
    $variables = json_decode($requestBody, true);

	session_start();
	if(!isset($_SESSION['user_id'])){
		echo '{"error":{"text":"Not Logged-In"}}';
		die;
	}
	$lastDownloaded = time();
    $sql = "UPDATE users SET `dailydownloads` = ".$variables['dailyDownloads'].", `ip` = '".$_SERVER['REMOTE_ADDR']."', `downloads` = ".$variables['totalDownloads'].", `image` = '".$variables['image']."', `last_downloaded` = '".$lastDownloaded."' WHERE userid = '".$_SESSION['user_id']."'";
	
	try {
        $dbCon = getConnection();
        $stmt = $dbCon->prepare($sql);  
        $stmt->bindParam("dailydownloads", $variables['dailyDownloads']);
        $stmt->bindParam("downloads", $variables['totalDownloads']);
        $stmt->bindParam("ip", $_SERVER['REMOTE_ADDR']);
		$stmt->bindParam("image", $variables['image']);
		$stmt->bindParam("last_downloaded", $lastDownloaded);
        $status->status = $stmt->execute();
 
        $dbCon = null;
        echo json_encode($status); 
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
?>