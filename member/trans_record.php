<?php
define('PERMI_CODE','login');
include_once('init_member.inc.php');


if(empty($act)){
	$page = empty($_GET['page'])?1:$_GET['page'];
	$pager = array('page'=>$page, 'size'=> 15,'sort'=>"trans_addtime",'dir'=>"desc");
	$list = $db->getdata("select * from ".DB_PREFIX."trans where trans_mname='{$login_info[1]}'",&$pager);
	//$list = $db->getAll("select * from ".DB_PREFIX."trans where trans_mname='{$login_info[1]}' order by trans_addtime desc  ");
	include_once('templates/trans_record.html');
}

?>