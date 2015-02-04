<?php
header('Content-type: text/javascript');
include('jsMinifier.php');
include('cache.php');

		class Assets
		{
			public $folder;
			public $ext;
			public $id;
		}

		$controllers = new Assets();
		$controllers->folder = 'js/controllers';
		$controllers->ext = 'js';
		$controllers->id = false;

		$moduals = new Assets();
		$moduals->folder = 'js/modules';
		$moduals->ext = 'js';
		$moduals->id = false;

		$models = new Assets();
		$models->folder = 'js/models';
		$models->ext = 'js';
		$models->id = false;

		$views = new Assets();
		$views->folder = 'js/views';
		$views->ext = 'js';
		$views->id = false;

		$assetList = array($moduals, $controllers, $models, $views);
		$js = null;
		$js .= file_get_contents('../../js/app.js');
		foreach($assetList as $asset) {
			if ($handle = opendir('../../'.$asset->folder)) {
				while (false !== ($file = readdir($handle)))
				{
					if ($file != "." && $file != ".." && strtolower(substr($file, strrpos($file, '.') + 1)) == $asset->ext)
					{
						//echo file_get_contents('../../'.$asset->folder.'/'.$file);
						$js .= file_get_contents('../../'.$asset->folder.'/'.$file);
					}
				}
				closedir($handle);
			}
		}
$js .= file_get_contents('../../js/router.js');
$js .= file_get_contents('../../bootstrap.js');
echo \JShrink\Minifier::minify($js);
?>