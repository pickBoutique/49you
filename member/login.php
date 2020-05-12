<?php
include_once('init_member.inc.php');

$gid = intval($_REQUEST['gid']);

$returl=$_REQUEST['returl'];
if(empty($act)){
	if($login_status == SUC_LOGIN){
		redir('member.html',true);
	}
	
	if($gid>0){
		$game = $db->getRow("select * from ".DB_PREFIX."game where game_id='{$gid}' ");	
		if(!empty($game)){
			if(file_exists(WEB_ROOT.'/member/templates/login_'.$game['game_code'].'.html')){
				
				$game_root = str_replace('www',$game['game_code'],YOU_ROOT);
				
				include_once('templates/login_'.$game['game_code'].'.html');
				exit();
			}
		}
	}
	include_once('templates/login.html');
}else if($act=='ajlogin'){
	$errormsg='';
	$synlogin_script='';
	if($login_status != SUC_LOGIN){
		$username = add_slashes($_REQUEST['username']);
		$password = add_slashes($_REQUEST['password']);
		
		if(!empty($username) && !empty($password) ){
			
			$timeout = !empty($_REQUEST['save_id']) ? 3600 * 24 * 7 : 0;
			$password = $obj_user->encry_pwd($username,$password);
			list($status,$info) = $obj_user->login($username,$password);
			
			if($status == SUC_LOGIN){
				//TODO:登录成功
				
				//同步登陆到UC
				
				$synlogin_script = synlogin($username, add_slashes($_REQUEST['password']), $info['email']);
				$errormsg='';
			}else if($status == ERR_LOGIN_PWD || $status == ERR_LOGIN_NOT_EXISTS){
				$errormsg='对不起，密码错误或帐户不存在';
			}else if($status == ERR_LOGIN_ACTIVE){
				$errormsg='对不起，帐户尚未激活';
			}
		}else{
			$errormsg='对不起，请把登录信息填写完整！';
		}
	}
	$str = array();
	if($errormsg==''){
		list($login_status,$login_info) = $obj_user->check_login();
		$usr_info = $obj_user->get_user_by_id($login_info[2]);
		$level_info = $db->getRow("select * from ".DB_PREFIX."member_level where level_id='{$usr_info[member_level]}' ");
		$game_id = intval($_REQUEST['gid']);
		//最新服务器
		$newserver = $db->getRow("SELECT server_id,server_name,server_gid FROM " .DB_PREFIX."server WHERE server_gid='$game_id' and server_status = 1 order by server_start desc limit 1");
		//最近登陆服务器
		$lastserver = $db->getRow("SELECT mg_server_id,mg_server_name,mg_game_id FROM " .DB_PREFIX."mygame WHERE mg_game_id='$game_id' and mg_mid='$usr_info[member_id]' order by mg_last_time desc limit 1");
		$str["member_id"]=$login_info[2];
		$str["username"]=$usr_info["member_name"];
		$str["vip"]=$level_info['level_name'];
		$str["money"]=$usr_info['money'];
		$str["news_id"]=$newserver["server_id"];
		$str["newg_id"]=$newserver["server_gid"];
		$str["news_name"]=empty($newserver["server_name"]) ? "" : $newserver["server_name"];
		$str["lasts_id"]=$lastserver["mg_server_id"];
		$str["lastg_id"]=$lastserver["mg_game_id"];
		$str["lasts_name"]= empty($lastserver["mg_server_name"]) ? "" : $lastserver["mg_server_name"];
		$str["errormsg"]="";
	}else{
		$str["errormsg"] = $errormsg;
	}
	
	$str["script"] = $synlogin_script . "<script language='javascript'>window.location.href=window.location.href;</script>";
	$json = json_encode($str);
	header('Content-Encoding: plain');

	//必需以下这样输出
	echo $_GET['jsoncallback'].'('.$json.')';
//	exit($str);
}else if($act=='login'){
	$username = add_slashes($_REQUEST['username']);
	$password = add_slashes($_REQUEST['password']);
	
	if(!empty($username) && !empty($password) ){
		$password = $obj_user->encry_pwd($username,$password);
		list($status,$info) = $obj_user->login($username,$password);
		
		if($status == SUC_LOGIN){
			//TODO:登录成功
			
			//同步登陆到UC
			$synlogin_script = synlogin($username, add_slashes($_REQUEST['password']), $info['email']);
			
			if(empty($returl)){
				$returl = 'member.html';
			}
			$script = $synlogin_script . "<script language='javascript'>window.location.href='$returl';</script>";
			
			exit($script);
			//redir($returl);
			
		}else if($status == ERR_LOGIN_PWD){
			show_error('登录失败','对不起，密码错误');
		}else if($status == ERR_LOGIN_NOT_EXISTS){
			show_error('登录失败','对不起，帐户不存在');
		}else if($status == ERR_LOGIN_ACTIVE){
			show_error('登录失败','对不起，帐户尚未激活');
		}
	}else{
		show_error('登录失败','对不起，请把登录信息填写完整！');
	}
	
}

?>