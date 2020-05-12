<?php
include_once('init.inc.php');
$_REQUEST['id']=intval($_REQUEST['id']);
$info_gid = $game_id;
//新闻资讯
$sqlstr="select cate_id,info_id,subtitle,title,source,summary,pic,content,add_time from ".DB_PREFIX."info where info_start<=".time()." and info_id=$_REQUEST[id] and info_gid={$info_gid}";
$row_news = $db->getRow($sqlstr);
//$row_news["cate_name"]="cate_name";
//format_news_catename_by_id(&$row_news,"cate_id","cate_name","infocate","cate_id","cate_name");

include_once('templates/news_info.html');
?>