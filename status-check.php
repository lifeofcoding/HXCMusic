<?php
function extstr3($content, $start, $end)
{
    if ($content && $start && $end) {
        $r = explode($start, $content);
        if (isset($r[1])) {
            $r = explode($end, $r[1]);
            return $r[0];
        }
        return '';
    }
}

$droplet_id = '1649609';
$client_id = '7bc73d4c43e353b818aaf2c97248fd3e';
$api_key = '44dec663ad158b3ab8bca6c39a1b440c';

function sendEmailAlert($status, $success){
	if($success){
		$subject = 'Alert: HXCWeb offline';
		$message = 'HXCWeb rebooted successfully with status: '.$status;
	}else{
		$subject = 'Alert: HXCWeb offline - Failed to reboot!';
		$message = 'HXCWeb was unable to be rebooted! Error Message: '.$status;
	}
	$to      = 'jimmy@hxcmusic.me';
	$headers = 'From: alerts@hxcmusic.me';
	mail($to, $subject, $message, $headers);
}
//https://api.digitalocean.com/v1/droplets/1649609/reboot/?client_id=7bc73d4c43e353b818aaf2c97248fd3e&api_key=44dec663ad158b3ab8bca6c39a1b440c
function restartHXCWeb($droplet_id, $client_id, $api_key){
	$ch  = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://api.digitalocean.com/v1/droplets/'.$droplet_id.'/reboot/?client_id='.$client_id.'&api_key='.$api_key);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$gurl = curl_exec($ch);
	$dohttp_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);
	$json_o=json_decode($gurl);

	if($dohttp_status === 200){
		if($json_o->status == 'OK'){
			sendEmailAlert($json_o->status, true);
		}else if($json_o->status == 'ERROR'){
			sendEmailAlert($json_o->error_message, false);
		}
	}else{
		sendEmailAlert('DigitalOcean API not responding!', false);
	}
}

$ch  = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://stats.hxcmusic.me/');
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout in seconds
$gurl = curl_exec($ch);
$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if($http_status !== 200){
	restartHXCWeb($droplet_id, $client_id, $api_key);
}
?>