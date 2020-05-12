<?php
define('PERMI_CODE','login');
include_once('init_member.inc.php');


if(empty($act)){
	//$list = $db->getAll("select * from ".DB_PREFIX."transret where transret_mid='{$login_info[2]}' ");
	$page = empty($_GET['page'])?1:$_GET['page'];
	$pager = array('page'=>$page, 'size'=> 15,'sort'=>"",'dir'=>"");
	$list = $db->getdata("select * from ".DB_PREFIX."transret where transret_mid='{$login_info[2]}'",&$pager);
	include_once('templates/member_return.html');
}

?>