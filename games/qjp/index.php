<?php
include_once('init.inc.php');
if(empty($act)){
$info_gid=1;
//游戏资讯56 69=>热点 59=>活动 58=>新闻 57=>公告 
$sqlstr="select 69 as cate_id,info_id,subtitle,add_time from (select cate_id,info_id,subtitle,add_time from ".DB_PREFIX."info where info_start<=".time()." and (cate_id = 57 or  cate_id=58 or  cate_id=59) and info_ishot=1 and info_gid={$info_gid} order by sort_num desc,add_time desc limit 0,8) a
		union all (select cate_id,info_id,subtitle,add_time from ".DB_PREFIX."info where info_start<=".time()." and cate_id = 59 and info_gid={$info_gid} order by sort_num desc,add_time desc limit 0,8)
		union all (select cate_id,info_id,subtitle,add_time from ".DB_PREFIX."info where info_start<=".time()." and cate_id = 58 and info_gid={$info_gid} order by sort_num desc,add_time desc limit 0,8)
		union all (select cate_id,info_id,subtitle,add_time from ".DB_PREFIX."info where info_start<=".time()." and cate_id = 57 and info_gid={$info_gid} order by sort_num desc,add_time desc limit 0,8)";
$rs_news = $db->getAll($sqlstr);
//游戏资料60

//游戏攻略64
$sqlstr="select cate_id,info_id,subtitle,add_time from ".DB_PREFIX."info where info_start<=".time()." and cate_id = 64 and info_gid={$info_gid} order by sort_num desc,add_time desc limit 0,6";
$rs_gamegn = $db->getAll($sqlstr);
//常见问题65
$sqlstr="select cate_id,info_id,subtitle,add_time from ".DB_PREFIX."info where info_start<=".time()." and cate_id = 65 and info_gid={$info_gid} order by sort_num desc,add_time desc limit 0,6";
$rs_gamequ = $db->getAll($sqlstr);
//游戏截图73
$sqlstr="select cate_id,info_id,pic from ".DB_PREFIX."info where info_start<=".time()." and cate_id = 73 and info_gid={$info_gid} order by sort_num desc,add_time desc limit 0,4";
$rs_gamepic = $db->getAll($sqlstr);
//玩家照片74
$sqlstr="select cate_id,info_id,pic from ".DB_PREFIX."info where info_start<=".time()." and cate_id = 74 and info_gid={$info_gid} order by sort_num desc,add_time desc limit 0,4";
$rs_memberpic = $db->getAll($sqlstr);
//游戏道具68
$sqlstr="select cate_id,info_id,subtitle,pic from ".DB_PREFIX."info where info_start<=".time()." and cate_id = 68 and info_gid={$info_gid} order by sort_num desc,add_time desc limit 0,10";
$rs_gametools = $db->getAll($sqlstr);
	
include_once('templates/index.html');
}else if($act=="getjson" & false){
	$url=$_REQUEST["url"];
	$opts = array(
	  'http'=>array(
		'method'=>"GET",
		'timeout'=>5,
	   )
	);
	
	$context = stream_context_create($opts);
	$html =file_get_contents($url, false, $context);
	exit($html);
}
?>