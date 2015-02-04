<?php
include('headers.php');
header('Access-Control-Allow-Credentials: true');

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
$p = $_GET['page'];
$srch = $_GET['search'];
$srch = str_replace(' ', '-', $srch);
$srch = str_replace('%20', '-', $srch);
$srch = str_replace('+', '-', $srch);

if ($_GET['page'] == 0 || $_GET['page'] == '') {
	$p = 1;
}else{
	$p = $_GET['page'];
}

$maxresults = 25 * $p;

$url = 'http://'.$_SERVER['HTTP_HOST'].'/youtube-api/examples/youtube/search.php?q='.$srch.'&maxResults='.$maxresults.'&page='.$p;

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
$hitung = 0;
$res = strstr($gurl, '<video>');

if ($res) {

	class Video
	{
		public $video_id;
		public $title;
		public $description;
		public $image;
	}

	$results = array();

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

    } else {
      ++$hitung;
	$video = new Video();
      $video->title = html_entity_decode($title);
      $video->id = $id;
      $video->description = $desc;
      $video->image = $image;
		
	array_push($results, $video);

    }
    ++$i;
  }
	echo json_encode($results);
}
?>