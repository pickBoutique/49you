<?php

include_once('init_member.inc.php');
if(empty($act)){
	include_once('templates/pwd_find_by_mail.html');
}else if($act=='get_protect'){
	$loginname = add_slashes($_REQUEST['loginname']);
	if(!empty($loginname)){
		$row = $obj_user->get_user_by_name($loginname);
		if($row['question1'] > 0){
			exit($cfg_question[$row['question1']]);
		}else{
			exit('E@该帐号尚未设置密保问题！');
		}
	}
}else if($act=='check_mail'){
	$loginname = htmlspecialchars(add_slashes($_REQUEST['loginname']));
	$email = htmlspecialchars(add_slashes($_REQUEST['email']));
	$seccode = add_slashes($_REQUEST['seccode']);
$ret_url="pwd_find_by_mail.html?loginname={$loginname}&email={$email}";
	if(strtolower($_SESSION['validationcode']) != strtolower($seccode)){
		show_error('注册失败','对不起，验证码错误，请重新输入！',$ret_url);
	}
	if(!empty($loginname) && !empty($email)){
		$row = $obj_user->get_user_by_name($loginname);
		if($row['email'] == $email ){
			if($row['email_isvalid'] == '0'){
				show_error('提示','该帐号尚未绑定邮箱！');
			}
			$mid = $row['member_id'];
			$time = time();
			$encry_mac = md5(time());
			$mac = md5($row['member_id'].$time.$encry_mac); 
			$obj_user->set_mac($mid,$encry_mac);
			
		    include_once(WEB_ROOT."/include/email.class.php");
			$obj_email = new EMail();
			
			$params = array();
			$params['link'] = HTTP_ROOT . "/pwd_set_new.html?mid=$mid&time=$time&mac=$mac";
			$params['mid'] = $mid;
			$params['sender'] = '49you';
			$params['name'] = $row['member_name'] ;
			if($obj_email->send_template($row['email'],"49you-找回密码",null,"member_get_pwd",$params)){
				redir("pwd_find_mail.html?mid=$mid&mail=$email");
			}else{
				show_error('提示','邮件服务繁忙！');
			}
				
			
		}else{
			show_error('提示','帐号不存在！');
		}
	}else{
		show_error('提示','帐户与邮箱必须填写正确！');
	}
}
?>