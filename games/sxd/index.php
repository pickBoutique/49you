<?php
include_once('init.inc.php');
if(empty($act)){
$info_gid=$game_id;
//游戏资讯96 123=>热点 124=>公告 125=>活动
$sqlstr="select  123 as cate_id,info_id,subtitle,add_time,attachment from (select cate_id,info_id,subtitle,add_time,attachment from ".DB_PREFIX."info where info_start<=".time()." and (cate_id = 124 or  cate_id=125) and info_ishot=1 and info_gid={$info_gid} order by sort_num desc,add_time desc limit 0,8) a
		union all (select cate_id,info_id,subtitle,add_time,attachment from ".DB_PREFIX."info where info_start<=".time()." and cate_id = 124 and info_gid={$info_gid} order by sort_num desc,add_time desc limit 0,8)
		union all (select cate_id,info_id,subtitle,add_time,attachment from ".DB_PREFIX."info where info_start<=".time()." and cate_id = 125 and info_gid={$info_gid} order by sort_num desc,add_time desc limit 0,8)";
$rs_news = $db->getAll($sqlstr);

//新手秘籍
$sqlstr="select cate_id,info_id,subtitle,add_time,attachment from ".DB_PREFIX."info where info_start<=".time()." and cate_id = 131 and info_gid={$info_gid} order by sort_num desc,add_time desc limit 0,8";
$rs_gamexs = $db->getAll($sqlstr);
//高手宝典
$sqlstr="select cate_id,info_id,subtitle,add_time,attachment from ".DB_PREFIX."info where info_start<=".time()." and cate_id = 132 and info_gid={$info_gid} order by sort_num desc,add_time desc limit 0,8";
$rs_gamegs = $db->getAll($sqlstr);

//游戏截图
$sqlstr="select cate_id,info_id,pic,summary from ".DB_PREFIX."info where info_start<=".time()." and cate_id = 138 and info_gid={$info_gid} order by sort_num desc,add_time desc limit 0,5";
$rs_gamepic = $db->getAll($sqlstr);
//玩家照片
$sqlstr="select cate_id,info_id,pic,summary from ".DB_PREFIX."info where info_start<=".time()." and cate_id = 139 and info_gid={$info_gid} order by sort_num desc,add_time desc limit 0,5";
$rs_memberpic = $db->getAll($sqlstr);

//常见问题
$sqlstr="select cate_id,info_id,subtitle,add_time,attachment from ".DB_PREFIX."info where info_start<=".time()." and cate_id = 136 and info_gid={$info_gid} order by sort_num desc,add_time desc limit 0,8";
$rs_gamequ = $db->getAll($sqlstr);

//玩家心情
$sqlstr="select cate_id,info_id,subtitle,add_time,attachment from ".DB_PREFIX."info where info_start<=".time()." and cate_id = 178 and info_gid={$info_gid} order by sort_num desc,add_time desc limit 0,8";
$rs_gamehd = $db->getAll($sqlstr);

//服务器列表
$sqlstr="select server_id,server_sid,server_num from ".DB_PREFIX."server where server_gid={$info_gid} order by server_id";
$rs_server = $db->getAll($sqlstr);

include_once('templates/index.html');
}
?>