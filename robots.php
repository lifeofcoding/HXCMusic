<?php
session_start();
$_SESSION['robot'] = 1;
$thedomain =  $_SERVER['HTTP_HOST'];
$thedomain = "http://" .$thedomain. "/";
?>
User-agent: *
Disallow:
sitemap: <?=$thedomain?>sitemap.xml