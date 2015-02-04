<?php
/* http://slider.kz/new/include/vk_auth.php?act=source1&q=feed+her+to+the+sharks&page=1 */
/* http://slider.kz/new/include/vk_auth.php?act=source2&q=feed+her+to+the+sharks&page=1 */
/* http://slider.kz/new/include/vk_auth.php?act=source3&q=feed+her+to+the+sharks&page=1 */
/* http://slider.kz/new/include/vk_auth.php?act=source4&q=feed+her+to+the+sharks&page=1 */
//$new_addr = 'http://cloud.hxcmusic.com' .$_SERVER['REQUEST_URI'];
//@header('location: ' . $new_addr);
//die;
//include('/home/hxcmusi/public_html/includes/session.php');

/* error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE); */

/* if(($_SERVER['HTTP_HOST'] !== 'stream.hxcmusic.com')&&($_SERVER['HTTP_HOST'] !== 'stream.hxcmusic.me')){
	$new_addr = 'http://stream.' .$_SERVER['HTTP_HOST']. '' .$_SERVER['REQUEST_URI'];
	@header('location: ' . $new_addr);
	die;
} */
/*
$link = mysql_connect('localhost', 'hxc', 'pe6eby8um');
if (!$link) {
    die('Could not connect: ' . mysql_error());
}
*/
//echo 'Connected successfully';
//mysql_close($link);

//mysql_select_db('zadmin_hxc', $link) or die('Could not select database.');

///fix cloudflare IP problem
if ($_SERVER["HTTP_CF_CONNECTING_IP"]) {
    $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"] ? $_SERVER["HTTP_CF_CONNECTING_IP"] : $_SERVER["REMOTE_ADDR"];
}

//check if this is sever or member
$ip = $_SERVER['REMOTE_ADDR'];
//check guest table
//$query = mysql_query("SELECT ip FROM `active_guests` WHERE ip='$ip'");

//$found = mysql_num_rows($query);

//if($found < 1){

//check users table
//$query = mysql_query("SELECT ip FROM `users` WHERE ip='$ip'");

//$found = mysql_num_rows($query);

//if($found < 1){
//echo 'Request Denied! [Error: 1]';
//die;
//}
//}
//$query = mysql_query("SELECT users.ip, active_guests.ip, active_users.username FROM  users ,  active_guests, active_users WHERE users.ip = '$ip', users.username = //active_users.username OR active_guests.ip = '$ip'");
/*
$query = mysql_query("SELECT DISTINCT(users.ip) AS active FROM active_guests, users RIGHT JOIN active_users ON active_users.username=users.username WHERE users.ip='$ip' OR active_guests.ip = '$ip'");

$found = mysql_num_rows($query);
$found=1;
if ($found < 1) {
    if (isset($_GET['stream'])) {
        //serve error audio
        $thedomain   = $_SERVER['HTTP_HOST'];
        $unavailable = 'http://' . $thedomain . '/unavailable.mp3';
        header('Location: ' . $unavailable . '');
    } else {
        echo 'Request Denied! [Error: 1]';
    }
    die;
}
*/

//causing errors for some users.
if (isset($_SERVER['HTTP_REFERER'])) {
    $ref = $_SERVER['HTTP_REFERER'];
    //echo $ref; die;
    //$ref='http://google.com/';
}

if (isset($ref)) {
    $string = $ref;
    $pos1   = strpos($string, "hxcmusic.com");
    $pos2   = strpos($string, "adf.ly");
    $pos3   = strpos($string, "hxcmusic.me");
    if (($pos1 === false) && ($pos2 === false) && ($pos3 === false)) {
        echo '<META http-equiv="refresh" content="3;URL=http://hxcmusic.com/">Request Denied! [Error: 1]';
        die;
    }
}

if (isset($ref)) {
    if (strpos($ref, 'http://hxcmusic.com/') === 0 || strpos($ref, 'http') !== 0 || strpos($ref, 'http://www.hxcmusic.com/') === 0) {
        //echo 'Request Denied! [Error: 1]';
        //die;
    }
}


include('../includes/config.php');
include($website_config['web_root']."crypt.php");
$secretPass = 'lol#hxc*)&$ID';

if (isset($_GET['limit'])) {
    $limit = 1;
}

if (isset($_GET['debug'])) {
    error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
} else {
    error_reporting(0);
}

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


