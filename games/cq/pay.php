<?php
include_once('init.inc.php');

//http://cq.49you.com/pay.php?qid=116&server_id=21001
if(!empty($_REQUEST['server_id'])){
	$sid = add_slashes($_REQUEST['server_id']);
	$row = $db->getRow("select * from ".DB_PREFIX."server where server_gid=3 and server_sid='$sid' ");
	if(!empty($row)){
		redir(YOU_ROOT . "/pay.html?gid=3&sid={$row[server_id]}",true);
	}
	
}
?>