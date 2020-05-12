<?php

/**
 * 易宝银行支付插件
 */

if (!function_exists("hmac"))
{
    function hmac($data, $key)
    {
        // RFC 2104 HMAC implementation for php.
        // Creates an md5 HMAC.
        // Eliminates the need to install mhash to compute a HMAC
        // Hacked by Lance Rushing(NOTE: Hacked means written)

        //$key  = ecs_iconv('GB2312', 'UTF8', $key);
        //$data = ecs_iconv('GB2312', 'UTF8', $data);

        $b = 64; // byte length for md5
        if (strlen($key) > $b)
        {
            $key = pack('H*', md5($key));
        }

        $key    = str_pad($key, $b, chr(0x00));
        $ipad   = str_pad('', $b, chr(0x36));
        $opad   = str_pad('', $b, chr(0x5c));
        $k_ipad = $key ^ $ipad ;
        $k_opad = $key ^ $opad;

        return md5($k_opad . pack('H*', md5($k_ipad . $data)));
    }
}



/* 模块的基本信息 */
if (isset($set_modules) && $set_modules == TRUE)
{
    $i = isset($modules) ? count($modules) : 0;

    /* 代码 */
    $modules[$i]['code']    = basename(__FILE__, '.php');
	
	/* 名称 */
    $modules[$i]['name']    = '神州行支付';

    /* 描述对应的语言项 */
    $modules[$i]['desc']    = '';

    /* 是否支持货到付款 */
    $modules[$i]['is_cod']  = '0';

    /* 是否支持在线支付 0-直接跳转到付款成功页面 1-跳转到相应的支付网关 2-跳转到等待付款页面*/
    $modules[$i]['is_online']  = '1';

    /* 作者 */
    $modules[$i]['author']  = '莫伟志';

    /* 网址 */
    $modules[$i]['website'] = 'http://www.yeepay.com';
	
	/* 图标 */
    $modules[$i]['pic'] = '/images/main/pay01.jpg';

    /* 版本号 */
    $modules[$i]['version'] = '1.0.2';

    /* 配置信息 */
    $modules[$i]['config']  = array(
        array('name' => 'yp_account',    'desc'=>'易宝帐户',       'type' => 'text',   'value' => '10011281192'),
        array('name' => 'yp_key',    'desc'=>'交易安全校验码',           'type' => 'text',   'value' => 'r9340jtZA02tp9S5q9GcK5n7U1dKy0b9QhG76y87Ag3YcbHQx281o6453457')
    );

    return;
}

/**
 * 类
 */
class yeepay_szx
{

    /**
     * 构造函数
     *
     * @access  public
     * @param
     *
     * @return void
     */
    function yeepay_szx()
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
        $data_pay_key     = $payment['yp_key'];
        $data_pay_account = $payment['yp_account'];
       
		
		$parameter = array(
            'p0_Cmd'           => 'Buy',
            'p1_MerId'         => $data_pay_account,
            'p2_Order'    	   => $data_order_id,
            'p3_Amt'           => $data_amount ,
            'p4_Cur'           => 'CNY' ,
            'p5_Pid'           => '' ,
            'p6_Pcat'          => '' ,
            'p7_Pdesc'         => '', 
            'p8_Url'           => $payment['notify_url'],
            'p9_SAF'      	   => '0',
			'pa_MP'            => '',
			'pd_FrpId'            => 'SZX-NET',
			'pr_NeedResponse'            => '1'
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
		
		$MD5KEY = hmac($sign, $data_pay_key);

        $def_url  = "\n<form action='https://www.yeepay.com/app-merchant-proxy/node' method='post' >\n";
        $def_url .= $html;
        $def_url .= "<input type='hidden' name='hmac' value='".$MD5KEY."'>\n";
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