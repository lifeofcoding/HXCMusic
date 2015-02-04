<?php
//include("includes/session.php");
$action = $_REQUEST['action'];

//error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);

header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Content-type: text/xml; charset=utf-8");

if(!$action){
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
   <sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
   <sitemap>
      <loc><?=$web_path?>map1.xml</loc>
   </sitemap>
   <sitemap>
      <loc><?=$web_path?>map6.xml</loc>
   </sitemap>
   <sitemap>
      <loc><?=$web_path?>map7.xml</loc>
   </sitemap>
   <sitemap>
      <loc><?=$web_path?>map8.xml</loc>
   </sitemap>
   <sitemap>
      <loc><?=$web_path?>map9.xml</loc>
   </sitemap>
   <sitemap>
      <loc><?=$web_path?>map10.xml</loc>
   </sitemap>
   <sitemap>
      <loc><?=$web_path?>map11.xml</loc>
   </sitemap>
   <sitemap>
      <loc><?=$web_path?>map12.xml</loc>
   </sitemap>
   <sitemap>
      <loc><?=$web_path?>map13.xml</loc>
   </sitemap>
   <sitemap>
      <loc><?=$web_path?>map14.xml</loc>
   </sitemap>
   <sitemap>
      <loc><?=$web_path?>map15.xml</loc>
   </sitemap>
   <sitemap>
      <loc><?=$web_path?>map16.xml</loc>
   </sitemap>
   <sitemap>
      <loc><?=$web_path?>map17.xml</loc>
   </sitemap>
   <sitemap>
      <loc><?=$web_path?>map18.xml</loc>
   </sitemap>
   <sitemap>
      <loc><?=$web_path?>map19.xml</loc>
   </sitemap>
   <sitemap>
      <loc><?=$web_path?>map20.xml</loc>
   </sitemap>
   <sitemap>
      <loc><?=$web_path?>map21.xml</loc>
   </sitemap>
   <sitemap>
      <loc><?=$web_path?>map22.xml</loc>
   </sitemap>
   <sitemap>
      <loc><?=$web_path?>map23.xml</loc>
   </sitemap>
   <sitemap>
      <loc><?=$web_path?>map24.xml</loc>
   </sitemap>
   <sitemap>
      <loc><?=$web_path?>map25.xml</loc>
   </sitemap>
   <sitemap>
      <loc><?=$web_path?>map26.xml</loc>
   </sitemap>
   <sitemap>
      <loc><?=$web_path?>map27.xml</loc>
   </sitemap>
   <sitemap>
      <loc><?=$web_path?>map28.xml</loc>
   </sitemap>
   <sitemap>
      <loc><?=$web_path?>map29.xml</loc>
   </sitemap>
   <sitemap>
      <loc><?=$web_path?>map30.xml</loc>
   </sitemap>
   <sitemap>
      <loc><?=$web_path?>map31.xml</loc>
   </sitemap>
   <sitemap>
      <loc><?=$web_path?>map32.xml</loc>
   </sitemap>
   <sitemap>
      <loc><?=$web_path?>map33.xml</loc>
   </sitemap>
   <sitemap>
      <loc><?=$web_path?>map34.xml</loc>
   </sitemap>
   <sitemap>
      <loc><?=$web_path?>map35.xml</loc>
   </sitemap>
   <sitemap>
      <loc><?=$web_path?>map36.xml</loc>
   </sitemap>
   <sitemap>
      <loc><?=$web_path?>map37.xml</loc>
   </sitemap>
   <sitemap>
      <loc><?=$web_path?>map38.xml</loc>
   </sitemap>
   <sitemap>
      <loc><?=$web_path?>map39.xml</loc>
   </sitemap>
   <sitemap>
      <loc><?=$web_path?>map40.xml</loc>
   </sitemap>
   <sitemap>
      <loc><?=$web_path?>map41.xml</loc>
   </sitemap>
   <sitemap>
      <loc><?=$web_path?>map42.xml</loc>
   </sitemap>
   <sitemap>
      <loc><?=$web_path?>map43.xml</loc>
   </sitemap>
   <sitemap>
      <loc><?=$web_path?>map44.xml</loc>
   </sitemap>
   <sitemap>
      <loc><?=$web_path?>map45.xml</loc>
   </sitemap>
   <sitemap>
      <loc><?=$web_path?>map46.xml</loc>
   </sitemap>
   <sitemap>
      <loc><?=$web_path?>map47.xml</loc>
   </sitemap>
</sitemapindex>
<?
die;
}

