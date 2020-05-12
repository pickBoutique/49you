<?php

/**
 * 预付款插件
 */


/* 模块的基本信息 */
if (isset($set_modules) && $set_modules == TRUE)
{
    $i = isset($modules) ? count($modules) : 0;

    /* 代码 */
    $modules[$i]['code']    = basename(__FILE__, '.php');
	
	/* 名称 */
    $modules[$i]['name']    = '预付款';

    /* 描述对应的语言项 */
    $modules[$i]['desc']    = '国内先进的网上支付平台,支持支付宝担保付款和即时到账两种交易方式!';

    /* 是否支持货到付款 */
    $modules[$i]['is_cod']  = '0';

    /* 是否支持在线支付 */
    $modules[$i]['is_online']  = '0';

    /* 作者 */
    $modules[$i]['author']  = '莫伟志';

    /* 网址 */
    $modules[$i]['website'] = 'http://www.alipay.com';
	
	/* 图标 */
    $modules[$i]['pic'] = '/images/main/pay03.jpg';

    /* 版本号 */
    $modules[$i]['version'] = '1.0.2';

    /* 配置信息 */
    $modules[$i]['config']  = array(
        array('name' => 'alipay_account',    'desc'=>'支付宝帐户',       'type' => 'text',   'value' => ''),
        array('name' => 'alipay_key',    'desc'=>'交易安全校验码',           'type' => 'text',   'value' => ''),
        array('name' => 'alipay_partner',    'desc'=>'合作者身份ID',        'type' => 'text',   'value' => ''),
        array('name' => 'alipay_pay_method',    'desc'=>'选择接口类型',     'type' => 'select', 'value' => '',
			  'range' => array(
					0 => '使用标准双接口',
					1 => '使用担保交易接口',
					2 => '使用即时到帐交易接口'
							  )
			  )
    );

    return;
}

/**
 * 类
 */
class prepay
{

    /**
     * 构造函数
     *
     * @access  public
     * @param
     *
     * @return void
     */
    function prepay()
    {
    }

   

    /**
     * 生成支付代码
     * @param   array   $order      订单信息
     * @param   array   $payment    支付方式信息
     */
    function get_code($order, $payment)
    {
		global $db,$obj_user;
		
		list($status , $login_info) = $obj_user->check_login();
		if($status){
			$rs = $obj_user->get_user_by_id( $login_info[2] );
			if($rs){
				
				//余额够时修改账户余额
				$moy = $rs['money'] - $order['order_money'];
				if($rs['money'] < $order['order_money'])
				{
					return false;
					//$show_msg = "<p>您的预付款不够支付，请选择其他支付方式。</p>";
				}
				else
				{
					$sql = "update ".DB_PREFIX."member set money=".$moy." WHERE 1 and member_id='". $login_info[2] ."' ";
					$query = $db->query($sql); 
					if($query)
					{
						$_SESSION['web_member_money'] = $moy;
						//支付状态修改为已收款  
						order_paid($order['order_no'], $order['order_money']);
						return true;
					}
				}
			}
		}
		
      	
        return false;
    }

}

?>