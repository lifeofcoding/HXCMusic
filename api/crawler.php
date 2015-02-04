<?php
$current_addr =  $_SERVER['HTTP_HOST'];
$current_addr = "http://cloud." .$current_addr. ":3000" .$_SERVER['REQUEST_URI'];

//echo $current_addr;
//die;
if (strpos($_SERVER['REQUEST_URI'],'crawler.php') !== false) {
    echo 'Cannot be viewed directly!';
	die;
}

function getExternalLoad(){
	$ch  = curl_init();
	curl_setopt($ch, CURLOPT_URL, "http://cloud.hxcmusic.com/api/load.php");
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.2; en-US; rv:1.8.1.7) Gecko/20070914 Firefox/2.0.0.7");
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$gurl = curl_exec($ch);
	$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);
	
	if($http_status === 200){
		return $gurl;
	}else{
		return 'failed';
	}
}

function systemLoadInPercent($coreCount = 1,$interval = 1){
    $rs = sys_getloadavg();
    $interval = $interval >= 1 && 3 <= $interval ? $interval : 1;
    $load  = $rs[$interval];
    return round(($load * 100) / $coreCount,2);
}

/// check server load - embed
function extstr4($content,$start,$end){
  if($content && $start && $end) {
    $r = explode($start, $content);
    if (isset($r[1])){
        $r = explode($end, $r[1]);
        return $r[0];
    }
    return '';
  }
}
$load = sys_getloadavg();
$res = "." .$load[0]. "";

$check = extstr4($res,'.','.');

function cmdrun($cmd) {
  global $connection,$ssh2;

  if (!$ssh2) {
    $result = shell_exec($cmd);
  } else if ($ssh2 && function_exists('ssh2_exec')) {
    ob_start();
    $stream = ssh2_exec($connection, $cmd);
    stream_set_blocking($stream, 1);
    echo stream_get_contents($stream);
    $result = ob_get_contents();
    fclose($stream);
    ob_end_clean();
  }

  return $result;
}

// Number Of CPUs.
// ==========================================
// CPU Info Function
// ==========================================
function cpuinfo() {
  $cpuinfoo = cmdrun('cat /proc/cpuinfo');
  $cpuinfo = explode("\n", $cpuinfoo);
  $cpuinf = array();
  $cpuinf['total'] = 0;
  foreach ($cpuinfo as $cpuin) {
    $cpui = explode(':', $cpuin);
    if (isset($cpui[0])) { $item = rtrim($cpui[0]); }
    if (isset($cpui[1])) { $data = rtrim($cpui[1]); }
    if ($item == 'processor') { ++$cpuinf['total']; }
    if ($item == 'model name') { $cpuinf['name'] = $data; }
    if ($item == 'cpu MHz') { $cpuinf['mhz'] = $data; }
  }
  return $cpuinf;
}
$cpuinf = cpuinfo();

//echo $load[0];

$core_nums = $cpuinf['total'];
$loadPercent = systemLoadInPercent($core_nums);


$cloudLoadPercent = getExternalLoad();
/* echo 'cloudLoadPercent: '.$cloudLoadPercent;
echo '<br>loadPercent: '.$loadPercent;
if ($loadPercent > $cloudLoadPercent) {
	echo '<br>Go to cloud';
}else{
	echo '<br>Stay on main';
}
die; */
//if ($load[0] > ($cpuinf['total'] - 0.50)) {
if ($cloudLoadPercent !== 'failed' && ($loadPercent > $cloudLoadPercent)) {
	$current_addr =  $_SERVER['HTTP_HOST'];
	$current_addr = "http://cloud." .$current_addr. ":3000" .$_SERVER['REQUEST_URI'];
	echo file_get_contents($current_addr);
	echo '<!--- HXCWeb: '.$loadPercent.' HXCMachine: '.$cloudLoadPercent.' - Rendered Via HXCMachine Server -->';
	echo '<!--- CPU: '.$load[0].'/'.$cpuinf['total'].' - Rendered Via HXCMachine Server -->';
}else{
	$current_addr =  $_SERVER['HTTP_HOST'];
	$current_addr = "http://stats." .$current_addr. ":3000" .$_SERVER['REQUEST_URI'];
	echo file_get_contents($current_addr);
	echo '<!--- HXCWeb: '.$loadPercent.' HXCMachine: '.$cloudLoadPercent.' - Rendered Via HXCWeb Server -->';
	echo '<!--- CPU: '.$load[0].'/'.$cpuinf['total'].' - Rendered Via HXCWeb Server -->';
}
//end check server load
?>