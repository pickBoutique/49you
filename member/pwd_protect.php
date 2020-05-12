<?php
define('PERMI_CODE','login');
include_once('init_member.inc.php');
$usr_info=$obj_user->get_user_by_id($login_info[2]);
if(empty($act)){
	include_once('templates/pwd_protect.html');
}else if($act == 'edit'){
	$ret_url="pwd_protect.html?question1=$_REQUEST[question1]";
	if(empty($_REQUEST['question1'])){
		show_error('修改失败','请选择密保问题！',$ret_url);
	}else if(empty($_REQUEST['answer1'])){
		show_error('修改失败','密保答案不能为空，请重新输入！',$ret_url);
	}
	//echo $usr_info["question1"];
	if($usr_info["is_setup"]==1){
		if($_REQUEST['myanswer1']!=$usr_info["answer1"]){
			show_error('修改失败','原密保答案不正确，请重新输入！',$ret_url);
		}
	}
	$row= array();
	$row['is_setup'] = 1;
	$row['question1'] =add_slashes($_REQUEST['question1']);
	$row['answer1'] =add_slashes($_REQUEST['answer1']);
	$row['member_id']  =$login_info[2];
	$ret = $db->update($row,DB_PREFIX."member");
	if($ret){
		show_error("修改成功","密码保护修改成功！","member.html");
	}
	else{
		show_error('修改失败','服务器繁忙，请重试！',$ret_url);
	}
}

	
?>