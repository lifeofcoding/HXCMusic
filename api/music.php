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

if(isset($_GET['sort_by'])){
$sort_by = urldecode($_GET['sort_by']);
}else{
$sort_by = 'default';
}

if(isset($_GET['page'])){
	$p = $_GET['page'];
}else{
	$p = 1;
}
	
if ($sort_by == 'default') {
$url = "http://www.purevolume.com/browse/?t=Artist&p=$p";
///$url = "http://www.purevolume.com/browse/?t=Artist&music_genres_include=24&p=$p";
}

if ($sort_by == 'Metal') {
$url = "http://www.purevolume.com/browse/?t=Artist&music_genres_include=4&p=$p";
}

if ($sort_by == 'Punk') {
$url = "http://www.purevolume.com/browse/?t=Artist&music_genres_include=2&p=$p";
}

if ($sort_by == 'Emo') {
$url = "http://www.purevolume.com/browse/?t=Artist&music_genres_include=28&p=$p";
}

if ($sort_by == 'Screamo' || $sort_by == 'Alternative') {
$url = "http://www.purevolume.com/browse/?t=Artist&music_genres_include=36&p=$p";
}

if ($sort_by == 'Indie') {
$url = "http://www.purevolume.com/browse/?t=Artist&music_genres_include=37&p=$p";
}

if ($sort_by == 'Hardcore') {
$url = "http://www.purevolume.com/browse/?t=Artist&music_genres_include=35&p=$p";
}

if ($sort_by == 'Ska') {
$url = "http://www.purevolume.com/browse/?t=Artist&music_genres_include=27&p=$p";
}

if ($sort_by == 'Rap') {
$url = "http://www.purevolume.com/browse/?t=Artist&music_genres_include=9&p=$p";
}

if ($sort_by == 'Pop') {
$url = "http://www.purevolume.com/browse/?t=Artist&music_genres_include=7&p=$p";
}


if ($sort_by == 'Reggae') {
$url = "http://www.purevolume.com/browse/?t=Artist&music_genres_include=32&p=$p";
}

if ($sort_by == 'Christian') {
$url = "http://www.purevolume.com/browse/?t=Artist&music_genres_include=42&p=$p";
}

$sort_by_dec = urldecode($sort_by);
if ($sort_by_dec== 'R&B') {
$url = "http://www.purevolume.com/browse/?t=Artist&music_genres_include=31&p=$p";
}

if ($sort_by == 'Southernrock') {
$url = "http://www.purevolume.com/browse/?t=Artist&music_genres_include=34&p=$p";
}

if ($sort_by == 'Deathmetal') {
$url = "http://www.purevolume.com/browse/?t=Artist&music_genres_include=18&p=$p";
}

if ($sort_by == 'Hiphop') {
$url = "http://www.purevolume.com/browse/?t=Artist&music_genres_include=8&p=$p";
}

if ($sort_by == 'Country') {
$url = "http://www.purevolume.com/browse/?t=Artist&music_genres_include=3&p=$p";
}

if ($sort_by == 'Metalcore') {
$url = "http://www.purevolume.com/browse/?t=Artist&music_genres_include=46&p=$p";
}

if ($sort_by == 'House' || $sort_by == 'Dance') {
$url = "http://www.purevolume.com/browse/?t=Artist&music_genres_include=24&p=$p";
}

if ($sort_by == 'Techno') {
$url = "http://www.purevolume.com/browse/?t=Artist&music_genres_include=47&p=$p";
}

if ($sort_by == 'Electronica') {
$url = "http://www.purevolume.com/browse/?t=Artist&music_genres_include=10&p=$p";
}

if ($sort_by == 'Progressive') {
$url = "http://www.purevolume.com/browse/?t=Artist&music_genres_include=29&p=$p";
}

if ($sort_by == 'Experimental') {
$url = "http://www.purevolume.com/browse/?t=Artist&music_genres_include=22&p=$p";
}

if ($sort_by == 'Psychedelic') {
$url = "http://www.purevolume.com/browse/?t=Artist&music_genres_include=30&p=$p";
}

if ($sort_by == 'Jazz') {
$url = "http://www.purevolume.com/browse/?t=Artist&music_genres_include=5&p=$p";
}

if ($sort_by == 'Folk') {
$url = "http://www.purevolume.com/browse/?t=Artist&music_genres_include=19&p=$p";
}

$sort_by_dec = urldecode($sort_by);
if ($sort_by_dec == 'Christian Rap') {
$url = "http://www.purevolume.com/browse/?t=Artist&music_genres_include=41&p=$p";
}

$sort_by_dec = urldecode($sort_by);
if ($sort_by_dec == 'Pop Punk') {
$url = "http://www.purevolume.com/browse/?t=Artist&music_genres_include=40&p=$p";
}

