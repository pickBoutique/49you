<?php
define('PERMI_CODE','login');
include_once('init_member.inc.php');
$recom_mail=$db->getRow("select * from ".DB_PREFIX."recommail where recommail_mid='$login_info[2]'");
if(empty($act)){
	//游戏信息
	$games = $db->getAll("select game_id,game_name from ".DB_PREFIX."game where game_status='1' " );
	if(!empty($recom_mail)){
		foreach($games as $k=>$v){
			if($v['game_id']==$recom_mail['recommail_game']){
				$gamename=$v['game_name'];
				break;
			}
		}
	}
	include_once('templates/recom_by_mail.html');
}else{
	$row= array();
	$row['recommail_pwd']  =add_slashes($_REQUEST['pwd']);
	$row['recommail_email']=add_slashes($_REQUEST['email']);
	$row['recommail_game'] =intval($_REQUEST['gameid']);
	$row['recommail_mid']  =$login_info[2];
	$ret_url="recom_by_mail.html?email=$_REQUEST[email]";
	
	if(empty($_REQUEST['email'])){
		show_error('修改失败','请输入您的推广邮箱！',$ret_url);
	}
	
	if(empty($_REQUEST['pwd'])){
		show_error('修改失败','邮箱密码不能为空，请重新输入！',$ret_url);
	}
	
	$mail = explode('@',$row['recommail_email']);
	
	//TODO:验证邮箱密码正确性
	include_once(WEB_ROOT."/include/smtp.class.php");
	$smtp = new SMTP();
	if(@$smtp->Connect('smtp.'.$mail[1])){
		$smtp->Hello('www.49you.com');
		
		if(@$smtp->Authenticate($row['recommail_email'], $row['recommail_pwd'])){
		}else{
			show_error('设置失败','您的邮箱帐号或密码错误！');
		}

		
	}else{
		show_error('设置失败','无法验证您的邮箱所在的服务器');
	}
	
	
	if($act=='add' && empty($recom_mail)){
		$ret = $db->insert($row,DB_PREFIX."recommail");
	}else{
		
		$row['recommail_id']=$recom_mail['recommail_id'];
		$ret = $db->update($row,DB_PREFIX."recommail");
	}
	if($ret){
		
		show_error("修改成功","推广邮箱设置成功！","member.html");
	}
	else{
		show_error('修改失败','服务器繁忙，请重试！',$ret_url);
	}
}
?>