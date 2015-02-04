<?php
	header('Content-type: text/javascript');
	session_start();
	include('config.php');
	include('database.php');
	class User {
		public $id;
		public $username;
		public $userId;
		public $userLevel;
		public $isLoggedIn;
		public $email;
		public $totalDownloads;
		public $dailyDownloads;
		public $downloadLimit;
		public $ip;
		public $image;
	}

	$user = new User();
	
	if (strpos(getcwd(), 'development') !== false) {
    	$environment = 'development';
	}else{
		$environment = 'production';
	}

	if(isset($_COOKIE["HXCID"])) {
		if(isset($_SESSION['userDataOld'])){
			$result = $_SESSION['userData'];
			$user = $result;
/* 			if($result['lastDownloaded'] < (time() - 86400)){
				$user->dailyDownloads = 0;
			}else{
				$user->dailyDownloads = intval($result['dailyDownloads']);
			}
			$user->downloadLimit = $result['userLevel'] === '5' ? null : 3;
			$user->email = $result['email'];
			$user->ip = $_SERVER['REMOTE_ADDR'];
			$user->isLoggedIn = true;
			$user->totalDownloads = intval($result['totalDownloads']);
			$user->userId = $result['userId'];
			$user->userLevel = intval($result['userLevel']);
			$user->username = $result['username'];
			$user->image = $result['image'];

			$_SESSION['userData'] = $user; */
		}else{
			$sql = "SELECT * FROM users WHERE id='".$_COOKIE['HXCID']."'";

			$conn = mysql_connect($db_config['server'], $db_config['username'], $db_config['password']) or die('Could not connect: ' . mysql_error());
			mysql_select_db($db_config['database']) or die('Could not select database');

			// Performing SQL query
			$result = mysql_query($sql) or die('Query failed: ' . mysql_error());
			//$result = mysql_fetch_row($result);
			$result = mysql_fetch_array($result);

			if($result['userId']){
				$_SESSION['user_id'] = $result['userId'];
				$_SESSION['isLoggedIn'] = true;
				
				$user->id = $result['id'];
				if($result['lastDownloaded'] < (time() - 86400)){
					$user->dailyDownloads = 0;
				}else{
					$user->dailyDownloads = intval($result['dailyDownloads']);
				}
				$user->downloadLimit = $result['userLevel'] === '5' ? null : 3;
				$user->email = $result['email'];
				$user->ip = $_SERVER['REMOTE_ADDR'];
				$user->isLoggedIn = true;
				$user->totalDownloads = intval($result['totalDownloads']);
				$user->userId = $result['userId'];
				$user->userLevel = intval($result['userLevel']);
				$user->username = $result['username'];
				$user->image = $result['image'];
				
				$_SESSION['userData'] = $user;
			}else{
				$user->isLoggedIn = false;
				$user->userLevel = 0;
				$user->dailyDownloads = 0;
				$user->downloadLimit = 1;
				$user->ip = $_SERVER['REMOTE_ADDR'];
			}
		}
		}else{
			if (strpos($_SERVER['REQUEST_URI'],'favorites') !== false) {
				@header('Location: /');
				die;
			}else{
				$user->isLoggedIn = false;
				$user->userLevel = 0;
				$user->dailyDownloads = 0;
				$user->downloadLimit = 1;
				$user->ip = $_SERVER['REMOTE_ADDR'];
			}
		}

	$initialData = json_encode($user);
?>
window.downloadServer = '<?=$website_config['download_server']?>';
window.environment = '<?=$environment?>';
window.preloadData = JSON.parse('<?=$initialData?>');
<? if($user->isLoggedIn){ ?>
<?
	class Favorites {
		public $id;
		public $bitrate;
		public $userId;
		public $songId;
		public $name;
		public $playtime;
	}

	$sql = "SELECT * FROM favorites WHERE userId='".$user->userId."'";

	$conn = mysql_connect($db_config['server'], $db_config['username'], $db_config['password']) or die('Could not connect: ' . mysql_error());
	mysql_select_db($db_config['database']) or die('Could not select database');

	// Performing SQL query
	$result = mysql_query($sql) or die('Query failed: ' . mysql_error());
	//$result = mysql_fetch_row($result);
	//$favorites = mysql_fetch_row($result);
	$favorites = array();
	while($row = mysql_fetch_array($result)) {
		$favs = new Favorites();
		$favs->bitrate = $row['bitrate'];
		$favs->id = $row['id'];
		$favs->name = $row['name'];
		$favs->playtime = $row['playtime'];
		$favs->songId = $row['songId'];
		$favs->userId = $row['userId'];
		array_push($favorites, $favs);
    }
?>
	window.favorites = JSON.parse('<?=json_encode($favorites)?>');
<? } ?>
<? if($user->isLoggedIn){ ?>
<?
	class Playlists {
		public $id;
		public $title;
		public $userId;
	}

	$sql = "SELECT * FROM playlists WHERE userId='".$user->userId."'";

	$conn = mysql_connect($db_config['server'], $db_config['username'], $db_config['password']) or die('Could not connect: ' . mysql_error());
	mysql_select_db($db_config['database']) or die('Could not select database');

	// Performing SQL query
	$result = mysql_query($sql) or die('Query failed: ' . mysql_error());
	//$result = mysql_fetch_row($result);
	//$favorites = mysql_fetch_row($result);
	$playlists = array();
	while($row = mysql_fetch_array($result)) {
		$play = new Playlists();
		$play->id = $row['id'];
		$play->title = $row['title'];
		$play->userId = $row['userId'];
		array_push($playlists, $play);
    }
?>
	window.playlists = JSON.parse('<?=json_encode($playlists)?>');
<? }else{ ?>
	window.playlists = [];
<? } ?>