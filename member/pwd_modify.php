<?php
define('PERMI_CODE','login');
include_once('init_member.inc.php');
if(empty($act)){
	include_once('templates/pwd_modify.html');
}else if($act == 'edit'){
	$ret_url="pwd_modify.html";
	if(empty($_REQUEST['psw'])||empty($_REQUEST['oldpsw'])){
		show_error('修改失败','对不起，密码不能为空，请重新输入！',$ret_url);
	}else if($_REQUEST['psw']!=$_REQUEST['psw2']){
		show_error('修改失败','对不起，新密码和确认码不匹配，请重新输入！',$ret_url);
	}
	$usr_info  =$obj_user->get_user_by_id($login_info[2]);
	$oldpsw    =$obj_user->encry_pwd($usr_info['member_name'],add_slashes($_REQUEST['oldpsw']));
//	exit($oldpsw."  ".$usr_info['member_pwd']);
	if($oldpsw!=$usr_info['member_pwd']){
		show_error('修改失败','对不起，原密码不正确，请重新输入！',$ret_url);
	}else{
		$row= array();
		$row['member_pwd'] =$obj_user->encry_pwd($usr_info['member_name'],add_slashes($_REQUEST['psw']));
		$row['member_id']  =$login_info[2];
		$ret = $db->update($row,DB_PREFIX."member");
		
		$new_pwd = $obj_user->encry_pwd($usr_info['member_name'],add_slashes($_REQUEST['psw']));
		$ret = $obj_user->modify_pwd($login_info[2], $new_pwd);
		
		if($ret){
			show_error("修改成功","密码修改成功，请重新登陆！","loginout.html?returl=login.html");
		}
		else{
			show_error('注册失败','服务器繁忙，请重试！',$ret_url);
		}
	}

}
?>