<?php
define('PERMI_CODE','user_all_mgs');
define('CONN_SALVE',true);
include_once('init.inc.php');


if($act == 'dataget'){
	//组建搜索项
	$where = "";
	$setdate="";
	if(!empty($_REQUEST['filter'])){
		$ret = $json->decode($_REQUEST['filter'],1);
		foreach ($ret as $k => $sub){
			if($sub['name']=='member_name' && trim($sub['value'])!=''){
				$where .= " and ".$sub['name']." like '".$sub['value']."%'";
			}
			if($sub['name']=='add_time' && trim($sub['value'])!=''){
				if($sub['oper']=="<=") $sub['oper']="<";
				$where .= " and ".$sub['name'].$sub['oper']."'".$sub['value']."'";
				$setdate="set";
			}
		}
	}
	if($setdate == ''){$where .= "and add_time>=".(strtotime(date("Y-m-d")));}
	
	$sqlstr="select admin_id,admin_advtype from ".DB_PREFIX."admin where admin_id='".$login_info[2]."'";
	$trs=$db_salve->getRow($sqlstr);
	if($trs["admin_advtype"]>0){
		$where.= " and member_advtype={$trs[admin_advtype]}";
	}else{
		$where.= " and false";
	}
	//查询数据库
	$page = floor(intval($_REQUEST['start']) / intval($_REQUEST['limit'])) + 1;
	$pager = array( 'page'=>$page, 'size'=>$_REQUEST['limit'],'sort'=>$_REQUEST['sort'],'dir'=>$_REQUEST['dir'] );
	$sql = "SELECT *,(select sum(trans_money) from ".DB_PREFIX."trans where member_name = trans_mname and trans_instatus = 1) trans_money FROM ".DB_PREFIX."member WHERE 1 $where ";
	
	

	$rs = $db_salve->getdata($sql, &$pager);
	
	//echo(preg_replace("/\(([^()]|(?R))*\)/i",'aaaa',$sql));
	
	//echo(preg_replace("/select((.*)\(([^()]|(?R))*\)(.*)*)from/i",'select count(*) from',$sql));
	format_namelist_by_id(&$rs,"member_advtype","advtype_name","advtype","advtype_id","advtype_name");
	format_namelist_by_id(&$rs,"member_advid","adv_name","adv","adv_id","adv_name");
	format_namelist_by_id(&$rs,"member_metrid","material_name","material","material_id","material_name");
	$record_count = $pager['count'];
	exit( get_list_json($rs, $record_count ) );

}else{
	
	$page_nav = "会员管理 >> 会员列表";
	include_once("templates/member-list-alliance.html");
	
}
?>
