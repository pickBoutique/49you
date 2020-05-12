<?php
include_once('init.inc.php');
if(empty($act)){
$info_gid=$game_id;
//游戏资讯 162=>热点163=>公告164=>活动

$sqlstr="select  (case cate_id when 163 then 1 when 164 then 2 end) as cate_id,info_id,subtitle,add_time,attachment from (select cate_id,info_id,subtitle,add_time,attachment from ".DB_PREFIX."info where info_start<=".time()." and (cate_id = 163 or  cate_id=164) and info_ishot=1 and info_gid={$info_gid} order by sort_num desc,add_time desc limit 0,8) a
		union all (select cate_id,info_id,subtitle,add_time,attachment from ".DB_PREFIX."info where info_start<=".time()." and cate_id = 163 and info_gid={$info_gid} order by sort_num desc,add_time desc limit 0,8)
		union all (select cate_id,info_id,subtitle,add_time,attachment from ".DB_PREFIX."info where info_start<=".time()." and cate_id = 164 and info_gid={$info_gid} order by sort_num desc,add_time desc limit 0,8)";
$rs_news = $db->getAll($sqlstr);

//新手秘籍
$sqlstr="select cate_id,info_id,subtitle,add_time,attachment from ".DB_PREFIX."info where info_start<=".time()." and cate_id = 171 and info_gid={$info_gid} order by sort_num desc,add_time desc limit 0,8";
$rs_gamexs = $db->getAll($sqlstr);
//高手宝典
$sqlstr="select cate_id,info_id,subtitle,add_time,attachment from ".DB_PREFIX."info where info_start<=".time()." and cate_id = 172 and info_gid={$info_gid} order by sort_num desc,add_time desc limit 0,8";
$rs_gamegs = $db->getAll($sqlstr);

//游戏截图
$sqlstr="select cate_id,info_id,pic,summary from ".DB_PREFIX."info where info_start<=".time()." and cate_id = 174 and info_gid={$info_gid} order by sort_num desc,add_time desc limit 0,5";
$rs_gamepic = $db->getAll($sqlstr);
//玩家照片
$sqlstr="select cate_id,info_id,pic,summary from ".DB_PREFIX."info where info_start<=".time()." and cate_id = 175 and info_gid={$info_gid} order by sort_num desc,add_time desc limit 0,5";
$rs_memberpic = $db->getAll($sqlstr);

//常见问题
$sqlstr="select cate_id,info_id,subtitle,add_time,attachment from ".DB_PREFIX."info where info_start<=".time()." and cate_id = 166 and info_gid={$info_gid} order by sort_num desc,add_time desc limit 0,8";
$rs_gamequ = $db->getAll($sqlstr);


include_once('templates/index.html');
}
?>