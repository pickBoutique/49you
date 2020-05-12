<?php
include_once('init_member.inc.php');
require(WEB_ROOT."/include/json.class.php");
$json = new JSON();
if(empty($act)){
	$game_id = intval($_REQUEST['gid']);
	
	$game = $db->getRow("SELECT game_id,game_name,game_code,game_class,game_time,game_recom,game_desc,game_web,game_bbs,game_register,game_isnew,game_ishot,game_status,game_rate,game_service,game_guide,game_guidenew,game_pay,game_quit,game_currency FROM " .DB_PREFIX."game WHERE game_id='$game_id' ");
	
	$server_start = strtotime("+2 day",strtotime(date('Y-m-d'))); //不显示两天后才开的服
	$list = $db->getAll("SELECT server_id,server_name,server_gid,server_num,server_status,server_register,server_isnew,server_ishot,server_time ,server_start FROM " .DB_PREFIX."server WHERE server_gid='$game_id' and server_start < $server_start order by server_start desc ");
	
	$game["game_classname"]=$cfg_game_type[$game["game_class"]];
	$str = "{game:" . $json->encode($game) . ",servers:" . $json->encode($list) . "}";
	exit($str);
	
}else if($act=="mygame"){
	list($login_status,$login_info) = $obj_user->check_login();
	if($login_status == SUC_LOGIN){
		$game_id = intval($_REQUEST['gid']);
		//最近登陆服务器
		//$lastserver=array();
		$lastserver = $db->getAll("SELECT mg_server_id,mg_server_name,mg_game_id,from_unixtime(mg_last_time) mg_last_time FROM " .DB_PREFIX."mygame WHERE mg_game_id='$game_id' and mg_mid='{$login_info[2]}' order by mg_last_time desc limit 4");
		//print_r($lastserver);
		$myjson = json_encode($lastserver);
		//必需以下这样输出
		echo $_GET['jsoncallback'].'('.$myjson.')';
	}
}else if($act=="getorderjson"){
	$_server=$_REQUEST["_server"];
	$url_arr = array(
		'sxd' => "http://api.sxd.xd.com/order/{$_server}.json"
	);
	
	$url = $url_arr[$_REQUEST["gamecode"]];
	if(!empty($url)){
		$opts = array(
		  'http'=>array(
			'method'=>"GET",
			'timeout'=>5,
		   )
		);
		
		$context = stream_context_create($opts);
		$html =file_get_contents($url, false, $context);
		
		$ret_arr = array();
		$ret_arr['status'] = '1';
		$ret_arr['result'] = $html;
		$myjson = json_encode($ret_arr);
		exit($_GET['jsoncallback'].'('.$myjson.')');
	}else{
		exit();
	}
	
	
}
?>