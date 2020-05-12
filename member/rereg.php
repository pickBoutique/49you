<?php
define('PERMI_CODE','login');
include_once('init_member.inc.php');
$gid = intval($_REQUEST['gid']);
$mid = intval($_REQUEST['mid']);
$at = intval($_REQUEST['at']);
$ad = intval($_REQUEST['ad']);
$md = intval($_REQUEST['md']);
if(empty($act)){
	$member_info = $obj_user->get_user_by_id($mid);
	$returl = $_REQUEST['returl'];
	if(empty($returl)){
		$returl = 'reg_suc.html';
	}
	
	if($gid>0){
		$game = $db->getRow("select * from ".DB_PREFIX."game where game_id='{$gid}' ");	
		if(!empty($game)){
			if(file_exists(WEB_ROOT.'/member/templates/rereg_'.$game['game_code'].'.html')){
				$game_root = str_replace('www',$game['game_code'],YOU_ROOT);
				include_once('templates/rereg_'.$game['game_code'].'.html');
				exit();
			}
		}
	}
	
	include_once('templates/rereg.html');
}else if($act=='reg'){
	$obj_member = new User();
	$row = array();
	$row['member_name']     =add_slashes($_REQUEST['loginname']);
	$row['email']           =add_slashes($_REQUEST['email']);
	$row['member_pwd']      =add_slashes($_REQUEST['psw']);
	$row['member_truename'] =add_slashes($_REQUEST['truename']);
	$row['member_idcard']   =add_slashes($_REQUEST['idcard']);
	$row['member_reomname'] =add_slashes($_REQUEST['reomname']);
	$row['birth'] = strtotime($_REQUEST['birth']);
	$row['member_pwd'] = $obj_member->encry_pwd($row['member_name'],$row['member_pwd']);
	$row['member_recom'] = $mid;
	$row['member_advtype'] = $at;
	$row['member_advid'] = $ad;
	$row['member_metrid'] = $md;
	$row['member_id'] = $login_info[2];

	$validcode = $_SESSION['validationcode'];
	$_SESSION['validationcode'] = md5(time());
	$seccode = $_REQUEST['seccode'];

	if(preg_match("/[\x80-\xff]./", $row['member_name'])){
		show_error('注册失败','对不起，用户名称不能使用中文！');
	}

	if(strtolower($validcode) != strtolower($seccode)){
		//show_error('注册失败','对不起，验证码错误，请重新输入！');
	}
	//echo $_REQUEST['psw']."==".$_REQUEST['psw2'];
	if(empty($_REQUEST['psw']) || empty($_REQUEST['psw2'])){
		show_error('注册失败','对不起，密码不能为空，请重新输入！');
	}
	if($_REQUEST['psw']!=$_REQUEST['psw2']){
		show_error('注册失败','对不起，密码和确认码不匹配，请重新输入！');
	}
	
	if($login_info[6]=='1'){
		show_error('激活失败','对不起，当前帐号已经激活过了！');
	}
	
	$mid = $obj_user->rereg($row);
	$returl = $_REQUEST['returl'];
	if(empty($returl)){
		if(!empty($gid)){
			$returl = 'game.html?gid='.$gid;
		}else{
			$returl = 'member.html';
		}
	}
	
	
	
	if($mid>0){
		list($status,$info) = $obj_user->login($row['member_name'], $row['member_pwd']);
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
			//同步登陆到UC
			$synlogin_script = synlogin($row['member_name'], add_slashes($_REQUEST['psw']) , $usr_info['email']);
			//TODO:登录成功
			if(empty($returl)){
				$returl = 'reg_suc.html';
			}
			//exit($returl);
			$script = $synlogin_script . "<script language='javascript'>window.location.href='{$returl}';</script>";
			exit($script);
			
		}
	}else if($mid==ERR_REG_EXISTS){
		show_error('注册失败','对不起，该用户已存在，请重新输入！');
	}else if($mid==ERR_SQL_QUERY){
		show_error('注册失败','服务器繁忙，请重试！');
	}
}else if($act=='ajchkuser'){
	$member_name=add_slashes($_REQUEST['loginname']);
	echo($obj_user->check_member_name($member_name));
}

?>