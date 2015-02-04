<?php
if(isset($_GET['debug'])){
	error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
}

include("crypt.php");
$secretPass = 'lol#hxc*)&$ID';

function getfilesize($url)
{
    $ary_header = get_headers($url, 1);
    $filesize   = $ary_header['Content-Length'];
    if (is_array($filesize)) {
        $return = $filesize[1];
    } else {
        $return = $filesize;
    }
    
    return $return;
}
	
if(isset($_GET['id'])){
	$url = hxcdec($_GET['id']);
}else{
	echo 'Error';
	exit;
}

$size = getfilesize($url);

if (isset($_GET['limit'])) {
	set_time_limit(0);

	// set the download rate limit (=> 20,5 kb/s)
	$download_rate = 50 * 100; // i.e. 50 kb/s download rate

	// send headers
	header("Cache-Control: no-cache, must-revalidate");
	header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
	if ($size) {
	header("Content-Length: " . $size);
	}

	//header("Content-Type: audio/mpeg, audio/x-mpeg, audio/x-mpeg-3, audio/mpeg3");
	header("Content-Type: audio/mpeg");
	header("Content-Disposition: attachment; filename=" . $_GET['filename'] . "[HXCMusic.me].mp3");

	// flush content
	flush();
	// open file stream
	$file = fopen($url, "r");
	while (!feof($file)) {

	// send the current file part to the browser
	//print fread($file, round($download_rate * 1024));        
	print fread($file, 3000);

	// flush the content to the browser
	flush();

	// sleep one second
	//sleep(1);   


	// wait for micro-seconds (2 = 2000000)
	usleep(100000);

	}

	// close file stream
	fclose($file);

	die;
}else{
	header("Cache-Control: no-cache, must-revalidate");
	header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
	if ($size) {
		header("Content-Length: " . $size);
	}
	header("Content-Type: audio/mpeg, audio/x-mpeg, audio/x-mpeg-3, audio/mpeg3");
	header("Content-Type: audio/mpeg");
	header("Content-Disposition: attachment; filename=" . $_GET['filename'] . "[HXCMusic.me].mp3");

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, str_replace(' ', '%20', $url));
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.2; en-US; rv:1.8.1.7) Gecko/20070914 Firefox/2.0.0.7");
	//curl_setopt($ch, CURLOPT_REFERER, "http://mp3-besplatno.net/");
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
	$fileURL = curl_exec($ch);
	curl_close($ch);
}
?>