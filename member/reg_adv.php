<?php

include_once('init_member.inc.php');
$gid = intval($_REQUEST['gid']);   //游戏编号
$sid = intval($_REQUEST['sid']);   //服务器编号
$mid = intval($_REQUEST['mid']);   //推荐人id
$at = intval($_REQUEST['at']);
$ad = intval($_REQUEST['ad']);
$md = intval($_REQUEST['md']);
$subtype = add_slashes($_REQUEST['subtype']);


if(!empty($_REQUEST['game_id'])){
	$gid = intval($_REQUEST['game_id']);   //游戏编号
}
if(!empty($_REQUEST['server_id'])){
	$sid = intval($_REQUEST['server_id']);   //服务器编号
}
$education = intval($_REQUEST['wid']);   //
$fax = add_slashes($_REQUEST['recom_man']);   //
if(!empty($_REQUEST['recom_2'])){
	$subtype = add_slashes($_REQUEST['recom_2']);   //
}

function callbackhandler($tid,$obj){
	$str = "CallBackHandler.handleCallBack( $tid ,";
	$str = $str . json_encode($obj) . ');';
	return $str;
}

if(!empty($ad)){
	$row = $db->getRow("select * from ".DB_PREFIX."adv where adv_id='$ad' ");
	if(!empty($ad)){
		$gid = empty($gid) ? $row['adv_gid'] : $gid;
		$sid = empty($sid) ? $row['adv_sid'] : $sid;
		$at = empty($at) ? $row['adv_type'] : $at;
		$md = empty($md) ? $row['adv_metrid'] : $md;
	}
}

