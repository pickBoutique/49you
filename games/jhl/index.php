<?php
include_once('init.inc.php');
if(empty($act)){
$info_gid=$game_id;
//游戏资讯 216=>热点217=>新闻218=>活动

$sqlstr="select  (case cate_id when 217 then 1 when 218 then 2 end) as cate_id,info_id,subtitle,add_time,attachment from (select cate_id,info_id,subtitle,add_time,attachment from ".DB_PREFIX."info where info_start<=".time()." and (cate_id = 217 or  cate_id=218) and info_ishot=1 and info_gid={$info_gid} order by sort_num desc,add_time desc limit 0,8) a
		union all (select cate_id,info_id,subtitle,add_time,attachment from ".DB_PREFIX."info where info_start<=".time()." and cate_id = 217 and info_gid={$info_gid} order by sort_num desc,add_time desc limit 0,8)
		union all (select cate_id,info_id,subtitle,add_time,attachment from ".DB_PREFIX."info where info_start<=".time()." and cate_id = 218 and info_gid={$info_gid} order by sort_num desc,add_time desc limit 0,8)";
$rs_news = $db->getAll($sqlstr);

//游戏攻略
$sqlstr="select cate_id,info_id,subtitle,add_time,attachment from ".DB_PREFIX."info where info_start<=".time()." and cate_id = 223 and info_gid={$info_gid} order by sort_num desc,add_time desc limit 0,8";
$rs_gamegs = $db->getAll($sqlstr);

//玩家心情
$sqlstr="select cate_id,info_id,subtitle,add_time,attachment from ".DB_PREFIX."info where info_start<=".time()." and cate_id = 225 and info_gid={$info_gid} order by sort_num desc,add_time desc limit 0,8";
$rs_gamexq = $db->getAll($sqlstr);

//常见问题
$sqlstr="select cate_id,info_id,subtitle,add_time,attachment from ".DB_PREFIX."info where info_start<=".time()." and cate_id = 224 and info_gid={$info_gid} order by sort_num desc,add_time desc limit 0,8";
$rs_gamequ = $db->getAll($sqlstr);



include_once('templates/index.html');
}
?>