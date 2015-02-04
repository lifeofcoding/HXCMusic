<?php
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
    $srch = str_replace ('-', '+', $srch);
    $srch = str_replace (' ', '+', $srch);
	$srch = str_replace ('+++', '+', $srch);

  $url = "http://ws.audioscrobbler.com/2.0/?method=track.search&track=" .$srch. "&api_key=be95f4ce88883dc02180d2736384ba74";

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

      while ($i <= 5)

      {

        $res = strstr ($res, '<track>');
		$image = extstr3($res,'<image size="large">','</image>');
        $res = strstr ($res, '</track>');

			$string = $image;
			$pos = strpos($string, "Keepstatsclean");
		  
				if ($image == '' || $pos !== false)
				{
					//@header('Location: http://'.$_SERVER['HTTP_HOST'].'/images/music.png');
					//die;
					++$i;
				}
				else
				{
					++$ts;
					@header('Location: '.$image);
					die;
				}
		}
		//@header('Location: http://'.$_SERVER['HTTP_HOST'].'/images/music.png');
		header('HTTP/1.0 404 Not Found', true, 404);
		die;
}else{
		//@header('Location: http://'.$_SERVER['HTTP_HOST'].'/images/music.png');
		header('HTTP/1.0 404 Not Found', true, 404);
		die;
	}
?>