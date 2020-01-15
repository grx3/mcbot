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
$tabs = array('Setup','Config','Join','Commands','Store','Voting','Users','Stats');
$currenttab = 0;	
$tabbar = '';
$message = '';

//Handle Post
if(isset($_POST['action'])){
	//var_dump($_POST);
	//escape posts for database insertion
	foreach($_POST as $k=>$v)
		$_POST[$k] = $db->escapeString($v);

	//setup post handling
	if($_POST['action'] == 'setup'){
		setconfig('command_char',$_POST['command_char']);
		setconfig('botname',$_POST['botname']);
		setconfig('default_color',$_POST['default_color']);
		$cmdchar = $_POST['command_char'];
		$botname = $_POST['botname'];
		$defaultcolor = $_POST['default_color'];
		$message = 'Updated Setup';
	}
	//config post handling
	if($_POST['action'] == 'config'){
		setconfig('pathminecraft',$_POST['pathminecraft']);
		setconfig('pathmcrcon',$_POST['pathmcrcon']);
		setconfig('mcrcon',$_POST['mcrcon']);
		setconfig('logfile',$_POST['logfile']);
		$pathminecraft = $_POST['pathminecraft'];
		$pathmcrcon = $_POST['pathmcrcon'];
		$mcrcon = $_POST['mcrcon'];
		$logfile = $_POST['logfile'];
		$message = 'Updated Config';	
	}

}

// Tab Switching
if(isset($_GET['tab']) && is_numeric($_GET['tab']))
	$currenttab = $_GET['tab'];
if(isset($_POST['tab']) && is_numeric($_POST['tab']))
	$currenttab = $_POST['tab'];

// Setup
// Any of these null must mean it's new or something went wrong
if($cmdchar == NULL || $botname == NULL || $defaultcolor == NULL){
	$currenttab = 0;//Setup
}	

//build tabs
foreach($tabs as $k=>$v){
	if($k == $currenttab)
		$tabbar .= "<li class=\"tab current\"><a href=\"?tab=$k\">$v</a></li>";
	else
		$tabbar .= "<li class=\"tab\"><a href=\"?tab=$k\">$v</a></li>";

}

?>
<html>
	<head>
		<title>GRX3 MCBot admin</title>
		<link href="styles.css" rel="stylesheet">
	</head>
	<body>
		<div class="container">
			<div class="tabbar">
				<ul>
				<?php echo $tabbar;?>
				</ul>
			</div>
			<div class="page">
				<div class="message">
					<?php if(!empty($message)) echo $message;?>
				</div>
			<?php if($currenttab == 0) : ?>
				<h3>Setup</h3>
				<form action="" method="post">
					<input type="hidden" name="action" value="setup">
					<input type="hidden" name="tab" value="0">
					<label>Command Character <span class="description">This is what users will type first to start a command ex. !help</span> </label>
					<input type="text" name="command_char" placeholder="!" value="<?php echo $cmdchar;?>">
					<label>BotName <span class="description">This is the name tag that will be associated with commands</span></label>
					<input type="text" name="botname" placeholder="grx3mcbot" value="<?php echo $botname;?>">
					<label>Default Text Color <span class="description">This is the default text color commands will use</span>
					<select name="default_color">
						<option></option>
						<?php echo coloroptions($defaultcolor);?>
					</select>
				<input type="submit" name="submit" value="Update Setup">
				</form>
			<?php endif; ?>

			<?php if($currenttab == 1) : ?>
				<h3>Config</h3>
				<form action="" method="post">
					<input type="hidden" name="action" value="config">
					<input type="hidden" name="tab" value="1">
					<label>Path To Minecraft <span class="description">Where your .jar file is? [Absolute Path]</span> </label>
					<input type="text" name="pathminecraft" value="<?php echo $pathminecraft;?>">
					<label>Path to McRcon<span class="description">Where the executeable binary is [Absolute Path]</span></label>
					<input type="text" name="pathmcrcon" value="<?php echo $pathmcrcon;?>">
					<label>McRcon Binary Name <span class="description">This should <u>not</u> need changing</span>
					<input type="text" name="mcrcon" value="<?php echo $mcrcon;?>">
					<label>logfile <span class="description">This should <u>not</u> need changing[Relative Path]</span>
					<input type="text" name="logfile" value="<?php echo $logfile;?>">

				<input type="submit" name="submit" value="Update Config">
				</form>
			<?php endif;?>
			</div>
		
		</div>
	</body>
</html>
