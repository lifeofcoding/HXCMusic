<?php
//header( "Content-type: image/jpeg");
include("/var/zpanel/hostdata/zadmin/public_html/hxcmusic_com/production/crypt.php");
$id = $_GET['id'];
$id = str_replace(".jpg", "", $id);

$decoded = Encode(hex2bin($id),$secretPass); 

//echo $decoded;
//die;

//$headers = get_headers($decoded,1) or die("Error!");
//$type = $headers['Content-Type'];
//$size = $headers['Content-Length'];

//header("Content-Type: ".$type);
//header("Content-Length: ".$size);

//$image = file_get_contents($decoded);

header('location: '.$decoded.'');

//echo $image;
?>
