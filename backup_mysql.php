<?php
include('includes/config.php');
function sendAlert($subject, $message){
    // message lines should not exceed 70 characters (PHP rule), so wrap it
    //$message = wordwrap($message, 70);

    // send mail
    mail("lifeofcoding@gmail.com", $subject, $message,"From: no-reply@hxcmusic.com\n");
}
$output = shell_exec("mysqldump -u hxc -p zadmin_hxc > ".$website_config['web_root']."hxcmusic.sql --password=yhu8unana");
if(trim($output) != ''){
	sendAlert('HXC Database Backup Successful', 'Backup Successful!');
}else{
	sendAlert("HXC Database Backup Successful", "Backup Successful! Output:\n".$output);
}
?>