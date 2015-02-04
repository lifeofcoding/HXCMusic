<?php
include('headers.php');
header('Access-Control-Allow-Credentials: true');

//$random = rand(1000,10000000000000);
//$filename = $random;
$imageType = $_FILES['imageFile']['type'];
$imageName = str_replace(' ','-',strtolower($_FILES['imageFile']['name'])); //get image name
$imageExt = substr($imageName, strrpos($imageName, '.'));
$imageExt = str_replace('.','',$imageExt);
$filename = md5($_POST['username']);

switch(strtolower($imageType)){
	case 'image/png':
		$ext = '.png';
		break;
	case 'image/gif':
		$ext = '.gif';
		break;          
	case 'image/jpeg':
	case 'image/pjpeg':
		$ext = 'jpg';
		break;
	default:
	echo '{"error":{"text":"Unsupported File"}}';
	die;
}

if($_POST['hasImage'] !== ''){
	if (strpos($_POST['hasImage'],'.') !== false) {
    	unlink('../avatar/delete/'.$_POST['hasImage']);
	}else{
		unlink('../avatar/delete/'.$_POST['hasImage'].'.jpg');
	}
}

 
if ($_FILES["imageFile"]["size"] < 512000){

  if ($_FILES["imageFile"]["error"] > 0){
	echo '{"error":{"text":"' . $_FILES['imageFile']['error'] . '"}}';
	die;
  }else{
    if (file_exists("delete/" . $filename . "." . $imageExt)){
     	echo '{"error":{"text":"file exists"}}';
		die;
    }else{
		move_uploaded_file($_FILES["imageFile"]["tmp_name"], "../avatar/delete/" . $filename . "." . $imageExt);
		echo '{"success":"true", "url":"'.$filename.'.'.$imageExt.'"}';
    }
 }
}else{
	echo '{"error":{"text":"Filesize too large"}}';
}
?>