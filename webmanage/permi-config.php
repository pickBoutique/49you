<?php
/*
creater mwz 2011-01-27
*/
define('PERMI_CODE','permi_mgs');
include_once('init.inc.php');

$actions = $db_admin->getdata("SELECT * FROM ".DB_PREFIX."permiaction ");
$json_actions = $json->encode($actions);

if($act == 'dataget'){
	
	$uid = $_REQUEST['uid'];
	$gid = $_REQUEST['gid'];
	
	//组建搜索项
	$filter = $_REQUEST['filter'];
	$where = get_where($filter);
	
	//查询数据库
	$page = floor(intval($_REQUEST['start']) / intval($_REQUEST['limit'])) + 1;
	$pager = array( 'page'=>$page, 'size'=>$_REQUEST['limit'],'sort'=>$_REQUEST['sort'],'dir'=>$_REQUEST['dir'] );
	$rs = $db_admin->getdata("SELECT * FROM ".DB_PREFIX."permi WHERE 1 $where ", &$pager);
	$record_count = $pager['count'];
	
	//获取模块id组
	$ids = '';
	$spt = '';
	foreach($rs as $k =>$v){
		$ids .= $spt . $v['permi_id'];
		$spt = ',';
	}
	
	//获取用户分组id组
	$objids = '';
	$last_obj_ids  = array();
	$spt = '';
	$id = '';
	$rs_admin_group = array();
	if(!empty($uid)){
		$where = " and admin_id = '{$uid}' ";
		$rs_admin = $db_admin->getRow("SELECT admin_id,group_id FROM ".DB_PREFIX."admin WHERE 1 $where ");
		if($rs_admin){
			$id = $rs_admin['group_id'];
			$objids .= '\'u' . $rs_admin['admin_id'] . '\'';
			$spt = ',';
			$rs_admin_group = $db_admin->getRow("SELECT group_id,group_pid FROM ".DB_PREFIX."admin_group WHERE group_id = '{$id}' ");
		}
	}
	if(!empty($gid)){
		$rs_admin_group = $db_admin->getRow("SELECT group_id,group_pid FROM ".DB_PREFIX."admin_group WHERE group_id = '{$gid}' ");
	}
	//循环获取所有上级的id
	while(!empty($rs_admin_group['group_id']) && $rs_admin_group['group_id'] > 0 ){
		
		if($gid != $rs_admin_group['group_id']){
			$last_obj_ids[] = 'g' . $rs_admin_group['group_id'];
		}
		$objids .= $spt . '\'g' . $rs_admin_group['group_id'] . '\'';
		$spt = ',';
		$rs_admin_group = $db_admin->getRow("SELECT group_id,group_pid FROM ".DB_PREFIX."admin_group WHERE group_id = '{$rs_admin_group[group_pid]}' ");
	}
			
	
	
	//查询现有权限设置表
	$where = "  and permiset_pid in ($ids) and permiset_obj in ($objids) ";
	$rs_permiset = $db_admin->getdata("SELECT * FROM ".DB_PREFIX."permiset WHERE 1 $where ");
	
	//组合权限
	foreach($rs as $k => $v){
		$rs[$k]['permiset_allow'] = 0;
		$rs[$k]['permiset_deny'] = 0;
		$rs[$k]['last_permiset_allow'] = 0;
		$rs[$k]['last_permiset_deny'] = 0;
		//echo($v['permi_id']);
		foreach($rs_permiset as $key => $val){
			if($v['permi_id'] == $val['permiset_pid']){
				$rs[$k]['permiset_allow'] = $rs[$k]['permiset_allow'] | $val['permiset_allow'];
				$rs[$k]['permiset_deny'] = $rs[$k]['permiset_deny'] | $val['permiset_deny'];
				
				if( in_array( $val['permiset_obj'] ,$last_obj_ids) ){
					
					$rs[$k]['last_permiset_allow'] = $rs[$k]['last_permiset_allow'] | $val['permiset_allow'];
					$rs[$k]['last_permiset_deny'] = $rs[$k]['last_permiset_deny'] | $val['permiset_deny'];
				}
			}
		}
	}
	
	
	exit( get_list_json($rs, $record_count ) );

}else if($act == 'configpermi'){
	$field = $_REQUEST['field'];
	$value = intval($_REQUEST['value']);
	$pid = intval($_REQUEST['pid']);
	$uid = intval($_REQUEST['uid']);
	$gid = intval($_REQUEST['gid']);
	$where = '';
	if($uid > 0){
		$where .= " and permiset_obj = 'u{$uid}' ";
	}
	
	if($gid > 0){
		$where .= " and permiset_obj = 'g{$gid}' ";
	}
	
	if($pid > 0){
		$where .= " and permiset_pid = '{$pid}' ";
	}
	
	if( ( $gid > 0 || $uid > 0 ) && $pid > 0){
		$rs = $db_admin->getdata("SELECT * FROM ".DB_PREFIX."permiset WHERE 1 $where ");
		if($rs){
			$rs[0][$field] = $rs[0][$field] ^ $value;
			$ret = $db_admin->update($rs[0],DB_PREFIX."permiset");
			if(!$ret){
				exit('E@更新到数据库时发生错误');
			}
		}else{
			$rs[0][$field] = $value;
			$rs[0]['permiset_obj'] = $uid > 0 ? "u{$uid}" : "g{$gid}";
			$rs[0]['permiset_pid'] = $pid;
			$ret = $db_admin->insert($rs[0],DB_PREFIX."permiset");
			if(!$ret){
				exit('E@添加到数据库时发生错误');
			}
		}
	}else{
		exit('E@非法操作');
	}
}else{
	
	include_once("templates/permi-config.html");
	
}
?>