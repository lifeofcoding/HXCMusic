<?php
include('headers.php');
header('Access-Control-Allow-Credentials: true');

function remote_filesize($url, $user = "", $pw = "")
{
	ob_start();
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_HEADER, 1);
	curl_setopt($ch, CURLOPT_NOBODY, 1);
 
	if(!empty($user) && !empty($pw))
	{
		$headers = array('Authorization: Basic ' .  base64_encode("$user:$pw"));
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	}
 
	$ok = curl_exec($ch);
	curl_close($ch);
	$head = ob_get_contents();
	ob_end_clean();
 
	$regex = '/Content-Length:\s([0-9].+?)\s/';
	$count = preg_match($regex, $head, $matches);
 
	return isset($matches[1]) ? $matches[1] : "unknown";
}

function extstr3($content,$start,$end){
  if($content && $start && $end) {
    $r = explode($start, $content);
    if (isset($r[1])){
        $r = explode($end, $r[1]);
        return $r[0];
    }
    return '';
  }
}

  function getfilesize ($url)
  {
    $ary_header = get_headers ($url, 1);
    $filesize = $ary_header['Content-Length'];
    if (is_array ($filesize))
    {
      $return = $filesize[1];
    }
    else
    {
      $return = $filesize;
    }

    return $return;
  }

function get_redirect_url($url){
	$redirect_url = null; 
 
	$url_parts = @parse_url($url);
	if (!$url_parts) return false;
	if (!isset($url_parts['host'])) return false; //can't process relative URLs
	if (!isset($url_parts['path'])) $url_parts['path'] = '/';
 
	$sock = fsockopen($url_parts['host'], (isset($url_parts['port']) ? (int)$url_parts['port'] : 80), $errno, $errstr, 30);
	if (!$sock) return false;
 
	$request = "HEAD " . $url_parts['path'] . (isset($url_parts['query']) ? '?'.$url_parts['query'] : '') . " HTTP/1.1\r\n"; 
	$request .= 'Host: ' . $url_parts['host'] . "\r\n"; 
	$request .= "Connection: Close\r\n\r\n"; 
	fwrite($sock, $request);
	$response = '';
	while(!feof($sock)) $response .= fread($sock, 8192);
	fclose($sock);
 
	if (preg_match('/^Location: (.+?)$/m', $response, $matches)){
		if ( substr($matches[1], 0, 1) == "/" )
			return $url_parts['scheme'] . "://" . $url_parts['host'] . trim($matches[1]);
		else
			return trim($matches[1]);
 
	} else {
		return false;
	}
 
}
 
function get_all_redirects($url){
	$redirects = array();
	while ($newurl = get_redirect_url($url)){
		if (in_array($newurl, $redirects)){
			break;
		}
		$redirects[] = $newurl;
		$url = $newurl;
	}
	return $redirects;
}
 
function get_final_url($url){
	$redirects = get_all_redirects($url);
	if (count($redirects)>0){
		return array_pop($redirects);
	} else {
		return $url;
	}
}

///if in minutes comes in a m5:20s
$seconds = $_GET['seconds'];

    $string = $seconds;
    $pos = strpos($string, ":");
    if ($pos === false) {
       /// print "Not found\n";
    } else {
       /// was found convert to seconds
       $minutes = extstr3($seconds,'m',':');
       $seconds = extstr3($seconds,':','s');
       $seconds   = ($minutes * 60) + $seconds; 
    }

$file = $_GET['id'];
$file = 'http://'.str_replace('api.', '', $_SERVER['HTTP_HOST']).'/fetch/'.$file.'.mp3';

//ob_start();
//$ch = curl_init($file);
//curl_setopt($ch, CURLOPT_HEADER, 1);
//curl_setopt($ch, CURLOPT_NOBODY, 1);
//$ok = curl_exec($ch);
//curl_close($ch);
//$head = ob_get_contents();
//ob_end_clean();

$size = getfilesize($file);

class File {
	public $kbps;
	public $size;
}

$kbytes = $size / 1024;
$mbytes = $kbytes / 1024; //Size in Mega bytes

$mbytes = substr($mbytes, 0, 4); 

$kbps = floor(($size / 1024) / (7.328125 * ($seconds / 60)));

if(($kbps == 0)&&($kbps == 0)){
	echo '{"error":{"text":"Unable to fetch file"}}';
}else{
	$file = new File();
	$file->kbps = $kbps;
	$file->size = $mbytes;
	echo json_encode($file);
}
?>