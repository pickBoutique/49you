<?php

/**
 * 块钱银行支付插件
 */
if (!function_exists("appendParam"))
{
    function appendParam($returnStr,$paramId,$paramValue){

		if($returnStr!=""){
			
				if($paramValue!=""){
					
					$returnStr.="&".$paramId."=".$paramValue;
				}
			
		}else{
		
			if($paramValue!=""){
				$returnStr=$paramId."=".$paramValue;
			}
		}
		
		return $returnStr;
	}
}

/* 模块的基本信息 */
if (isset($set_modules) && $set_modules == TRUE)
{
    $i = isset($modules) ? count($modules) : 0;

    /* 代码 */
    $modules[$i]['code']    = basename(__FILE__, '.php');
	
	/* 名称 */
    $modules[$i]['name']    = '块钱网银支付';

    /* 描述对应的语言项 */
    $modules[$i]['desc']    = '';

    /* 是否支持货到付款 */
    $modules[$i]['is_cod']  = '0';

    /* 是否支持在线支付 0-直接跳转到付款成功页面 1-跳转到相应的支付网关 2-跳转到等待付款页面*/
    $modules[$i]['is_online']  = '1';

    /* 作者 */
    $modules[$i]['author']  = '莫伟志';

    /* 网址 */
    $modules[$i]['website'] = 'http://www.99bill.com';
	
	/* 图标 */
    $modules[$i]['pic'] = '/images/main/pay01.jpg';

    /* 版本号 */
    $modules[$i]['version'] = 'v2.0';

    /* 配置信息 */
    $modules[$i]['config']  = array(
		array('name' => 'inputCharset',    'desc'=>'字符集',       'type' => 'text',   'value' => '1'),
	    array('name' => 'version',    'desc'=>'网关版本',       'type' => 'text',   'value' => 'v2.0'),
		array('name' => 'language',    'desc'=>'语言种类',       'type' => 'text',   'value' => '1'),
		array('name' => 'signType',    'desc'=>'签名类型',       'type' => 'text',   'value' => '1'),
		array('name' => 'merchantAcctId',    'desc'=>'块钱帐户',       'type' => 'text',   'value' => '1002174366203'),
		array('name' => 'payType',    'desc'=>'支付方式',       'type' => 'text',   'value' => '42'),
		array('name' => 'bossType',    'desc'=>'充值卡类型',       'type' => 'text',   'value' => '1'),
		array('name' => 'key',    'desc'=>'设置神州行网关密钥',       'type' => 'text',   'value' => 'Y25XZ8BYCLK4K554'),		
		array('name' => 'fullAmountFlag',    'desc'=>'全额支付标志',       'type' => 'text',   'value' => '1')
    );
    return;
}

/**
 * 类
 */
class bill99_unicom
{

    /**
     * 构造函数
     *
     * @access  public
     * @param
     *
     * @return void
     */
    function bill99_unicom()
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
        $data_amount      = intval($order['order_amount'] * 100);
        $data_pay_key     = $payment['key'];
        $data_pay_account = $payment['merchantAcctId'];
		$url = explode('?',$payment['notify_url']);
		$url = $url[0];
		
		$parameter = array(
            'inputCharset'         	=> $payment['inputCharset'],
			'bgUrl'    	   			=> $url,
            'pageUrl'         		=> '',           
            'version'           	=> $payment['version'],
            'language'           	=> $payment['language'],
            'signType'           	=> $payment['signType'] ,
            'merchantAcctId'        => $payment['merchantAcctId'],
            'payerName'         	=> '', 
            'payerContactType'      => '',
            'payerContact'      	=> '',
			'orderId'            	=> $data_order_id,
			'orderAmount'           => $data_amount,
			'payType' 				=> $payment['payType'],
			'fullAmountFlag'        => $payment['fullAmountFlag'],
			'orderTime'            	=> date('YmdHis',time()),
			'productName'			=> '',
			'productNum'          	=> '',
            'productId'         	=> '', 
			'productDesc'			=> '',
            'ext1'           		=> 'bill99_unicom',
            'ext2'      	   		=> '',
			'bossType'            	=> $payment['bossType']
        );
		
