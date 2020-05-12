<?php
include_once('init.inc.php');
if(empty($act)){
$info_gid=$game_id;
//游戏资料
$sqlstr="select cate_id,info_id,subtitle,add_time,attachment from ".DB_PREFIX."info where info_start<=".time()." and cate_id = {$ids[gamedata]} and info_gid={$info_gid} order by sort_num desc,add_time desc limit 0,20";
$rs_gamedata = $db->getAll($sqlstr);
//新闻
$sqlstr="select cate_id,info_id,subtitle,add_time,attachment from ".DB_PREFIX."info where info_start<=".time()." and cate_id = {$ids[gn_xw]} and info_gid={$info_gid} order by sort_num desc,add_time desc limit 0,7";
$rs_news = $db->getAll($sqlstr);

include_once('templates/gamedata.html');
}
?>