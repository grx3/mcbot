<?php 
ini_set('display_errors',1);
include('config.php');
include('functions.php');
include($pathdata.'color.php');
$db = new MyDB($pathdata.$dbname); 
$cmdchar = getconfig('command_char');
$botname = getconfig('botname');
$defaultcolor = getconfig('default_color');
$pathminecraft  = getconfig('pathminecraft');
$pathmcrcon = getconfig('pathmcrcon');
$mcrcon = getconfig('mcrcon');
$logfile = getconfig('logfile');
$serverlog = $pathminecraft.$logfile;
$uuid = '';
$username = '';

//This is it where it all starts
$lastline = shell_exec("tail -n 1 $serverlog");

//parse out time,type 
$parts = explode(' ',$lastline);
$linenotime = substr($lastline,strpos($lastline,' ')+1);
$logtime = $parts[0];
$entrytype = substr($linenotime,strpos($linenotime,'[')+1,strpos($linenotime,']')-1);

// if login get uuid and username
if(strpos($entrytype,'Authenticator')){
	$uuid = substr($linenotime,strpos($linenotime,'is')+2);
	$start = strpos($linenotime,'player')+6;
	$end = strpos($linenotime,'is')-2;
	$length = ($end - $start)+1;
	$username = substr($linenotime,$start,$length);
}

echo 'entrytype:'.$entrytype."\n";
echo 'uuid:'.$uuid."\n";
echo 'username:'.$username."\n";
?>