if(empty($act) && !empty($_REQUEST['username'])){
	
	$ret = $obj_user->check_member_name(add_slashes($_REQUEST['username']));
	if($ret==ERR_REG_EXISTS){
		exit("data=1");
	}else{
		exit("data=2");
	}
}else if($act=='reg_check' && !empty($_REQUEST['username']) && !empty($_REQUEST['tid']) ){
	
	
	$arr = array();
	$ret = $obj_user->check_member_name(add_slashes($_REQUEST['username']));
	if($ret==ERR_REG_EXISTS){
		$arr['success'] = '0';
		$arr['message'] = '用户名已存在';
		$str = callbackhandler($_REQUEST['tid'],$arr);
		exit($str);
	}else{
		$arr['success'] = '1';
		$arr['message'] = '';
		$str = callbackhandler($_REQUEST['tid'],$arr);
		exit($str);
	}
}else if($act=='reg'){
	
	//设置返回地址
	$returl = $_REQUEST['returl'];
	if(empty($returl)){
		if(!empty($gid)){
			$returl = 'game.html?gid='.$gid;
			if(!empty($sid)){
				$returl = 'game_add.html?fromadv=1&gid='.$gid.'&sid='.$sid;
			}
		}else{
			$returl = 'member.html';
		}
	}
	
	$obj_member = new User();
	$row = array();
	$timeout = 0;  //cookie保存的超时秒数
	$code = genCode(16).microtime();
	if(empty($_REQUEST['username']) && empty($_REQUEST['password'])){
		$_REQUEST['username'] = 'guest'.sub_str(md5($code),0,12);
		$_REQUEST['password'] = sub_str(md5($code),12,12);
		$row['member_active'] = '0';
		$timeout=3600*24*30;
		if($login_status == SUC_LOGIN){
			$script =  "<script language='javascript'>window.location.href='$returl';</script>";
			exit($script);
		}
	}
	
	$row['member_name']     =add_slashes($_REQUEST['username']);
	$row['email']           ='';
	$row['member_pwd']      =add_slashes($_REQUEST['password']);
	$row['member_reomname'] =add_slashes($_REQUEST['reomname']);
	$row['member_pwd'] = $obj_member->encry_pwd($row['member_name'],$row['member_pwd']);
	$row['member_recom'] = $mid;
	$row['member_advtype'] = $at;
	$row['member_advid'] = $ad;
	$row['member_metrid'] = $md;
	$row['account_type'] = $subtype;
	$row['education'] = $education;
	$row['fax'] = $fax;

	if(empty($row['member_name'])){
		show_error('注册失败','对不起，用户名不能为空，请重新输入！');
	}
	
	if(empty($row['member_pwd'])){
		show_error('注册失败','对不起，密码不能为空，请重新输入！');
	}
	

	
	$mid = $obj_user->register($row);
	if($mid>0){
		
		list($status,$info) = $obj_user->login($row['member_name'], $row['member_pwd'],false,$timeout);
		$usr_info=$obj_user->get_user_by_id($mid);
		if($usr_info["email"]!=""){
			include_once(WEB_ROOT.'/include/email.class.php');
			$mail = new EMail();
			$code = md5(time());
			$actemail = $usr_info['email'];
			$params = array();
			$params['link'] = HTTP_ROOT . "/bind_mail.html?act=active&mid=".$mid."&code=".$code;
			$params['mid'] = $mid;
			$params['code'] = $code;
			$params['sender'] = '49you';
			$params['name'] = $usr_info['member_name'];
			$url = 'bind_mail.html?act=sendmail&mid='.$mid;
			
			if($mail->send_template($actemail,"49you-邮箱激活",null,"member_email",$params)){
				$arremail = explode('@',$actemail);
				$email_path = 'http://mail.' . $arremail[1];
				
				$reg_success = '恭喜您！系统已经发送一封激活邮件到您的邮箱！请在24小时内登录您的邮箱进行激活。<p>点击登录邮箱：<a href="' . $email_path . '" target="_blank" style=" color:#F06706;" >' . $actemail . '</a><a href="' . $url . '"  style=" color:#F06706;">(重发)</a></p>';
				$row=array();
				$row['member_id']=$mid;
				$row['email']=$actemail;
				$row['mac']  =$code;
				$ret = $db->update($row,DB_PREFIX."member");
				show_error("注册成功",$reg_success,"reg_suc.html");
			}
		}
		if($status == SUC_LOGIN){
			
			//TODO:登录成功
			if(empty($returl)){
				$returl = 'reg_suc.html';
			}
			
			$row_ad = $db->getRow("select * from ".DB_PREFIX."adv where adv_id='$ad' ");
			
			
			
			$synscript = '';
			//if(!empty($script[$at])){
				//$synscript = $script[$at];
			//}
			
			if(!empty($row_ad['adv_regcode'])){
				$synscript = $row_ad['adv_regcode'];
			}
			
			
			//同步登陆+注册代码
			/*
			$synlogin_script = synlogin($row['member_name'], add_slashes($_REQUEST['password']) , $usr_info['email']);
			$script = $synlogin_script . $synscript . " <script language='javascript'>window.location.href='$returl';</script>";
			*/
			
			//注册代码
			$script = $synscript . " <script language='javascript'>window.location.href='$returl';</script>";
			
			/*
			$script = " <script language='javascript'>window.location.href='$returl';</script>";
			*/
			
			if(!empty($_REQUEST['jsonp'])){
				$arr['success'] = '1';
				$arr['message'] = $script;
				$str = callbackhandler($_REQUEST['tid'],$arr);
				exit($str);
			}
			
			exit($script);
			
		}
	}else if($mid==ERR_REG_EXISTS){
		show_error('注册失败','对不起，该用户已存在，请重新输入！');
	}else if($mid==ERR_SQL_QUERY){
		show_error('注册失败','服务器繁忙，请重试！');
	}
}else if($act=='togame'){
	$url = 'game_add.html?fromadv=1&gid='.$gid.'&sid='.$sid;
	redir($url,true);
}else if($act=='download'){
	$type=$_REQUEST['type'];
	$url_s = array(
		'7' => 'http://dl.xdcdn.net/sg/49you/49you%e3%80%8a%e7%9b%9b%e4%b8%96%e4%b8%89%e5%9b%bd%e3%80%8b%e5%bf%ab%e9%80%9f%e7%89%88%e5%ae%89%e8%a3%85%e5%8c%85.exe',
		'31' => 'http://dl.xdcdn.net/sg/56uu/56uu%e3%80%8a%e7%9b%9b%e4%b8%96%e4%b8%89%e5%9b%bd%e3%80%8b%e5%bf%ab%e9%80%9f%e5%ae%89%e8%a3%85%e5%8c%85.exe',
		'10' => 'http://dl.xdcdn.net/sg/joy400/%e5%bf%ab%e4%b9%90%e8%90%a5%e3%80%8a%e7%9b%9b%e4%b8%96%e4%b8%89%e5%9b%bd%e3%80%8b%e5%bf%ab%e9%80%9f%e5%ae%89%e8%a3%85%e5%8c%85.exe'
	);
	
	$url_l = array(
		'7' => 'http://dl.xdcdn.net/sg/49you/49you%e3%80%8a%e7%9b%9b%e4%b8%96%e4%b8%89%e5%9b%bd%e3%80%8b%e5%ae%8c%e6%95%b4%e7%89%88%e5%ae%89%e8%a3%85%e5%8c%85.exe'
	);
	
	$url = $url_s[$gid];
	if($type=='l'){
		$url = $url_l[$gid];
	}

	$row['download_gid'] = $gid;
	$row['download_sid'] = $sid;
	$row['download_uid'] = $login_info[2];
	$row['download_ip'] = get_client_ip();
	$row['download_at'] = $at;
	$row['download_ad'] = $ad;
	$row['download_mid'] = $md;
	$row['download_subtype'] = $subtype;
	$db->insert($row,DB_PREFIX.'download');
	
	//TODO:增加客户端下载记录
	redir($url,true);
}

?>