<?php

/**
 * 银行电汇插件
 */


/* 模块的基本信息 */
if (isset($set_modules) && $set_modules == TRUE)
{
    $i = isset($modules) ? count($modules) : 0;

    /* 代码 */
    $modules[$i]['code']    = basename(__FILE__, '.php');
	
	/* 名称 */
    $modules[$i]['name']    = '银行电汇';

    /* 描述对应的语言项 */
    $modules[$i]['desc']    = '国内先进的网上支付平台,支持支付宝担保付款和即时到账两种交易方式!';

    /* 是否支持货到付款 */
    $modules[$i]['is_cod']  = '0';

    /* 是否支持在线支付 */
    $modules[$i]['is_online']  = '2';

    /* 作者 */
    $modules[$i]['author']  = '莫伟志';

    /* 网址 */
    $modules[$i]['website'] = 'http://www.alipay.com';
	
	/* 图标 */
    $modules[$i]['pic'] = '/images/main/pay04.jpg';

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
class bankpay
{

    /**
     * 构造函数
     *
     * @access  public
     * @param
     *
     * @return void
     */
    function bankpay()
    {
    }

   

    /**
     * 生成支付代码
     * @param   array   $order      订单信息
     * @param   array   $payment    支付方式信息
     */
    function get_code($order, $payment)
    {
        
        return true;
    }

}

?>