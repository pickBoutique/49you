<?php
/**
 * 登录成功
 */
define('SUC_LOGIN',1); 
/**
 * 密码错误
 */
define('ERR_LOGIN_PWD',-1);
/**
 * 帐号未激活
 */
define('ERR_LOGIN_ACTIVE',-2);
/*
*	管理员类
*/
class Admin
{
	/*
	*	获取自动保存于cookie中的会员登录信息
	*	注意：会员密码已经加密过
	*	@return array '0'-会员名称 '1'-已经加密过的会员密码
	*/
	function get_cookie(){
		$ret = array();
		$ret[] = $_COOKIE["auto_admin_name"] ? $_COOKIE["auto_admin_name"] : '';
		$ret[] = $_COOKIE["auto_admin_pwd"] ? $_COOKIE["auto_admin_pwd"] : '';
		return $ret;
	}
	
	
	/*
	*	将会员登录信息保存于cookie中
	*	@member_name string 会员名称
	*	@member_pwd string 使用encry_pwd成员方法加密过的会员密码
	*/
	function set_cookie($member_name,$member_pwd,$timeout=0){
		
		setcookie("auto_admin_name" , $member_name , time()+$timeout);
		setcookie("auto_admin_pwd" , $member_pwd , time()+$timeout);
	}
	
	/*
	*	清除cookie中会员登录信息
	*/
	function clear_cookie(){
		setcookie("auto_admin_name" , '');
		setcookie("auto_admin_pwd" , '');
	}
	
	/*
	*	获取session中会员的登录信息
	*	@return array '0'-是否已登陆 '1'-会员名称 '2'-会员id '3'-会员真实姓名 '4'-用户所有分组id 
	*/
	function get_session(){
		$ret = array();
		$ret[] = $_SESSION['session_admin_login_status'] ? $_SESSION['session_admin_login_status'] : 0;
		$ret[] = $_SESSION['session_admin_name'] ? $_SESSION['session_admin_name'] : '';
		$ret[] = $_SESSION['session_admin_id'] ? $_SESSION['session_admin_id'] : 0;
		$ret[] = $_SESSION['session_admin_truename'] ? $_SESSION['session_admin_truename'] : '';
		$ret[] = $_SESSION['session_admin_group_ids'] ? $_SESSION['session_admin_group_ids'] : 0;
		return $ret;
	}
	
	
	/*
	*	设置会员会话信息
	*	$usrinfo: 要登陆的会员信息
	*/
	function set_session($usrinfo){
		
		$_SESSION['session_admin_login_status'] = 1;
		$_SESSION['session_admin_name'] = $usrinfo['admin_name'];
		$_SESSION['session_admin_id'] = $usrinfo['admin_id'];
		$_SESSION['session_admin_truename'] = $usrinfo['admin_truename'];
		$_SESSION['session_admin_group_ids'] = $usrinfo['admin_group_ids'];
	}
	
	/*
		清除会员会话信息
	*/
	function clear_session(){
		unset($_SESSION['session_admin_login_status']);
		unset($_SESSION['session_admin_name']);
		unset($_SESSION['session_admin_id']);
		unset($_SESSION['session_admin_truename']);
		unset($_SESSION['session_admin_group_ids']);
	}
	

	
	
	/*
	*	加密密码
	*/
	function encry_pwd($member_name, $member_pwd, $key=''){
		return md5($member_pwd);
	}
	
	
	function get_user_by_id($member_id){
		global $db_admin;
		$sql = "SELECT * FROM ".DB_PREFIX."admin WHERE admin_id='".$member_id."' ";
		return $db_admin->getRow($sql);
		
	}
	
	/*
	*	根据指定的用户名获取会员信息
	*	@param string member_name 会员名
	*	@return array 空数组或会员完整信息行
	*/
	function get_user_by_name($member_name){
		global $db_admin;
		$sql = "SELECT * FROM ".DB_PREFIX."admin WHERE admin_name='".$member_name."'";
		return $db_admin->getRow($sql);
	}
	
