<?php
	$cc = '§';
	$colors['dark_red'] = array('mc'=>4,'hex'=>'aa0000');
	$colors['red'] = array('mc'=>'c','hex'=>'ff5555');
	$colors['gold'] = array('mc'=>6,'hex'=>'ffaa00');
	$colors['yellow'] = array('mc'=>'e','hex'=>'ffff55');
	$colors['dark_green'] = array('mc'=>'2','hex'=>'00aa00');
	$colors['green'] = array('mc'=>'a','hex'=>'55ff55');
	$colors['aqua'] = array('mc'=>'b','hex'=>'55ffff');
	$colors['dark_aqua'] = array('mc'=>3,'hex'=>'00aaaa');
	$colors['dark_blue'] = array('mc'=>1,'hex'=>'0000aa');
	$colors['blue'] = array('mc'=>9,'hex'=>'5555ff');
	$colors['light_purple'] = array('mc'=>'d','hex'=>'ff55ff');
	$colors['dark_purple'] = array('mc'=>5,'hex'=>'aa00aa');
	$colors['white'] = array('mc'=>'f','hex'=>'ffffff');
	$colors['gray'] = array('mc'=>7,'hex'=>'aaaaaa');
	$colors['dark_gray'] = array('mc'=>8,'hex'=>'555555');
	$colors['black'] = array('mc'=>0,'hex'=>'000000');


function coloroptions($defaultcolor){
	global $colors;
	$opt = '';
	foreach($colors as $k=>$v){
		$color = $v['hex'];
		$value = $v['mc'];
		if($defaultcolor == $value)
			$opt .= "<option style=\"color:$color\" selected value=\"$value\">$k</option>";
		else
			$opt .= "<option style=\"color:$color\" value=\"$value\">$k</option>";
	}
	return $opt;
}

?>
