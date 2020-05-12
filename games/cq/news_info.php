<?php

include_once('init.inc.php');
$_REQUEST['id']=intval($_REQUEST['id']);
$info_gid = $game_id;
//新闻资讯
$sqlstr="select cate_id,info_id,subtitle,title,source,summary,pic,content,add_time from ".DB_PREFIX."info where info_start<=".time()." and info_id=$_REQUEST[id] and info_gid={$info_gid}";
$row_news = $db->getRow($sqlstr);
//$row_news["cate_name"]="cate_name";
format_news_catename_by_id(&$row_news,"cate_id","cate_name","infocate","cate_id","cate_name");
$imginfo=$row_news["cate_name"];

if($row_news){
	//上一条下一条
	$sqlstr="select * from(select info_id,subtitle,1 ty from ".DB_PREFIX."info where  info_start<=".time()." and cate_id=$row_news[cate_id] and info_id<$row_news[info_id] order by info_id desc limit 1) a union all (select info_id,subtitle,2 ty from ".DB_PREFIX."info where info_start<=".time()." and cate_id=$row_news[cate_id] and info_id>$row_news[info_id] order by info_id limit 1)";
	$row_move = array();
	$row_move = $db->getAll($sqlstr);
	//print_r($row_move);
	include_once('templates/news_info.html');
}else{
	redir('news.html');
}
function format_news_catename_by_id($rs,$id,$name,$tb,$idfiled,$namefield){
	global $db;
	$ids = $rs[$id];
	if(!empty($ids)){
		$list = $db->getAll("select cate_id,cate_name,parent_id from ".DB_PREFIX."infocate where $idfiled in ($ids)");
		//print_r($db);
		if($list){
			foreach($list as $key=>$val){
				if($rs[$id]==$val[$idfiled]){
					$rs[$name] = $val[$namefield];
					$rs["reurl"] = "news.html?pid=$val[parent_id]&cid=$val[cate_id]";
				}
			}
		}
		
	}	
}
?>