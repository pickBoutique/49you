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
/**
 * 帐号已存在
 */
define('ERR_REG_EXISTS',-3);
/**
 * 帐号不存在
 */
define('ERR_LOGIN_NOT_EXISTS',-4);
/**
 * SQL执行错误
 */
define('ERR_SQL_QUERY',-5);
/*
*	会员类
*/
class User
{
	/*
	*	获取自动保存于cookie中的会员登录信息
	*	注意：会员密码已经加密过
	*	@return array '0'-会员名称 '1'-已经加密过的会员密码
	*/
	function get_cookie(){
		$ret = array();
		$ret[] = $_COOKIE["auto_user_name"] ? $_COOKIE["auto_user_name"] : '';
		$ret[] = $_COOKIE["auto_user_pwd"] ? $_COOKIE["auto_user_pwd"] : '';
		return $ret;
	}
	
	
	/*
	*	将会员登录信息保存于cookie中
	*	@member_name string 会员名称
	*	@member_pwd string 使用encry_pwd成员方法加密过的会员密码
	*/
	function set_cookie($member_name,$member_pwd,$timeout=0){
		setcookie("auto_user_name" , $member_name , time()+$timeout);
		setcookie("auto_user_pwd" , $member_pwd , time()+$timeout);
	}
	
	/*
	*	清除cookie中会员登录信息
	*/
	function clear_cookie(){
		setcookie("auto_user_name" , '');
		setcookie("auto_user_pwd" , '');
	}
	
	/*
	*	获取session中会员的登录信息
	*	@return array '0'-是否已登陆 '1'-会员名称 '2'-会员id '3'-会员等级 '4'-会员的帐户余额 '5'-会员邮箱
	*/
	function get_session(){
		$ret = array();
		$ret[] = $_SESSION['web_member_login_status'] ? $_SESSION['web_member_login_status'] : 0;
		$ret[] = $_SESSION['web_member_name'] ? $_SESSION['web_member_name'] : '';
		$ret[] = $_SESSION['web_member_id'] ? $_SESSION['web_member_id'] : 0;
		$ret[] = $_SESSION['web_member_level'] ? $_SESSION['web_member_level'] : 0;
		$ret[] = $_SESSION['web_member_money'] ? $_SESSION['web_member_money'] : 0;
		$ret[] = $_SESSION['web_member_email'] ? $_SESSION['web_member_email'] : '';
		$ret[] = $_SESSION['web_member_active'] ? $_SESSION['web_member_active'] : 0;
		return $ret;
	}
	
	
	/*
	*	设置会员会话信息
	*	$usrinfo: 要登陆的会员信息
	*/
	function set_session($usrinfo){
		
		$_SESSION['web_member_login_status'] = 1;
		$_SESSION['web_member_name'] =  $usrinfo['member_name'] ;
		$_SESSION['web_member_id'] = $usrinfo['member_id'];
		$_SESSION['web_member_level'] = $usrinfo['member_level'];//getMemberMsgCount($usrinfo['member_id']);
		$_SESSION['web_member_money'] = $usrinfo['money'];
		$_SESSION['web_member_email'] = $usrinfo['email'];
		$_SESSION['web_member_active'] = $usrinfo['member_active'];
		
	}
	
	/*
		清除会员会话信息
	*/
	function clear_session(){
		unset($_SESSION['web_member_login_status']);
		unset($_SESSION['web_member_name']);
		unset($_SESSION['web_member_id']);
		unset($_SESSION['web_member_level']);
		unset($_SESSION['web_member_money']);
		unset($_SESSION['web_member_email']);
		unset($_SESSION['web_member_active']);
	}
	

	
	
	/*
	*	加密密码
	*	@member_name string 登陆帐号
	*	@member_pwd  string	登陆密码
	*	@key         string 加密密钥-保留
	*	@return		 string	加密过的帐码
	*/
	function encry_pwd($member_name, $member_pwd, $key=''){
		return md5($member_pwd);
	}
	
	
	function get_user_by_id($member_id){
		global $db;
		$sql = "SELECT * FROM ".DB_PREFIX."member WHERE member_id='".$member_id."' ";
		return $db->getRow($sql);
		
	}
	
	/*
	*	根据指定的用户名获取会员信息
	*	@param string member_name 会员名
	*	@return array 空数组或会员完整信息行
	*/
	function get_user_by_name($member_name){
		global $db;
		$sql = "SELECT * FROM ".DB_PREFIX."member WHERE member_name='".$member_name."' ";
		return $db->getRow($sql);
	}
	
