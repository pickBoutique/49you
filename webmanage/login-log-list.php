<?php
define('PERMI_CODE','login_log_msg');
define('CONN_SALVE',true);
include_once('init.inc.php');

$cfg_log_status = array('1'=>'登录成功','-1'=>'密码错误','-2'=>'帐号已存在','-3'=>'帐号已存在','-4'=>'帐号不存在');
if($act == 'dataget'){
	//组建搜索项
	$filter = $_REQUEST['filter'];
	$where = get_where($filter);
	//查询数据库
	$page = floor(intval($_REQUEST['start']) / intval($_REQUEST['limit'])) + 1;
	$pager = array( 'page'=>$page, 'size'=>$_REQUEST['limit'],'sort'=>$_REQUEST['sort'],'dir'=>$_REQUEST['dir'] );
	$rs = $db_salve->getdata("SELECT * FROM ".DB_PREFIX."login_log a  WHERE 1 $where ", &$pager);
	
	$record_count = $pager['count'];
	exit( get_list_json($rs, $record_count ) );

}else{
	
	$page_nav = "会员管理 >> 会员登陆日志";
	include_once("templates/login-log-list.html");
	
}

?>