if($action == '1'){
echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <url>
	   <loc><?=$web_path?></loc> 
	   <priority>1.0</priority>
  </url>
  <url>
	   <loc><?=$web_path?>music</loc> 
	   <priority>1.0</priority>
  </url>
  <url>
	   <loc><?=$web_path?>videos</loc> 
	   <priority>1.0</priority>
  </url>
  <url>
	   <loc><?=$web_path?>events</loc>
	   <priority>1.0</priority>
  </url>
</urlset>
<?
die;
}

//no longer using 2
if($action == '2'){
echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?

$query = mysql_query("SELECT * FROM tags ORDER by id DESC LIMIT 1000");

$i=1;

while ($row = mysql_fetch_array($query))
{
$tag=$row["tag"];
$tag_name=$row["tag"];
$type=$row["type"];

if(($type=="lyrics")||($type=="video")) {
$tag = str_replace("-","+",$tag);
}

if(($type == "mp3")||($type == "")){
$tag = str_replace(" ","-",$tag);
} else{
$tag = str_replace(" ","+",$tag);
}

$tag = html_entity_decode($tag);
$tag = str_replace("&","",$tag);
$tag = preg_replace('/[^(\x20-\x7F)]*/','', $tag);

$count = $i++;

if($type == ""){
if($type=="lyrics") {
$type = "music";
} elseif($type=="video") {
$type = "videos";
} elseif($type=="movie") {
$type = "videos";
} elseif($type=="albums") {
$type = "music";
} else {
$type = "music";
}
}

    $string = $tag;
    $pos = strpos($string, "pthc");
    $pos2 = strpos($string, "http");
    $pos3 = strpos($string, "sex");
    $pos4 = strpos($string, "nude");
    if (($pos === false)&&($tag)&&($pos2 === false)&&($pos3 === false)&&($pos4 === false)) {
       /// print "Not found\n";

$random = rand(1,4);

if($random == '1'){
$time = time() - 1000;
}
if($random == '2'){
$time = time() - 9754;
}
if($random == '3'){
$time = time() - 100;
}
if($random == '4'){
$time = time() - 12000;
}
?>
  <url>
	   <loc><?=$web_path?><?=$type?>/<?=$tag?></loc> 
	   <priority>0.9</priority>
	   <lastmod><?=date('Y-m-d\TH:i:s+00:00',$time);?></lastmod>
	   <changefreq>weekly</changefreq>
  </url>
<?
    }
} ?>
</urlset>
<?
die;
}

if($action == '6'){
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

echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?
$url = "http://www.apple.com/itunes/charts/songs/";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,"$url");
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.2; en-US; rv:1.8.1.7) Gecko/20070914 Firefox/2.0.0.7");
curl_setopt($ch, CURLOPT_REFERER, "http://www.apple.com/"); 
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
///curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, "10");
///curl_setopt($ch, CURLOPT_TIMEOUT, "10");
$gurl = curl_exec($ch);
curl_close($ch);

  $i = 1;
  $ts = 1;

