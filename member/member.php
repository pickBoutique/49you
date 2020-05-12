<?php
define('PERMI_CODE','login');
include_once('init_member.inc.php');
if(empty($act)){
	$top_game_id = intval($config_index['member_recom_game']);
	//推荐游戏
	$top_game = $db->getRow("select * from ".DB_PREFIX."game where game_id=$top_game_id  limit 0,1");
	
	//游戏截图
	$sqlstr="SELECT pic,subtitle,info_id FROM ".DB_PREFIX."info  where  info_start<=".time()."  and cate_id='50' and info_gid='{$top_game[game_id]}' order by sort_num desc,add_time desc limit 0,4";
	$game_imgs = $db->getAll($sqlstr);
	
	//我的游戏列表
	$sqlstr="select mg_game_id,mg_game_name,mg_server_id,mg_server_name,game_web from ".DB_PREFIX."mygame left join ".DB_PREFIX."game on mg_game_id=game_id where mg_mid='$login_info[2]' order by mg_last_time desc limit 0,4";
	$rs_gamelist = $db->getAll($sqlstr);
	
	//常见问题-帐号问题
	$sqlstr="select info_id,subtitle,source from ".DB_PREFIX."info where  info_start<=".time()."  and cate_id in (48) order by add_time desc limit 0,7";
	$rs_news_acount = $db->getAll($sqlstr);
	//常见问题-充值问题
	$sqlstr="select info_id,subtitle,source from ".DB_PREFIX."info where  info_start<=".time()."  and cate_id in (49) order by add_time desc limit 0,7";
	$rs_news_pay = $db->getAll($sqlstr);
	
	//完善安全设置 邮箱绑定，身份认证，密保
	$member_save = $db->getRow("select email_isvalid,member_idvalid,is_setup from ".DB_PREFIX."member where member_id='$login_info[2]'");
	include_once('templates/member.html');
}

?>