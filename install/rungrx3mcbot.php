<?php

$botrunscriptname = 'grx3mcbot.sh';
$screenbotname = 'grx3mcbot';
$pathtorunscript = $pathrun.'/'.$botrunscriptname;

ob_start();
?>
#!/bin/bash
# grx3mc runbot script (this is the main execution file without it nothing will work)
while $(inotifywait -q -r <?php echo $logfile;?> -e modify >/dev/null); do { /usr/bin/php <?php echo $pathtobot;?>; }; done

<?php
$rundata = ob_get_contents();
ob_end_clean();

ob_start();
?>
#!/bin/bash
# grx3mc cronable checker script (this makes the runbot script a daemon-like bot)
if pgrep -x "<?php echo $botrunscriptname;?>" > /dev/null
then
       	echo "Running"
else
	if ! screen -list | grep "<?php echo $screenbotname;?>" 
	then
		screen -d -m -S  <?php echo $screenbotname;?> bash -c <?php echo $pathtorunscript."\n";?>
	else
		screen -r <?php echo $screenbotname;?> bash -c <?php echo $pathtorunscript."\n";?>
	fi	
fi
<?php
$watcherdata = ob_get_contents();
ob_end_clean();

file_put_contents($pathrun.'/'.$botrunscriptname,$rundata);
file_put_contents($pathrun.'/watcher.sh',$watcherdata);


?>

