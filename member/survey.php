<?php
define('PERMI_CODE','login');
include_once('init_member.inc.php');
if(empty($act)){
	include_once('templates/survey.html');
}else if($act=='survey'){
	$row = array();
	$usr_info = $obj_user->get_user_by_id($login_info[2]);
	$row['survey_type']='120210';//活动编号
	$row['survey_addtime']=time();
	$row['survey_mid']=$login_info[2];
	$row['survey_mname']=$usr_info['member_name'];
	$row['survey_answer1']=htmlspecialchars(add_slashes($_REQUEST['answer1']));
	$row['survey_answer2']=htmlspecialchars(add_slashes($_REQUEST['answer2']));
	$row['survey_answer3']=htmlspecialchars(add_slashes($_REQUEST['answer3']));
	$row['survey_answer4']=htmlspecialchars(add_slashes($_REQUEST['answer4']));
	$row['survey_other']=htmlspecialchars(add_slashes($_REQUEST['other']));
	
	$sqlstr="select survey_id from ".DB_PREFIX."survey where survey_mid = '{$row[survey_mid]}'";
	$rs_survey = $db->getRow($sqlstr);
	if(!empty($rs_survey)){
		$ret = $db->update($row,DB_PREFIX."survey",$rs_survey["survey_id"]);
	}else{
		$ret = $db->insert($row,DB_PREFIX."survey");
	}
	if($ret){
		show_error("提交成功","感谢您参与此次问卷调查活动，感谢您对49you平台的支持！","index.html");
	}
	else{
		show_error('提交失败','服务器繁忙，请重试！',$ret_url);
	}
}

?>