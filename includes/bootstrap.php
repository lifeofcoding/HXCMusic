<?php
$cacheNumber = filemtime(lastModifiedInFolder('./js')); //set to last file modified date to force re-cache

//Added to scan each js asset and find the last modified
function lastModifiedInFolder($folderPath) {
    /* First we set up the iterator */
    $iterator = new RecursiveDirectoryIterator($folderPath);
    $directoryIterator = new RecursiveIteratorIterator($iterator);

    /* Sets a var to receive the last modified filename */
    $lastModifiedFile = "";        

    /* Then we walk through all the files inside all folders in the base folder */
    foreach ($directoryIterator as $name => $object) {
        /* In the first iteration, we set the $lastModified */
        if (empty($lastModifiedFile)) {
            $lastModifiedFile = $name;
        }
        else {
            $dateModifiedCandidate = filemtime($lastModifiedFile);
            $dateModifiedCurrent = filemtime($name);

            /* If the file we thought to be the last modified 
               was modified before the current one, then we set it to the current */
            if ($dateModifiedCandidate < $dateModifiedCurrent && $name !== '../js/..') {
                $lastModifiedFile = $name;
            }
        }
    }

    /* If the $lastModifiedFile isn't set, there were no files
       we throw an exception */
    if (empty($lastModifiedFile)) {
        throw new Exception("No files in the directory");
    }

    return $lastModifiedFile;
}

function loadTemplates(){
	class Assets{
		public $folder;
		public $ext;
		public $id;
	}

	$templates = new Assets();
	$templates->folder = 'templates';
	$templates->ext = 'tpl';
	$templates->id = true;

	$assetList = array($models, $views, $templates, $moduals);
	echo '<!-- Templates start -->';
	foreach($assetList as $asset) {
		if ($handle = opendir('./'.$asset->folder)) {
			while (false !== ($file = readdir($handle))){
				if ($file != "." && $file != ".." && strtolower(substr($file, strrpos($file, '.') + 1)) == $asset->ext){
					if($asset->id){
						echo "\r\n<script type='text/template' id='".str_replace('.tpl', '', $file)."-tpl'>";
						echo "\r\n".file_get_contents('./'.$asset->folder.'/'.$file);
						echo "\r\n</script>";
						//echo "\r\n<script id='".str_replace('.tpl', '', $file)."-tpl' src='".$asset->folder."/".$file."' type='text/x-template'></script>";
					}else{
						echo "\r\n<script src='/".$asset->folder."/".$file."?cache=".$cacheNumber."'></script>";
					}
				}
			}
			closedir($handle);
		}
	}
	echo '<!-- Templates end -->';
}
?>