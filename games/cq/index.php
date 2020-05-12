<?php
include_once('init.inc.php');
if(empty($act)){
$info_gid=$game_id;
//游戏资讯96 97=>热点 98=>公告 99=>活动
$sqlstr="select 97 as cate_id,info_id,subtitle,add_time from (select cate_id,info_id,subtitle,add_time from ".DB_PREFIX."info where info_start<=".time()." and (cate_id = 98 or  cate_id=99) and info_ishot=1 and info_gid={$info_gid} order by sort_num desc,add_time desc limit 0,8) a
		union all (select cate_id,info_id,subtitle,add_time from ".DB_PREFIX."info where info_start<=".time()." and cate_id = 98 and info_gid={$info_gid} order by sort_num desc,add_time desc limit 0,8)
		union all (select cate_id,info_id,subtitle,add_time from ".DB_PREFIX."info where info_start<=".time()." and cate_id = 99 and info_gid={$info_gid} order by sort_num desc,add_time desc limit 0,8)";
$rs_news = $db->getAll($sqlstr);

//新手秘籍 112
$sqlstr="select cate_id,info_id,subtitle,add_time from ".DB_PREFIX."info where info_start<=".time()." and cate_id = 112 and info_gid={$info_gid} order by sort_num desc,add_time desc limit 0,8";
$rs_gamexs = $db->getAll($sqlstr);
//高手宝典 113
$sqlstr="select cate_id,info_id,subtitle,add_time from ".DB_PREFIX."info where info_start<=".time()." and cate_id = 113 and info_gid={$info_gid} order by sort_num desc,add_time desc limit 0,8";
$rs_gamegs = $db->getAll($sqlstr);

//游戏截图103
$sqlstr="select cate_id,info_id,pic,summary from ".DB_PREFIX."info where info_start<=".time()." and cate_id = 103 and info_gid={$info_gid} order by sort_num desc,add_time desc limit 0,5";
$rs_gamepic = $db->getAll($sqlstr);
//玩家照片104
$sqlstr="select cate_id,info_id,pic,summary from ".DB_PREFIX."info where info_start<=".time()." and cate_id = 104 and info_gid={$info_gid} order by sort_num desc,add_time desc limit 0,5";
$rs_memberpic = $db->getAll($sqlstr);

//常见问题 107
$sqlstr="select cate_id,info_id,subtitle,add_time from ".DB_PREFIX."info where info_start<=".time()." and cate_id = 107 and info_gid={$info_gid} order by sort_num desc,add_time desc limit 0,8";
$rs_gamequ = $db->getAll($sqlstr);

//传奇之路 106
$sqlstr="select cate_id,info_id,subtitle,add_time from ".DB_PREFIX."info where info_start<=".time()." and cate_id = 106 and info_gid={$info_gid} order by sort_num desc,add_time desc limit 0,8";
$rs_gamehd = $db->getAll($sqlstr);

//服务器列表
$sqlstr="select server_id,server_name from ".DB_PREFIX."server where server_gid={$info_gid} order by server_id";
$rs_server = $db->getAll($sqlstr);

include_once('templates/index.html');
}
?>