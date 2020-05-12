<?php

include_once('init_member.inc.php');

if(empty($act)){
	$mid = intval($_REQUEST['mid']);
	$time = add_slashes($_REQUEST['time']);
	$mac =  add_slashes($_REQUEST['mac']);
	$encry_mac = $obj_user->get_mac($mid);
	if(empty($encry_mac)){
		show_error('错误','该链接已经失效','pwd_find.html');
	}
	$check_mac = md5($mid.$time.$encry_mac); 
	if(time() < strtotime("+10 minute",$time) ){
		if($mac == $check_mac){
			include_once('templates/pwd_set_new.html');
		}else{
			show_error('错误','该链接不合法','pwd_find.html');
		}
	}else{
		show_error('错误','该链接已经失效','pwd_find.html');
	}
}else if($act=='set_pwd'){
	$mid = intval($_REQUEST['mid']);
	$time = add_slashes($_REQUEST['time']);
	$mac =  add_slashes($_REQUEST['mac']);
	$new_pwd = add_slashes($_REQUEST['new_pwd']);
	$re_new_pwd = add_slashes($_REQUEST['re_new_pwd']);
	$encry_mac = $obj_user->get_mac($mid);
	if(empty($encry_mac)){
		show_error('错误','该链接已经失效','pwd_find.html');
	}
	$check_mac = md5($mid.$time.$encry_mac); 
	if(!empty($new_pwd) && !empty($re_new_pwd) ){
		if($new_pwd == $re_new_pwd){
			if(time() < strtotime("+10 minute",$time) ){
				if($mac == $check_mac){
					$info = $obj_user->get_user_by_id($mid);
					$new_pwd = $obj_user->encry_pwd($info['member_name'],$new_pwd);
					$ret = $obj_user->modify_pwd($mid, $new_pwd);
					if($ret){
						$row = array();
						$row['mac'] = '';
						$db->update($row,DB_PREFIX.'member',$mid);
						include_once('templates/pwd_modify_suc.html');
					}else{
						show_error('错误','密码设置失败','pwd_find.html');
					}
					
				}else{
					show_error('错误','该链接不合法','pwd_find.html');
				}
			}else{
				show_error('错误','该链接已经失效','pwd_find.html');
			}
		}else{
			show_error('错误','两次输入的密码不一致');
		}
	}else{
		show_error('提示','密码必须填写');
	}
	
}
?>