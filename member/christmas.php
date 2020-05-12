<?php
define('PERMI_CODE','login');
include_once('init_member.inc.php');

if($act=='save_mail'){
	$email = $_REQUEST['email'];
	$mid = $login_info[2];
	if(!empty($email) && !empty($mid)){
		$rs = $db->getRow("select * from ".DB_PREFIX."email where email_mid='$mid' ");
		if(empty($rs)){
			$row = array();
			$row['email_mid']= $mid;
			$row['email_to']= $email;
			$db->insert($row,DB_PREFIX."email");
		}else{
			$db->update($rs,DB_PREFIX."email");
		}
		exit('1');
	}else{
		exit('-1');
	}
	
}

include_once('templates/christmas.html');
?>