<?php

include_once('init_member.inc.php');
$game_id = intval($_REQUEST['gid']);
$list = $db->getAll("select * from " .DB_PREFIX."cardtype left join ".DB_PREFIX."server on cardtype_sid=server_id where cardtype_gid='$game_id' order by cardtype_sid desc");

include_once('templates/package_list.html');

?>