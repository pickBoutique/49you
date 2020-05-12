<?php
include_once('init.inc.php');
require(WEB_ROOT."/include/json.class.php");
if(empty($act)){
	$gettitle=true;
	$pid = intval($_REQUEST['pid']);
	$info_gid = $game_id;

	$cid = intval($_REQUEST['cid']);
	if(empty($cid) || $cid == 0) {
		if(empty($pid) || $pid == 0) $pid = $ids['gamenews'];
		//分类导航
		$list_infocate = $db->getAll("select cate_id,cate_name from ".DB_PREFIX."infocate where parent_id=$pid and sort_num>=0 order by sort_num desc limit 1");
		
		$cid = $list_infocate[0]["cate_id"];
		$titleinfo=$list_infocate[0]["cate_name"];
		$gettitle=false;
	}
	$allcid=getSubCateID($cid);
	
	$where = " cate_id in ({$allcid}) ";
	if($cid==$ids['gn_rd']){
		//对热点新闻进行处理
		$where = " cate_id in ('".$ids['gn_xw']."','".$ids['gn_hd']."') and info_ishot=1 ";
	}
	
	//内容列表
	$page = empty($_GET['page'])?1:$_GET['page'];
	$pager = array('page'=>$page, 'size'=> 20,'sort'=>"sort_num desc,add_time desc",'dir'=>"");
	$list_news = $db->getdata("select title,subtitle,add_time,info_id from ".DB_PREFIX."info where info_start<=".time()." and $where and info_gid={$info_gid} ",&$pager);
	//获取新闻标题
	if($gettitle){
		$list_infocate = $db->getAll("select cate_id,cate_name from ".DB_PREFIX."infocate where cate_id='{$cid}' and sort_num>=0 order by sort_num desc limit 1");
		$titleinfo=$list_infocate[0]["cate_name"];
	}
	
	include_once('templates/news.html');
}
?>