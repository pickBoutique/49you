<?php
include_once('init_member.inc.php');
header("Content-type:text/xml");
$gamelist = $db->getAll("select game_id,game_name,game_rate,game_currency from ".DB_PREFIX."game where game_status=1 order by game_recom desc");
$serverlist = $db->getAll("select server_id,server_gid,server_name from ".DB_PREFIX."server");
include_once('templates/pay_junnet_xml.html');
?>