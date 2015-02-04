<?php
include("../includes/session.php");
$siteURL = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/';

$bookmarklet = 'javascript:document.location.href=\''.$siteURL.'?url=\'+escape(document.location.href)';

function getApplet($jarFile, $className, $params = array(), $width=1, $height=1, $name='japplet') {
    $retVal = "";

    $useApplet = 0;
    $user_agent = $_SERVER['HTTP_USER_AGENT'];

    if (stristr($user_agent, "konqueror") || stristr($user_agent, "macintosh") || stristr($user_agent, "opera")) {
        $useApplet = 1;
        $retVal = sprintf('<applet name="%s" id="%s" archive="%s" code="%s" width="%s" height="%s" MAYSCRIPT >', $name, $name, $jarFile, $className, $width, $height);
    } else {
        if (strstr($user_agent, "MSIE")) {
            $retVal = sprintf('<object  name="%s" id="%s" classid="clsid:8AD9C840-044E-11D1-B3E9-00805F499D93" style="border-width:0;" codebase="http://java.sun.com/products/plugin/autodl/jinstall-1_4_1-windows-i586.cab#version=1,4,1"  width= "%s" height= "%s">', $name, $name, $width, $height);
        } else {
            $retVal = sprintf('<object  name="%s" id="%s" type="application/x-java-applet;version=1.4.1" width= "%s" height= "%s">', $name, $name, $width, $height);
        }

        $params['archive'] = $jarFile;
        $params['code'] = $className;
        $params['mayscript'] = 'true';
        $params['scriptable'] = 'true';
        $params['name'] = $name;
    }

    foreach ($params as $var => $val) {
        $retVal .= sprintf('<param name="%s" value="%s">', $var, $val);
    }

    $retVal .= '<br><br>It appears you do not have Java installed or it is disabled on your system.<br /><br />
                    Please download it <a href="http://www.java.com/getjava/" class="link" target="_blank">here</a>';
    if ($useApplet == 1) {
        $retVal .= '</applet>';
    } else {
        $retVal .= '</object>';
    }

    return $retVal;
}

if (isset($_REQUEST['url']) && !empty($_REQUEST['url'])) {
    if (strstr($_REQUEST['url'], 'http')) {
        $url = $_REQUEST['url'];
    } else {
        $error = "Invalid url please enter valid video url.";
    }
}
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="content-type" content="text/html;charset=iso-8859-2" />
        <link rel="stylesheet" href="style.css" type="text/css" />
<link href='http://hxcmusic.com/default.css' rel='stylesheet' type='text/css' media='screen'>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>

        <title>HXC Instant Downloader v4.0</title>
        <meta name="description" content="Download and save videos directly from Youtube, Google, Metacafe and more. Simply copy and paste." />
            <meta name="keywords" content="freevid, keep, vid, keep vid, download, direct, help, rip, copy, save, video, stream, youtube, yuotube, toutube, uoutube, houtube" />
                <meta name="robots" content="noindex,nofollow" />

<style type="text/css">
.little-box{
border:1px solid #333333;
max-width:580px;
background:#CCCCCC url(http://hxcmusic.me/images/overlaybg.png);
padding:3px;
text-align:center;
font-style:italic;
}
</style>

