<?php

include_once('init.inc.php');
$cardlist = $db->getAll("select * from " .DB_PREFIX."cardtype where cardtype_gid='$game_id' order by cardtype_id desc");
include_once('templates/cardlist.html');
?>