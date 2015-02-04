<?php
	/* Save images from url and add to avatars directory - used by Google Signin script */
	include('headers.php');
	$imageURL = $_POST['url'];
	$filename = md5($_POST['username']);
	$img = '../avatar/delete/'.$filename.'.jpg';
	file_put_contents($img, file_get_contents($imageURL));
	echo $filename.'.jpg';
?>