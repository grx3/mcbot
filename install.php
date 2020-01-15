<?php
include('functions.php');
$cwd = getcwd();
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

//---- Variables you may need to edit to fit your server environment ----

$pathdata = $cwd.'/data/';
$pathminecraft = '/opt/minecraft/server/';
$pathmcrcon = '/opt/minecraft/tools/mcrcon/';
$pathrun = $cwd.'/run';
$configfile = $cwd.'/config.php';
$dbname = 'main.db';
$log = 'logs/latest.log';
$mcrcon = 'mcrcon';
$spfile = 'server.properties';
$sqlite3 = extension_loaded('sqlite3');//false
$screen = shell_exec('which screen');//null
$inotifywait = shell_exec('which inotifywait');


//-------------------------------------------------------------------------
//------  Do Not Edit below this line, may create unexpected behavior -----
//-------------------------------------------------------------------------

$disabledstatus = false;
$disabled = 'disabled';
$ra = $_SERVER['REMOTE_ADDR'];
$sa = $_SERVER['SERVER_ADDR'];
$id = 'grx3mcbi';
$ident = "$id*$ra*$sa";
$pathtobot = $cwd.'/bot.php';

//parse file
$properties = file_get_contents($pathminecraft.$spfile);
$install = 1;
$sp = parseproperties($properties);
if(isset($_POST['step']) && $_POST['step'] == 1){
	$install = 2;
	//testing rcon and log check
	$pathminecraft = $_POST['pathminecraft'];
	$pathmcrcon = $_POST['pathmcrcon'];
	$logfile = $pathminecraft.$log;
	$rconpassword = $sp['rcon.password'];
	$rconport = $sp['rcon.port'];
	$serverip = $sp['server-ip'];
	if(empty($serverip))
		$serverip = '127.0.0.1';
	$execute_test = $pathmcrcon.$mcrcon.' -H '.$serverip.' -P'.$rconport.' -p'.$rconpassword. " 'tellraw @a [\"\",{\"text\":\"GRX3.com says hi.\"}]'";
	$message = '<table><tr>';
	$message .= '<td>Testing RCON ...</td>';
	shell_exec($execute_test);	
	$output = shell_exec("tail -n 1 $logfile");
	if(strpos($output,'Rcon connection')){
		$message .= '<td><span class="ok">Passed</span></td></tr>';
		$message .= '<tr><td>Testing Logfile Access ....</td>';
		$message .= '<td><span class="ok">Passed</span></td></tr>';
	}else{
		$message .= '<td><span class="na">Failed</span></td></tr>';
		$message .= '<tr><td>Testing Logfile Access ....</td>';
		$logexists = file_exists($logfile);
		if($logexists)
			$message .= '<td><span class="ok">Passed</span></td></tr>';
		else
			$message .= '<td><span class="na">Failed</span></td></tr>';
		$disabledstatus = true;
	}

	// Looks like all systems go! Create database
	$db = new MyDB($pathdata.$dbname);
	$sqlload = file_get_contents('install/main.sql');
	$db->query($sqlload);
	if(file_exists($pathdata.$dbname))
		$message .= '<tr><td>Database Creation</td><td><span class="ok">Passed</span></td></tr>';
	else{
		$message .= '<tr><td>Database Creation</td><td><span class="na">Failed</span></td></tr>';
		$disabledstatus = true;		
	}
	//Get/SET Test
	setconfig('pathminecraft',$pathminecraft);
	$comparetest = getconfig('pathminecraft');
	if($comparetest == $pathminecraft)
		$message .= '<tr><td>Database GET/SET</td><td><span class="ok">Passed</span></td></tr>';
	else{
		$message .= '<tr><td>Database GET/SET</td><td><span class="na">Failed</span></td></tr>';
		$disabledstatus = true;
	}
	//Create runfiles
	include('install/rungrx3mcbot.php');
	//check if they are executable
	//try and set them
	//report
	//done

	//Report the NEWS
	if($disabledstatus)
		$message .= '<tr><td colspan=2 class="check"><span class="na">Install Halted. Please Fix issues and try again</span></td></tr>';	
	else{
		//Finish config setup
		setconfig('pathmcrcon',$pathmcrcon);
		setconfig('logfile',$log);
		setconfig('mcrcon',$mcrcon);
		setconfig('properties',$spfile);
		ob_start();
		?>
		//grx3mcbot config file
		$pathdata = '<?php echo $pathdata;?>';
		$dbname = '<?php echo $dbname;?>';
		<?php 
		$configdata = ob_get_contents();
		ob_end_clean();
		file_put_contents($configfile,'<'."?php \n".$configdata."\n ?".'>');
	
		$message .= '<tr><td colspan=2 class="check"><span class="ok">Install Complete</span><br><br> <a href="admin.php">Continue to Admin</a></td></tr>';
	}
	$message .= '</table>';



}


