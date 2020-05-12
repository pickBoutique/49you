<?php
/*
creater devil 2010-08-16
*/
include_once('init.inc.php');
header("Content-type: text/css");
if( !empty($_REQUEST['permi_code'])  ){
	
	$rs_check_permi = $db->getRow("SELECT permi_id, permi_action FROM ".DB_PREFIX."permi WHERE permi_code='" . $_REQUEST['permi_code'] . "'");
	if($rs_check_permi){
		$rs_check_permitmp = $db->getRow("SELECT permitmp_allow,permitmp_deny FROM ".DB_PREFIX."permitmp WHERE permitmp_uid='{$login_info[2]}' and permitmp_pid='{$rs_check_permi[permi_id]}'");
		
		if($rs_check_permitmp){
			
			$allow = array();
			$deny=array('editor'=>'border:0px;');
			
			$check_act_arr = explode("\r\n",$rs_check_permi['permi_action']);
			$css = '';
			foreach($check_act_arr as $k => $v){
				$check_val = pow( 2, $k);
				
				$css_allow = '';
				if(isset($allow[$v])){
					$css_allow = $allow[$v];
				}
				$css_deny = 'display:none;';
				if(isset($deny[$v])){
					$css_deny = $deny[$v];
				}
				
				if(($rs_check_permitmp['permitmp_allow'] & $check_val) == $check_val){
					if( ($rs_check_permitmp['permitmp_deny'] & $check_val) != $check_val){
						$css .= ".action_{$v}{ $css_allow }";
					}else{
						$css .= ".action_{$v}{ $css_deny }";
					}
				}else{
					$css .= ".action_{$v}{ $css_deny }";
				}
			}
			
			exit($css);
			
		}else{
			//exit('E@您没有权限进行此操作');
		}
	}
}
?>