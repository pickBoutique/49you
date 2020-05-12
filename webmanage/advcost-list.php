<?php
define('PERMI_CODE','advcost_msg');
include_once('init.inc.php');

if($act == 'dataget'){
	//组建搜索项
	$where="";
	$where2="";
	$filter = $_REQUEST['filter'];
	if(!empty($filter)){
		$ret = $json->decode($filter,1);
		foreach ($ret as $k => $sub){
			if($sub['name']=='adv_advtype' && trim($sub['value'])!=''){
				$where2 .= " and adv_type".$sub['oper']."'".$sub['value']."'";
			}
			if($sub['name']=='adv_advid' && trim($sub['value'])!=''){
				$where2 .= " and adv_id".$sub['oper']."'".$sub['value']."'";
			}
			if($sub['name']=='adv_date' && trim($sub['value'])!=''){
				$where .= " and advcost_start='".$sub['value']."' ";
			}
			if($sub['name']=='adv_system' && trim($sub['value'])!='' ){
				$where2 .=" and a.adv_system='{$sub[value]}' ";
			}
		}
	}
	
	//查询数据库
	$page = floor(intval($_REQUEST['start']) / intval($_REQUEST['limit'])) + 1;
	$pager = array( 'page'=>$page, 'size'=>$_REQUEST['limit'],'sort'=>$_REQUEST['sort'],'dir'=>$_REQUEST['dir'] );
	$rs = $db_admin->getdata("SELECT a.adv_id,a.adv_sort,a.adv_name,IFNULL(b.advcost_startcost,0) advcost_startcost,advcost_id,advcost_start FROM ".DB_PREFIX."adv a left join ".DB_PREFIX."advcost b on a.adv_id=b.advcost_advid $where WHERE 1 $where2 ", &$pager);
	//format_namelist_by_id($rs,$id,$name,$tb,$idfiled,$namefield)
	//(原数据表，对应的ID，需要关联的字段，需要关联的表名，对应的ID名，对应的字段名)
	
	$record_count = $pager['count'];
	exit( get_list_json($rs, $record_count ) );

}else{
	
	$page_nav = "广告管理 >> 广告成本表";
	include_once("templates/advcost-list.html");
	
}

?>
