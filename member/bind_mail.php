<?php
//define('PERMI_CODE','login');
include_once('init_member.inc.php');
if(empty($act)){
	if($login_status != SUC_LOGIN){
		redir('login.html');
	}
	$usr_info=$obj_user->get_user_by_id($login_info[2]);
	include_once('templates/bind_mail.html');
}else if($act == 'sendmail'){
	$mid = intval($_REQUEST['mid']);
	$usr_info=$obj_user->get_user_by_id($mid);
	if($usr_info){
		include_once(WEB_ROOT.'/include/email.class.php');
		$mail = new EMail();
		$code = md5(time());
		$actemail = $_REQUEST['email']=='' ? $usr_info['email'] : $_REQUEST['email'];
		//echo($_REQUEST['email']." us:".$usr_info['email']."actemail:".$actemail);
		//$mid = $usr_info['member_id'];
		
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
			$row['member_id']=$login_info[2];
			$row['email']=$actemail;
			$row['mac']  =$code;
			$ret = $db->update($row,DB_PREFIX."member");
			show_error("发送成功",$reg_success,"member.html");
		}else{
			$reg_success = '系统繁忙或邮箱地址有误码！请稍后(<a href="' . $url . '"  style=" color:#F06706;">重试发送</a>)</p>';
			//print_r($usr_info);
			//exit("actemail:".$actemail);
			show_error("发送失败",$reg_success,"member.html");
		}
	}else{
			show_error("发送失败","发送资料有误！","member.html");
	}
}else if($act == 'active'){
	$mid = intval($_REQUEST['mid']);
	$code = trim($_REQUEST['code']);
	
	$usr_info=$obj_user->get_user_by_id($mid);
	if($usr_info){
		if(($usr_info['mac']=='' && $usr_info['email_isvalid']=='0')||trim($usr_info['mac'])!=$code){
			show_error('邮箱激活失败','激活邮件已超时，请重新发送激活邮件！',"member.html");
		}		
		$row= array();
		$row['mac'] ='';
		$row['member_id'] =$mid;
		$row['email_isvalid'] =1;
		$ret = $db->update($row,DB_PREFIX."member");
		list($status,$info) = $obj_user->login($usr_info['member_name'],$usr_info['member_pwd']);
		show_error("邮箱激活成功","您的邮箱已正确进行了激活！","member.html");
	}else{
		show_error('邮箱激活失败','激活邮件已超时，请重新发送激活邮件！',"member.html");
	}
	
}
?>