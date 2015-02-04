<?php
	error_reporting(E_ALL); 
	ini_set( 'display_errors','1');

	if (strpos(getcwd(),'development') !== false) {
		if(isset($_POST['push'])){
			$push = true;
			include('../merge.php');
			die($output);
		}else if(isset($_POST['sync'])){
			$mover = file_get_contents('http://hxcmusic.com/mover.io/run.php');
			//$mover = 'success';
			class Sync{
				public $status;
			}
			$Sync = new Sync();

			if($mover === 'success'){
				$Sync->status = 'success';
				$output = json_encode($Sync);
				
				$push = true;
				include('../merge.php');
				die($output);
			}else{
				$Sync->status = 'failed';
				$output = json_encode($Sync);
			}
			die($output);
		}else{
			$push = false;
			include('../merge.php');
		}
?>
<html>
	<head>
		<title>Merge to Production</title>
		<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Ubuntu|Raleway" />
		<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
		<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
		<link rel="stylesheet" href="http://hxcmusic.com/css/custom-animations.css">
		<style type="text/css">
			.diff{
				//border: 1px solid #000;
				border-radius: 5px;
				background: #2B2B2B;
				padding: 10px;
				font-family: Raleway;
				//width: 60%;
				//max-width: 90% !important;
				border-collapse: inherit !important;
				color: #A8A8A8;
			}
			.diff td{
				padding:0 0.667em;
				vertical-align:top;
				white-space:pre;
				white-space:pre-wrap;
				font-family:Consolas,'Courier New',Courier,monospace;
				font-size:0.75em;
				line-height:1.333;
			}

			.diff span{
				display:block;
				min-height:1.333em;
				margin-top:-1px;
				padding:0 3px;
				font-family: Ubuntu;
			}

			* html .diff span{
				height:1.333em;
			}

			.diff span:first-child{
				margin-top:0;
			}

			.diffDeleted span{
				border: 1px solid rgb(255,192,192);
				background: rgb(238, 101, 101);
				color: #000;
				font-weight: 600;
				font-family: Ubuntu;
			}

			.diffInserted span{
				border: 1px solid rgb(192,255,192);
				background: rgb(120, 195, 120);
				color: #000;
				font-weight: 600;
				font-family: Ubuntu;
			}

			#toStringOutput{
				margin:0 2em 2em;
			}
			.changes{
				max-height:250px;
				overflow-y:auto;
				//display: none;
				
			}
			body{
				font-family: Ubuntu;
				padding: 10px;
			}
			.panel{
				margin-top: 10px;
				width: 78.5%;
				//display: table-caption;
				display: flex;
			}
			.panel-heading{
				border-bottom: none;
			}
			.left-column{
				width: 30%;
				min-width:30%;
				background: #F7F7F7;
			}
			.table{
				font-size:13px;
				height:100%;
				border: 1px solid #EEE;
				border-radius: 10px;
				border-collapse: inherit;
			}
			.table td{
				border-top: 0px !important;
				border-radius:10px;
				border-top-right-radius:0px;
				border-bottom-right-radius:0px;
			}
			.table tr{
				height: 250px;
			}
			.right-column{
				height: 200px;
			}
			.spinner{
				-webkit-animation: 1s linear infinite;
				-webkit-animation-name: spin;
			}
    </style>
	</head>
	<body>
		
		<? //if($filesCount > 0){ ?><b style="font-size:18px;">Total updated files: <?=$filesCount?></b><? //} ?>
		<? //if($filesCount > 0){ ?>
		<div class="btn-group pull-right">
			<button type="button" class="btn btn-primary btn-sm sync">
				<span class="glyphicon glyphicon-cloud-upload"></span> Sync Backup & Push
			</button>
			<button type="button" class="btn btn-primary btn-sm push">
				<span class="glyphicon glyphicon-new-window"></span> Push To Production
			</button>
		</div><br>
		<? //} ?>
		<span class="output" style="font-size:13px;">
			<table class="table">
					<?=$output?>
			</table>
		</span>
		
		<script>
			window.viewingChanges = false;
			window.idleCount = false;
			var refreshPage = function(){
				setTimeout(function(){
					if(!window.viewingChanges){ //do not refresh if in the middle of viewing changes
						document.location.reload();
					}else{
						refreshPage();
					}
				}, 5000);
			};
			refreshPage();
			$('.changes').on('scroll', function(){
				/* if no scrolling after 10 sec enable refresh again */
				window.viewingChanges = true;
				
				if(window.idleCount){
					clearTimeout(window.idleCount);
					window.idleCount = false;
				}
				window.idleCount = setTimeout(function(){
					window.viewingChanges = false;
				}, 10000);
			});
			$('body').on('click', '.show-diff, .push, .sync', function(e){
				e.preventDefault();
				if($(e.target).hasClass('push')){
					$.ajax({
						method: 'POST',
						url: document.location.href,
						data: {push:true}
					}).done(function(data) {
						data = JSON.parse(data);
						if(data.status === 'success'){
							document.location.reload();
						}
					})
					.fail(function(error) {
						alert('Failed with error: ' + error);
					});
				}

				if($(e.target).hasClass('sync')){
					$.ajax({
						method: 'POST',
						url: document.location.href,
						data: {sync:true},
						beforeSend: function( xhr ) {
							$(e.target).find('span').removeClass('glyphicon-cloud-upload');
							$(e.target).find('span').addClass('glyphicon-refresh spinner');
							
						}
					}).done(function(data) {
						data = JSON.parse(data);
						if(data.status !== 'success'){
							alert('Mover.io sync failed with message: ' + data.status);
						}
						$(e.target).find('span').removeClass('glyphicon-refresh spinner');
						$(e.target).find('span').addClass('glyphicon-ok');
						setTimeout(function(){
							$(e.target).find('span').fadeOut('normal', function(){
								$(this).removeClass('glyphicon-ok');
								$(e.target).find('span').addClass('glyphicon-cloud-upload').fadeIn();
								setTimeout(function(){
									document.location.reload();
								}, 3000);
							});
						}, 1500);
					})
					.fail(function(error) {
						alert('Failed with error: ' + error);
					});
				}
				
				if($(e.target).hasClass('show-diff')){
					if($(this).data('visible')){
						$(this).data('visible', false).siblings('div').slideUp();
					}else{
						$(this).data('visible', true).siblings('div').slideDown();
					}
				}
			});
		</script>
	</body>
</html>
<? }else{ ?>
<html>
	<head>
		<title>Merge to Production</title>
		<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Ubuntu|Raleway" />
	</head>
	<body style="font-family:Ubuntu;">
		<b style="font-size:18px;">This file can only be ran via developmental environment!</b>
	</body>
</html>
<?
	}
?>