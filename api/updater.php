<?php
include('headers.php');
header('Access-Control-Allow-Credentials: true');

class Assets {
	public $folder;
	public $ext;
	public $id;
}

class File {
	public $filename;
	public $modifiedAt;
}

$moduals         = new Assets();
$moduals->folder = 'js/modules';
$moduals->ext    = 'js';
$moduals->id     = false;

$models         = new Assets();
$models->folder = 'js/models';
$models->ext    = 'js';
$models->id     = false;

$views         = new Assets();
$views->folder = 'js/views';
$views->ext    = 'js';
$views->id     = false;

$templates         = new Assets();
$templates->folder = 'templates';
$templates->ext    = 'tpl';
$templates->id     = true;

$assetList = array(
	$models,
	$views,
	$templates,
	$moduals
);

$files = array();
foreach ($assetList as $asset) {
	if ($handle = opendir('../' . $asset->folder)) {
		while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != ".." && strtolower(substr($file, strrpos($file, '.') + 1)) == $asset->ext) {
				$fileTime             = new File();
				$fileTime->filename   = $asset->folder . '/' . $file;
				$fileTime->modifiedAt = filemtime('../' . $asset->folder . '/' . $file);
				array_push($files, $fileTime);
			}
		}
		closedir($handle);
	}
}
echo json_encode($files);
?>