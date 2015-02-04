<?php
header( "Content-type: image/png");
include_once("/var/zpanel/hostdata/zadmin/public_html/crypt.php");
$secretPass = 'lol#hxc*)&$ID';

$image = $_GET['id'];
$image = str_replace('.png', '', $image);
$image = hxcdec($image);
//echo $image;
//die;
echo file_get_contents($image);
?>