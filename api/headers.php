<?php
$validDomains = array(
    "http://hxcmusic.com",
    "http://hxcmusic.me",
	"http://development.hxcmusic.me",
	"http://development.hxcmusic.com"
);
$http_origin = $_SERVER['HTTP_ORIGIN'];

if(in_array($http_origin, $validDomains)){  
    header("Access-Control-Allow-Origin: $http_origin");
}
?>