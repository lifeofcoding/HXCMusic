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
	$srch = urldecode($_GET['search']);
	$srch = str_replace('-', ' ', $srch);
	$srch = 'START'.urldecode($srch).'END';
	$artist = trim(extstr3($srch,'START','  '));
	$track = trim(extstr3($srch,'  ','END'));
	$artist = str_replace(' ', '+', $artist);
	$track = str_replace(' ', '+', $track);

  $url = "http://ws.audioscrobbler.com/2.0/?method=track.getInfo&api_key=be95f4ce88883dc02180d2736384ba74&artist=".$artist."&track=".$track;

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
			public $title;
			public $image;
			public $wiki;
			public $published;
		}
		$results = array();
      while ($i <= 1)

      {

        $albumres = strstr ($res, '<album');
		$albumTitle = extstr3($albumres,'<title>','</title>');
		$albumImage = extstr3($albumres, '<image size="large">','</image>');
        $albumres = strstr ($res, '</album>');
		  
        $wikires = strstr ($res, '<album position="1">');
		$wiki = extstr3($wikires,'<summary>','</summary>');
		$wiki = str_replace('<![CDATA[', '', $wiki);
		$wiki = str_replace(']]>', '', $wiki);
		$published = extstr3($wikires, '<published>','</published>');
        $wikires = strstr ($res, '</album>');

		$album = new Album();
		$album->title = $albumTitle;
		$album->image = $albumImage;
		$album->wiki = $wiki;
		$album->published = $published;
		
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
if(!$res){
	echo '[]';
}
?>