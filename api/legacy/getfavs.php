<?php
	session_start();
	if(!isset($_SESSION['user_id'])){
		echo '{"error":{"text":"not loggedin"}}';
		die;
	}
	$sql = "SELECT `bitrate`, `name`, `song_id`, `playtime` FROM favorites WHERE user_id='".$_SESSION['user_id']."'";
    try {
		$dbCon = getConnection();
        $stmt   = $dbCon->query($sql);
        $favorites  = $stmt->fetchAll(PDO::FETCH_OBJ);
        $dbCon = null;
		echo json_encode($favorites);
        //echo '{"users": ' . json_encode($users) . '}';
		
/* 		only displays first results */
/*         $dbCon = getConnection();
        $stmt = $dbCon->prepare($sql);  
        //$stmt->bindParam("id", $id);
		$stmt->bindParam("user_id", $_SESSION['user_id']);
        $stmt->execute();
        $favorites = $stmt->fetchObject();  
        $dbCon = null;
		echo json_encode($favorites); */
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
?>