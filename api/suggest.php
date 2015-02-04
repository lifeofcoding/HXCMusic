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

class Artist{
	public $artist;
	public $image;
}

$results = array();

include_once($website_config['web_root']."crypt.php");
$secretPass = 'lol#hxc*)&$ID';

$random = rand(1,10);

if($random == "1"){
$genre="metalcore";
}
if($random == "2"){
$genre="punk";
}
if($random == "3"){
$genre="screamo";
}
if($random == "4"){
$genre="emo";
}
if($random == "5"){
$genre="ska";
}
if($random == "6"){
$genre="rap";
}
if($random == "7"){
$genre="hip-hop";
}
if($random == "8"){
$genre="electronica";
}
if($random == "9"){
$genre="techno";
}
if($random == "10"){
$genre="house";
}

//echo 'test-'.$_COOKIE['Genre'];
/* if(isset($_COOKIE['Genre']))
{
$genre=$_COOKIE['Genre'];
$genre = str_replace (' ', '+', $genre);
} */

if(isset($_COOKIE['lastSearched'])){
	$lastSearched=$_COOKIE['lastSearched'];
	$lastSearched = str_replace (' ', '+', $lastSearched);
}else{
	$random = rand(1,3);
	if($random === 1){
		$lastSearched = 'Coldplay';
	}
	if($random === 2){
		$lastSearched = 'Lady Gaga';
	}
	if($random === 3){
		$lastSearched = 'Justin Bieber';
	}
	
/* $url = "http://www.iptolatlng.com/?ip=" . $_SERVER['REMOTE_ADDR'];
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.2; en-US; rv:1.8.1.7) Gecko/20070914 Firefox/2.0.0.7");
curl_setopt($ch, CURLOPT_REFERER, "http://api.hostip.info/"); 
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
///curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, "10");
///curl_setopt($ch, CURLOPT_TIMEOUT, "10");
$gurl = curl_exec($ch);
curl_close($ch); */
	
$url = "http://api.hostip.info/?ip=" . $_SERVER['REMOTE_ADDR'];
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.2; en-US; rv:1.8.1.7) Gecko/20070914 Firefox/2.0.0.7");
curl_setopt($ch, CURLOPT_REFERER, "http://api.hostip.info/"); 
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, "10");
curl_setopt($ch, CURLOPT_TIMEOUT, "10");
$gurl = curl_exec($ch);
curl_close($ch);

$country = extstr3($gurl,'<countryName>','</countryName>');

if($country == '(Unknown Country?)'){
	$country = null;
}
if($country){
	
	$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://ws.audioscrobbler.com/2.0/?method=geo.gettopartists&country=".urlencode($country)."&api_key=be95f4ce88883dc02180d2736384ba74&limit=60");
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.2; en-US; rv:1.8.1.7) Gecko/20070914 Firefox/2.0.0.7");
curl_setopt($ch, CURLOPT_REFERER, "http://ws.audioscrobbler.com/"); 
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
///curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, "10");
///curl_setopt($ch, CURLOPT_TIMEOUT, "10");
$gurl = curl_exec($ch);
curl_close($ch);
	
		$res = strstr($gurl, '<artist rank="1">');

  $i = 1;

    if ($res)

    {
		
		$res = $gurl;
		while ($i <= 60){
			$res = strstr ($res, '<artist');
			$name = extstr3($res,'<name>','</name>');
			$image = extstr3($res,'<image size="extralarge">','</image>');
			$string = $image;
			$pos = strpos($string, "Keepstatsclean");
			if ($pos !== false) {
				//keepstatsclean image!
				//$image = 'http://'.$_SERVER['HTTP_HOST'].'/images/music.png';
				$name = false;
				//$i = $i - 1;
			}
/* 			$image = hxcenc($image);
			
			$current_addr =  $_SERVER['HTTP_HOST'];
			$image = 'http://' .$current_addr.'/imagecache/image.php?src=image.php?id='.$image.'.png&w=190&zc=1'; */
			
			//$image = 'https://i.embed.ly/1/display/resize?height=126&width=126&grow=false&key=64f15f0114474399971686c78b65b318&url='.$image.'';
			$res = strstr ($res, '</artist>');
			
			$artistDetails = new Artist();
			$artistDetails->artist = $name;
			$artistDetails->image = $image;
			
			if($name){
				array_push($results, $artistDetails);
				++$i;
			}
			
		}
		
	}
if(count($results) > 0){
	echo json_encode($results);
	die;
}
}
	$lastSearched = str_replace (' ', '+', $lastSearched);
}

  $url = "http://ws.audioscrobbler.com/2.0/?method=artist.getsimilar&artist=".$lastSearched."&api_key=be95f4ce88883dc02180d2736384ba74&limit=60";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,"$url");
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.2; en-US; rv:1.8.1.7) Gecko/20070914 Firefox/2.0.0.7");
curl_setopt($ch, CURLOPT_REFERER, "http://www.last.fm/"); 
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
///curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, "10");
///curl_setopt($ch, CURLOPT_TIMEOUT, "10");
$gurl = curl_exec($ch);
curl_close($ch);

	$res = strstr($gurl, '<artist>');

  $i = 1;

    if ($res)

    {

		$res = $gurl;
		while ($i <= 60){
			$res = strstr ($res, '<artist>');
			$name = extstr3($res,'<name>','</name>');
			$image = extstr3($res,'<image size="extralarge">','</image>');
/* 			$image = hxcenc($image);
			
			$current_addr =  $_SERVER['HTTP_HOST'];
			$image = 'http://' .$current_addr.'/imagecache/image.php?src=image.php?id='.$image.'.png&w=190&zc=1'; */
			
			//$image = 'https://i.embed.ly/1/display/resize?height=126&width=126&grow=false&key=64f15f0114474399971686c78b65b318&url='.$image.'';
			$res = strstr ($res, '</artist>');
			
			$artistDetails = new Artist();
			$artistDetails->artist = $name;
			$artistDetails->image = $image;
			
			if($name){
				array_push($results, $artistDetails);
			}
			
			++$i;
			
		}
		
	}

echo json_encode($results);
die;


$run=0;

if($run == "1"){

  $url = "http://www.last.fm/tag/".$genre."/artists";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,"$url");
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.2; en-US; rv:1.8.1.7) Gecko/20070914 Firefox/2.0.0.7");
curl_setopt($ch, CURLOPT_REFERER, "http://www.last.fm/"); 
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
///curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, "10");
///curl_setopt($ch, CURLOPT_TIMEOUT, "10");
$gurl = curl_exec($ch);
curl_close($ch);

$count=60;

$res = strstr($gurl, '<li  class=" first" id="');

  $i = 1;

    if ($res)

    {
		
				class Artist
				{
					public $artist;
					public $image;
				}

				$results = array();

      while ($i <= $count)

      {

        $res = strstr ($res, '<li  id="');
	$name = extstr3($res,'href="/music/','"');
        $name = str_replace ('+', ' ', $name);
        $search_name = str_replace (' ', '-', $name);
        $search_name = strtolower($search_name);
	$image = extstr3($res,'src="','"');
		  
		        $artistDetails = new Artist();
		  		$artistDetails->artist = $name;
		  		$artistDetails->image = $image;

        $res = strstr ($res, '</li>');

				if ($name == '')
				{

				}
				else
				{
						++$ts;

				array_push($results, $artistDetails);

				}
				++$i;
		}
}
	echo json_encode($results);
}
?>