<?php
include('headers.php');
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
	$artist = str_replace('-', ' ', $artist);
	$artist = str_replace(' ', '+', $artist);

  $url = "http://ws.audioscrobbler.com/2.0/?method=artist.gettopalbums&artist=".$artist."&api_key=be95f4ce88883dc02180d2736384ba74";

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

$res = strstr($gurl, '<lfm status="ok">');

    if ($res)

    {

		class Album
		{
			public $artist;
			public $title;
			public $image;
		}
		$results = array();
		
      while ($i <= 10)

      {

        $res = strstr ($res, '<album rank="'.$i.'">');
		$albumTitle = extstr3($res,'<name>','</name>');
		$albumImage = extstr3($res, '<image size="large">','</image>');
		  
		$artistres = strstr ($res, '<artist>');
		$albumArtist = extstr3($artistres,'<name>','</name>');
        $artistres = strstr ($res, '</artist>');
		  
        $res = strstr ($res, '</album>');

		$album = new Album();
		$album->title = $albumTitle;
		$album->image = $albumImage;
		$album->artist = $albumArtist;
		
				if ($albumTitle == '')
				{

				}
				else
				{
						++$ts;
					array_push($results, $album);
				}
				++$i;
		}
		echo json_encode($results);
}
if($ts === 0){
	//echo '[]';
}
?>