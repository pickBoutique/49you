<?php

include_once('init_member.inc.php');
if(empty($act)){
	include_once('templates/pwd_find_by_protect.html');

/*ajax 回调 获取密保问题 */
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
}else if($act=='check_protect'){
	$loginname = add_slashes($_REQUEST['loginname']);
	$answer = add_slashes($_REQUEST['answer']);

	if(!empty($loginname) && !empty($answer)){
		$row = $obj_user->get_user_by_name($loginname);
		if(empty($row['question1'])){
			show_error('提示','请选择密保问题！');
		}
		if($row['answer1'] == $answer ){
			
			$mid = $row['member_id'];
			$time = time();
			$encry_mac = md5(time());
			$mac = md5($row['member_id'].$time.$encry_mac); 
			$obj_user->set_mac($mid,$encry_mac);
			redir("pwd_set_new.html?mid=$mid&time=$time&mac=$mac");
		}else{
			show_error('提示','密保答案错误！');
		}
	}else{
		show_error('提示','帐户与密保答案必须填写正确！');
	}
}
?>