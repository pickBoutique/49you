<?php
include_once('init.inc.php');
require(WEB_ROOT."/include/json.class.php");
if(empty($act)){
	$pid = intval($_REQUEST['pid']);
	$info_gid = $game_id;
	if(empty($pid) || $pid == 0) $pid = 122;
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
	if($cid=='123'){
		//对热点新闻进行处理
		$where = " cate_id in (124,125) and info_ishot=1 ";
	}
	
	//内容列表
	$page = empty($_GET['page'])?1:$_GET['page'];
	$pager = array('page'=>$page, 'size'=> 20,'sort'=>"sort_num desc,add_time desc",'dir'=>"");
	$list_news = $db->getdata("select title,subtitle,add_time,info_id from ".DB_PREFIX."info where info_start<=".time()." and $where and info_gid={$info_gid} ",&$pager);
	
	//新服开服
	$sqlstr="select cate_id,info_id,subtitle,add_time,attachment from ".DB_PREFIX."info where info_start<=".time()." and cate_id = 137 and info_gid={$info_gid} order by sort_num desc,add_time desc limit 0,3";
	$rs_gamehd = $db->getAll($sqlstr);
	
	include_once('templates/news.html');
}else if($act=="getnews"){
	$info_gid=$game_id;
	$sqlstr="select cate_id,info_id,subtitle,source from ".DB_PREFIX."info where info_start<=".time()." and cate_id = 137 and info_gid={$info_gid} order by add_time desc";
	$rs_news = $db->getRow($sqlstr);
	//$rs_news["pgadd"]="http://".$_SERVER["SERVER_NAME"]."/".get_newslink($rs_news["info_id"]);
	$rs_news["pgadd"]=$rs_news["source"];
	$myjson = json_encode($rs_news);
	//必需以下这样输出
	echo $_GET['jsoncallback'].'('.$myjson.')';
}
?>