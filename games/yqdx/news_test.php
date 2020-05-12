<?php
include_once('init.inc.php');
require(WEB_ROOT."/include/json.class.php");
if(empty($act)){
	$cid = intval($_REQUEST['cid']);
	$info_gid = $game_id;
	if(empty($cid) || $cid == 0) $cid = $ids['topindex'];
	//分类导航
	$allcid=getSubCateID($cid);
	$where = " cate_id in ({$allcid}) ";
	//内容列表
	$page = empty($_GET['page'])?1:$_GET['page'];
	$pager = array('page'=>$page, 'size'=> 20,'sort'=>"sort_num desc,add_time desc",'dir'=>"");
	$list_news = $db->getdata("select title,source,summary,subtitle,add_time,info_id from ".DB_PREFIX."info where info_start<=".time()." and $where and info_gid={$info_gid} ",&$pager);

	
	include_once('templates/news_test.html');
}
?>