<?php
	include('headers.php');
	include('../includes/config.php');
	header('Access-Control-Allow-Credentials: true');

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

	class Event {
		public $artist;
		public $location;
		public $date;
		public $image;
	}

	class Error {
		public $message;
	}

	include_once($website_config['web_root']."crypt.php");
	$secretPass = 'lol#hxc*)&$ID';
	
	/* if ($page == '') {
	$url = "http://www.purevolume.com/events/?country=$country&miles=$miles&zip=$zip&x=37&y=27";
	}else{ */

	$country = $_GET['country'];
	$miles = $_GET['miles'];
	$zipCode = $_GET['zipCode'];
	$page = $_GET['page'];

	$country = str_replace('-', ' ', $country);

	if(!isset($_GET['page'])){
		$page = 1;
	}
	$country = str_replace(' ', '+', $country);
	$country = str_replace('%20', '+', $country);
	$url     = "http://www.purevolume.com/events/?country=$country&miles=$miles&zip=$zipCode&x=37&y=27&p=$page";
	/* } */
	
	//die($url);
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.2; en-US; rv:1.8.1.7) Gecko/20070914 Firefox/2.0.0.7");
	curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");
	curl_setopt($ch, CURLOPT_REFERER, "http://purevolume.com/");
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, "30");
	curl_setopt($ch, CURLOPT_TIMEOUT, "30");
	curl_setopt($ch, CURLOPT_HEADER, true);
	$gurl = curl_exec($ch);
	curl_close($ch);
	
	
	$res = strstr($gurl, '<div class="results">');
	
	$i  = 1;
	$ts = 0;
	
	if ($gurl == "") {
		$error = new Error();
		$error->message = 'Unable to fetch results';
		die(json_encode($error));
	}
	
	$results = array();
	if ($res) {
		while ($i <= 20) {
			
			$res = strstr($res, '<div class="result_item">');
			
			$artistscrap = extstr3($res, '<a href="/', '">');
			
			$artist = extstr3($res, '<p><strong><a href="/' . $artistscrap . '">', '</a></strong>');
			
			if (strpos($artist, "(")) {
				$artist = extstr3($res, '<p><strong><a href="/' . $artistscrap . '">', '(');
			}
			
			$res2 = strstr($res, '</a></strong></p>');
			
			$location = extstr3($res2, '<p><strong>', '</p>');
			
			$res2 = strstr($res, '<p style="margin-bottom: 5px;">');
			
			$date = extstr3($res, '<p style="margin-bottom: 5px;">', '</p>');
			
			$location = str_replace('</strong>', '', $location);
			
			$image = extstr3($res, '<img src="', '" /></a>');
			
			if ($image == 'http://g.purevolumecdn.com/_images/_default/artist_crop_87x65.gif') {
				
				$image = "" . $web_path . "imagecache/image.php?src=images/noimage.jpg&h=150&w=120&zc=1";
			} else {
				$image = str_replace('crop_87x65', 'crop_120x150', $image);
			}
			
			$res = strstr($res, '<div class="clear"><span class="nodisp">&nbsp;</span></div>');
			
			if ($artist == '') {
				$nomsg = $nomsg + 50;
			} else {
				++$ts;
				
				$encoded_image = bin2hex(Encode($image, $secretPass));
				$encoded_image = 'artistimg.php?id=' . $encoded_image . '.jpg';
				
				$event           = new Event();
				$event->artist   = $artist;
				$event->location = $location;
				$event->date     = $date;
				$event->image    = $image;
				array_push($results, $event);
			}
			++$i;
		}
	}
	if ($ts == 0) {
		$error = new Error();
		$error->message = 'Not Found';
		die(json_encode($error));
	} else {
		echo json_encode($results);
	}
	
?>