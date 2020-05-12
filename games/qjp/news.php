<?php

include_once('init.inc.php');
$pid = intval($_REQUEST['pid']);
$info_gid = 1;
if(empty($pid) || $pid == 0) $pid = 56;
//分类导航
$list_infocate = $db->getAll("select cate_id,cate_name from ".DB_PREFIX."infocate where parent_id=$pid order by sort_num desc");
//print_r($list_infocate);

$cid = intval($_REQUEST['cid']);
if(empty($cid) || $cid == 0) $cid = $list_infocate[0]["cate_id"];
$where = " cate_id = '$cid' ";
if($cid=='69'){
	//对热点新闻进行处理
	$where = " cate_id in (57,58,59) and info_ishot=1 ";
}
//内容列表
$page = empty($_GET['page'])?1:$_GET['page'];
$pager = array('page'=>$page, 'size'=> 20,'sort'=>"sort_num desc,add_time desc",'dir'=>"");
$list_news = $db->getdata("select title,subtitle,add_time,info_id from ".DB_PREFIX."info where info_start<=".time()." and $where and info_gid={$info_gid} ",&$pager);
//print_r($list_news);

//小图标 游戏资讯56 游戏资料60 游戏攻略64 常见问题65 游戏截图66 玩家照片67
$parico=array(56=>"a_6.png",60=>"a_5.png",72=>"a_2.png");
$parinfo=array(56=>"新闻中心",60=>"热门道具",72=>"游戏图片");

$cateico=array(60=>"a_2.png",
64=>"a_3.png",
65=>"a_4.png",
73=>"a_2.png",
74=>"a_2.png",
71=>"a_7.png");
$cateinfo=array(60=>"游戏资料",
64=>"游戏攻略",
65=>"常见问题",
73=>"游戏截图",
74=>"玩家照片",
71=>"新手指南");

if(!empty($cateico[$cid])){
  $imgico=$cateico[$cid];
  $imginfo=$cateinfo[$cid];
}else if(!empty($parico[$pid])){
  $imgico=$parico[$pid];
  $imginfo=$parinfo[$pid];
}else{
  $imgico="a_6.png";
  $imginfo="新闻中心";
}


include_once('templates/news.html');
?>