$res = strstr($gurl, '<section class="grid">');


    if ($res)
    {

      while ($i <= 100)

      {

        $res = strstr ($res, '<li>');
   
	$song = extstr3($res,'alt="','"');

	$artist = extstr3($res,'?uo=4">','</a>');


	$name = $artist.' - '.$song;

$name = str_replace("'","",$name);
$name = str_replace('"','',$name);

$name = str_replace('(','',$name);
$name = str_replace(')','',$name);

$name = str_replace(']','',$name);
$name = str_replace('[','',$name);

$name = html_entity_decode($name);
$name = str_replace("&","",$name);
$name = preg_replace('/[^(\x20-\x7F)]*/','', $name);

//$position=24; // Define how many character you want to display.

//$short_title=$name;
//$tag = substr($short_title, 0, $position);

$tag=$name;

$tag = str_replace(" ","-",$tag);
$tag = str_replace("---","-",$tag);
$tag = str_replace('&','%26',$tag);

$tag = strtolower($tag);

        $res = strstr ($res, '</li>');


				if ($name == '')
				{
						//$nomsg = $nomsg + 50;
				}
				else
				{
						++$ts;

$random = rand(1,4);

if($random == '1'){
$time = time() - 1000;
}
if($random == '2'){
$time = time() - 9754;
}
if($random == '3'){
$time = time() - 100;
}
if($random == '4'){
$time = time() - 12000;
}
?>

  <url>
	   <loc><?=$web_path?>music/<?=$tag?></loc> 
	   <priority>0.9</priority>
	   <lastmod><?=date('Y-m-d\TH:i:s+00:00',$time);?></lastmod>
	   <changefreq>weekly</changefreq>
  </url>

<?
				}
				++$i;

		}
}
?>
</urlset>
<?
die;
}

if($action == '7'){
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

echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?
$url = "http://www.loudtronix.me/sitemap2.xml";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,"$url");
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.2; en-US; rv:1.8.1.7) Gecko/20070914 Firefox/2.0.0.7");
curl_setopt($ch, CURLOPT_REFERER, "http://www.loudtronix.me/"); 
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
///curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, "10");
///curl_setopt($ch, CURLOPT_TIMEOUT, "10");
$gurl = curl_exec($ch);
curl_close($ch);

  $i = 1;
  $ts = 1;

$res = strstr($gurl, '<url>');


    if ($res)
    {

      while ($i <= 1000)

      {

        $res = strstr ($res, '<url>');
   
	$result = extstr3($res,'<loc>http://www.loudtronix.me/search/','</loc>');

	$result = $web_path.'music/'.$result;

        $res = strstr ($res, '</url>');


				if ($result == '')
				{
						//$nomsg = $nomsg + 50;
				}
				else
				{
						++$ts;

$random = rand(1,4);

if($random == '1'){
$time = time() - 1000;
}
if($random == '2'){
$time = time() - 9754;
}
if($random == '3'){
$time = time() - 100;
}
if($random == '4'){
$time = time() - 12000;
}
?>

  <url>
	   <loc><?=$result?></loc> 
	   <priority>0.9</priority>
	   <lastmod><?=date('Y-m-d\TH:i:s+00:00',$time);?></lastmod>
	   <changefreq>weekly</changefreq>
  </url>

<?
				}
				++$i;

		}
}
?>
</urlset>
<?
die;
}

if($action == '8'){
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

echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?
$url = "http://www.loudtronix.me/sitemap3.xml";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,"$url");
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.2; en-US; rv:1.8.1.7) Gecko/20070914 Firefox/2.0.0.7");
curl_setopt($ch, CURLOPT_REFERER, "http://www.loudtronix.me/"); 
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
///curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, "10");
///curl_setopt($ch, CURLOPT_TIMEOUT, "10");
$gurl = curl_exec($ch);
curl_close($ch);

  $i = 1;
  $ts = 1;

$res = strstr($gurl, '<url>');


    if ($res)
    {

      while ($i <= 1000)

      {

        $res = strstr ($res, '<url>');
   
	$result = extstr3($res,'<loc>http://www.loudtronix.me/search/','</loc>');

	$result = $web_path.'music/'.$result;

        $res = strstr ($res, '</url>');


				if ($result == '')
				{
						//$nomsg = $nomsg + 50;
				}
				else
				{
						++$ts;

$random = rand(1,4);

if($random == '1'){
$time = time() - 1000;
}
if($random == '2'){
$time = time() - 9754;
}
if($random == '3'){
$time = time() - 100;
}
if($random == '4'){
$time = time() - 12000;
}
?>

  <url>
	   <loc><?=$result?></loc> 
	   <priority>0.9</priority>
	   <lastmod><?=date('Y-m-d\TH:i:s+00:00',$time);?></lastmod>
	   <changefreq>weekly</changefreq>
  </url>

<?
				}
				++$i;

		}
}
?>
</urlset>
<?
die;
}