	/*
	*  修改密码
	*  @mid_or_name int or string  会员id或会员名称
	*/
	function modify_pwd($mid_or_name, $pwd){
		global $db;
		if(intval($mid_or_name) > 0){
			return $db->update( array( 'member_pwd'=>$pwd ), DB_PREFIX.'member',$mid_or_name);
		}else{
			$row = $db->getRow(" SELECT member_id FROM ".DB_PREFIX."member WHERE member_name='{$mid_or_name}' ");
			return $db->update( array( 'member_pwd'=>$pwd ), DB_PREFIX.'member', $row['member_id']);
		}
	}
	
	/*
	*  激活帐号
	*  @mid_or_name int or string  会员id或会员名称
	*	
	*/
	function active($mid_or_name){
		global $db;
		if(intval($mid_or_name) > 0){
			return $db->update( array( 'member_status'=>'1' ), DB_PREFIX.'member',$mid_or_name);
		}else{
			$row = $db->getRow(" SELECT member_id FROM ".DB_PREFIX."member WHERE member_name='{$mid_or_name}' ");
			return $db->update( array( 'member_status'=>'1' ), DB_PREFIX.'member', $row['member_id']);
		}
	}
	
	/*
	*	会员登录
	*	@param string member_name 用户名
	*	@param string member_pwd 使用encry_pwd成员方法加密过的会员密码
	*	@param string dir_login 直接登陆 false-需要密码  true=不需要密码直接登陆
	*	@return array '0'-登陆状态 '1'-会员的完整信息
	*/
	function login($member_name,$member_pwd='',$dir_login=false,$timeout=0){
		global $db,$config;
		
		$ret = array();
		
		if($dir_login == false){
			if(empty($member_name) || empty($member_pwd) ){
				$ret[] = ERR_LOGIN_NOT_EXISTS;
				$ret[] = array();
				return $ret;
			}
		}
		
		$rs = $this->get_user_by_name($member_name);
		if($rs)
		{
			if( ($dir_login == false && $rs['member_pwd'] == $member_pwd) || $dir_login ){
			
				if($rs['member_status'] != '1'){
					$this->add_login_log($member_name,$member_pwd,ERR_LOGIN_ACTIVE);
					$ret[] = ERR_LOGIN_ACTIVE;
					$ret[] = $rs;
					return $ret;
			
				}
				
				//登陆成功，增加活跃天数、记录当前ip、登陆时间
				$upd_row = array();
				$upd_row['last_time']=time();
				$upd_row['last_ip'] = get_client_ip();
				$upd_row['login_count'] = $rs['login_count'] + 1;
				
				$today = date('Y-m-d');
				$lastday = date('Y-m-d', $rs['last_time'] );
				$regtime = date('Y-m-d', $rs['add_time']);
				$active_time = strtotime("$regtime +7 day"); 
				//当前时间少于活跃时间（注册后7天内），并且登陆时间与上次登陆时间日期不一致则增加一天活跃数
				if($today != $lastday && (time() <= $active_time) ){
					$upd_row['login_day'] = $rs['login_day'] + 1;
					
					
					if(intval($rs['member_recom']) > 0){
						//活跃天数是否到达可赠送推荐人积分的限制
						if(intval($config['recom_active_member']) > 0 && $upd_row['login_day'] == $config['recom_active_member']){	
							add_integral($rs['member_recom'] ,'2', $config['recom_member_integral'] , '', $rs['member_id']);
						}else{
							if($upd_row['login_day']=='1'){
								add_integral($rs['member_recom'] ,'2', $config['recom_member_integral'] , '', $rs['member_id']);
							}
						}
					}
					
				}
				
				
				$db->update( $upd_row, DB_PREFIX.'member', $rs['member_id']);
				
				update_level($rs['member_id']);
				
				$this->clear_session();
				$this->clear_cookie();
				
				$this->set_session($rs);
				if($timeout>0){
					$this->set_cookie($rs['member_name'],$rs['member_pwd'],$timeout);
				}
				$this->add_login_log($member_name,$member_pwd,SUC_LOGIN);
				$ret[] = SUC_LOGIN;
				$ret[] = $rs;		
				return $ret;
			}else{
				$this->add_login_log($member_name,$member_pwd,ERR_LOGIN_PWD);
				$ret[] = ERR_LOGIN_PWD;
				$ret[] = array();
				return $ret;
			}
		}
		else
		{
			$this->add_login_log($member_name,$member_pwd,ERR_LOGIN_NOT_EXISTS);
			$ret[] = ERR_LOGIN_NOT_EXISTS;
			$ret[] = array();
			return $ret;	
			
		}
		
	}
	
