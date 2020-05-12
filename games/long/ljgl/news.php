<?php
include_once('init.inc.php');
//require(WEB_ROOT."/include/json.class.php");
if(empty($act)){
	$info_gid = $game_id;
	$cid = $ids['gamegl'];
	$allcid=getSubCateID($cid);
	$where = " cate_id in ({$allcid}) ";
	$sertext = add_slashes(($_REQUEST["sertext"]));
	
	//exit(($sertext));
	if(!empty($sertext)){
		$where .= " and title like '%".$sertext."%'";
	}
	//内容列表
	$page = empty($_GET['page'])?1:$_GET['page'];
	$pager = array('page'=>$page, 'size'=> 6,'sort'=>"sort_num desc,add_time desc",'dir'=>"");
	$list_news = $db->getdata("select title,subtitle,add_time,info_id from ".DB_PREFIX."info where info_start<=".time()." and $where and info_gid={$info_gid} ",&$pager);
	
	include_once('templates/news.html');
}
?>