<?php
include('headers.php');
//header('Content-Type: application/json');
header('Access-Control-Allow-Credentials: true');

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
	$artist = urldecode($_GET['artist']);
	$album = urldecode($_GET['album']);
	$artist = str_replace('-', ' ', $artist);
	$album = str_replace('-', ' ', $album);
	$artist = str_replace(' ', '+', $artist);
	$album = str_replace(' ', '+', $album);

  $url = "http://ws.audioscrobbler.com/2.0/?method=album.getinfo&api_key=be95f4ce88883dc02180d2736384ba74&artist=".$artist."&album=".$album;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,"$url");
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.2; en-US; rv:1.8.1.7) Gecko/20070914 Firefox/2.0.0.7");
//curl_setopt($ch, CURLOPT_REFERER, "http://www.last.fm/"); 
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, "5");
//curl_setopt($ch, CURLOPT_TIMEOUT, "5");
$gurl = curl_exec($ch);
curl_close($ch);

  $i = 1;
  $ts = 0;

$res = strstr($gurl, '<tracks>');

    if ($res)

    {

		class Track
		{
			public $title;
			public $duration;
		}
		$results = array();
      while ($i <= 20)

      {
        $res = strstr ($res, '<track rank="'.$i.'">');
		$title = extstr3($res,'<name>','</name>');
		$duration = extstr3($res,'<duration>','</duration>');
        $res = strstr ($res, '</track>');

		$track = new Track();
		$track->title = $title;
		$track->duration = $duration;
		
				if ($title == '')
				{

				}
				else
				{
						++$ts;
					array_push($results, $track);
				}
				++$i;
		}
		echo json_encode($results);
}
if($ts == 0){
	echo '[]';
}
?>