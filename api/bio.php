<?php
include('headers.php');
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

    $srch = $_GET['artist'];
	$srch = str_replace ('%20', '+', $srch);
	$srch = str_replace (' ', '+', $srch);
    $srch = str_replace ('-', '+', $srch);

$url = "http://ws.audioscrobbler.com/2.0/?method=artist.getinfo&artist=" .$srch. "&api_key=be95f4ce88883dc02180d2736384ba74&autocorrect=1";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,"$url");
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.2; en-US; rv:1.8.1.7) Gecko/20070914 Firefox/2.0.0.7");
curl_setopt($ch, CURLOPT_REFERER, "http://www.last.fm/"); 
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, "10");
curl_setopt($ch, CURLOPT_TIMEOUT, "10");
$gurl = curl_exec($ch);
curl_close($ch);

  $i = 1;

$res = strstr($gurl, '<lfm status="ok">');


    if ($res)

    {

		class Artist
		{
			public $image;
			public $artist;
			public $from;
			public $bio;
			public $genres;
			public $suggestions;
			public $correction;
		}

		$results = array();
		
$name = extstr3($res,'<name>','</name>');

$image = extstr3($res,'<image size="mega">','</image>');

$published = extstr3($res,'<published>','</published>');

$summary = extstr3($res,'<![CDATA[',']]>');

$summary = preg_replace("/\<a(.*)\>(.*)\<\/a\>/iU", "", $summary);

$summary = preg_replace("/\<a([^>]*)\>([^<]*)\<\/a\>/i", "", $summary);

$summary = str_replace ('title="', '', $summary);

$summary = str_replace ('<a', '', $summary);

$summary = str_replace ('="', '', $summary);

$summary = str_replace ('href', '', $summary); 

$summary = str_replace ('"', '', $summary);

$summary = str_replace ('http://www.last.fm/music/', '', $summary);

$placeformed = extstr3($res,'<placeformed>','</placeformed>');

$yearformed = extstr3($res,'<yearformed>','</yearformed>');

$image = extstr3($res,'<image size="mega">','</image>');

$published = extstr3($res,'<published>','</published>');

$i=1;

$ressim = strstr($res, '<similar>');
$restags = strstr($res, '<tags>');

while($i < 6){
$ressim = strstr($ressim, '<artist>');
        $sim[$i] = extstr3($ressim,'<name>','</name>');
        $simurl[$i] = str_replace (' ', '-', $sim[$i]);
        $simurl[$i] = strtolower($simurl[$i]);
$ressim = strstr($ressim, '</artist>');

       /// $tag = extstr3($restags,'<name>','</name>');

$restags = strstr($restags, '<tag>');
        $tag[$i] = extstr3($restags,'<name>','</name>');
$restags = strstr($restags, '</tag>');

++$i;
}

//add customized suggestions
if($name){
	$expire = time() + 60*60*24*30; //1 month expired.
	setcookie("lastSearched", $name,  $expire);
}

if($tag){
//echo $tag;
$expire = time() + 60*60*24*30; //1 month expired.
if(($tag[1])&&($tag[2])&&($tag[3])){
	$random = rand(1,3);
	setcookie("Genre", json_encode($tag),  $expire);
//setcookie ("Genre", $tag[$random],  $expire, "/");
//echo "dsgsgsgsgrgsg-".$tag[$random];
}else{
//echo 'testdgdgdgd';
//setcookie ("Genre", $tag[1],  $expire, "/");
}

}

$string = $image;
$pos = strpos($string, "Keepstatsclean");
if ($pos === false) {
//not found
}else{
$summary=null;
//echo 'true';
}

if($summary){
$terms = $srch;
$terms = str_replace ('-', ' ', $terms);
$terms = str_replace ('+', ' ', $terms);
$terms = str_replace ('%20', ' ', $terms);
$terms = str_replace ('-', ' ', $terms);
$terms = str_replace ('&', '', $terms);
$terms = str_replace ('and', '', $terms);
$terms = str_replace ('amp;', '', $terms);
$nametest = $name;
$nametest = str_replace ('-', ' ', $nametest);
$nametest = str_replace ('&', '', $nametest);
$nametest = str_replace ('and', '', $nametest);
$nametest = str_replace ('amp;', '', $nametest);

$nametest = strtolower($nametest);
$terms = strtolower($terms);
$terms = str_replace ('!', '', $terms);
$terms = str_replace (',', '', $terms);
$terms = str_replace ('.', '', $terms);
$nametest = str_replace ('!', '', $nametest);
$nametest = str_replace (',', '', $nametest);
$nametest = str_replace ('.', '', $nametest);

if($nametest!=$terms){
$auto_correct=1;
$termsurl = str_replace (' ', '-', $terms);
}else{
$auto_correct=0;
}

	$artist = new Artist();
	$artist->image = $image;
	$artist->artist = $name;
	$artist->from = $placeformed;
	$artist->bio = $summary;
	$artist->genres = array($tag[1], $tag[2], $tag[3], $tag[4], $tag[5]);
	$artist->suggestions = array($sim[1], $sim[2], $sim[3], $sim[4], $sim[5]);
	if($auto_correct == '1'){
		$artist->correction = true;
	}else{
		$artist->correction = false;
	}
	
	array_push($results, $artist);

} //end if summary
	echo json_encode($results);
}
?>