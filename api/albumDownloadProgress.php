<?php
function createZip($dir, $artist, $album){
	$zip = new ZipArchive();
	
    //create the file and throw the error if unsuccessful
    if ($zip->open('tmpAlbums/'.$artist.' - '.$album.'.zip', ZIPARCHIVE::CREATE )!==TRUE) {
        exit("cannot open <$archive_file_name>\n");
    }

    //add each files of $file_name array to archive
    foreach(getFiles($dir) as $files)
    {
          $zip->addFile('tmpAlbums/'.$dir.'/'.$files,$files);
        //echo 'tmpAlbums/'.$dir.'/'.$files.'<br>';
    }
    $zip->close();
}

function getFiles($dir){
	$files = array();
	if ($handle = opendir('tmpAlbums/'.$dir)) {
			while (false !== ($file = readdir($handle))){
				//echo $file;
				if ($file != "." && $file != ".." && strtolower(substr($file, strrpos($file, '.') + 1)) == 'mp3'){
					array_push($files, $file);
				}
			}
			closedir($handle);
		}
	return $files;
}

$dir = $_GET['dir'];
$files = $_GET['files'];
$album = $_GET['album'];
$artist = $_GET['artist'];

$downloadedFiles = count(glob('tmpAlbums/'.$dir.'/*',GLOB_BRACE));

if($downloadedFiles >= $files){
	// Create zip and serve to user
	createZip($dir, $artist, $album);
	echo '{"status":"finished", "url":"tmpAlbums/'.$artist.' - '.$album.'.zip"}';
}else{
	///show progress
	echo '{"status":"downloading", "progress":"unknown", "progressText":"'.$downloadedFiles.' of '.$files.'"}';
}
?>