if ($sort_by == 'Rockabilly') {
$url = "http://www.purevolume.com/browse/?t=Artist&music_genres_include=44&p=$p";
}

$sort_by_dec = urldecode($sort_by);
if ($sort_by_dec == 'Surf Rock') {
$url = "http://www.purevolume.com/browse/?t=Artist&music_genres_include=43&p=$p";
}

if ($sort_by == 'Ambient') {
$url = "http://www.purevolume.com/browse/?t=Artist&music_genres_include=13&p=$p";
}

$sort_by_dec = urldecode($sort_by);
if ($sort_by == 'Drum and Bass') {
$url = "http://www.purevolume.com/browse/?t=Artist&music_genres_include=49&p=$p";
}

if ($sort_by == 'Industrial') {
$url = "http://www.purevolume.com/browse/?t=Artist&music_genres_include=50&p=$p";
}

if ($sort_by == 'Soul') {
$url = "http://www.purevolume.com/browse/?t=Artist&music_genres_include=33&p=$p";
}

if ($sort_by == 'Grunge') {
$url = "http://www.purevolume.com/browse/?t=Artist&music_genres_include=23&p=$p";
}

if ($sort_by == 'Blues') {
$url = "http://www.purevolume.com/browse/?t=Artist&music_genres_include=6&p=$p";
}

if ($sort_by == 'Powerpop') {
$url = "http://www.purevolume.com/browse/?t=Artist&music_genres_include=38&p=$p";
}

$sort_by_dec = urldecode($sort_by);
if ($sort_by == 'Classic Rock' || $sort_by == 'Rock') {
$url = "http://www.purevolume.com/browse/?t=Artist&music_genres_include=15&p=$p";
}

if ($sort_by == 'Club') {
$url = "http://www.purevolume.com/browse/?t=Artist&music_genres_include=16&p=$p";
}

$sort_by_dec = urldecode($sort_by);
if ($sort_by == 'Post Hardcore') {
$url = "http://www.purevolume.com/browse/?t=Artist&music_genres_include=39&p=$p";
}

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,"$url");
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.2; en-US; rv:1.8.1.7) Gecko/20070914 Firefox/2.0.0.7");
curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");
curl_setopt($ch, CURLOPT_REFERER, "http://purevolume.com/"); 
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, "15");
curl_setopt($ch, CURLOPT_TIMEOUT, "15");
$gurl = curl_exec($ch);
curl_close($ch);

$res = strstr($gurl, '<div class="results">');

$ts=0;
$i=1;
$bgcolor=1;

if($gurl == ""){
//add buffer function to session so now can use headers anywhere.
//echo "<script type='text/javascript'>document.location.href='" .$web_path. "page_error.php'</script>";
//header('Location: ' .$web_path. 'page_error.php');
echo '{"error":{"text":"Load Error"}}';
die;
}

    if ($res)

    {
		
		class Artist
		{
			public $artist;
			public $image;
			public $artistscrap;
			public $location;
			public $genre;
		}

		$results = array();

      while ($i <= 20)

      {

        $res = strstr ($res, '<div class="result_item">');

	$artistscrap = extstr3($res,'<a href="/','" class="image_container"');

	$artist = extstr3($res,'<p><strong><a href="/' .$artistscrap. '">','</a></strong>');

if(strpos($artist, "(")) {
	$artist = extstr3($res,'<p><strong><a href="/' .$artistscrap. '">','(');
}

	$genre = extstr3($res,'</span></p>
                                    <p>','</p>');

	$location = extstr3($res,'<p style="margin-bottom: 0;">','</p>');

       $titlesearch = str_replace(' ','-',$artist);
       $titlesearch = str_replace("'", "", "$titlesearch");

	$image = extstr3($res,'<img src="','" /></a>');

				if ($image == 'http://g.purevolumecdn.com/_images/_default/artist_crop_80x53.gif'){

$image = "" .$web_path. "imagecache/image.php?src=images/noimage.jpg&h=150&w=120&zc=1";
}else{
       $image = str_replace('crop_80x53','crop_120x150',$image);
}

       /// $res = strstr ($res, '<div class="total_progress">');
		  
		        $artistDetail = new Artist();
		  		$artistDetail->artist = $artist;
		  		$artistDetail->image = $image;
		  		$artistDetail->location = $location;
		  		$artistDetail->genre = $genre;
		  		$artistDetail->artistId = $artistscrap;

          		$res = strstr ($res, '<div class="clear"><span class="nodisp">&nbsp;</span></div>');


				if ($artistscrap == '')
				{

				}
				else
				{
					++$ts;
					array_push($results, $artistDetail);
				}
				++$i;

		}
}

	echo json_encode($results);
?>