if ($_GET['encoded']) {
    $encoded = $_GET['encoded'];
    $encoded = str_replace('.mp3', '', $encoded);
    //$srch = dec($encoded);
    
    //$srch = Encode(hex2bin($encoded),$secretPass);
    $srch = hxcdec($encoded);
    
    $srch = str_replace('---', '-', $srch);
} else {
    $srch = $_GET['string'];
}


if(!isset($_GET['geturl'])){
  $srch = urldecode($srch);

  $srch = str_replace('-', ' ', $srch);
  $srch = str_replace('%20', ' ', $srch);
  $srch = str_replace('+', ' ', $srch);
  $srch = str_replace('_', ' ', $srch);
  $srch = str_replace('.', '', $srch);
  $srch = str_replace('&', '', $srch);
  $srch = strtolower($srch);

  $srch_url = urlencode($srch);
  $srch_url = str_replace('+++', '+', $srch_url);

  $url = 'http://'.$_SERVER['HTTP_HOST'].'/api/engine.php?search='.$srch_url.'&page=1&random='.rand(1,2);
  //echo $url;
  //die;
  $ch  = curl_init();
  curl_setopt($ch, CURLOPT_URL, "$url");
  curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.2; en-US; rv:1.8.1.7) Gecko/20070914 Firefox/2.0.0.7");
  //curl_setopt($ch, CURLOPT_REFERER, "http://mp3juices.com/");
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $gurl = curl_exec($ch);
  $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  curl_close($ch);

  //$error = extstr3($gurl, '<error>', '</error>');

  if ($http_status !== 200) {
    
      $thedomain = $_SERVER['HTTP_HOST'];
      $errorpage = "http://" . $thedomain . "/page_error.php";
    
      function SendDownloadErrorAlert($ip, $email, $http_status, $details)
      {
          $from    = "From: HXC Staff <no-reply@hxcmusic.me>";
          $subject = "HXCMusic source failed";
          $body    = "Stream failed with http status: " . $http_status . "\nUsers IP: " . $ip . "\nAdditional details: " . $details;
        
          return mail($email, $subject, $body, $from);
      }
    
      $email   = 'jimmy@hxcmusic.me';
      $details = 'Query: ' . $srch;
      //SendDownloadErrorAlert($ip, $email, $http_status, $details);
    
      header('Location: ' . $errorpage . '');
      die;
  }

  $i     = 1;
  $ts    = 0;
  $lapse = 30;

  $track = strtolower($srch);

  $res = strstr($gurl, '<audio>');

}else{
  $res = false;
}

if (!$res) {
    
    $srch = str_replace(' ', '-', $srch);
    $srch = str_replace('%20', '-', $srch);
    $srch = str_replace('+', '-', $srch);
    
    $maxresults = '1';
    
    $url = 'http://'.$_SERVER['HTTP_HOST'].'/youtube-api/examples/youtube/search.php?q=' . $srch . '&maxResults=' . $maxresults . '';
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "$url");
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.2; en-US; rv:1.8.1.7) Gecko/20070914 Firefox/2.0.0.7");
    //curl_setopt($ch, CURLOPT_REFERER, "http://api.vkontakte.ru/");
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $res = curl_exec($ch);
    curl_close($ch);
    
    $i      = 1;
    $hitung = 0;
    $res    = strstr($res, '<video>');
    
    if ($res) {
        
        while ($i <= $maxresults) {
            $res = strstr($res, '<video>');
            
            $title = extstr3($res, '<title>', '</title>');
            
            $id = extstr3($res, '<ytId>', '</ytId>');
            
            $image = extstr3($res, '<thumbnail>', '</thumbnail>');
            
            $desc = extstr3($res, '<desc>', '</desc>');
            
            $playtime = '';
            
            //$playtime = extstr3($res, '<playtime>', '</playtime>');
            
            $res = strstr($res, '</video>');
            
            if ($title == '') {
                $nomsg = $nomsg + 50;
            } else {
                ++$hitung;
                $title = html_entity_decode($title);
                $id    = $id;
                
            }
            ++$i;
        }
    }
    
    $thedomain = $_SERVER['HTTP_HOST'];
    $thedomain = "http://" . $thedomain . "/";
    
    if (isset($id)) {
        if (isset($_GET['stream'])) {
            if (isset($_GET['geturl'])) {
                echo $id;
            } else {
                header('Location: http://www.youtube.com/watch?v=' . $id);
            }
        } else {
            header('Location: ' . $thedomain . 'converter/index.php?url=http://www.youtube.com/watch?v=' . $id);
            //header('Location: '.$thedomain.'dl_video_continue.php?id='.enc($id).'&name='.$title.'');
        }
    } else {
        $unavailable = $thedomain . 'unavailable.mp3';
        header('Location: ' . $unavailable . '');
    }
    die;
}


