<?php
define('PERMI_CODE','login');
include_once('init_member.inc.php');


if(empty($act)){
	$page = empty($_GET['page'])?1:$_GET['page'];
	$pager = array('page'=>$page, 'size'=> 15,'sort'=>"",'dir'=>"");
	
	$list = $db->getdata("select *,(select sum(transret_currency) from ".DB_PREFIX."transret where transret_recomname=member_name) as ret from ".DB_PREFIX."member where member_recom='{$login_info[2]}' ",&$pager);
	
	//$list = $db->getAll("select *,(select sum(transret_currency) from ".DB_PREFIX."transret on transret_recomname=member_name) as ret from ".DB_PREFIX."member where member_recom='{$login_info[2]}' ");
	include_once('templates/return_record.html');
}

?>