<script type="text/javascript">
if (top.location.href == document.location.href) {
    top.location.href = "<?=$web_path?>main.php";
}
</script>



                        <script type="text/javascript">

                            
                            loaderVisible = true;
                            VFResult = "";
                            VFProxy = "";
                            VFThumbnail = "";

                            function vidfetchSearching()
                            {
                                $('#vidfetchLoader applet').width(0);
                                $('#vidfetchLoader applet').height(0);
                                $('#vidfetchSearching').show();
                            }

                            function vidfetchAppend(type,name,index,title)
                            {

                                if(loaderVisible)
                                {
                                    $('#vidfetchSearching').hide();
                                    $('#vidfetchLinks').show();
                                    loaderVisible = false;
                                }

if(type == 'MP3'){
name = name+' (MP3)';
}
if(type == 'AAC'){
name = name+' (AAC)';
}
if(type == 'MP4'){
name = name+' (MP4)';
}
if(type == '3GP'){
name = name+' (3GP)';
}
if(type == 'FLV'){
name = name+' (FLV)';
}
if(type == 'WEBM'){
name = name+' (WEBM)';
}
                                name = name.replace("Low Quality","Low Quality");
                                name = name.replace("Medium Quality","Medium Quality");
                                name = name.replace("High Quality","High Quality");

                                var proxyLink = VFProxy+index+"/"+title+"."+type.toLowerCase();
                                $('.vthumb').html('<img src="'+VFThumbnail+'" alt="'+title+'" />');
                                $('.vtitle').html(title);
<? if($session->logged_in){ ?>
                                if(type == 'MP3' || type == 'AAC')
                                {
<? if($session->userlevel == '1'){ ?>
                                    $('#vidfetchLinks ul.audio').append('<a href="http://adf.ly/630487/'+proxyLink+'" target="_blank"><span>'+name+'</span></a><br>');
                                    //$('#vidfetchLinks ul.audio').append('<a href="http://www.cpalead.com/spotlightpath/39939/499447/'+proxyLink+'" target="_blank"><span>'+name+'</span></a><br>');

<? }else{ ?>
                                    $('#vidfetchLinks ul.audio').append('<a href="'+proxyLink+'"><span>'+name+'</span></a><br>');
<? } ?>
                                } else {
<? if($session->userlevel == '1'){ ?>

if(name.match("High Quality")){
//dont print
                                    $('#vidfetchLinks ul.video').append('<a href="javascript:window.parent.GetContent(\'premium.php?quality=1\',\'Upgrade\');"><span>'+name+'</span></a><br>');
}else{
                                    $('#vidfetchLinks ul.video').append('<a href="'+proxyLink+'"><span>'+name+'</span></a><br>');
}

                                    //$('#vidfetchLinks ul.video').append('<a href="http://www.cpalead.com/spotlightpath/39939/499447/'+proxyLink+'" target="_blank"><span>'+name+'</span></a><br>');
<? }else{ ?>
                                    $('#vidfetchLinks ul.video').append('<a href="'+proxyLink+'"><span>'+name+'</span></a><br>');
<? } ?>
                                }
<? }else{ ?>
                                if(type == 'MP3' || type == 'AAC')
                                {
//$('#vidfetchLinks ul.audio').append('<a href="http://adf.ly/630487/'+proxyLink+'" target="_blank"><span>'+name+'</span></a><br>');
                                } else {
if(name.match("High Quality")){
//dont print
$('#vidfetchLinks ul.video').append('<a href="<?=$web_path?>promo.php?quality=1" target="_top"><span>'+name+'</span></a><br>');
}else{
$('#vidfetchLinks ul.video').append('<a href="http://adf.ly/630487/'+proxyLink+'" target="_blank"><span>'+name+'</span></a><br>');
//$('#vidfetchLinks ul.video').append('<a href="http://www.cpalead.com/spotlightpath/39939/499447/'+proxyLink+'" target="_blank"><span>'+name+'</span></a><br>');
}
                                }
<? } ?>
                            }

                            function vidfetchDone()
                            {
                                if(!loaderVisible)
                                {
                                    // set thumbnail


                                    return;
                                }

                                $('#vidfetchSearching').hide();
                                $('#vidfetchError').show();
                            }

                            function vidfetchProxy(url)
                            {

                            }

                            function vidfetchError()
                            {
                                $('#vidfetchSearching').hide();
                                $('#vidfetchError').show();
                            }
                        </script>

                        </head>
                        <body>

<?php
if (isset($url)) {
?>
<div id="main" align="center" style="height:400px;">
                                    <div class="gbox" align="left">

                                        <div id="vidfetchLoader">
                                            <center>
<?php
    echo getApplet('http://vidfetch.com/java/VidFetchApplet.signed.jar', 'VidFetchApplet.class', array('url' => $url, 'userAgent' => $_SERVER['HTTP_USER_AGENT']), 1, 1);
?>
                                            </center>
                                        </div>

                                        <div id="vidfetchSearching" >
                                            <center>
                                                <img src="<?php echo $siteURL; ?>images/loader.gif" alt="loading" />
                                                <br />
                                                <span style="color: rgb(204, 51, 51);">To download this file, please click '<b>Run</b>' when prompted.<br> Tick the box '<b>Always trust content from the publisher</b>' to download seamlessly in the future.</span>
                                            </center>

                                            <script type="text/javascript">
                                                jQuery(document).ready(function() {
                                                    setTimeout("vidfetchDone()",60000);
                                                });

                                            </script>
                                        </div>

                                        <div id="vidfetchLinks"   style="display:none;">
                                            <center><strong><i><div class="vtitle"></div></i></strong></center>
<?
$type = $_GET['type'];

if($type){
?>
<? if($type == "audio"){ ?>
<br><i>Please select audio type & quality:</i><br>
<? } ?>
<? }else{ ?>
<br><i>Please select video type & quality:</i><br>
<? } ?>
<?
$type = $_GET['type'];

if($type){
?>
<? if($type == "audio"){ ?>
                                            <ul class="audio"></ul>
<? } ?>
<?
}else{
?>
                                            <ul class="video"></ul>
<? } ?>
                                    </div>


                                        <div id="vidfetchError"  style="display:none;">
                                            <center>
                                                <span style="color: rgb(204, 51, 51);"><b>Error Occurred</b><br />

                                                    <br>Please make shore you selected yes when prompted for '<b>Always trust content from the publisher</b>'.</span>
                                            </center>
                                        </div>



                                    </div>

<?php
} elseif (isset($error) && !empty($error)) {
?>
                                    <center><h3><?php echo $error; ?> </h3></center>
                                    <?php
                                }
                                    ?>
                                    <div class="footer">
<div class="little-box">Note: Do not close this window in till your download is finished.</div>
                                    </div>
</div>


                        </body>
                        </html>