if ($res) {
    
    while ($i <= $lapse) {
        
        $res = strstr($res, '<audio>');
        $name   = extstr3($res, '<name>', '</name>');
		$dur = extstr3($res, '<duration>', ' </duration>');
        
        //This will replace anything that isn't a letter, number or space.
        $filename = preg_replace("/[^a-zA-Z 0-9]+/", "", $name);
        $filename = str_replace(' ', '_', $filename);
        
        $artist_match = $name;

        $id     = extstr3($res, '<url>', '</url>');

        $url = $id;
        
        $encoded = $_GET['encoded'];
        $encoded = str_replace('.mp3', '', $encoded);

        $track   = hxcdec($encoded);
        
        $track = str_replace('---', '-', $track);
        $track = str_replace('-', '', $track);
        
        $artist_match = str_replace('-', '', $artist_match);
        
		/* clean up for comparing | Start */
        $artist_match = strtolower($artist_match);
        $track        = strtolower($track);
        
        //This will replace anything that isn't a letter, number or space.
        $track        = preg_replace("/[^a-zA-Z 0-9]+/", "", $track);
        $artist_match = preg_replace("/[^a-zA-Z 0-9]+/", "", $artist_match);
		
		$unwantedChars = array(',', '!', '?', '\'', '"'); // create array with unwanted chars
		$track = str_replace($unwantedChars, '', $track); // remove them
		$artist_match = str_replace($unwantedChars, '', $artist_match); // remove them
        /* clean up for comparing | End */
		
        //echo 'Track: \''.$track.'\'<br>';
        //echo 'Track2: \''.$artist_match.'\'';
        //echo '<br><br>';
        
        $matcha = $artist_match;
        $matchb = $track;
        //echo '1:'.$matcha.'-'.$matchb.'-';
        
        //trying to fix songs not working with punctuation.
        //$matcha = 'match';
        //$matchb = 'match';

		$quality=100;
		$isLive = strpos(strtolower($name), 'live');
		$isCover = strpos(strtolower($name), 'cover');

		if (($track == $artist_match)&&($quality >= 100)&&($dur > 100)&&(!$isLive)&&(!$isCover)) {
        //if ((strpos($matcha, $matchb) !== false)&&($quality >= 100)&&($dur > 100)&&(!$isLive)&&(!$isCover)) {
 			  $cloud = 'http://cloud.hxcmusic.com/';
			  $cch  = curl_init();
			  curl_setopt($cch, CURLOPT_URL, $cloud);
			  curl_setopt($cch, CURLOPT_FOLLOWLOCATION, true);
			  curl_setopt($cch, CURLOPT_RETURNTRANSFER, true);
			  $cgurl = curl_exec($cch);
			  $chttp_status = curl_getinfo($cch, CURLINFO_HTTP_CODE);
			  curl_close($cch);
/*
			if ($chttp_status === 200) {
				if ($limit == '1') {
					@header('location: '.$cloud.'download.php?filename=' . str_replace('_mp3', '', $filename) . '&limit=1&id=' .hxcenc($url));
				}else{
					@header('location: '.$cloud.'download.php?filename=' . str_replace('_mp3', '', $filename) . '&id=' .hxcenc($url));				
				}
				die;
			}else{
				function SendCloudServerErrorAlert($email, $chttp_status, $cloud)
				{
					$from    = "From: HXC Staff <no-reply@hxcmusic.me>";
					$subject = "HXCMusic cloud server down!";
					$body    = "Stream failed connecting to ".$cloud." http status: " . $chttp_status;

					return mail($email, $subject, $body, $from);
				}

				$email   = 'jimmy@hxcmusic.me';
				SendCloudServerErrorAlert($email, $chttp_status, $cloud);
			}
*/	
            $size = getfilesize($url);
            //$limit=0;
            if ($limit == '1') {

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
                header("Content-Disposition: attachment; filename=" . $filename . "[HXCMusic.com].mp3");
                
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
            } else {
                header("Cache-Control: no-cache, must-revalidate");
                header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
                if ($size) {
                    header("Content-Length: " . $size);
                }
                header("Content-Disposition: attachment; filename=" . $filename . "[HXCMusic.com].mp3");
                header("Content-Type: audio/mpeg");
                //readfile(str_replace(' ', '%20', $url));
				
				//download via curl
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, str_replace(' ', '%20', $url));
				curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.2; en-US; rv:1.8.1.7) Gecko/20070914 Firefox/2.0.0.7");
				curl_setopt($ch, CURLOPT_REFERER, "http://mp3-besplatno.net/");
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
				$fileURL = curl_exec($ch);
				curl_close($ch);
				
                break;
                //die;
            } //end if limit
            
        }

        $res  = strstr($res, '</audio>');
        $name = '';
        
        if (($name == '') || ($name == ' - ')) {
            //$nomsg = $nomsg + 50;
        } else {
            ++$ts;
            //display results
        }
        ++$i;
    }
}

