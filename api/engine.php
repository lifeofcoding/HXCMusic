<?
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

function sendAlert($subject, $message){
    // message lines should not exceed 70 characters (PHP rule), so wrap it
    //$message = wordwrap($message, 70);

    // send mail
    mail("lifeofcoding@gmail.com",$subject,$message,"From: Alerts@HXCMusic.com\n");
}

  $p = $_GET['page'];

  $srch = $_GET['search'];
    $srch = urldecode ($srch);

$source = $_GET['source'];

    $srch = str_replace ('-', ' ', $srch);
    $srch = str_replace ('%20', ' ', $srch);
    $srch = str_replace ('+', ' ', $srch);
    $srch = str_replace ('_', ' ', $srch);
    $srch = str_replace ('.', '', $srch);

$srch_url = urlencode($srch);

$random = $_GET['random'];

if($random == "1"){
$api_id="2138269";
$app_key="au34jkZmuo";
$user="123680484";
}
if($random == "2"){
$api_id="2152440";
$app_key="X6RnGGdulV";
$user="126540539";
}

/* old one */
//$api_id="3010666";
//$app_key="aCAbvLrtu8npwajWmcOc";
//$user="176428396";

$sig = md5('' .$user. 'api_id=' .$api_id. 'count=30method=audio.searchq=' .$srch. 'test_mode=1' .$app_key. '');
$url = 'http://api.vkontakte.ru/api.php?api_id=' .$api_id. '&count=30&method=audio.search&sig='.$sig.'&test_mode=1&q=' .$srch_url. '';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,"$url");
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.2; en-US; rv:1.8.1.7) Gecko/20070914 Firefox/2.0.0.7");
curl_setopt($ch, CURLOPT_REFERER, "http://api.vkontakte.ru/");
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$chttp_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$gurl = curl_exec($ch);
curl_close($ch);
$chttp_status=200;
if ($chttp_status === 200) {
	$i = 1;
	$res = strstr($gurl, '<audio>');

    if ($res)

    {

      while ($i <= 30)

      {

        $res = strstr ($res, '<audio>');
		  $artist = extstr3($res,'<artist>','</artist>');
		  $title = extstr3($res,'<title>','</title>');
        $name = $artist. " - " .$title;
        $getid1 = extstr3($res,"return operate(",",");
        $id = extstr3($res,"<url>","</url>");

        $url = $id;

        $dur = extstr3($res,'<duration>','</duration>');
       /// $playtime = floor($dur/60) . ":" . $dur % 60; 

        $res = strstr ($res, '</audio>');


				if (($name == '')||($name == ' - '))
				{
						$nomsg = $nomsg + 50;
				}
				else
				{
						++$ts;
					echo "<audio>";
					echo "<name>".$name."</name>";
					echo "<br>";
					echo "<url>".$url."</url>";
					echo "<br>";
					echo "<duration>".$dur."</duration>";
					echo "<br>";
					echo "<br>";
					echo "</audio>";
				}
			++$i;
		}
	}
}else{
	sendAlert('[HXCMusic] Source Failed', 'Status: '.$chttp_status);
}
?>