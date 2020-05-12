<?php
include_once('init.inc.php');
if(empty($act)){
$info_gid=$game_id;
//新闻
$sqlstr="select cate_id,info_id,subtitle,add_time,attachment from ".DB_PREFIX."info where info_start<=".time()." and cate_id = {$ids[gn_xw]} and info_gid={$info_gid} order by sort_num desc,add_time desc limit 0,7";
$rs_news = $db->getAll($sqlstr);

//新闻图片
$sqlstr="select cate_id,info_id,subtitle,add_time,attachment,pic,source from ".DB_PREFIX."info where info_start<=".time()." and cate_id = {$ids[gn_xwpic]} order by sort_num desc,add_time desc limit 1";
$rs_newspic = $db->getAll($sqlstr);

//活动
$sqlstr="select cate_id,info_id,subtitle,add_time,attachment from ".DB_PREFIX."info where info_start<=".time()." and cate_id = {$ids[gn_hd]} and info_gid={$info_gid} order by sort_num desc,add_time desc limit 0,7";
$rs_huodong = $db->getAll($sqlstr);

//活动图片
$sqlstr="select cate_id,info_id,subtitle,add_time,attachment from ".DB_PREFIX."info where info_start<=".time()." and cate_id = {$ids[gn_hdpic]} and info_gid={$info_gid} order by sort_num desc,add_time desc limit 1";
$rs_huodongpic = $db->getAll($sqlstr);


//游戏攻略
$sqlstr="select cate_id,info_id,subtitle,add_time,attachment from ".DB_PREFIX."info where info_start<=".time()." and cate_id = {$ids[gamegl]} and info_gid={$info_gid} order by sort_num desc,add_time desc limit 0,6";
$rs_gamegl = $db->getAll($sqlstr);
//新手指南
$sqlstr="select cate_id,info_id,subtitle,add_time,attachment from ".DB_PREFIX."info where info_start<=".time()." and cate_id = {$ids[gd_xszn]} and info_gid={$info_gid} order by sort_num desc,add_time desc limit 0,9";
$rs_xszn = $db->getAll($sqlstr);
//特色系统
$sqlstr="select cate_id,info_id,subtitle,add_time,attachment from ".DB_PREFIX."info where info_start<=".time()." and cate_id = {$ids[gd_xtjs]} and info_gid={$info_gid} order by sort_num desc,add_time desc limit 0,9";
$rs_xtjs = $db->getAll($sqlstr);
//玩法介绍
$sqlstr="select cate_id,info_id,subtitle,add_time,attachment from ".DB_PREFIX."info where info_start<=".time()." and cate_id = {$ids[gd_tswf]} and info_gid={$info_gid} order by sort_num desc,add_time desc limit 0,9";
$rs_tswf = $db->getAll($sqlstr);

//玩家心情
$sqlstr="select cate_id,info_id,subtitle,add_time,attachment from ".DB_PREFIX."info where info_start<=".time()." and cate_id = {$ids[gamerchd]} and info_gid={$info_gid} order by sort_num desc,add_time desc limit 0,6";
$rs_gamexq = $db->getAll($sqlstr);

//常见问题
$sqlstr="select cate_id,info_id,subtitle,add_time,attachment from ".DB_PREFIX."info where info_start<=".time()." and cate_id = {$ids[gamecjwt]} and info_gid={$info_gid} order by sort_num desc,add_time desc limit 0,7";
$rs_gamequ = $db->getAll($sqlstr);



include_once('templates/index.html');
}
?>