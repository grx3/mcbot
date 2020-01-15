<?php
function parseproperties($data){
	//takes server properties and creates array
	$properties = explode("\n",$data);
	foreach($properties as $k=>$v){
		$key = substr($v,0,strpos($v,'='));
		$value = substr($v,strpos($v,'=')+1);
		$serverproperties[$key] = $value;
	}
	return $serverproperties;
}
function getconfig($name){
	global $db;
	$ret = $db->query("SELECT value FROM config WHERE name='$name' LIMIT 1");
	if($ret->numColumns() > 0){
		$row = $ret->fetchArray(SQLITE3_ASSOC);
		return $row['value'];
	}else
		return '';
}
function setconfig($name,$value){
	global $db;
	$config = getconfig($name);
	if($config == ''){
		$db->query("INSERT INTO config(name,value) VALUES ('$name','$value')");
		return $db->lastInsertRowID();	
	}else{
		$db->query("UPDATE config SET value = '$value' WHERE name = '$name'");
		return true;	
	}
}
class MyDB extends SQLite3 {
	function __construct($dbname) {
		$this->open($dbname);
	}
	
}
?>