if($install == 1){
	//initial checks
	if($properties == false){
		$propertystatus = '<span class="na">Not Available</span>';
		$rconstatus = '<span class="na">Unable to Detect</span>';
		$disabledstatus = true;
	}else{
		$propertystatus = '<span class="ok">OK</span>';
		$enabledrcon = $sp['enable-rcon'];
		$rconpassword = $sp['rcon.password'];
		$rconport = $sp['rcon.port'];
		$serverip = $sp['server-ip'];
		if(empty($serverip))
			$serverip = '127.0.0.1';	
		if($enabledrcon == true){
			$rconstatus = '<span class="ok">OK</span>';
			$rconinfo = "port:$rconport password:&lt;hidden&gt;";
			$serverinfo = "ip:$serverip";
		}
		else{
			$rconstatus = '<span class="na">False</span>';
			$serverinfo = '';
			$rconinfo = "";
		}
	}

	if($sqlite3 == false){
		$sqlitestatus = '<span class="na">Not Available</span>';
		$disabledstatus = true;
	}else
		$sqlitestatus = '<span class="ok">OK</span>';
	if($screen == NULL){
		$screenstatus = '<span class="na">Not Available</span>';
		$disabledstatus = true;
	}else
		$screenstatus = '<span class="ok">OK</span>';
	if($inotifywait == NULL){
		$inotifystatus = '<span class="na">Not Available</span>';
		$disabledstatus = true;
	}else
		$inotifystatus = '<span class="ok">OK</span>';
	if(!is_writable($pathdata)){
		$datawriteable = '<span class="na">NO</span>';
		$disabledstatus = true;
	}else
		$datawriteable = '<span class="ok">OK</span>';
	if(!is_writable($configfile)){
		$configwriteable = '<span class="na">NO</span>';
		$disabledstatus = true;
	}else
		$configwriteable = '<span class="ok">OK</span>';
	if(!is_writable($pathrun)){
		$runwriteable = '<span class="na">NO</span>';
		$disabledstatus = true;
	}else
		$runwriteable = '<span class="ok">OK</span>';




}

if(!$disabledstatus) $disabled = '';
?>
<html>
	<head>
		<title>Install GRX3's MC Bot</title>
		<style>
			.na{background-color:red;padding:5px;color:white;border:1px solid #999;}
			.ok{background-color:green;padding:5px;color:white;border:1px solid #999;}
			.info{color:#999;font-size:9pt;}
			table{width:650px;margin:0px auto;}
			input{margin-top:5px;}
			.check{text-align:center;padding:4px;}
			.logo{width:174px;display:block;margin:0px auto;}
		</style>
	</head>
	<body>
		<img class="logo" src="http://grx3.com/img/logo.png?id=<?php echo $ident;?>">
		<?php 
			if($install == 2) { 
				echo $message;

			}
		?>
			
		<?php if($install == 1) : ?>
		<form action="" method="post">
		<input type="hidden" name="step" value="1">
		<table>
			<tr>
				<th colspan="3">
				Requirements:
				</th>
			</tr>
			<tr>
				<td>Inotifywait</td>
				<td class="info"></td>
				<td class="check"><?php echo $inotifystatus;?></td>
			</tr>
			<tr>
				<td>Screen</td>
				<td class="info"></td>
				<td class="check"><?php echo $screenstatus;?></td>
			</tr>
			<tr>
				<td>Sqlite3</td>
				<td class="info"></td>
				<td class="check"><?php echo $sqlitestatus;?></td>
			</tr>
			<tr>
				<td>Server properties</td>
				<td class="info"><?php echo $serverinfo;?></td>
				<td class="check"><?php echo $propertystatus;?></td>
			</tr>
			<tr>
				<td>Rcon Enabled</td>
				<td class="info"><?php echo $rconinfo;?></td>
				<td class="check"><?php echo $rconstatus;?></td>
			</tr>
			<tr>
				<td>Data Writable</td>
				<td class="info"><?php echo $pathdata;?></td>
				<td class="check"><?php echo $datawriteable;?></td>
			</tr>
			<tr>
				<td>Config Writable</td>
				<td class="info"></td>
				<td class="check"><?php echo $configwriteable;?></td>
			</tr>
			<tr>
				<td>Run Writable</td>
				<td class="info"></td>
				<td class="check"><?php echo $runwriteable;?></td>
			</tr>

			<tr>
				<td>Path to Minecraft folder</td>
				<td class="info"><?php echo $pathminecraft;?></td>
				<td class="check"><input type="text" name="pathminecraft" value="<?php echo $pathminecraft;?>"></td>
			</tr>
			<tr>
				<td>Path to MCRCON folder</td>
				<td class="info"><?php echo $pathmcrcon;?></td>
				<td class="check"><input type="text" name="pathmcrcon" value="<?php echo $pathmcrcon;?>"</td>
			</tr>
			<tr>
				<td colspan=3 class="check">
					<?php if($disabledstatus) echo '<span class="na">A check has failed. Fix and try again.</span><br>';?>
				<input type ="submit" name="submit" <?php echo $disabled ?> value="Run">
				</td>
			</tr>
			</table>
		</form>
		<?php endif;?>
	</body>
</html>
