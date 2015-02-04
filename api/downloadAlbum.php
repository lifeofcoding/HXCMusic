<?php
	include('headers.php');
	include('../includes/config.php');
	include_once($website_config['web_root']."crypt.php");
	$secretPass = 'lol#hxc*)&$ID';

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

	function createZip($dir, $fileNames){
		$file_path = 'tmpAlbums/'.$dir;
		$zip = new ZipArchive();
		//create the file and throw the error if unsuccessful
		if ($zip->open('test.zip', ZIPARCHIVE::CREATE )!==TRUE) {
			exit("cannot open <$archive_file_name>\n");
		}
		//add each files of $file_name array to archive
		foreach($fileNames as $files)
		{
			  $zip->addFile($file_path.$files,$files);
			//echo $file_path.$files,$files."<br>";
		}
		$zip->close();
	}

	/* First delete old folders & files */
	$iterator = new DirectoryIterator('./tmpAlbums');
	$workingDir = getcwd();
	foreach ($iterator as $fileinfo) {
		if($fileinfo->getFilename() !== '.' && $fileinfo->getFilename() !== '..'){
			if ($fileinfo->isFile()) {
				if((time() - $fileinfo->getMTime()) > 1*3600){ //more than 1 hour old
					unlink($workingDir.'/tmpAlbums/'.$fileinfo->getFilename());
				}
			}else{
				if((time() - $fileinfo->getMTime()) > 1*3600){ //more than 1 hour old
					system("/bin/rm -rf ".$workingDir.'/tmpAlbums/'.$fileinfo->getFilename());
				}
			}
		}
	}

	$artist = urldecode($_GET['artist']);
	$artist = str_replace(' ', '+', $artist);

	$album = urldecode($_GET['album']);
	$album = str_replace(' ', '+', $album);

	$url = "http://ws.audioscrobbler.com/2.0/?method=album.getinfo&api_key=be95f4ce88883dc02180d2736384ba74&artist=".$artist."&album=".$album."";
	//echo $url;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,"$url");
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.2; en-US; rv:1.8.1.7) Gecko/20070914 Firefox/2.0.0.7");
	curl_setopt($ch, CURLOPT_REFERER, "http://www.last.fm/"); 
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, "10");
	curl_setopt($ch, CURLOPT_TIMEOUT, "10");
	$gurl = curl_exec($ch);
	curl_close($ch);

	$i = 1;
	$ts = 0;

	$fileNames = array();

	$res = strstr($gurl, '<tracks>');

    if ($res){
		$random = rand(1000,9999999);
		$dir = 'tmpAlbums/'.$random;
		mkdir($dir);

      while ($i <= 20){
        $res = strstr ($res, '<track rank="'.$i.'">');
		$track = extstr3($res,'<name>','</name>');
		$id = hxcenc($artist.' - '.$track);
		  
		if($track){
			$artist = str_replace('+', ' ', $artist);
			file_put_contents($dir.'/'.$artist.' - '.$track.'.mp3', fopen('http://hxcmusic.com/fetch/'.$id.'.mp3', 'r'));
		}
		  
		  
        $res = strstr ($res, '</track>');
		
				if ($track != ''){
					++$ts;
					array_push($fileNames, $artist.' - '.$track.'.mp3');
				}
				++$i;
		}
	echo '{"dir":"'. $random .'", "files":"'.$ts.'"}';
}
?>