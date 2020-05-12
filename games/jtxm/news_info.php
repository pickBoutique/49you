<?php

include_once('init.inc.php');
$_REQUEST['id']=intval($_REQUEST['id']);
$info_gid = 2;
//新闻资讯
$sqlstr="select cate_id,info_id,subtitle,title,source,summary,pic,content,add_time from ".DB_PREFIX."info where info_start<=".time()." and info_id=$_REQUEST[id] and info_gid={$info_gid}";
$row_news = $db->getrow($sqlstr);
//$row_news["cate_name"]="cate_name";
format_news_catename_by_id(&$row_news,"cate_id","cate_name","infocate","cate_id","cate_name");
$imginfo=$row_news["cate_name"];
//小图标 游戏资讯56 游戏资料60 游戏攻略64 常见问题65 游戏截图66 玩家照片67
/*
$cateico=array(60=>"a_2.png",
64=>"a_3.png",
65=>"a_4.png",
73=>"a_2.png",
74=>"a_2.png",
68=>"a_5.png",
71=>"a_7.png");
$cateinfo=array(60=>"游戏资料",
64=>"游戏攻略",
65=>"常见问题",
73=>"游戏截图",
74=>"玩家照片",
68=>"游戏道具",
71=>"新手指南");
$cid=$row_news["cate_id"];
if(!empty($cateico[$cid])){
  $imgico=$cateico[$cid];
  $imginfo=$cateinfo[$cid];
}else{
  $imgico="a_6.png";
  $imginfo="新闻中心";
}
*/
if($row_news){
	//上一条下一条
	$sqlstr="select * from(select info_id,subtitle,1 ty from ".DB_PREFIX."info where info_start<=".time()." and cate_id=$row_news[cate_id] and info_id<$row_news[info_id] order by info_id desc limit 1) a union all (select info_id,subtitle,2 ty from ".DB_PREFIX."info where info_start<=".time()." and cate_id=$row_news[cate_id] and info_id>$row_news[info_id] order by info_id limit 1)";
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