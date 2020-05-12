<?php
include_once('init_member.inc.php');
if(empty($act)){
	include_once('templates/account_appeal.html');
}else if($act=='appeal'){

	$row = array();
	$row['appeal_guser']     =htmlspecialchars(add_slashes($_REQUEST['guser']));
	$row['appeal_idcard']    =htmlspecialchars(add_slashes($_REQUEST['idcard']));
	$row['appeal_game']      =htmlspecialchars(add_slashes($_REQUEST['game']));
	$row['appeal_server']    =htmlspecialchars(add_slashes($_REQUEST['server']));
	$row['appeal_gnickname'] =htmlspecialchars(add_slashes($_REQUEST['gnickname']));
	$row['appeal_glevel']    =htmlspecialchars(add_slashes($_REQUEST['glevel']));
	$row['appeal_email']     =htmlspecialchars(add_slashes($_REQUEST['email']));
	$row['appeal_mobile']    =htmlspecialchars(add_slashes($_REQUEST['mobile']));
	$row['appeal_qq']        =htmlspecialchars(add_slashes($_REQUEST['qq']));
	$row['appeal_qtime']     =htmlspecialchars(add_slashes($_REQUEST['qtime']));
	$row['appeal_rtime']     =htmlspecialchars(add_slashes($_REQUEST['rtime']));
	$row['appeal_ltime']     =htmlspecialchars(add_slashes($_REQUEST['ltime']));
	$row['appeal_regcity']   =htmlspecialchars(add_slashes($_REQUEST['regcity']));
	$row['appeal_tarmoney']  =htmlspecialchars(add_slashes($_REQUEST['tarmoney']));
	$row['appeal_tartype']   =htmlspecialchars(add_slashes($_REQUEST['tartype']));
	$row['appeal_desc']      =htmlspecialchars(add_slashes($_REQUEST['desc']));
	$row['appeal_tartime']   =htmlspecialchars(add_slashes($_REQUEST['tartime']));
	$seccode = htmlspecialchars(add_slashes($_REQUEST['seccode']));
	$ret_url="account_appeal.html?guser={$row['appeal_guser']}&idcard={$row['appeal_idcard']}&game={$row['appeal_game']}&server={$row['appeal_server']}&gnickname={$row['appeal_gnickname']}&glevel={$row['appeal_glevel']}&email={$row['appeal_email']}&mobile={$row['appeal_mobile']}&qq={$row['appeal_qq']}&qtime={$row['appeal_qtime']}&rtime={$row['appeal_rtime']}&ltime={$row['appeal_ltime']}&regcity={$row['appeal_regcity']}&tarmoney={$row['appeal_tarmoney']}&tartype={$row['appeal_tartype']}&desc={$row['appeal_desc']}&tartime={$row['appeal_tartime']}";
	
	if(strtolower($_SESSION['validationcode']) != strtolower($seccode)){
		show_error('注册失败','对不起，验证码错误，请重新输入！',$ret_url);
	}
	//fileField
	$directory = "../uploadfiles/appeal";
	$return_arr = upload_file($_FILES['fileField'],$directory);
	$row['appeal_tarimg']=$return_arr['attachment_url'];
	
	$ret = $db->insert($row,DB_PREFIX."appeal");
	if($ret){
		show_error("提交成功","您的申诉已提交成功，我们会有专人负责跟进，谢谢您对49you平台的支持！","index.html");
	}
	else{
		show_error('提交失败','服务器繁忙，请重试！',$ret_url);
	}
}

?>