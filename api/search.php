<?php
include('headers.php');
include('../includes/config.php');

header('Access-Control-Allow-Credentials: true');
header('Content-Type: application/json');

include_once($website_config['web_root']."crypt.php");
$secretPass = 'lol#hxc*)&$ID';

class MP3 {
	public $id;
	public $songId;
	public $name;
	public $url;
	public $bitrate;
	public $playtime;
}

$results = array();

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

$srch = $_GET['search'];
$srch = str_replace ('-', '%20', $srch);
$srch = str_replace (' ', '%20', $srch);
$srch = str_replace ('%20', '+', $srch);

$p = $_GET['page'];

if($p == ''){
	$p=1;
}

$pereday = false;
$slider = true;

if($slider){
$url = 'http://slider.fm/welcome/search?page='.($p - 1).'&q=' .$srch;

$last_count = 100;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.2; en-US; rv:1.8.1.7) Gecko/20070914 Firefox/2.0.0.7");
curl_setopt($ch, CURLOPT_REFERER, "http://slider.fm/"); 
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, "10");
curl_setopt($ch, CURLOPT_TIMEOUT, "10");
$gurl = curl_exec($ch);
curl_close($ch);

  $i = 1;

$res = strstr($gurl, '[{"id"');

    if ($res){

      while ($i <= $last_count)

      {
                $res = strstr($res, '{"id"');
                $track = extstr3($res, '"title":"', '"');

                $track = str_replace('+', ' ', $track);
                $track = str_replace('/_/', ' - ', $track);
		  
                $artist = extstr3($res, '"artist":"', '"');

                $artist = str_replace('+', ' ', $artist);
                $artist = str_replace('/_/', ' - ', $artist);

                $name = $artist.' - '.$track;

                $encoded_id = hxcenc($name);

                $url = "" .$web_path. "fetch/" .$encoded_id. ".mp3";

				$playtime = extstr3($res, '"duration":"', '"');

				$bitrate = $playtime;
				$bitrate = "m".$bitrate."s";

                $mp3 = new MP3();
		  		$mp3->id = $encoded_id;
		  		$mp3->songId = $encoded_id;
		  		$mp3->name = $name;
		  		$mp3->url = $url;
		  		$mp3->bitrate = preg_replace( '/\s+/', '', $bitrate);
		  		$mp3->playtime = preg_replace( '/\s+/', '', $playtime);

                $res = strstr($res, '"},');


				if (($name == '')||($name == ' - '))
				{

				}
				else
				{
					
					array_push($results, $mp3);

						++$ts;
				}
				++$i;
		}

		echo json_encode($results);
		die;
}
///end
}

if($pereday){
$url = 'http://www.pereday.ru/?music=' .$srch;

$last_count = 100;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.2; en-US; rv:1.8.1.7) Gecko/20070914 Firefox/2.0.0.7");
curl_setopt($ch, CURLOPT_REFERER, "http://www.pereday.ru/"); 
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, "10");
curl_setopt($ch, CURLOPT_TIMEOUT, "10");
$gurl = curl_exec($ch);
curl_close($ch);

  $i = 1;

$res = strstr($gurl, '<tr align="center" bgcolor="#FFFFFF" height="31" valign="middle">');

    if ($res){

      while ($i <= $last_count)

      {
                $res = strstr($res, '<tr align="center" bgcolor="#FFFFFF" height="31" valign="middle">');
                $track = extstr3($res, 'href="/text.php?q=', '&id=');

                $track = str_replace('+', ' ', $track);
                $track = str_replace('/_/', ' - ', $track);
                $name = $track;

                $encoded_id = hxcenc($name);

                $url = "" .$web_path. "fetch/" .$encoded_id. ".mp3";

				$durRes = strstr($res, '[текст песни]</a> </td>');
                $playtime = extstr3($durRes, "<td width='110'> ", " </td>");
				$durRes = strstr($res, "<!--td width='100'>  </td-->");
				$bitrate = $playtime;
				$bitrate = "m".$bitrate."s";

                $mp3 = new MP3();
		  		$mp3->id = $encoded_id;
		  		$mp3->songId = $encoded_id;
		  		$mp3->name = $name;
		  		$mp3->url = $url;
		  		$mp3->bitrate = preg_replace( '/\s+/', '', $bitrate);
		  		$mp3->playtime = preg_replace( '/\s+/', '', $playtime);

                $res = strstr($res, '</tr>');


				if (($name == '')||($name == ' - '))
				{

				}
				else
				{
					
					array_push($results, $mp3);

						++$ts;
				}
				++$i;
		}

		echo json_encode($results);
		die;
}
///end
} //pereday

if (!$res){
	$url = 'http://www.last.fm/search?q=' . $srch . '&type=track&page='.$p.'';

	$last_count = 100;

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.2; en-US; rv:1.8.1.7) Gecko/20070914 Firefox/2.0.0.7");
	curl_setopt($ch, CURLOPT_REFERER, "http://fullsongs.net/"); 
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, "10");
	curl_setopt($ch, CURLOPT_TIMEOUT, "10");
	$gurl = curl_exec($ch);
	curl_close($ch);


	  $i = 1;

	$res = strstr($gurl, '<div id="trackResults" class="results">');


		if ($res)

		{


		  while ($i <= $last_count)

		  {
					$res = strstr($res, '<tr class="');

					$artist = extstr3($res, '<a href="/music/', '/');
					$artist = str_replace('+', ' ', $artist);

					$song = extstr3($res, '/_/', '"');
					$song = str_replace('+', ' ', $song);

					$track = extstr3($res, '<a href="/music/', '"');

					$track = str_replace('+', ' ', $track);
					$track = str_replace('/_/', ' - ', $track);
					$name = $track;

					//$url = "" .$web_path. "fetch/" .enc($name). ".mp3";

					//$encoded_id = bin2hex(Encode($name,$secretPass));
					$encoded_id = hxcenc($name);

					$url = "" .$web_path. "fetch/" .$encoded_id. ".mp3";

					$playtime = extstr3($res, '<td class="duration">', '</td>');

					$pos = strpos($playtime, ":");
					if ($pos === false) {
					$playtime = $playtime.':00';
					}

					$getcount = strlen($playtime);

					if($getcount == '3'){
					$playtime = $playtime.'0';
					}

					$bitrate = extstr3($res, '<td class="duration">', '</td>');

					$pos = strpos($bitrate, ":");
					if ($pos === false) {
					$bitrate = $bitrate.':00';
					}

					$getcount = strlen($bitrate);

					if($getcount == '3'){
					$bitrate = $bitrate.'0';
					}

					$bitrate = "m".$bitrate."s";

					$mp3 = new MP3();
					$mp3->id = $encoded_id;
					$mp3->songId = $encoded_id;
					$mp3->name = $name;
					$mp3->url = $url;
					$mp3->bitrate = preg_replace( '/\s+/', '', $bitrate);
					$mp3->playtime = preg_replace( '/\s+/', '', $playtime);

					$res = strstr($res, '</tr>');


					if (($name == '')||($name == ' - '))
					{

					}
					else
					{

						array_push($results, $mp3);

							++$ts;
					}
					++$i;
			}

			echo json_encode($results);
	}
}
?>