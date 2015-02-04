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

function dec($hex){

    $string='';

    for ($i=0; $i < strlen($hex)-1; $i+=2){

        $string .= chr(hexdec($hex[$i].$hex[$i+1]));

    }

    return $string;

}

$id = dec($_GET['id']);

$thedomain =  $_SERVER['HTTP_HOST'];
$thedomain = "http://" .$thedomain. "/";

header('Location: '.$thedomain.'fetch.php?encoded='.$_GET['id'].'&stream=1&geturl=1');
?>