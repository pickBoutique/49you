<?php
include_once('init_member.inc.php');
	
	//推荐游戏
	$top_games = $db->getAll("select *,(select sum(server_register) from ".DB_PREFIX."server where server_gid=game_id) server_register from ".DB_PREFIX."game where game_status=1 and game_recom>=0 order by game_recom desc limit 11",100);
	
	//游戏截图
	$sqlstr="SELECT pic,subtitle,info_id FROM ".DB_PREFIX."info  where  info_start<=".time()." and cate_id='50' and info_gid='{$top_game[game_id]}' order by sort_num desc,add_time desc limit 0,6";
	$game_imgs = $db->getAll($sqlstr);
	
	//常见问题
	$ids = get_cateids_by_parent(46);
	$where = " and cate_id IN ($ids) ";
	$sqlstr="select title,subtitle,add_time,info_id from ".DB_PREFIX."info where info_start<=".time()." $where order by add_time desc limit 0,5";
	$rs_news = $db->getAll($sqlstr);
	
	
	//友情链接
	$sqlstr="SELECT * FROM ".DB_PREFIX."link a where link_status=1 and link_sort>=0 order by link_sort desc";
	$rs_link = $db->getAll($sqlstr,100);
	
	
	
	
	if(!empty($config_index['slide_newsid'])){
		
		$info = $db->getAll("select info_id,info_gid,info_sid from ".DB_PREFIX."info where info_id='".$config_index['slide_newsid']."' ",300);
		if(!empty($info)){
			
			$game = $db->getAll("select game_web,game_bbs from ".DB_PREFIX."game where game_id='".$info[0]['info_gid']."' ",300);
			$server = $db->getAll("select server_name from ".DB_PREFIX."server where server_id='".$info[0]['info_sid']."' ",300);
			$config_index['slide_server_list'] = $game[0]['game_web'].'/server_list.html';
			$config_index['slide_game_web'] = $game[0]['game_web'];
			$config_index['slide_game_bbs'] = $game[0]['game_bbs'];
			$config_index['slide_server_name']=$server[0]['server_name'].'火爆开启';
			$config_index['slide_bigimg_link']= $game[0]['game_web'].'/news_info.html?id='.$info[0]['info_id'];
		}
	}
	
	if(!empty($config_index['slide2_newsid'])){
		$info = $db->getAll("select info_id,info_gid,info_sid from ".DB_PREFIX."info where info_id='".$config_index['slide2_newsid']."' ",300);
		if(!empty($info)){
			$game = $db->getAll("select game_web,game_bbs from ".DB_PREFIX."game where game_id='".$info[0]['info_gid']."' ",300);
			$server = $db->getAll("select server_name from ".DB_PREFIX."server where server_id='".$info[0]['info_sid']."' ",300);
			$config_index['slide2_server_list'] = $game[0]['game_web'].'/server_list.html';
			$config_index['slide2_game_web'] = $game[0]['game_web'];
			$config_index['slide2_game_bbs'] = $game[0]['game_bbs'];
			$config_index['slide2_server_name']=$server[0]['server_name'].'火爆开启';
			$config_index['slide2_bigimg_link']= $game[0]['game_web'].'/news_info.html?id='.$info[0]['info_id'];
		}
	}
	
include_once('templates/index.html');
?>