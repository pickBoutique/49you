<?php
include_once('init_member.inc.php');
$obj_user->clear_cookie();
$obj_user->clear_session();
$returl = '/index.html';
if(!empty($_REQUEST['returl'])){
	$returl = $_REQUEST['returl'];
}

//同步登陆到UC
$synlogout_script = '';
if(defined('UC_API')){
	require_once WEB_ROOT.'/uc_client/client.php';
	$synlogout_script = uc_user_synlogout();
}
$script = $synlogout_script . "<script type=\"text/javascript\">top.location.href=\"$returl\";</script>";
exit($script);

?>