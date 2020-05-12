<?php
include_once('init_member.inc.php');
if($login_status != SUC_LOGIN){
	$list = $db->getAll("select * from ".DB_PREFIX."game where game_package='1' order by game_recom desc");
}else{
	$strsql="select a.* 
from online_game a 
left join 
(select mg_game_id
,max(mg_last_time) mg_last_time
from online_mygame
where mg_mid='".$login_info[2]."'
group by mg_game_id) b on a.game_id=b.mg_game_id
where game_package='1' 
order by mg_last_time desc
,game_recom desc";
	$list = $db->getAll($strsql);
}

include_once('templates/package.html');
?>