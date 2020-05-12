<?php
include_once('init.inc.php');
require(WEB_ROOT."/include/game/yqdx.class.php");
require(WEB_ROOT."/include/json.class.php");
$json = new JSON();
if($act=="getorderjson"){
	
	$_server=$_REQUEST["_server"];
	$sqlstr="select * from ".DB_PREFIX."server where server_id = '$_server' limit 0,1";
	$rs_servers = $db->getRow($sqlstr);
	$yqdx = new Game_yqdx();
	$html = $yqdx->get_top_url($rs_servers);
	
	exit($html);
}
?>