if($action == '9'){
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

echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?
$url = "http://www.loudtronix.me/sitemap4.xml";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,"$url");
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.2; en-US; rv:1.8.1.7) Gecko/20070914 Firefox/2.0.0.7");
curl_setopt($ch, CURLOPT_REFERER, "http://www.loudtronix.me/"); 
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
///curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, "10");
///curl_setopt($ch, CURLOPT_TIMEOUT, "10");
$gurl = curl_exec($ch);
curl_close($ch);

  $i = 1;
  $ts = 1;

$res = strstr($gurl, '<url>');


    if ($res)
    {

      while ($i <= 1000)

      {

        $res = strstr ($res, '<url>');
   
	$result = extstr3($res,'<loc>http://www.loudtronix.me/search/','</loc>');

	$result = $web_path.'music/'.$result;

        $res = strstr ($res, '</url>');


				if ($result == '')
				{
						//$nomsg = $nomsg + 50;
				}
				else
				{
						++$ts;

$random = rand(1,4);

if($random == '1'){
$time = time() - 1000;
}
if($random == '2'){
$time = time() - 9754;
}
if($random == '3'){
$time = time() - 100;
}
if($random == '4'){
$time = time() - 12000;
}
?>

  <url>
	   <loc><?=$result?></loc> 
	   <priority>0.9</priority>
	   <lastmod><?=date('Y-m-d\TH:i:s+00:00',$time);?></lastmod>
	   <changefreq>weekly</changefreq>
  </url>

<?
				}
				++$i;

		}
}
?>
</urlset>
<?
die;
}

