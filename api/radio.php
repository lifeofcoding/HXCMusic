<?php
//var_dump($_COOKIE);
include('../includes/config.php');
include_once($website_config['web_root']."crypt.php");
$secretPass = 'lol#hxc*)&$ID';

function extstr3($content, $start, $end){
	if ($content && $start && $end) {
		$r = explode($start, $content);
		if (isset($r[1])) {
			$r = explode($end, $r[1]);
			return $r[0];
		}
		return '';
	}
}
$thedomain =  $_SERVER['HTTP_HOST'];
$thedomain = "http://" .$thedomain. "/";


if($_COOKIE['Genre']){
	$genres = json_decode(urldecode($_COOKIE['Genre']));
	$genreArray = (array)$genres;
	$total = count($genreArray);

	$random = rand(1, $total);
	$genre = $genres->$random;

	$genre = str_replace (' ', '+', $genre);
}else{
	$genre='Rock';
	$genre = str_replace (' ', '+', $genre);
}


  $url = "http://www.last.fm/tag/".$genre."/tracks";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.2; en-US; rv:1.8.1.7) Gecko/20070914 Firefox/2.0.0.7");
curl_setopt($ch, CURLOPT_REFERER, "http://www.last.fm/"); 
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
///curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, "10");
///curl_setopt($ch, CURLOPT_TIMEOUT, "10");
$gurl = curl_exec($ch);
curl_close($ch);

$res = strstr($gurl, '<tr class="first');

$count=100;
$random=rand(1,$count);
$i = 1;
$ts = 0;

    if ($res)

    {

      while ($i <= $count)

      {

        $res = strstr ($res, '<tr ');

		$artist = extstr3($res,'href="/music/','"');
		$title = extstr3($res,'/music/'.$artist.'/_/','"');

        $artist = str_replace ('+', ' ', $artist);
        $title = str_replace ('+', ' ', $title);

        $name = $artist.' - '.$title;
        $name = urldecode($name);

        //$encode_name = strtolower($name);
        $encode_name = $name;
		$encoded = hxcenc($encode_name);

        $res = strstr ($res, '</tr>');

				if ($name == '')
				{
						//$nomsg = $nomsg + 50;
				}
				else
				{
						++$ts;

				if($random == $i){
					$my_array = array(
						'track_title' => $name,
						'track_id' => $encoded
					);
					header( 'Content-Type: application/json' );
					echo json_encode($my_array);
					exit;
				}

				}
				++$i;
		}
}
?>