	/*
	*	会员登录
	*	@param string member_name 用户名
	*	@param string member_pwd 使用encry_pwd成员方法加密过的会员密码
	*	@return array '0'-登陆状态 '1'-会员的完整信息
	*/
	function login($member_name,$member_pwd,$timeout=0){
		global $db_admin;
		
		$ret = array();
		
		if(empty($member_name) || empty($member_pwd) ){
			$ret[] = ERR_LOGIN_PWD;
			$ret[] = array();	
			return $ret;
		}
		
		$rs = $this->get_user_by_name($member_name);
		
		if($rs && $rs['admin_pwd'] == $member_pwd)
		{
			
			if($rs['admin_status'] != '1'){
				$ret[] = ERR_LOGIN_ACTIVE;
				$ret[] = $rs;
				return $ret;
		
			}
			
			//获取用户所有分组id
			$admin_group_ids = '';
			if(!empty($rs['group_id'])){
				
				$admin_group_ids = $rs['group_id'];
				$rs_admin_group = $db_admin->getRow("SELECT group_pid FROM ".DB_PREFIX."admin_group WHERE group_id = '{$rs[group_id]}' ");
				
				while(!empty($rs_admin_group['group_pid']) && $rs_admin_group['group_pid'] > 0 ){
					$admin_group_ids .= ',' . $rs_admin_group['group_pid'];
				
					$rs_admin_group = $db_admin->getRow("SELECT group_pid FROM ".DB_PREFIX."admin_group WHERE group_id = '{$rs_admin_group[group_pid]}' ");
				}
				
			}
			$rs['admin_group_ids'] = $admin_group_ids;
			
			//$this->clear_session();
			//$this->clear_cookie();
			
			$this->set_session($rs);
			if($timeout>0){
				$this->set_cookie($member_name,$member_pwd,$timeout);
			}
			$ret[] = SUC_LOGIN;
			$ret[] = $rs;		
			return $ret;
		}
		else
		{
			
			$ret[] = ERR_LOGIN_PWD;
			$ret[] = array();
			return $ret;	
			
		}
		
	}
	
	/*
	*	检查当前是否已经登录
	*	@return array 'status': 1为已经登录 0为未登录 'login_info':当status为1时返回会员的登录信息,为0时返回空数组
	*	
	*/
	function check_login(){
		list($status) = $this->check_session();
		if($status == 0){
			
			list($cookie_name,$cookie_pwd) = $this->get_cookie();
			if(!empty($cookie_name) && !empty($cookie_pwd)){
				
				list($login_status,$usrinfo) = $this->login($cookie_name,$cookie_pwd);
			}
		}
		return $this->check_session();
	}
	
	/*
	*	检查当前session中的登陆状态
	*/
	function check_session(){
		$ret = array();
		$login_info = $this->get_session();
		if($login_info[0] == SUC_LOGIN){
			$ret[] = 1;
			$ret[] = $login_info;
		}else{
			
			$ret[] = 0;
			$ret[] = array();
		}
		return $ret;
	}
	
	/*
	*	根据保存在cookie中的信息进行自动登录
	*	@return 失败返回false,成功返回完整的会员信息
	*/
	function auto_login(){
		list($status) = $this->check_login();
		if($status == 0){
			list($cookie_name,$cookie_pwd) = $this->get_cookie();
			//exit($cookie_name . '-' . $cookie_pwd);
			list($login_status,$usrinfo) = $this->login($cookie_name,$cookie_pwd);
			if($login_status){
				return $usrinfo;
			}
		}
		
		return false;
	}
	
	/*
	*	清除指定用户的权限缓存
	*/
	function clear_permi_cache($uid){
		global $db_admin;
		$sql = "DELETE FROM ".DB_PREFIX."permitmp WHERE permitmp_uid='{$uid}'";
		$db_admin->query($sql);
	}
	
