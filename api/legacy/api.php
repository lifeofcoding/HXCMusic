<?php
error_reporting(E_ERROR | E_PARSE);

///fix cloudflare IP problem
	if ($_SERVER["HTTP_CF_CONNECTING_IP"]) {
		$_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"] ? $_SERVER["HTTP_CF_CONNECTING_IP"] : $_SERVER["REMOTE_ADDR"];
	}

$current_addr =  $_SERVER['HTTP_HOST'];
$current_addr = "http://" .$current_addr. "" .$_SERVER['REQUEST_URI'];

require 'Slim/Slim.php';
\Slim\Slim::registerAutoloader();

include_once("/var/zpanel/hostdata/zadmin/public_html/crypt.php");
$secretPass = 'lol#hxc*)&$ID';

function extstr3($content, $start, $end){
	if ($content && $start && $end) {
		$r = explode($start, $content);
		if (isset($r[1])) {
			$r = explode($end, $r[1]);
			return $r[0];
		}
		return '';
	}
}

function getConnection() {
	try {
		$db_username = "hxcmusic";
		$db_password = "7y5a2ujat";
		$conn = new PDO('mysql:host=localhost;dbname=zadmin_hxcmusic', $db_username, $db_password);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	} catch(PDOException $e) {
		echo 'ERROR: ' . $e->getMessage();
	}
	return $conn;
}

function getUsers() {
    $sql_query = "select `name`,`email`,`date`,`ip` FROM restAPI ORDER BY name";
    try {
        $dbCon = getConnection();
        $stmt   = $dbCon->query($sql_query);
        $users  = $stmt->fetchAll(PDO::FETCH_OBJ);
        $dbCon = null;
        echo '{"users": ' . json_encode($users) . '}';
    }
    catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }    
}

function sessionDetails(){
	require_once('session.php');
}

function getMusic(){
	$sort_by = 'default';
	require_once('music.php');
}

function getGenre($genre){
	$sort_by = $genre;
	require_once('music.php');
}

function getBio($artist){
	require_once('bio.php');
}

function getAlbumCover($search){
	require_once('album-cover.php');
}

function getAlbumInfo($search){
	require_once('album-info.php');
}

function favorites(){
	require_once('favorites.php');
}

function getFavorites(){
	require_once('getfavs.php');
}

function login(){
	require_once('login.php');
}

function logout(){
	session_start();
 	session_destroy();
	echo '{"success": true}';
}

function checkForUpdate(){
	require_once('updater.php');
}

function radio(){
	require_once('radio.php');
}

function signUp(){
	require_once('signup.php');
}

function updateUserData(){
	require_once('update-user.php');
}

function getEvents($zipCode, $miles, $country, $page){
	require_once('events.php');
}

function upgradeUser(){
	require_once('upgrade.php');
}

//$app = new \Slim\Slim();
$app = new \Slim\Slim(array(
    'debug' => true
));
$app->get('/session.json', 'sessionDetails'); // Using Get HTTP Method and process getUsers function
$app->get('/music.json', 'getMusic'); // Using Get HTTP Method and process getUsers function
$app->get('/:artist/bio.json', 'getBio'); // Using Get HTTP Method and process getUsers function
$app->get('/albumcover/:search.png', 'getAlbumCover'); // Using Get HTTP Method and process getUsers function
$app->post('/favorites.json', 'favorites');
$app->get('/favorites.json', 'getFavorites');
$app->post('/login', 'login');
$app->get('/logout', 'logout');
$app->get('/update.json', 'checkForUpdate');
$app->get('/radio.json', 'radio');
$app->post('/signup', 'signUp');
$app->get('/albuminfo/:search.json', 'getAlbumInfo');
$app->put('/session.json', 'updateUserData');
$app->get('/:zipCode/:miles/:country/:page/events.json', 'getEvents');
$app->get('/upgrade', 'upgradeUser');
$app->get('/:genre/music.json', 'getGenre');
$app->run();
?>