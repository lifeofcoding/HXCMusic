<?php
function sendAlert($from, $subject, $message){
    // message lines should not exceed 70 characters (PHP rule), so wrap it
    //$message = wordwrap($message, 70);

    // send mail
    mail("jimmy@hxcmusic.me",$subject,$message,"From: $from\n");
}

$action = $_GET['action'];
$script = $_GET['script'];

if($action === 'nodeAPI'){
	if($script == 'api.js'){
		$apiOutput = shell_exec('ps aux | grep api.js | grep -v grep | wc -l');
		if($apiOutput === 0){
			sendAlert('alerts@hxcmusic.me', 'NodeAPI Down! - api.js', 'Status: Failed to start! Output: '.$apiOutput);
		}else{
			sendAlert('alerts@hxcmusic.me', 'NodeAPI Down! - api.js', 'Status: Started successfully!');
		}
	}
	if($script == 'web.js'){
		$webOutput = shell_exec('ps aux | grep web.js | grep -v grep | wc -l');
		if($webOutput === 0){
			sendAlert('alerts@hxcmusic.me', 'NodeAPI Down! - web.js', 'Status: Failed to start! Output: '.$webOutput);
		}else{
			sendAlert('alerts@hxcmusic.me', 'NodeAPI Down! - web.js', 'Status: Started successfully!');
		}
	}

/* 	if($apiOutput >= 1 && $webOutput >= 1){
		sendAlert('alerts@hxcmusic.me', 'NodeAPI Down!', 'All scripts started successfully!');
	}else if($apiOutput === 0){
		sendAlert('alerts@hxcmusic.me', 'NodeAPI Down! - api.js', 'Status:\nScript api.js online!\nScript web.js offline!');
	}else if($webOutput === 0){
		sendAlert('alerts@hxcmusic.me', 'NodeAPI Down! - web.js', 'Status:\nScript api.js offline!\nScript web.js online!');
	}else{
		sendAlert('alerts@hxcmusic.me', 'NodeAPI Down!', 'Failed to start any scripts!');
	} */
}
?>