	/*
	*	检查当前是否非法用户，清除SESSION，COOKIES
	*	@return array 'status': true 已禁止登陆 false 可正常登陆
	*	
	*/
	function check_banlogin($mid){
		global $db;
		$list = $db->getAll("select * from ".DB_PREFIX."member_banlogin where bg_mid='$mid' or bg_mid='0' ");
		if(!empty($list)){
			foreach($list as $key => $row){
				if($row["bg_bantime"]>time()){
					if($row["bg_ip"]==get_client_ip() || $row["bg_ip"]==""){
						$this->clear_session();
						$this->clear_cookie();
						return true;
					}
				}else{
					/* 删除已过期的策略 */
					$db->delete($row,DB_PREFIX."member_banlogin");
				}
			}
		}
		return false;
	}
	
	/*
	*	检查当前是否已经登录
	*	@return array 'status': 1为已经登录 0为未登录 'login_info':当status为1时返回会员的登录信息,为0时返回空数组
	*	
	*/
	function check_login(){
		list($status) = $this->check_session();
		if($status == 0){
			//list($cookie_name,$cookie_pwd) = $this->get_cookie();
			//if(!empty($cookie_name) && !empty($cookie_pwd)){
				//list($login_status,$usrinfo) = $this->login($cookie_name,$cookie_pwd);
			//}
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
		list($status) = $this->check_session();
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
	*	用户注册
	*	
	*/
	function register($reg_info){
		global $db,$config;
		
		
		$info = array();
		//必填字段
		$info['member_name'] = trim($reg_info['member_name']); //用户名
		
		$info['email'] = trim($reg_info['email']); //email
		$info['member_pwd'] = $reg_info['member_pwd']; //使用user->encry_pwd方法加密后的密码
		$info['sex'] = intval($reg_info['sex']); //性别
		$info['birth'] = intval($reg_info['birth']); //出生年月
		$info['member_nickname'] = trim($reg_info['member_nickname']); //昵称
		$info['member_level'] = intval($config['new_member_level']); //新会员默认等级
		
		
		//防沉迷验证
		if( !empty($reg_info['member_truename']) && !empty($reg_info['member_idcard']) ){
			$info['member_idvalid'] = '1';
			$info['member_truename'] = trim($reg_info['member_truename']); //昵称
			$info['member_idcard'] = trim($reg_info['member_idcard']); //身份证号
		}
		
		//记录推荐人id及用户名
		if( !empty($reg_info['member_recom']) && empty($reg_info['member_reomname']) ){
			$row_recom = $this->get_user_by_id($reg_info['member_recom']);
			$info['member_recom'] = intval($row_recom['member_id']);
			$info['member_reomname'] = $row_recom['member_name'];
			
		}
		
		if( empty($reg_info['member_recom']) && !empty($reg_info['member_reomname']) ){
			$row_recom = $this->get_user_by_name($reg_info['member_reomname']);
			$info['member_recom'] = intval($row_recom['member_id']);
			$info['member_reomname'] = $row_recom['member_name'];
		}
		
		$info['member_advtype'] = intval($reg_info['member_advtype']);//注册渠道
		$info['member_advid'] = intval($reg_info['member_advid']); //注册广告
		$info['member_metrid'] = intval($reg_info['member_metrid']); //注册素材
		
		$info['mobile'] = trim($reg_info['mobile']);//手机电话
		$info['education'] = intval($reg_info['education']);//教育情况
		$info['account_type'] = trim($reg_info['account_type']);//子渠道
		$info['fax'] = trim($reg_info['fax']);//传真
		
		//可选字段
		$info['member_status'] = '1'; //启用状态 0-未启用 1-默认已启用
		$info['member_gamename'] = !empty($reg_info['member_gamename']) ? trim($reg_info['member_gamename']) : $info['member_name'];  //游戏中的帐号
		$info['member_active'] = isset($reg_info['member_active']) ? intval($reg_info['member_active']) : '1'; //激活状态 0-未激活 1-已激活
		$info['address_cn'] = $reg_info['address_cn']; //详细地址
		$info['postcode'] = $reg_info['postcode'];  //邮编
		//$info['account_type'] = $reg_info['account_type'];  //合作网站帐号类型
		//$info['account'] = $reg_info['account']; //合作网站帐号
		$info['add_time'] = time();
		/*
		$where = " and member_name='". $info['member_name'] ."' ";
		$sql = "SELECT * FROM ".DB_PREFIX."member WHERE 1 $where";
		$rs = $db->getRow($sql);
		*/
		$mid = 0;
		if($this->check_member_name($info['member_name'])!=1 || $this->check_member_gamename($info['member_gamename'])!=1 )
		{
			return ERR_REG_EXISTS;
		}
		
		if($mid==0){
			$ret = $db->insert($info,DB_PREFIX."member");
			if($ret){
				$mid = $db->get_insertid();
			}else{
				return ERR_SQL_QUERY;
			}
		}else{
			$ret = $db->update($info,DB_PREFIX."member",$mid);
			if($ret<=0){
				return ERR_SQL_QUERY;
			}
		}
		
		//注册成功 返回member_id
		return $mid;
		
	}
	
	
	/*
	*	用户重新激活
	*	
	*/
	function rereg($reg_info){
		global $db,$config;
		
		
		$info = array();
		//必填字段
		$info['member_name'] = trim($reg_info['member_name']); //用户名
		
		$info['email'] = trim($reg_info['email']); //email
		$info['member_pwd'] = $reg_info['member_pwd']; //使用user->encry_pwd方法加密后的密码

		//防沉迷验证
		if( !empty($reg_info['member_truename']) && !empty($reg_info['member_idcard']) ){
			$info['member_idvalid'] = '1';
			$info['member_truename'] = trim($reg_info['member_truename']); //昵称
			$info['member_idcard'] = trim($reg_info['member_idcard']); //身份证号
		}
		

		
		//可选字段
		$info['member_active'] = '1'; //激活状态 0-未激活 1-已激活
		
		$mid = 0;
		if($this->check_member_name($info['member_name'])!=1 || $this->check_member_gamename($info['member_name'])!=1 )
		{
			return ERR_REG_EXISTS;
		}
		
		
		$ret = $db->update($info,DB_PREFIX."member",$reg_info['member_id']);
		if($ret<=0){
			return ERR_SQL_QUERY;
		}

		
		//激活成功
		return $reg_info['member_id'];
		
	}
	
	function set_mac($mid,$mac=''){
		global $db;
		$db->update( array('mac'=>$mac) ,DB_PREFIX.'member',$mid);
	}
	
	function get_mac($mid){
		global $db;
		$mac = $db->getOne("select mac from ".DB_PREFIX."member where member_id='$mid' ");
		return $mac;
	}
	/*
	*	检查会员名是否可用
	*	@return 1 成功
	*/
	function check_member_name($member_name){
		global $db;
		if(in_array($member_name,explode(",",$config['member_name_save']))){
			return ERR_REG_EXISTS;
		}
		$where = " and member_name='". $member_name ."' ";
		$sql = "SELECT member_name FROM ".DB_PREFIX."member WHERE 1 $where";
		$rs = $db->getRow($sql);
		
		if(!empty($rs)){
			return ERR_REG_EXISTS;
		}
		return 1;
	}
	
	/*
	*	检查游戏帐号是否可用
	*	@return 1 成功
	*/
	function check_member_gamename($member_gamename){
		global $db;
		if(in_array($member_gamename,explode(",",$config['member_name_save']))){
			return ERR_REG_EXISTS;
		}
		$where = " and member_gamename='". $member_gamename ."' ";
		$sql = "SELECT member_gamename FROM ".DB_PREFIX."member WHERE 1 $where";
		$rs = $db->getRow($sql);
		
		if(!empty($rs)){
			return ERR_REG_EXISTS;
		}
		return 1;
	}
	
	/*
	*	添加登陆日志
	*/
	function add_login_log($name,$pwd,$status){
		global $db;
		
		
		$row = array();
		$row['login_log_mname'] = $name;
		$row['login_log_pwd'] = $pwd;
		$row['login_log_status'] = $status;
		$row['login_log_time'] = time();
		$row['login_log_ip'] = get_client_ip();
		$db->insert($row,DB_PREFIX.'login_log');
		
		if($status<=0){
			
			/*检查10分钟内的错误登陆次数是否超过10次*/
			$timeout = strtotime("-10 minute",time());
			$count = $db->getOne("select count(*) from ".DB_PREFIX."login_log where login_log_mname='$name' and  login_log_time>{$timeout} and login_log_status=-1 ");
			if($count >= 10){
				/*禁止该IP登陆该帐号10分钟*/
				$mid = $db->getOne("select member_id from ".DB_PREFIX."member where member_name='$name' ");
				
				$row = array();
				$row['bg_mid'] = $mid;
				$row['bg_bantime'] = strtotime("+10 minute",time());
				$row['bg_ip'] = get_client_ip();
				$db->insert($row,DB_PREFIX.'member_banlogin');
			}
		}
		
		
	}
}
?>