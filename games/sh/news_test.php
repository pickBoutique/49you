<?php
include_once('init.inc.php');
require(WEB_ROOT."/include/json.class.php");
if(empty($act)){
	$info_gid = $game_id;
	$pid = 142;

	$allcid=getSubCateID($pid);
	
	$where = " cate_id in ({$allcid}) ";

	//内容列表
	$page = empty($_GET['page'])?1:$_GET['page'];
	$pager = array('page'=>$page, 'size'=> 20,'sort'=>"add_time desc",'dir'=>"");
	$list_news = $db->getdata("select title,subtitle,add_time,info_id,source,summary from ".DB_PREFIX."info where $where and info_gid={$info_gid} ",&$pager);

	
	include_once('templates/news_test.html');
}
?>