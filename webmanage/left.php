<?php
/*
creater devil 2010-08-16
*/
include_once('init.inc.php');

$module_html = '';

if($login_status == SUC_LOGIN){
	
	$sql = "SELECT * FROM ".DB_PREFIX."permitmp WHERE permitmp_uid='{$login_info[2]}' ";
	$rs = $db_admin->getdata($sql);
	$ids = '';
	$spt = '';
	foreach($rs as $key => $val){
		$ids .= $spt . $val['permitmp_uid'];
		$spt = ',';
	}
	
	//权限操作
	$sql = "SELECT * FROM ".DB_PREFIX."permi ";
	$permis = $db_admin->getdata($sql);
	$ids = '';
	$spt = '';
	foreach($permis as $key => $val){
		foreach($rs as $k => $v){
			
			if($v['permitmp_pid'] == $val['permi_id']){
				$act_arr = explode("\r\n",$val['permi_action']);
				$val_index = array_keys($act_arr,'list');
				
				if(!empty($val_index)){
					$val_index = pow( 2, $val_index[0]);
					
					if(($v['permitmp_allow'] & $val_index) == $val_index){
						
						if( ($v['permitmp_deny'] & $val_index) != $val_index){
							
							$ids .= $spt . "'" . $val['permi_code'] . "'";
							$spt = ',';
						}
					}
				}
			}
		}
	}
	
	//顶级模块
	if(!empty($ids)  )
	{
		//获取授权的子节点
		$where = " and (permi_code = '' or permi_code in(".$ids.")) ";
		$sql = "SELECT * FROM ".DB_PREFIX."module WHERE parent_id>0 AND is_active=1 $where ORDER BY sort_num ASC,module_id ASC ";
		$rs_sub = $db_admin->getAll($sql);
		$main_ids = array();
		if($rs_sub){
			
			//筛选需要显示的父节点id
			foreach($rs_sub as $k=>$v){
				if( !in_array($v['parent_id'],$main_ids) ){
					$main_ids[] = $v['parent_id'];
				}
			}
			$main_ids = implode(',',$main_ids);
			
			//查找父节点
			$where = " and module_id in(".$main_ids.") ";
			$sql = "SELECT * FROM ".DB_PREFIX."module WHERE parent_id=0 AND is_active=1 $where ORDER BY sort_num ASC,module_id ASC ";
			$rs = $db_admin->getAll($sql);
			if($rs)
			{
				foreach($rs as $key=>$v)
				{
					$module_html .= "<div><a href=\"###\" onclick=\"toggleMenu('".($key+1)."');\"><img src=\"/images/LeftUnExpand.gif\" id=\"left_img_".($key+1)."\" class=\"left_menu_img\" />&nbsp;".$v['module_name']."</a></div>\n<ul class=\"left_menu\" id=\"left_menu_".($key+1)."\">\n";
					
					//二级模块
					foreach($rs_sub as $val)
					{
						if($v['module_id'] == $val['parent_id']){
							$module_html .= "<li>·<a href=\"###\" onclick=\"selsubitem(this,$val[module_id],'$val[module_name]','$val[module_url]');\" >".$val['module_name']."</a></li>\n";
						}
					}
					
					$module_html .= "</ul>\n";
				}
			}
		}
	}
}
include_once("templates/left.html");


?>