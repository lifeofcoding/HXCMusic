<?php
	global $app;
	$requestBody = $app->request()->getBody();  // <- getBody() of http request
    $variables = json_decode($requestBody, true);

	session_start();
	if(!isset($_SESSION['user_id'])){
		echo '{"error":{"text":"Not Logged-In"}}';
		die;
	}
 
    $sql = "INSERT INTO favorites (`bitrate`,`name`,`song_id`,`playtime`,`user_id`) VALUES ('".$variables['bitrate']."','".$variables['name']."','".$variables['song_id']."','".$variables['playtime']."','".$_SESSION['user_id']."')";
    try {
        $dbCon = getConnection();
        $stmt = $dbCon->prepare($sql);  
        $stmt->bindParam("bitrate", $variables['bitrate']);
        $stmt->bindParam("name", $variables['name']);
        $stmt->bindParam("song_id", $variables['song_id']);
		$stmt->bindParam("playtime", $variables['playtime']);
        $stmt->execute();
        $results->id = $dbCon->lastInsertId();
        $dbCon = null;
        echo json_encode($results); 
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
?>