	/*
	*	重新生成指定用户的权限缓存
	*/
	function update_permi_cache($uid){
		global $db_admin;
		
		$sql = "SELECT count(*) FROM ".DB_PREFIX."permitmp WHERE permitmp_uid='{$uid}' ";
		$rs = $db_admin->getOne($sql);
		if($rs<=0){
			
			$rs = $db_admin->getdata("SELECT * FROM ".DB_PREFIX."permi ");
			
			//获取模块id组
			$ids = '';
			$spt = '';
			foreach($rs as $k =>$v){
				$ids .= $spt . $v['permi_id'];
				$spt = ',';
			}
			
			//获取用户分组id组
			$objids = '';
			$spt = '';
			$id = '';
			$rs_admin_group = array();
			if(!empty($uid)){
				$where = " and admin_id = '{$uid}' ";
				$rs_admin = $db_admin->getRow("SELECT admin_id,group_id FROM ".DB_PREFIX."admin WHERE 1 $where ");
				if($rs_admin){
					$id = $rs_admin['group_id'];
					$objids .= '\'u' . $rs_admin['admin_id'] . '\'' . ',' . '\'g' . $id . '\'';
					$spt = ',';
					$rs_admin_group = $db_admin->getRow("SELECT group_pid FROM ".DB_PREFIX."admin_group WHERE group_id = '{$id}' ");
				}
			}
			
			while(!empty($rs_admin_group['group_pid']) && $rs_admin_group['group_pid'] > 0 ){
				$objids .= $spt . '\'g' . $rs_admin_group['group_pid'] . '\'';
				$spt = ',';
				
				$rs_admin_group = $db_admin->getRow("SELECT group_pid FROM ".DB_PREFIX."admin_group WHERE group_id = '{$rs_admin_group[group_pid]}' ");
			}
		
			
			//查询现有权限设置表
			$where = "  and permiset_pid in ($ids) and permiset_obj in ($objids) ";
			$rs_permiset = $db_admin->getdata("SELECT * FROM ".DB_PREFIX."permiset WHERE 1 $where ");
			
			//组合权限
			foreach($rs as $k => $v){
				$rs[$k]['permiset_allow'] = 0;
				$rs[$k]['permiset_deny'] = 0;
				if($rs_permiset){
					foreach($rs_permiset as $key => $val){
						if($v['permi_id'] == $val['permiset_pid']){
							$rs[$k]['permiset_allow'] = $rs[$k]['permiset_allow'] | $val['permiset_allow'];
							$rs[$k]['permiset_deny'] = $rs[$k]['permiset_deny'] | $val['permiset_deny'];		
						}
					}
				}
			}
			
			foreach($rs as $k => $v){
				$row = array();
				$row['permitmp_uid'] = $uid;
				$row['permitmp_pid'] = $v['permi_id'];
				$row['permitmp_allow'] = $v['permiset_allow'];
				$row['permitmp_deny'] = $v['permiset_deny'];
				
				$db_admin->insert($row,DB_PREFIX."permitmp");
			}
			
		}
		
		
	}
	
	/*
	*	获取分组的下拉列表项
	*/
	function get_group_select($parent_id=0,$selectid=0,$isJSON=false)
	{
		global $db_admin;
		$sql = "SELECT * FROM ".DB_PREFIX."admin_group WHERE group_pid ='".$parent_id."' ORDER BY group_id ASC ";
		$rs = $db_admin->getAll($sql);
		if($rs)
		{
			foreach($rs as $v)
			{
				$str = "";
				for($i=1;$i<=$v['group_level'];$i++)
				{
					$str .= "　";
				}
				$str = $str!=""?$str."┣&nbsp;":"";
				$selected = $v['group_id']==$selectid?"selected":"";
					
				if($isJSON){
					if(!empty($select_html)){
						$select_html .= ',';
					}
					$select_html .= '"' .$str. $v['group_name'] . '":"' . $v['group_id'] . '"';
					
				}else{
					$select_html .= "<option value=\"".$v['group_id']."\" $selected>".$str.$v['group_name']."</option>";
				}
				$sub_html = $this->get_group_select($v['group_id'],$selectid,$isJSON);
				if($isJSON){
					if(!empty($sub_html)){
						$select_html .= ',';
					}
				}
				$select_html .= $sub_html;
			}
		}
		return $select_html;
	}
	
	/*
	*	检查权限
	*   $code=权限编号, $action=动作类型
	*/
	function check_permi($code,$action=''){
		global $db_admin;
		//global $db_admin,$act; 
		list($login_status , $login_info) = $this->check_login();
		
		if($login_status == SUC_LOGIN){
			$rs_check_permi = $db_admin->getRow("SELECT * FROM ".DB_PREFIX."permi WHERE permi_code='" . $code . "'");
			if($rs_check_permi){
				$check_act = $act;
				if(empty($act)){
					$check_act = 'list';
				}
				if($act == 'dataget' || $act == 'show'){
					$check_act = 'list';
				}
				if(!empty($action)){
					$check_act = $action;
				}
				
				$check_act_arr = explode("\r\n",$rs_check_permi['permi_action']);
				$check_val_index = array_keys($check_act_arr,$check_act);
				if(!empty($check_val_index)){
					$check_val = pow( 2, $check_val_index[0]);
					
					$rs_check_permitmp = $db_admin->getRow("SELECT * FROM ".DB_PREFIX."permitmp WHERE permitmp_uid='{$login_info[2]}' and permitmp_pid='{$rs_check_permi[permi_id]}'");
					
					if($rs_check_permitmp){
						
						if(($rs_check_permitmp['permitmp_allow'] & $check_val) == $check_val){
							
							if( ($rs_check_permitmp['permitmp_deny'] & $check_val) != $check_val){
								return true;
							}else{
								return '您被禁止进行此操作';
							}
						}else{
							return '您没有被允许进行此操作';
						}
					}else{
						return '您没有权限进行此操作';
					}
				}else{
					return '非法操作';
				}
			}else{
				return "$code 模块不存在";
			}
		}else{
			return "您尚未登陆";
		}
	}
	
	
}
?>