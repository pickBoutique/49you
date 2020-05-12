<?php

/**
 * 支付宝插件
 */


/* 模块的基本信息 */
if (isset($set_modules) && $set_modules == TRUE)
{
    $i = isset($modules) ? count($modules) : 0;

    /* 代码 */
    $modules[$i]['code']    = basename(__FILE__, '.php');
	
	/* 名称 */
    $modules[$i]['name']    = '支付宝';

    /* 描述对应的语言项 */
    $modules[$i]['desc']    = '国内先进的网上支付平台,支持支付宝担保付款和即时到账两种交易方式!';

    /* 是否支持货到付款 */
    $modules[$i]['is_cod']  = '0';

    /* 是否支持在线支付 0-直接跳转到付款成功页面 1-跳转到相应的支付网关 2-跳转到等待付款页面*/
    $modules[$i]['is_online']  = '1';

    /* 作者 */
    $modules[$i]['author']  = '莫伟志';

    /* 网址 */
    $modules[$i]['website'] = 'http://www.alipay.com';
	
	/* 图标 */
    $modules[$i]['pic'] = '/images/main/pay01.jpg';

    /* 版本号 */
    $modules[$i]['version'] = '1.0.2';

    /* 配置信息 */
    $modules[$i]['config']  = array(
        array('name' => 'alipay_account',    'desc'=>'支付宝帐户',       'type' => 'text',   'value' => 'polaris@76wan.com'),
        array('name' => 'alipay_key',    'desc'=>'交易安全校验码',           'type' => 'text',   'value' => 'l7fft112qtcxwwkfqsgnujbiubiymnxt'),
        array('name' => 'alipay_partner',    'desc'=>'合作者身份ID',        'type' => 'text',   'value' => '2088702259484890'),
        array('name' => 'alipay_pay_method',    'desc'=>'选择接口类型',     'type' => 'select', 'value' => '2',
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
class alipay
{

    /**
     * 构造函数
     *
     * @access  public
     * @param
     *
     * @return void
     */
    function alipay()
    {
    }

   

    /**
     * 生成支付代码
     * @param   array   $order      订单信息
     * @param   array   $payment    支付方式信息
     */
    function get_code($order, $payment)
    {
        if (!defined('EC_CHARSET'))
        {
            $charset = 'utf-8';
        }
        else
        {
            $charset = EC_CHARSET;
        }
		
		$data_order_id    = $order['order_sn'];
        $data_amount      = $order['order_amount'];
        $data_pay_key     = $payment['alipay_key'];
        $data_pay_partner = $payment['alipay_partner'];
		$data_pay_account = $payment['alipay_account'];
//        if (empty($payment['is_instant']))
//        {
//            /* 未开通即时到帐 */
//            $service = 'trade_create_by_buyer';
//        }
//        else
//        {
//            if (!empty($order['order_id']))
//            {
//                /* 检查订单是否全部为虚拟商品 */
//                $sql = "SELECT COUNT(*) FROM " .$GLOBALS['ecs']->table('order_goods').
//                        " WHERE is_real=1 AND order_id='$order[order_id]'";
//
//                if ($GLOBALS['db']->getOne($sql) > 0)
//                {
//                    /* 订单中存在实体商品 */
//                    $service =  (!empty($payment['alipay_real_method']) && $payment['alipay_real_method'] == 1) ?
//                        'create_direct_pay_by_user' : 'trade_create_by_buyer';
//                }
//                else
//                {
//                    /* 订单中全部为虚拟商品 */
//                    $service = (!empty($payment['alipay_virtual_method']) && $payment['alipay_virtual_method'] == 1) ?
//                        'create_direct_pay_by_user' : 'create_digital_goods_trade_p';
//                }
//            }
//            else
//            {
//                /* 非订单方式，按照虚拟商品处理 */
//                $service = (!empty($payment['alipay_virtual_method']) && $payment['alipay_virtual_method'] == 1) ?
//                    'create_direct_pay_by_user' : 'create_digital_goods_trade_p';
//            }
//        }

        $real_method = $payment['alipay_pay_method'];

        switch ($real_method){
            case '0':
                $service = 'trade_create_by_buyer';
                break;
            case '1':
                $service = 'create_partner_trade_by_buyer';
                break;
            case '2':
                $service = 'create_direct_pay_by_user';
                break;
        }

        //$agent = 'C4335319945672464113';

        $parameter = array(
            //'agent'             => $agent,
            'service'           => $service,
            'partner'           => $data_pay_partner,
            '_input_charset'    => $charset,
            'notify_url'        => $payment['notify_url'] ,
            'return_url'        => $payment['notify_url'] ,
            /* 业务参数 */
            'subject'           => $data_order_id ,
            'out_trade_no'      => $data_order_id ,
            'price'             => $data_amount,  //付款金额 $order['order_money']
            'quantity'          => 1,
            'payment_type'      => 1,
			//'paymethod'			=> 'bankPay',
			//'defaultbank'		=> 'CMB',
			//'buyer_email'		=> 'jy86070375@126.com',
			//'buyer_account_name' => 'jy86070375@126.com',
            /* 物流参数 */
            //'logistics_type'    => 'EXPRESS',
            //'logistics_fee'     => 0,
            //'logistics_payment' => 'BUYER_PAY_AFTER_RECEIVE',
            /* 买卖双方信息 */
            'seller_email'      => $data_pay_account
        );
		
		//if(!empty($payment['channelToken'])){
			//$parameter['paymethod'] = 'bankPay';
			//$parameter['defaultbank'] = $payment['channelToken'];
		//}

        ksort($parameter);
        reset($parameter);

        $html = '';
        $sign  = '';
		
        foreach ($parameter AS $key => $val)
        {
            //$param .= "$key=" .urlencode($val). "&";
            //$sign  .= "$key=$val&";
			
			$html .= "<input type='hidden' name='{$key}' value='".$val."'>\n";
            $sign  .= "$key=$val&";
        }

    
        $sign  = substr($sign, 0, -1) . $data_pay_key;
        
		$def_url  = "\n<form action='https://www.alipay.com/cooperate/gateway.do' method='post' >\n";
        $def_url .= $html;
        $def_url .= "<input type='hidden' name='sign' value='".md5($sign)."'>\n";
		$def_url .= "<input type='hidden' name='sign_type' value='MD5'>\n";
        $def_url .= "</form>\n";

		//$button = 'https://www.alipay.com/cooperate/gateway.do?' . $param . '&sign=' . md5($sign) . '&sign_type=MD5';
        
		//$button = '<div style="text-align:center"><input type="button" onclick="window.open(\'https://www.alipay.com/cooperate/gateway.do?'.$param. '&sign='.md5($sign).'&sign_type=MD5\')" value="' .$GLOBALS['_LANG']['pay_button']. '" /></div>';

        return $def_url;
    }

    /**
     * 响应操作
     */
    function respond()
    {
        if (!empty($_POST))
        {
            foreach($_POST as $key => $data)
            {
                $_GET[$key] = $data;
            }
        }
        $payment  = get_payment($_GET['code']);
        $seller_email = rawurldecode($_GET['seller_email']);
		$order_sn = trim($_REQUEST['out_trade_no']);
        //$order_sn = str_replace($_GET['subject'], '', $_GET['out_trade_no']);
        //$order_sn = trim($order_sn);
		
        /* 检查支付的金额是否相符 */
        if (!check_money($order_sn, $_GET['total_fee']))
        {
            return false;
        }
		
        /* 检查数字签名是否正确 */
        ksort($_GET);
        reset($_GET);

        $sign = '';
        foreach ($_GET AS $key=>$val)
        {
            if ($key != 'sign' && $key != 'sign_type' && $key != 'code')
            {
                $sign .= "$key=$val&";
            }
        }

        $sign = substr($sign, 0, -1) . $payment['alipay_key'];
        //$sign = substr($sign, 0, -1) . ALIPAY_AUTH;
        if (md5($sign) != $_GET['sign'])
        {
            return false;
        }
		
		//担保交易接口 等待发货状态
        if ($_GET['trade_status'] == 'WAIT_SELLER_SEND_GOODS')
        {
            /* 改变订单状态 */
			//order_paid($order_sn, $_GET['code'], '');
            return true;
        }//交易完成
		elseif ($_GET['trade_status'] == 'TRADE_FINISHED' || $_GET['trade_status'] == 'TRADE_SUCCESS')
        {
            /* 改变订单状态 */
            order_paid($order_sn, $_GET['code'], '');
            return true;
        }//交易失败
        else
        {
            return false;
        }
    }
}

?>