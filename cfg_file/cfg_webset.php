<?php
/*
*  自定义配置项格式： 新会员默认等级
*/
function webset_new_member_level($name,$cfg,$val){
	global $db;
	$levels = $db->getAll(" select * from ".DB_PREFIX."member_level ");
	$ret = "<select name='$name'>";
	if($levels){
	foreach($levels as $k=>$v){
		$selected = '';
		if($v['level_id']==$val){
			$selected = 'selected';
		}
		$ret .= "<option value='$v[level_id]' $selected >{$v[level_name]}</option>";
	}
	}
	$ret .= "</select>";
	return $ret;
}

/* 站点配置项	*/
$cfg_webset = array(

	'title' => array(
			'name' => '网站名称',
			'type' => 'text',
			'maxlen' => '25'
	),
	'logo' => array(
			'name' => '网站logo',
			'type' => 'img_upload'
	),
	'keyword' => array(
			'name' => '站点关键字',
			'type' => 'text',
			'maxlen' => '50'
	),
	'description' => array(
			'name' => '站点描述',
			'type' => 'text',
			'maxlen' => '250'
	),
	'copyright' => array(
			'name' => '版权信息',
			'type' => 'text',
			'maxlen' => '50'
	),
	'footer' => array(
			'name' => '页脚内容',
			'type' => 'text_editor'
	),
	'new_member_level' => array(
			'name' => '新会员默认等级',
			'type' => 'function',
			'function_name' => 'webset_new_member_level'
	),
	'enabled_integ' => array(
			'name' => '开启会员积分模块',
			'type' => 'select',
			'options' => array(
					'0' => '关闭',
					'1' => '开启'
			)
	),
	'money_name' => array(
			'name' => '平台币名称',
			'type' => 'text',
			'maxlen' => '50'
	),
	'money_to_integral' => array(
			'name' => '充值1平台币奖励多少积分',
			'type' => 'text',
			'maxlen' => '50'
	),
	'recom_pay_integral' => array(
			'name' => '推荐好友充值1平台币奖励多少积分',
			'type' => 'text',
			'maxlen' => '50'
	),
	'recom_pay_money' => array(
			'name' => '推荐好友充值1平台币奖励多少平台币',
			'type' => 'text',
			'maxlen' => '50'
	),
	'recom_active_member' => array(
			'name' => '邀请好友的限制活跃天数',
			'type' => 'text',
			'maxlen' => '50'
	),
	'recom_member_integral' => array(
			'name' => '邀请好友奖励多少积分',
			'type' => 'text',
			'maxlen' => '50'
	),
	'default_to_currency' => array(
			'name' => '默认充值1元兑换多少平台币',
			'type' => 'text',
			'maxlen' => '50'
	),
	'min_to_charge' => array(
			'name' => '最少充值金额（元）',
			'type' => 'text',
			'maxlen' => '50'
	),
	'service_tel1' => array(
			'name' => '客服热线电话1',
			'type' => 'text',
			'maxlen' => '50'
	),
	'service_tel2' => array(
			'name' => '客服热线电话2',
			'type' => 'text',
			'maxlen' => '50'
	),
	'service_mail1' => array(
			'name' => '客服邮箱1',
			'type' => 'text',
			'maxlen' => '50'
	),
	'service_mail2' => array(
			'name' => '客服邮箱2',
			'type' => 'text',
			'maxlen' => '50'
	),
	'member_name_save' => array(
			'name' => '保留会员名称(,)号分隔',
			'type' => 'text',
			'maxlen' => '250'
	)
	
	

);
?>