<?php
define('PERMI_CODE','login');
include_once('init_member.inc.php');
$usr_info=$obj_user->get_user_by_id($login_info[2]);
if(empty($act)||$usr_info["member_idvalid"]==1){
	include_once('templates/bind_idcard.html');
}else if($act == 'edit'){
	$ret_url="bind_idcard.html?truename=$_REQUEST[truename]&sfznum=$_REQUEST[sfznum]";
	
	if(empty($_REQUEST['truename'])||$_REQUEST['truename']==''){
		show_error('修改失败','真实姓名不能为空，请重新输入！',$ret_url);
	}else if(empty($_REQUEST['sfznum'])||$_REQUEST['sfznum']==''){
		show_error('修改失败','身份证不能为空，请重新输入！',$ret_url);
	}
	$row= array();
	$row['member_truename'] =trim($_REQUEST['truename']);
	$row['member_idcard']   =trim($_REQUEST['sfznum']);
	$row['member_id']       =$login_info[2];
	$row['member_idvalid']  =1;
	$ret = $db->update($row,DB_PREFIX."member");
	if($ret){
		show_error("修改成功","身份认证成功！","member.html");
	}
	else{
		show_error('修改失败','服务器繁忙，请重试！',$ret_url);
	}
	

}


	
?>