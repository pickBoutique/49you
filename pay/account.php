<?php

/**
 * 平台币兑换
 */


/* 模块的基本信息 */
if (isset($set_modules) && $set_modules == TRUE)
{
    $i = isset($modules) ? count($modules) : 0;

    /* 代码 */
    $modules[$i]['code']    = basename(__FILE__, '.php');
	
	/* 名称 */
    $modules[$i]['name']    = '平台币支付';

    /* 描述对应的语言项 */
    $modules[$i]['desc']    = '';

    /* 是否支持货到付款 */
    $modules[$i]['is_cod']  = '0';

    /* 是否支持在线支付 0-直接跳转到付款成功页面 1-跳转到相应的支付网关 2-跳转到等待付款页面*/
    $modules[$i]['is_online']  = '1';

    /* 作者 */
    $modules[$i]['author']  = '莫伟志';

    /* 网址 */
    $modules[$i]['website'] = 'http://www.49you.com';
	
	/* 图标 */
    $modules[$i]['pic'] = '/images/main/pay01.jpg';

    /* 版本号 */
    $modules[$i]['version'] = '1.0.2';

    /* 配置信息 */
    $modules[$i]['config']  = array(
        
    );

    return;
}

/**
 * 类
 */
class account
{

    /**
     * 构造函数
     *
     * @access  public
     * @param
     *
     * @return void
     */
    function account()
    {
    }

   
	
	/**
     * 生成支付代码
     * @param   array   $order  订单信息
     * @param   array   $payment    支付方式信息
     */
    function get_code($order, $payment)
    {
        $data_order_id    = $order['order_sn'];
        $data_amount      = $order['order_amount'];
       
		
		$parameter = array(
            'act'           => 'confirm',
            'code'         => $data_order_id
        );
		
		
		ksort($parameter);
        reset($parameter);
		
		$html = '';
        $sign  = '';
        foreach ($parameter AS $key => $val)
        {
			
            $html .= "<input type='hidden' name='{$key}' value='".$val."'>\n";
            $sign  .= $val;
        }
		
		

        $def_url  = "\n<form action='/exchange.html' method='post' >\n";
        $def_url .= $html;
        $def_url .= "</form>\n";

        return $def_url;
    }


    /**
     * 响应操作
     */
    function respond()
    {
		
        $payment  = get_payment($_REQUEST['code']);
		$merchant_id    = $payment['yp_account'];       // 获取商户编号
        $merchant_key   = $payment['yp_key'];           // 获取秘钥
		
		$message_type   = trim($_REQUEST['r0_Cmd']);
        $succeed        = trim($_REQUEST['r1_Code']);   // 获取交易结果,1成功,-1失败
        $trxId          = trim($_REQUEST['r2_TrxId']);
        $amount         = trim($_REQUEST['r3_Amt']);    // 获取订单金额
        $cur            = trim($_REQUEST['r4_Cur']);    // 获取订单货币单位
        $product_id     = trim($_REQUEST['r5_Pid']);    // 获取产品ID
        $orderid        = trim($_REQUEST['r6_Order']);  // 获取订单ID
        $userId         = trim($_REQUEST['r7_Uid']);    // 获取产品ID
        $merchant_param = trim($_REQUEST['r8_MP']);     // 获取商户私有参数
        $bType          = trim($_REQUEST['r9_BType']);  // 获取订单ID

        $mac            = trim($_REQUEST['hmac']);      // 获取安全加密串

        ///生成加密串,注意顺序
        $ScrtStr  = $merchant_id . $message_type . $succeed . $trxId . $amount . $cur . $product_id .
                      $orderid . $userId . $merchant_param . $bType;
        $mymac    = hmac($ScrtStr, $merchant_key);
		
		$v_result = false;
		if (strtoupper($mac) == strtoupper($mymac))
        {
            if ($succeed == '1')
            {
                ///支付成功
                $v_result = true;
                order_paid($orderid, $_REQUEST['code'], $merchant_param);
            }

		}

        return $v_result;
    }
}




?>