<?php
//set_time_limit(0);// to infinity for example
function crawlerDetect($USER_AGENT){
$crawlers = array(
    'Google'=>'Googlebot',
    'MSN' => 'msnbot',
    'Rambler'=>'Rambler',
    'Yahoo'=> 'Yahoo',
    'AbachoBOT'=> 'AbachoBOT',
    'accoona'=> 'Accoona',
    'AcoiRobot'=> 'AcoiRobot',
    'ASPSeek'=> 'ASPSeek',
    'CrocCrawler'=> 'CrocCrawler',
    'Dumbot'=> 'Dumbot',
    'FAST-WebCrawler'=> 'FAST-WebCrawler',
    'GeonaBot'=> 'GeonaBot',
    'Gigabot'=> 'Gigabot',
    'Lycos spider'=> 'Lycos',
    'MSRBOT'=> 'MSRBOT',
    'Altavista robot'=> 'Scooter',
    'AltaVista robot'=> 'Altavista',
    'ID-Search Bot'=> 'IDBot',
    'eStyle Bot'=> 'eStyle',
    'Scrubby robot'=> 'Scrubby',
	'Bing'=> 'Bingbot',
	'Yandex'=> 'Yandex',
	'HXCMusicTests'=> 'HXCBOT'
    );
    foreach ($crawlers as $c){
		if (strpos($USER_AGENT, $c) !== false) {
    		return true;
		}
	}
	return false;
}
 
// example
 
$crawler = crawlerDetect($_SERVER['HTTP_USER_AGENT']);
if ($_SERVER["HTTP_CF_CONNECTING_IP"]) {
	$_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"] ? $_SERVER["HTTP_CF_CONNECTING_IP"] : $_SERVER["REMOTE_ADDR"];
}

if(strpos($_SERVER['REQUEST_URI'],'index.php') === false && $_SERVER['REQUEST_URI'] !== '/' && $_SERVER['REQUEST_URI'] !== '' && $_SERVER['REMOTE_ADDR'] !== '178.62.169.191'){
	//$crawler = true;
}
if (isset($_SESSION['robot'])||$crawler){
	//die('test');
	//set_time_limit(0);// Set entire script timeout | Use 0 to wait indefinitely.
	$cache_life = 86400; //in seconds - 86400 = 24 hours
	include('cache.php');
	//Keep track of load time
	$time = microtime();
	$time = explode(' ', $time);
	$time = $time[1] + $time[0];
	$start = $time;
	
	//script for rendering from main and download servers
	//include('api/crawler.php');
	$current_addr =  $_SERVER['HTTP_HOST'];
	$current_addr = "http://bots." .$current_addr. ":3000" .$_SERVER['REQUEST_URI'];

	//$content = file_get_contents($current_addr);
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $current_addr);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0); //The number of seconds to wait while trying to connect. Use 0 to wait indefinitely.
	curl_setopt($ch, CURLOPT_TIMEOUT, 15); //The maximum number of seconds to allow cURL functions to execute. | timeout in seconds
	$content = curl_exec($ch);
	curl_close($ch);

	if(trim($content) == ''){
		header('HTTP/1.1 503 Service Temporarily Unavailable');
		header('Status: 503 Service Temporarily Unavailable');
		header('Retry-After: 900'); // (900) 15min | (600) 10min | (1800) 30min | 3600 seconds (1 hour) | was 300 seconds (5min)
		
		$nocache = true;
		?>
			<html>
				<head>
					<title>Service Temporarily Unavailable</title>
					<meta name="robots" content="noindex">
				</head>
				<body>Service Temporarily Unavailable</body>
			</html>
		<?
		die;
	}else{
		if($_SERVER['HTTP_USER_AGENT'] == 'HXCBOT'){
			$nocache = true;
		}
		echo $content;
	}
	//echo '<!--- HXCWeb: '.$loadPercent.' HXCMachine: '.$cloudLoadPercent.' - Rendered Via HXCWeb Server -->';
	$time = microtime();
	$time = explode(' ', $time);
	$time = $time[1] + $time[0];
	$finish = $time;
	$total_time = round(($finish - $start), 4);
	echo '<!-- Page generated in '.$total_time.' seconds. -->';
	die;
}
?>