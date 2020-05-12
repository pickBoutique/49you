<?php
define('PERMI_CODE','trans_list_all_msg');
include_once('init.inc.php');

$cfg_pay_type['console'] = '后台充值';
if($act == 'dataget'){
	//组建搜索项
	$filter = $_REQUEST['filter'];
	$where = get_where($filter);

	$sqlstr="select admin_id,admin_advtype from ".DB_PREFIX."admin where admin_id='".$login_info[2]."'";
	$trs=$db->getRow($sqlstr);
	if($trs["admin_advtype"]>0){
		$where.= " and trans_advtype={$trs[admin_advtype]}";
	}else{
		$where.= " and false";
	}
	
	//查询数据库
	$page = floor(intval($_REQUEST['start']) / intval($_REQUEST['limit'])) + 1;
	$pager = array( 'page'=>$page, 'size'=>$_REQUEST['limit'],'sort'=>$_REQUEST['sort'],'dir'=>$_REQUEST['dir'] );
	$rs = $db->getdata("SELECT * FROM ".DB_PREFIX."trans a  WHERE trans_instatus=1 $where ", &$pager);
	
	$record_count = $pager['count'];
	exit( get_list_json($rs, $record_count ) );

}else if($act == 'topay' ){
	$code = $_REQUEST['code'];
	if(!empty($code)){
		//用于测试充值
		//order_paid($code,'console');
		
		//游戏到款
		order_exchange($code);
	}
}else if($act == 'toconsolepay' ){
	//$db_salve->getRow("select * from ".DB_PREFIX."trans  where trans_name
	
	$code = $_REQUEST['code'];
	if(!empty($code)){
		//用于测试充值
		order_paid($code,'console');
		
		//游戏到款
		//order_exchange($code);
	}
}else{
	
	$page_nav = "会员管理 >> 充值管理";
	include_once("templates/trans-list-alliance.html");
}

?>
