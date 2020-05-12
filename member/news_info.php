<?php

include_once('init_member.inc.php');
	$_REQUEST['id']=intval($_REQUEST['id']);
	//新闻资讯
	$sqlstr="select cate_id,info_id,subtitle,title,source,summary,content,add_time,info_gid from ".DB_PREFIX."info where  info_start<=".time()."  and info_id=$_REQUEST[id]";
	$row_news = $db->getrow($sqlstr);
	//$row_news["cate_name"]="cate_name";
	format_news_catename_by_id(&$row_news,"cate_id","cate_name","infocate","cate_id","cate_name");

	if($row_news){
		include_once('templates/news_info.html');
	}else{
		redir('news.html');
	}
	
function format_news_catename_by_id($rs,$id,$name,$tb,$idfiled,$namefield){
	global $db;
	$ids = $rs[$id];
	$reurl = array(39=>"news",46=>"service");
	if(!empty($ids)){
		$list = $db->getAll("select cate_id,cate_name,parent_id from ".DB_PREFIX."infocate where $idfiled in ($ids)");
		//print_r($db);
		if($list){
			foreach($list as $key=>$val){
				$rs[$name] = $val[$namefield];
				$rs["parent_id"] = $val["parent_id"];
				$rs["reurl"] = $reurl[$val["parent_id"]];
			}
		}
		if(!empty($rs["info_gid"])){
			$list = $db->getAll("select game_code,game_name,game_web from ".DB_PREFIX."game where game_id = '".$rs["info_gid"]."'");
			if($list){
				foreach($list as $key=>$val){
					$rs["game_code"] = $val["game_code"];
					$rs["game_name"] = $val["game_name"];
					$rs["game_web"] = $val["game_web"];
				}
			}
		}
	}	
}
?>