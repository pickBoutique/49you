<?php
define('PERMI_CODE','adv_mgs');
include_once('init.inc.php');


if($act == 'dataget'){
	//组建搜索项
	$filter = $_REQUEST['filter'];
	$where = get_where($filter);
	
	//查询数据库
	$page = floor(intval($_REQUEST['start']) / intval($_REQUEST['limit'])) + 1;
	$pager = array( 'page'=>$page, 'size'=>$_REQUEST['limit'],'sort'=>$_REQUEST['sort'],'dir'=>$_REQUEST['dir'] );
	$rs = $db_admin->getdata("SELECT * FROM ".DB_PREFIX."adv a left join ".DB_PREFIX."advtype b on a.adv_type=b.advtype_id left join ".DB_PREFIX."material c on a.adv_metrid=c.material_id WHERE 1 AND adv_system='{$system_id}' $where ", &$pager);

	//format_namelist_by_id(&$rs,"adv_gid","game_name","game","game_id","game_name");
	//format_namelist_by_id(&$rs,"adv_sid","server_name","server","server_id","server_name");
	format_fields_by_id($db_admin,&$rs,'adv_groupid',DB_PREFIX.'advgroup','advgroup_id',array('advgroup_name'=>'advgroup_name'));
	
	if(!empty($rs)){
		foreach($rs as $k => $v){
			foreach($system as $key => $val){
				if($v['adv_pid'] == $val['id']){
					$rs_all = $db_admin->getAll("SELECT game_name,server_name FROM ".$val['master']['DB_DATABASE'].".".DB_PREFIX."server left join ".$val['master']['DB_DATABASE'].".".DB_PREFIX."game on server_gid=game_id WHERE  server_id=". $v['adv_sid'],3600 * 40);
					if(!empty($rs_all)){
						$rs[$k]['platform_name'] = $val['name'];
						$rs[$k]['game_name'] = $rs_all[0]['game_name'];
						$rs[$k]['server_name'] = $rs_all[0]['server_name'];
					}
				}
			}
		}
	}
	
	$record_count = $pager['count'];
	exit( get_list_json($rs, $record_count ) );

}else{
	
	$page_nav = "广告管理 >> 广告列表";
	include_once("templates/adv-list.html");
	
}
?>
