<?php
define('PERMI_CODE','popupcost_msg');
include_once('init.inc.php');

if($act == 'dataget'){
	//组建搜索项
	$filter = $_REQUEST['filter'];
	$where = get_where($filter);
	
	//查询数据库
	$page = floor(intval($_REQUEST['start']) / intval($_REQUEST['limit'])) + 1;
	$pager = array( 'page'=>$page, 'size'=>$_REQUEST['limit'],'sort'=>$_REQUEST['sort'],'dir'=>$_REQUEST['dir'] );
	$rs = $db->getdata("SELECT adv_id,adv_sort,adv_name,adv_cost FROM ".DB_PREFIX."adv a WHERE 1=1 $where ", &$pager);
	//format_namelist_by_id($rs,$id,$name,$tb,$idfiled,$namefield)
	//(原数据表，对应的ID，需要关联的字段，需要关联的表名，对应的ID名，对应的字段名)
	
	$record_count = $pager['count'];
	exit( get_list_json($rs, $record_count ) );
	
}else if($act == 'editor'){
	$arr = array('adv_cost');
    $name = $_REQUEST['name'];
	$id = $_REQUEST['id'];
	$value = $_REQUEST['value'];
	if(in_array($name,$arr)){
		$row = array();
		$row[$name] = trim($value);
		$ret = $db_admin->update($row,DB_PREFIX."adv",$id);
		if($ret)
		{
			exit('1');
		}
		else
		{
			exit("E@修改失败");
		}
	}
}else{
	
	$page_nav = "广告管理 >> 弹窗成本表";
	include_once("templates/popupcost-list.html");
	
}

?>
