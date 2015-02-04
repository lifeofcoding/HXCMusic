<?php
if(isset($_GET['debug'])){
	error_reporting(E_ALL);
}else{
	error_reporting(0);
}
include('includes/config.php');
include('includes/crawler_detect.php');
include('includes/bootstrap.php');
//require_once(__DIR__ . '/php-console/src/PhpConsole/__autoload.php');
?>
<!DOCTYPE html>
<html lang="en">
  <head>
  	<meta charset="UTF-8">
	<title><?=$website_config['title']?></title>
	  <script src="/socket.io/socket.io.js"></script>
	  <link rel="stylesheet" type="text/css" href="/css/app.css?cache=<?php echo $cacheNumber; ?>">
	  <script type="text/javascript" src="/libs/favicon.js"></script>
	  <script type="text/javascript" src="http://www.google.com/recaptcha/api/js/recaptcha_ajax.js"></script>
	  <script>
		  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
		  ga('create', 'UA-28385766-1', 'auto');
		  ga('send', 'pageview');
		  
/* 		   /* Error Tracking via Google Analytics */
		  var _gaq = _gaq || [];
		  _gaq.push(['_setAccount', 'UA-28385766-1']);
		  _gaq.push(['_trackPageview']);
		  (function() {
			  var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			  ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			  var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		  })();
		  
		  (function() {
			  var po = document.createElement('script');
			  po.type = 'text/javascript';
			  po.async = true;
			  po.src = 'https://apis.google.com/js/client.js?onload=onLoadCallback';
			  var s = document.getElementsByTagName('script')[0];
			  s.parentNode.insertBefore(po, s);
		  })();
		  
		  (function(){
			  var uv=document.createElement('script');
			  uv.type='text/javascript';
			  uv.async=true;
			  uv.src='//widget.uservoice.com/h3HKPplDESBLBbRD3I7mKA.js';
			  var s=document.getElementsByTagName('script')[0];
			  s.parentNode.insertBefore(uv,s)})()
		  UserVoice = window.UserVoice || [];
	  </script>
	  <meta name="description" content="<?=$website_config['description']?>">
	  <meta name="keywords" content="<?=$website_config['keywords']?>">
	  <link rel="shortcut icon" type="image/png" href="/favicon.png">
	  <link rel="icon" href="/favicon.png" type="image/png">
	  <script type="text/javascript" src="/js/preloadData.js"></script>
  </head>
  <body>
	  <div id="spinner" style="display:none;">
		<div class="loading-container">
		<div class="ball"></div>
		<div class="ball1"></div>
		</div>
	  </div>
	  <div id="corner-loader" style="display: none;position: absolute;z-index: 999999999999;right: 10px;bottom: 60px;"><img src="/loading-spin.svg" style="width: 48px;"></div>
	  <div id="fullpage-loading">
		  <img src="/loading-bars.svg">
	  </div>
	  
	  <div id="wrapper"></div>
	  
	  <div class="lockscreen-bg" style="display:none;"></div>
	  
  	<!-- 3rd party Dependencies -->
	  <script src="/libs/jquery.js"></script>
	  <script src="/libs/moment.js"></script>
	  <script src="/libs/underscore.js"></script>
	  <script src="/libs/backbone.js?cache=<?php echo $cacheNumber; ?>"></script>
	  <script src="/libs/backbone.marionette.js"></script>
	  
	  <!-- Backbone.Schema | https://github.com/DreamTheater/Backbone.Schema -->
	  <script src="/libs/backbone.schema.js"></script>

	  <script src="/libs/backbone.localStorage.js"></script>
	  
	  <script src="/libs/backbone.stickit.js"></script>
	  
	  <script src="/libs/jquery.transitionEvents.js"></script>
	  
	  <script src="/libs/jquery.row-grid.js"></script>
	  
	  <script src="/libs/jquery.cookie.js"></script>
	  <script type="text/javascript" src="/libs/jgrowl/jquery.jgrowl.js"></script>
	  <script src="/libs/animo.js"></script>
	  <script src="/libs/mediaelement/mediaelement-and-player.js"></script>
	  <script type="text/javascript" src="/libs/poshytip/src/jquery.poshytip.js"></script>
	  <script type="text/javascript" src="/libs/jquery.backstretch.js"></script>
	  <script src="/libs/messi.js"></script>
	  
	  <!-- Chitika Ads -->
	  <!--<script type="text/javascript" src="//cdn.chitika.net/getads.js" async></script>-->

	  <? loadTemplates(); ?>
	  
	  <? if(isset($_GET['debug']) || strpos($_SERVER['HTTP_HOST'],'development') !== false){ ?>
	  	<script src="/js/app.js?cache=<?php echo $cacheNumber; ?>"></script>
	  	<? include('debugger.php'); ?>
	  	<script src="/js/router.js?cache=<?php echo $cacheNumber; ?>"></script>
	  	<script src="/bootstrap.js?cache=<?php echo $cacheNumber; ?>"></script>
	  <? }else{ ?>
	  	<script src="/js/application.js?cache=<?php echo $cacheNumber; ?>"></script>
	  <? } ?>

	  <div id="modal-wrapper"></div>
	  <script>App.clientIP = '<?=$_SERVER['REMOTE_ADDR']?>';</script>
	  <?
		$current_addr =  $_SERVER['HTTP_HOST'];
		$current_addr = "http://" .$current_addr. "" .$_SERVER['REQUEST_URI'];
	  ?>
	  <!-- <?=$current_addr?> -->
  </body>
</html>