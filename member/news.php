<?php

include_once('init_member.inc.php');
$cid = intval($_REQUEST['cid']);
$ginf = intval($_REQUEST['ginf']);
$gcode = add_slashes($_REQUEST['gcode']);
$page = intval($_GET['page']);
$where = '';
if(!empty($cid)){
	$where = " and cate_id = '$cid' ";
}else if(!empty($ginf)){
	$ids = get_cateids_by_parent($ginf);
}else if(!empty($gcode)){
	//GET新闻的父ID
    $sqlstr="select cate_id from ".DB_PREFIX."infocate where pic in('news{$gcode}','gl{$gcode}')";
	$rs_getid = $db->getAll($sqlstr,100);

	$ids = "-1";
	  if($rs_getid){
		  foreach($rs_getid as $i=> $g){
		  $ids .= "," . $g["cate_id"];
		  }
	  }
	$where = " and a.cate_id IN ($ids) and a.pic=''";
}else{
	$ids = get_cateids_by_parent(0);
	$where = " and a.cate_id IN ($ids) and a.pic='' and a.content!='' and b.pic!=''";
}
//推荐游戏
$top_games = $db->getAll("select game_code,game_name from ".DB_PREFIX."game where game_status=1 and game_recom>=0 order by game_recom desc limit 9",100);

//查询数据库
$page = empty($page)?1:$page;
$pager = array('page'=>$page, 'size'=> 15,'sort'=>"a.add_time desc,a.sort_num desc",'dir'=>"");
$list_news = $db->getdata("select a.title,a.subtitle,a.add_time,a.info_id,(case when b.pic like 'new%' then '新闻公告' else '游戏攻略' end) typ  from ".DB_PREFIX."info a inner join ".DB_PREFIX."infocate b on a.cate_id=b.cate_id where a.info_start<=".time()."  $where ",&$pager);

include_once('templates/news.html');

?>