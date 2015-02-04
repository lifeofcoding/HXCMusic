<?php
header('Content-type: text/javascript');
include('jsMinifier.php');
//include('cache.php');
$urls = array(
	'http://127.0.0.1/js/app.js'
);
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
		//$js .= file_get_contents('../../js/app.js');
		$firstAssets = array();
		foreach($assetList as $asset) {
			if ($handle = opendir('../../'.$asset->folder)) {
				while (false !== ($file = readdir($handle)))
				{
					if ($file != "." && $file != ".." && strtolower(substr($file, strrpos($file, '.') + 1)) == $asset->ext)
					{
						
						//echo file_get_contents('../../'.$asset->folder.'/'.$file);
						//$js .= file_get_contents('../../'.$asset->folder.'/'.$file);
						
						array_push($urls, 'http://127.0.0.1/'.$asset->folder.'/'.$file);
						//array_push($firstAssets, file_get_contents('../../'.$asset->folder.'/'.$file));
					}
				}
				closedir($handle);
			}
		}

array_push($urls, 'http://127.0.0.1/js/router.js');
array_push($urls, 'http://127.0.0.1/bootstrap.js');
//$js .= file_get_contents('../../js/router.js');
//$js .= file_get_contents('../../bootstrap.js');

// Your URL array that hold links to files 
//$urls = array(); 

//Copy & pasted from the above link
function multiRequest($data, $options = array()) {
  // array of curl handles
  $curly = array();
  // data to be returned
  $result = array();
  // multi handle
  $mh = curl_multi_init();
  // loop through $data and create curl handles
  // then add them to the multi-handle
  foreach ($data as $id => $d) {
    $curly[$id] = curl_init();
    $url = (is_array($d) && !empty($d['url'])) ? $d['url'] : $d;
    curl_setopt($curly[$id], CURLOPT_URL,            $url);
    curl_setopt($curly[$id], CURLOPT_HEADER,         0);
    curl_setopt($curly[$id], CURLOPT_RETURNTRANSFER, 1);
    // post?
    if (is_array($d)) {
      if (!empty($d['post'])) {
        curl_setopt($curly[$id], CURLOPT_POST,       1);
        curl_setopt($curly[$id], CURLOPT_POSTFIELDS, $d['post']);
      }
    }
    // extra options?
    if (!empty($options)) {
      curl_setopt_array($curly[$id], $options);
    }
    curl_multi_add_handle($mh, $curly[$id]);
  }
  // execute the handles
  $running = null;
  do {
    curl_multi_exec($mh, $running);
  } while($running > 0);
  // get content and remove handles
  foreach($curly as $id => $c) {
	  
    $result[$id] = curl_multi_getcontent($c);
    curl_multi_remove_handle($mh, $c);
  }
  // all done
  curl_multi_close($mh);
  return $result;
}

$r = multiRequest($urls);

foreach($r as $val) {
    echo \JShrink\Minifier::minify($val);
}
//echo \JShrink\Minifier::minify($js);
?>