if (strpos($matcha, $matchb) === false) {
    //echo '<script>';
    //echo 'alert("Error Occurred - Please contact support.hxcmusic.me if this continues. [Fetch Error]");';
    //echo '</script>';
    
    //$encoded=$_GET['encoded'];
    //$encoded = str_replace ('.mp3', '', $encoded);
    //$track = dec($encoded);
    //$track = str_replace ('---', '-', $track);
    //$track = str_replace ('-', '', $track);
    //$track = str_replace (',', '', $track);
    
    $thedomain = $_SERVER['HTTP_HOST'];
    $thedomain = "http://" . $thedomain . "/";
    
    //@header('Location: '.$thedomain.'download_error.php?file='.$track.'');
    //die;
    
    $unavailable = '' . $thedomain . 'unavailable.mp3';
    
    @header('Location: ' . $unavailable . '');
    die;
}

echo '<script>';
echo 'alert("Error Occurred - Please contact support.hxcmusic.me if this continues. [Fetch Error]");';
echo '</script>';
die;

//end everything old code below
//
//
//
//
//end

function dec($hex)
{
    
    $string = '';
    
    for ($i = 0; $i < strlen($hex) - 1; $i += 2) {
        
        $string .= chr(hexdec($hex[$i] . $hex[$i + 1]));
        
    }
    
    return $string;
    
}


if ($_GET['encoded']) {
    $encoded = $_GET['encoded'];
    $encoded = str_replace('.mp3', '', $encoded);
    $srch    = dec($encoded);
    $srch    = str_replace('---', '-', $srch);
} else {
    $srch = $_GET['string'];
}

$srch = str_replace('+', '-', $srch);
$srch = str_replace(' ', '-', $srch);
$srch = str_replace('%20', '-', $srch);

$track = strtolower($srch);
$track = str_replace('-', ' ', $track);

$hxc_count = 1;

$url = "http://".$_SERVER['HTTP_HOST']."/engine/" . $srch . "/1/mp3";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "$url");
//curl_setopt($ch, CURLOPT_USERAGENT, "HXCBot");
//curl_setopt($ch, CURLOPT_REFERER, ""); 
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, "25");
//curl_setopt($ch, CURLOPT_TIMEOUT, "25");
$gurl = curl_exec($ch);
curl_close($ch);


$i     = 1;
$error = 0;

$res = strstr($gurl, '<div itemprop="tracks" itemscope itemtype="http://schema.org/MusicRecording">');

if (!$res) {
    echo 'Error Occurred - Please contact support.hxcmusic.me if this continues. [Fetch Error]';
    echo $gurl;
    die;
}


if ($res) {
    
    while ($i <= $hxc_count) {
        
        $res = strstr($res, '<table');
        
        $find_match = extstr3($res, 'title="', '"');
        $match      = strtolower($find_match);
        $match      = str_replace('-', '', $match);
        
        if ($match = $track) {
            //echo 'Match: '.$match.' - Track: '.$track.'';
            //echo ' matched track';
            //die;
            $url = extstr3($res, "inject_player('", "'");
            
            $url = dec($url);
        } else {
            //echo 'Match: '.$match.' - Track: '.$track.'';
            //echo ' track not found, please try again later.';
            //die;
        }
        
        $res = strstr($res, '</table>');
        
        if ($url) {
            ++$i;
            @header('Location: ' . $url . '');
            die;
        } else {
            if ($error != '2') {
                $i = 1;
                ++$error;
            } else {
                echo 'Error Occurred - Please contact support.hxcmusic.me if this continues. [Track Not Found]';
                die;
            }
        }
        
    }
}
?>