<?php
include_once('init.inc.php');
if(empty($act)){
$info_gid=2;
//游戏资讯79 80=>热点 81=>公告 82=>活动
$sqlstr="select 80 as cate_id,info_id,subtitle,add_time from (select cate_id,info_id,subtitle,add_time from ".DB_PREFIX."info where info_start<=".time()." and (cate_id = 81 or  cate_id=82) and info_ishot=1 and info_gid={$info_gid} order by sort_num desc,add_time desc limit 0,7) a
		union all (select cate_id,info_id,subtitle,add_time from ".DB_PREFIX."info where info_start<=".time()." and cate_id = 81 and info_gid={$info_gid} order by sort_num desc,add_time desc limit 0,7)
		union all (select cate_id,info_id,subtitle,add_time from ".DB_PREFIX."info where info_start<=".time()." and cate_id = 82 and info_gid={$info_gid} order by sort_num desc,add_time desc limit 0,7)";
$rs_news = $db->getAll($sqlstr);

//新手秘籍 89
$sqlstr="select cate_id,info_id,subtitle,add_time from ".DB_PREFIX."info where info_start<=".time()." and cate_id = 89 and info_gid={$info_gid} order by sort_num desc,add_time desc limit 0,8";
$rs_gamexs = $db->getAll($sqlstr);
//高手宝典 90
$sqlstr="select cate_id,info_id,subtitle,add_time from ".DB_PREFIX."info where info_start<=".time()." and cate_id = 90 and info_gid={$info_gid} order by sort_num desc,add_time desc limit 0,8";
$rs_gamegs = $db->getAll($sqlstr);

//游戏截图85
$sqlstr="select cate_id,info_id,pic,summary from ".DB_PREFIX."info where info_start<=".time()." and cate_id = 85 and info_gid={$info_gid} order by sort_num desc,add_time desc limit 0,5";
$rs_gamepic = $db->getAll($sqlstr);
//玩家照片86
$sqlstr="select cate_id,info_id,pic,summary from ".DB_PREFIX."info where info_start<=".time()." and cate_id = 86 and info_gid={$info_gid} order by sort_num desc,add_time desc limit 0,5";
$rs_memberpic = $db->getAll($sqlstr);

//常见问题 91 叶子猪攻略 120
$sqlstr="select cate_id,info_id,subtitle,add_time from ".DB_PREFIX."info where info_start<=".time()." and cate_id = 91 and info_gid={$info_gid} order by sort_num desc,add_time desc limit 0,7";
$rs_gamequ = $db->getAll($sqlstr);

//日常活动 92
$sqlstr="select cate_id,info_id,subtitle,add_time from ".DB_PREFIX."info where info_start<=".time()." and cate_id = 92 and info_gid={$info_gid} order by sort_num desc,add_time desc limit 0,7";
$rs_gamehd = $db->getAll($sqlstr);
	
include_once('templates/index.html');
}
?>