if($action >= '10'){
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

echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?
$p=1;

if ($action == '10') {
$url = "http://www.purevolume.com/browse/?t=Artist&music_genres_include=37&p=$p";
}

if ($action == '11') {
$url = "http://www.purevolume.com/browse/?t=Artist&music_genres_include=35&p=$p";
}

if ($action == '12') {
$url = "http://www.purevolume.com/browse/?t=Artist&music_genres_include=27&p=$p";
}

if ($action == '13') {
$url = "http://www.purevolume.com/browse/?t=Artist&music_genres_include=9&p=$p";
}

if ($action == '14') {
$url = "http://www.purevolume.com/browse/?t=Artist&music_genres_include=7&p=$p";
}


if ($action == '15') {
$url = "http://www.purevolume.com/browse/?t=Artist&music_genres_include=32&p=$p";
}

if ($action == '16') {
$url = "http://www.purevolume.com/browse/?t=Artist&music_genres_include=42&p=$p";
}


if ($action == '17') {
$url = "http://www.purevolume.com/browse/?t=Artist&music_genres_include=34&p=$p";
}

if ($action == '18') {
$url = "http://www.purevolume.com/browse/?t=Artist&music_genres_include=18&p=$p";
}

if ($action == '19') {
$url = "http://www.purevolume.com/browse/?t=Artist&music_genres_include=8&p=$p";
}

if ($action == '20') {
$url = "http://www.purevolume.com/browse/?t=Artist&music_genres_include=3&p=$p";
}

if ($action == '21') {
$url = "http://www.purevolume.com/browse/?t=Artist&music_genres_include=46&p=$p";
}

if ($action == '22') {
$url = "http://www.purevolume.com/browse/?t=Artist&music_genres_include=24&p=$p";
}

if ($action == '23') {
$url = "http://www.purevolume.com/browse/?t=Artist&music_genres_include=47&p=$p";
}

if ($action == '24') {
$url = "http://www.purevolume.com/browse/?t=Artist&music_genres_include=10&p=$p";
}

if ($action == '25') {
$url = "http://www.purevolume.com/browse/?t=Artist&music_genres_include=29&p=$p";
}

if ($action == '26') {
$url = "http://www.purevolume.com/browse/?t=Artist&music_genres_include=22&p=$p";
}

if ($action == '27') {
$url = "http://www.purevolume.com/browse/?t=Artist&music_genres_include=30&p=$p";
}

if ($action == '28') {
$url = "http://www.purevolume.com/browse/?t=Artist&music_genres_include=5&p=$p";
}

if ($action == '29') {
$url = "http://www.purevolume.com/browse/?t=Artist&music_genres_include=19&p=$p";
}

if ($action == '30') {
$url = "http://www.purevolume.com/browse/?t=Artist&music_genres_include=41&p=$p";
}

if ($action == '31') {
$url = "http://www.purevolume.com/browse/?t=Artist&music_genres_include=40&p=$p";
}

if ($action == '32') {
$url = "http://www.purevolume.com/browse/?t=Artist&music_genres_include=44&p=$p";
}

if ($action == '33') {
$url = "http://www.purevolume.com/browse/?t=Artist&music_genres_include=43&p=$p";
}

if ($action == '34') {
$url = "http://www.purevolume.com/browse/?t=Artist&music_genres_include=13&p=$p";
}

if ($action == '35') {
$url = "http://www.purevolume.com/browse/?t=Artist&music_genres_include=49&p=$p";
}

if ($action == '36') {
$url = "http://www.purevolume.com/browse/?t=Artist&music_genres_include=50&p=$p";
}

if ($action == '37') {
$url = "http://www.purevolume.com/browse/?t=Artist&music_genres_include=33&p=$p";
}

if ($action == '38') {
$url = "http://www.purevolume.com/browse/?t=Artist&music_genres_include=23&p=$p";
}

if ($action == '39') {
$url = "http://www.purevolume.com/browse/?t=Artist&music_genres_include=6&p=$p";
}

if ($action == '40') {
$url = "http://www.purevolume.com/browse/?t=Artist&music_genres_include=38&p=$p";
}

if ($action == '41') {
$url = "http://www.purevolume.com/browse/?t=Artist&music_genres_include=15&p=$p";
}

if ($action == '42') {
$url = "http://www.purevolume.com/browse/?t=Artist&music_genres_include=16&p=$p";
}

if ($action == '43') {
$url = "http://www.purevolume.com/browse/?t=Artist&music_genres_include=39&p=$p";
}

if ($action == '44') {
$url = "http://www.purevolume.com/browse/?t=Artist&music_genres_include=4&p=$p";
}

if ($action == '45') {
$url = "http://www.purevolume.com/browse/?t=Artist&music_genres_include=2&p=$p";
}

if ($action == '46') {
$url = "http://www.purevolume.com/browse/?t=Artist&music_genres_include=28&p=$p";
}

if ($action == '47') {
$url = "http://www.purevolume.com/browse/?t=Artist&music_genres_include=36&p=$p";
}


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,"$url");
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.2; en-US; rv:1.8.1.7) Gecko/20070914 Firefox/2.0.0.7");
curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");
curl_setopt($ch, CURLOPT_REFERER, "http://purevolume.com/"); 
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, "30");
curl_setopt($ch, CURLOPT_TIMEOUT, "30");
$gurl = curl_exec($ch);
curl_close($ch);

$res = strstr($gurl, '<div class="results">');

$i=1;

    if ($res)

    {
      while ($i <= 20)

      {

        $res = strstr ($res, '<div class="result_item">');

	$artistscrap = extstr3($res,'<a href="/','" class="image_container"');

	$artist = extstr3($res,'<p><strong><a href="/' .$artistscrap. '">','</a></strong>');

if(strpos($artist, "(")) {
	$artist = extstr3($res,'<p><strong><a href="/' .$artistscrap. '">','(');
}
$artist = urlencode($artist);

        $res = strstr ($res, '<div class="clear"><span class="nodisp">&nbsp;</span></div>');


				if ($artistscrap == '')
				{
						$nomsg = $nomsg + 50;
				}
				else
				{
						++$ts;
$random = rand(1,4);

if($random == '1'){
$time = time() - 1000;
}
if($random == '2'){
$time = time() - 9754;
}
if($random == '3'){
$time = time() - 100;
}
if($random == '4'){
$time = time() - 12000;
}
?>
  <url>
	   <loc><?=$web_path?>music/<?=$artist?></loc> 
	   <priority>0.5</priority>
	   <lastmod><?=date('Y-m-d\TH:i:s+00:00',$time);?></lastmod>
	   <changefreq>weekly</changefreq>
  </url>
<?
				}
				++$i;

		}
}
?>
</urlset>
<?
die;
}
?>