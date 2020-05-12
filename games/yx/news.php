<?php
include_once('init.inc.php');
require(WEB_ROOT."/include/json.class.php");
$id = intval($_REQUEST['id']);
if(!empty($id)){
	redir("news_$id.html",true);
}

if(empty($act)){
	$pid = intval($_REQUEST['pid']);
	$info_gid = $game_id;
	if(empty($pid) || $pid == 0) $pid = 243;
	//分类导航
	$list_infocate = $db->getAll("select cate_id,cate_name from ".DB_PREFIX."infocate where parent_id=$pid and sort_num>=0 order by sort_num desc");
	//print_r($list_infocate);
	
	$cid = intval($_REQUEST['cid']);
	if(empty($cid) || $cid == 0) {
		$cid = $list_infocate[0]["cate_id"];
		$titleinfo=$list_infocate[0]["cate_name"];
	}else{
		foreach($list_infocate as $v){
			if($v["cate_id"]==$cid){$titleinfo=$v["cate_name"];break;}
		}
	}
	$allcid=getSubCateID($cid);
	
	$where = " cate_id in ({$allcid}) ";
	if($cid=='244'){
		//对热点新闻进行处理
		$where = " cate_id in (245,246) and info_ishot=1 ";
	}
	
	//内容列表
	$page = empty($_GET['page'])?1:$_GET['page'];
	$pager = array('page'=>$page, 'size'=> 20,'sort'=>"sort_num desc,add_time desc",'dir'=>"");
	$list_news = $db->getdata("select title,subtitle,add_time,info_id from ".DB_PREFIX."info where info_start<=".time()." and $where and info_gid={$info_gid} ",&$pager);

	
	include_once('templates/news.html');
}
?>