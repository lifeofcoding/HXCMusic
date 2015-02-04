<?php
		class Asset
		{
			public $folder;
			public $ext;
			public $id;
		}

		$controllers = new Asset();
		$controllers->folder = 'js/controllers';
		$controllers->ext = 'js';
		$controllers->id = false;

		$moduals = new Asset();
		$moduals->folder = 'js/modules';
		$moduals->ext = 'js';
		$moduals->id = false;

		$models = new Asset();
		$models->folder = 'js/models';
		$models->ext = 'js';
		$models->id = false;

		$views = new Asset();
		$views->folder = 'js/views';
		$views->ext = 'js';
		$views->id = false;

		$assetList = array($models, $views, $moduals, $controllers);

	foreach($assetList as $asset) {
		if ($handle = opendir('./'.$asset->folder)) {
			while (false !== ($file = readdir($handle))){
				if ($file != "." && $file != ".." && strtolower(substr($file, strrpos($file, '.') + 1)) == $asset->ext){
						echo "\r\n<script src='/".$asset->folder."/".$file."?cache=".$cacheNumber."'></script>";
				}
			}
			closedir($handle);
		}
	}
		
?>