<?php
	session_start();
	if(!isset($_SESSION['user_id'])){
		echo '{"error":{"text":"Not Logged-In"}}';
		die;
	}

    $sql = "UPDATE users SET `userlevel` = '5' WHERE userid = '".$_SESSION['user_id']."'";

	try {
        $dbCon = getConnection();
        $stmt = $dbCon->prepare($sql);
        $stmt->bindParam("userlevel", "5");
        $status->status = $stmt->execute();
 
        $dbCon = null;
		if($status->status){
			@header('Location: '.$current_addr);
			die;
		}

        //echo json_encode($status); 
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
?>