		$html = '';
        $sign  = '';
        foreach ($parameter AS $key => $val)
        {
			
            $html .= "<input type='hidden' name='{$key}' value='".$val."'>\n";
            $sign  = appendParam($sign,$key,$val);
		
        }
		
		$sign =appendParam($sign,'key',$data_pay_key);
		$MD5KEY =strtoupper(md5($sign));  
        $def_url  = "\n<form  name='kqPay' action='http://www.99bill.com/szxgateway/recvMerchantInfoAction.htm' method='post' >\n";
        $def_url .= $html;
        $def_url .= "<input type='hidden' name='signMsg' value='".$MD5KEY."'>\n";
        $def_url .= "</form>\n";
        return $def_url;
    }


    /**
     * 响应操作
     */
    function respond()
    {
		
        $payment  = get_payment($_REQUEST['code']);
		$data_pay_key     = $payment['key'];
		
		$merchantAcctId			= trim($_REQUEST['merchantAcctId']);
		$version				= trim($_REQUEST['version']);
		$language				= trim($_REQUEST['language']);
		$payType				= trim($_REQUEST['payType']);
		$cardNumber				= trim($_REQUEST['cardNumber']);
		$cardPwd				= trim($_REQUEST['cardPwd']);
		$orderId				= trim($_REQUEST['orderId']);
		$orderAmount			= trim($_REQUEST['orderAmount']);
		$dealId					= trim($_REQUEST['dealId']);
		$orderTime				= trim($_REQUEST['orderTime']);
		$ext1					= trim($_REQUEST['ext1']);
		$ext2					= trim($_REQUEST['ext2']);
		$payAmount				= trim($_REQUEST['payAmount']);
		$billOrderTime			= trim($_REQUEST['billOrderTime']);
		$payResult				= trim($_REQUEST['payResult']);
		$signType				= trim($_REQUEST['signType']);
		$bossType				= trim($_REQUEST['bossType']);
		$receiveBossType 		= trim($_REQUEST['receiveBossType']);
		$receiverAcctId 		= trim($_REQUEST['receiverAcctId']);
		$signMsg				= trim($_REQUEST['signMsg']);
		
		$parameter = array(
			'merchantAcctId'         => $merchantAcctId,
            'version'        		 => $version,
            'language'    	   		 => $language,
            'payType'           	 => $payType,
            'cardNumber'           	 => $cardNumber,
            'cardPwd'          		 => $cardPwd,
            'orderId'         	 	 => $orderId, 
            'orderAmount'            => $orderAmount,
            'dealId'      	   		 => $dealId,
			'orderTime'              => $orderTime,
			'ext1'            	 	 => $ext1,
			'ext2'					 => $ext2,
			'payAmount'          	 => $payAmount,
            'billOrderTime'			 => $billOrderTime, 
			'payResult'				 => $payResult,
            'signType'           	 => $signType,	
			'bossType'				 =>$bossType,
			'receiveBossType'		 =>$receiveBossType,
			'receiverAcctId'			 =>$receiverAcctId,
			'key'					 => $data_pay_key
        );

        $sign  = '';
        foreach ($parameter AS $key => $val)
        {
            $sign  = appendParam($sign,$key,$val);
        }
		
		$MD5KEY = md5($sign);
		$v_result = false;
		if (strtoupper($MD5KEY) == strtoupper($signMsg))
        {
            if ($payResult == '10')
            {
                ///支付成功
                $v_result = true;				
                order_paid($orderId, $_REQUEST['code'],'');
            }

		}else{
				
		}
		
		if($v_result){
			$rtnOk=1;
			$rtnUrl= YOU_ROOT."/notify_success.html";	
		}else{
			$rtnOk=0;
			//$rtnUrl="http://www.yoursite.com/show.php?msg=error!";	
		}
		
		echo '<result>'.$rtnOk.'</result><redirecturl>'.$rtnUrl.'</redirecturl>';
		exit();
		return $v_result;
    }
}




?>