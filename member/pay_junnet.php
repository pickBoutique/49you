<?php
include_once('init_member.inc.php');
$gid = intval($_REQUEST['gid']);
$sid = intval($_REQUEST['sid']);

if(empty($act)){
	$usr_info = $obj_user->get_user_by_id($login_info[2]);

	$level = $db->getRow("SELECT * FROM " .DB_PREFIX."member_level where level_id='{$usr_info[member_level]}' ");
	
	//充值问题
	$sqlstr="select info_id,subtitle,source from ".DB_PREFIX."info where info_start<=".time()." and cate_id in (48) order by add_time desc limit 0,7";
	$rs_news = $db->getAll($sqlstr);
	
	include_once('templates/pay_junnet.html');
/*兑换下单 提交*/
}
?>