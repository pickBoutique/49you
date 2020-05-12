<?php
include_once('init.inc.php');
if(empty($act)){
$info_gid=$game_id;
//游戏资讯

$sqlstr="select  (case cate_id when {$ids[gn_xw]} then 1 when {$ids[gn_hd]} then 2 end) as cate_id,info_id,subtitle,add_time,attachment from (select cate_id,info_id,subtitle,add_time,attachment from ".DB_PREFIX."info where info_start<=".time()." and (cate_id = {$ids[gn_xw]} or  cate_id={$ids[gn_hd]}) and info_ishot=1 and info_gid={$info_gid} order by sort_num desc,add_time desc limit 0,7) a
		union all (select cate_id,info_id,subtitle,add_time,attachment from ".DB_PREFIX."info where info_start<=".time()." and cate_id = {$ids[gn_xw]} and info_gid={$info_gid} order by sort_num desc,add_time desc limit 0,7)
		union all (select cate_id,info_id,subtitle,add_time,attachment from ".DB_PREFIX."info where info_start<=".time()." and cate_id = {$ids[gn_hd]} and info_gid={$info_gid} order by sort_num desc,add_time desc limit 0,7)";
$rs_news = $db->getAll($sqlstr);

//游戏攻略
$sqlstr="select cate_id,info_id,subtitle,add_time,attachment from ".DB_PREFIX."info where info_start<=".time()." and cate_id = {$ids[gamegl]} and info_gid={$info_gid} order by sort_num desc,add_time desc limit 0,7";
$rs_gamegs = $db->getAll($sqlstr);

//日常活动
$sqlstr="select cate_id,info_id,subtitle,add_time,attachment from ".DB_PREFIX."info where info_start<=".time()." and cate_id = {$ids[gamerchd]} and info_gid={$info_gid} order by sort_num desc,add_time desc limit 0,7";
$rs_gamexq = $db->getAll($sqlstr);

//常见问题
$sqlstr="select cate_id,info_id,subtitle,add_time,attachment from ".DB_PREFIX."info where info_start<=".time()." and cate_id = {$ids[gamecjwt]} and info_gid={$info_gid} order by sort_num desc,add_time desc limit 0,7";
$rs_gamequ = $db->getAll($sqlstr);



include_once('templates/index.html');
}
?>