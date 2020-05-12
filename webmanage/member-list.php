<?php
define('PERMI_CODE','user_mgs');
define('CONN_SALVE',true);
include_once('init.inc.php');


if($act == 'dataget'){
	//组建搜索项
	$filter = $_REQUEST['filter'];
	$where = get_where($filter);
	
	//查询数据库
	$page = floor(intval($_REQUEST['start']) / intval($_REQUEST['limit'])) + 1;
	$pager = array( 'page'=>$page, 'size'=>$_REQUEST['limit'],'sort'=>$_REQUEST['sort'],'dir'=>$_REQUEST['dir'],'table'=>DB_PREFIX."member" );
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
	include_once("templates/member-list.html");
	
}
?>
