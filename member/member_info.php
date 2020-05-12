<?php
define('PERMI_CODE','login');
include_once('init_member.inc.php');
if(empty($act)){
	$row= array();
	if(empty($_REQUEST['posbak'])){
		$row=$obj_user->get_user_by_id($login_info[2]);
		$row['birth'] = date('Y-m-d',$row['birth']);
	}else{
		$row['member_nickname'] =$_REQUEST['nickname'];
		$row['sex']             =$_REQUEST['sex'];
		$row['birth']           =$_REQUEST['birth'];
		$row['education']       =$_REQUEST['education'];
		$row['job']             =$_REQUEST['job'];
		$row['mobile']          =$_REQUEST['mobile'];
		$row['address_cn']      =$_REQUEST['address_cn'];
	}
	include_once('templates/member_info.html');
}else if($act == 'edit'){
	$row= array();
	$row['member_nickname'] =trim($_REQUEST['nickname']);
	$row['sex']             =$_REQUEST['sex'];
	$row['birth']           =strtotime($_REQUEST['birth']);
	$row['education']       =$_REQUEST['education'];
	$row['job']             =$_REQUEST['job'];
	$row['mobile']          =$_REQUEST['mobile'];
	$row['address_cn']      =$_REQUEST['address_cn'];
	$row['member_id']       =$login_info[2];
	$ret = $db->update($row,DB_PREFIX."member");
	$ret_url="member_info.html?nickname=$_REQUEST[nickname]&sex=$_REQUEST[sex]&birth=$_REQUEST[birth]&education=$_REQUEST[education]&job=$_REQUEST[job]&mobile=$_REQUEST[mobile]&address_cn=$_REQUEST[address_cn]&posbak=1";
	if($ret){
		show_error("修改成功","资料修改成功","member.html");
	}
	else{
		show_error('注册失败','服务器繁忙，请重试！',$ret_url);
	}
	
	
}
?>