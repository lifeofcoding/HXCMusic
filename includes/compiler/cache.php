<?php
include('../config.php');
//error_reporting(0);
###############################################
#                                             #
# © 2005 Hans Feijoo - hans111@yahoo.com      #
# Free for you to use and abuse, it'd be nice #
# if you did not remove this tag and mention  #
# me in any derivative work, but you don't    #
# have to.                                    #
#                                             #
# Cacheton - An utility to cache pages that   #
# frequently query a db which is not updated  #
# so frequently                               #
#                                             #
# Usage: You must create a directory to store #
# cached pages, ($cache_dir) with the proper  #
# permisions (777). Name it 'cache'.          #
# Just include this file at the top of your   #
# script. That's it!                          #
# Comments: Default life of cache files is    #
# 300 seconds, you can change this by setting #
# $cache_life before including this script.   #
#                                              #
# $nocache=false;                             #
# $cache_life= 60*60*24*30 //1 month!         #
# include('cacheton.php');                    #
#                                             #
# I'd suggest renaming cacheton.php           #
# Files will be served even if not caching,   #
# so make sure cached files exist, if they    #
# don't, then THIS script is not working.     #
# If you want to know if files are actually   #
# being cached and the time they were           #
# view source, at the end, there should          #
# be a comment with the actual caching time.  #
#                                              #
# If you need to manually clean cache files,  #
# add the argument 'flushcache',               #
# ie: myscript.php?arg1=foo&flushcache        #
# Keep in mind that ALL files in cache dir    #
# will be deleted if parameter olderthan is   #
# not specified. You can call it directly     #
# from this script:                           #
# cacheton.php?flushcache&olderthan=3000      #
# parameter olderthan is self-explanatory     #
# if not specified, all files will be deleted #
# It is safe to use the                          #
# same cache dir and this same script across  #
# different applications. Just remember that  #
# when you clean cache, you clean it for all. #
#                                             #
# This should be obvious but                  #
# DO NOT use this script if request           #
# method is POST.                             #
# I'd love to hear your feedback and          #
# suggestions. If you do not want to use       #
# 'realpath', assign the full path to           #
# $cachedir                                   #
#                                             #
# Enjoy it, your database will thank you. You #
# can reduce server load up to 50%            #
###############################################
# Modified 17.07.2005 
###############################################
# Added autoclean, files older than specified 
# cache life will be flushed at each execution
###############################################
# Added cache comment, adds a comment at the 
# end of the output with the time file was
# cached
###############################################
# Added flag 'nocache' ; let's say your db was
# unavailable for a certain time or you do not
# want to cache 'not found' files, etc, 
# then just put somewhere in your script 
# (Where the error occurs) the flag 
# $nocache = true; 
# when this flag is set, files will not be 
# cached.
###############################################

//ob_start("ob_gzhandler"); //Uncomment this if you want your output to be compressed
//You shouldn't need to change anything, just remember to create cache dir with the appropriate permissions
$cache_dir=realpath('./cache').'/';
$period=isset($cache_life) ? $cache_life : 300; //cache file life in seconds
$thescript = $_SERVER['REQUEST_URI'];
$thedomain = $_SERVER['HTTP_HOST'];
$thecode = $thedomain."".$thescript;

$cache_file=$cache_dir.md5('app.js');
//autoclean($period); //Delete files older than cache life
if (isset($_REQUEST['flushcache'])) {
    if (is_dir($cache_dir)) {
        if ($dh = opendir($cache_dir)) {
            if(!empty($_REQUEST['olderthan']) && is_numeric($_REQUEST['olderthan'])){
                $tcache=floor($_REQUEST['olderthan']);
                while (($file = readdir($dh)) !== false) {
                    if (is_file($cache_dir . $file) && (time()-filemtime($cache_dir.$file))>= $tcache) {
                        unlink($cache_dir . $file);
                    }
                }
            }
            else {//this way it will be faster when deleting all files, does not have to check each file
                while (($file = readdir($dh)) !== false) {
                    if (is_file($cache_dir . $file)) {
                        unlink($cache_dir . $file);
                    }
                }
            }
            closedir($dh);
        }
    }
}
function update($content) {
    global $cache_file,$nocache;
    $content = ob_get_contents();
    if ($nocache !== true) {
        if($handler = @fopen($cache_file,'w')){
            fwrite($handler,$content.'<!--Copyright © HXCMusic Media Network - Generated by HXC Cache System 2.0 at '.date('l jS \of F Y h:i:s A').'-->');
            fclose($handler);
        }
    }

    return $content;
}
if (file_exists($cache_file)){
/*     if((time() - filemtime($cache_file))>= $period){
        ob_start('update');
    } */
	$lastModifiedFile = lastModifiedInFolder('../../js');
	if(filemtime($lastModifiedFile) >= filemtime($cache_file)){
		//die('updating cache file');
        ob_start('update');
    }
    elseif($handler = fopen($cache_file,'r')){
        $content = fread($handler, filesize($cache_file));
        fclose($handler);
        echo $content;
        exit;
    }
}
else{
    ob_start('update');
}
function autoclean($age) {
    global $cache_dir;
    if (is_dir($cache_dir)) {
        if ($dh = opendir($cache_dir)) {
            while (($file = readdir($dh)) !== false) {
                if (is_file($cache_dir . $file) && (time()-filemtime($cache_dir.$file))>= $age) {
                    unlink($cache_dir . $file);
                }
            }
        }
        closedir($dh);
    }
}  

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
?> 