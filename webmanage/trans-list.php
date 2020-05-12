<?php
define('PERMI_CODE','trans_list_msg');
define('CONN_SALVE',true);
include_once('init.inc.php');


if($act == 'dataget'){
	//组建搜索项
	$filter = $_REQUEST['filter'];
	$where = get_where($filter);
	
	//查询数据库
	$page = floor(intval($_REQUEST['start']) / intval($_REQUEST['limit'])) + 1;
	$pager = array( 'page'=>$page, 'size'=>$_REQUEST['limit'],'sort'=>$_REQUEST['sort'],'dir'=>$_REQUEST['dir'] );
	$rs = $db_salve->getdata("SELECT * FROM ".DB_PREFIX."trans a  WHERE 1 $where ", &$pager);
	
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
	
	$code = $_REQUEST['code'];
	if(!empty($code)){	
		
		$start = strtotime(date('Y-m-d',time()));
		$end = strtotime(date('Y-m-d 23:59:59',time()));
		$money = $db->getOne("select trans_money from ".DB_PREFIX."trans  where trans_intime>=$start and  trans_intime<=$end and trans_instatus='1' and trans_type='console' ");
		
		$add_money = $db->getOne("select trans_money from ".DB_PREFIX."trans  where trans_code='$code' ");
	
		if( ($money+$add_money) > 1000 ){
			exit("E@充值失败，已超过本日可充值限额!");
		}else{
			order_paid($code,'console');
		}
	
	}
}else{
	
	$page_nav = "会员管理 >> 交易管理";
	include_once("templates/trans-list.html");
}

?>
