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
    $modules[$i]['name']    = '易宝网银支付';

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
        array('name' => 'yp_account',    'desc'=>'易宝帐户',       'type' => 'text',   'value' => '10011505831'),
        array('name' => 'yp_key',    'desc'=>'交易安全校验码',           'type' => 'text',   'value' => '7N84sRs8m56642pHgd4V7o44h385m956c7WY23oiW1ipob9C79t8DU3DmQ4e')
    );

    return;
}

/**
 * 类
 */
class yeepay
{

    /**
     * 构造函数
     *
     * @access  public
     * @param
     *
     * @return void
     */
    function yeepay()
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
			'pd_FrpId'            => '',
			'pr_NeedResponse'            => ''
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
	
	
	/*
	*	查询订单
	*/
	function query($order_sn){
		 $payment  = get_payment('yeepay');
		 $p1_MerId    = $payment['yp_account'];       // 获取商户编号
         $merchantKey   = $payment['yp_key'];           // 获取密钥
		 
		 $ret = array();
		 
		  		
		  $p0_Cmd 	= "QueryOrdDetail";	            #接口类型
		  $p2_Order = $order_sn;						#商户订单号
		  #正式请求地址
		  $QueryOrdURL_onLine	= "https://www.yeepay.com/app-merchant-proxy/command";			
		  #测试请求地址					
		  //$QueryOrdURL_onLine	= "http://tech.yeepay.com:8080/robot/debug.action";									
			  
		  #	进行签名处理，一定按照文档中标明的签名顺序进行
		  $sbOld ="";
		  #	加入订单查询请求，固定值"QueryOrdDetail"
		  $sbOld = $sbOld.$p0_Cmd;
		  #	加入商户编号
		  $sbOld = $sbOld.$p1_MerId;
		  #	加入商户订单号
		  $sbOld = $sbOld.$p2_Order;
							 
		  $hmac	 = null;
		  $hmac	 = hmac($sbOld,$merchantKey);     
		
					 
		  #	进行签名处理，一定按照文档中标明的签名顺序进行
		  #	加入订单查询请求，固定值"QueryOrdDetail"
		  $params = array('p0_Cmd' => $p0_Cmd,
		  #	加入商户编号
		  'p1_MerId'	=>  $p1_MerId,
		  #	加入商户订单号
		  'p2_Order'	=>  $p2_Order,
		  #	加入校验码
		  'hmac' 			=>  $hmac);
		  include_once(WEB_ROOT."/include/httpclient.class.php");
		  
		  $pageContents = HttpClient::quickPost($QueryOrdURL_onLine, $params);
		  
		  $result = explode("\n",$pageContents);
		  
		  ## 声明查询结果
			  $r0_Cmd					= "";			#	取得业务类型
			  $r1_Code				= "";     #	查询结果状态码
			  $r2_TrxId				= "";			#	易宝支付交易流水号
			  $r3_Amt					= "";			#	支付金额
			  $r4_Cur					= "";			#	交易币种
			  $r5_Pid					= "";			#	商品名称
			  $r6_Order				= "";			#	商户订单号
			  $r8_MP					= "";			#	商户扩展信息
			  $rb_PayStatus		= "";			#	支付状态
			  $rc_RefundCount	= "";			#	已退款次数
			  $rd_RefundAmt		= "";			#	已退款金额
			  $hmac						= "";     #	查询返回数据的签名串
					  
			  for($index=0;$index<count($result);$index++){//数组循环
				  $result[$index] = trim($result[$index]);
				  if (strlen($result[$index]) == 0) {
					  continue;
				  }
				  $aryReturn = explode("=",$result[$index]);
				  $sKey = $aryReturn[0];
				  $sValue = $aryReturn[1];
				  if($sKey=="r0_Cmd"){											#业务类型 
					  $r0_Cmd = $sValue;
				  }elseif($sKey=="r1_Code"){								#查询结果状态码  
					  $r1_Code = $sValue;
				  }elseif($sKey == "r2_TrxId"){			        #易宝支付交易流水号
					  $r2_TrxId = $sValue;
				  }elseif($sKey == "r3_Amt"){			          #支付金额
					  $r3_Amt = $sValue;
				  }elseif($sKey == "r4_Cur"){			          #交易币种
					  $r4_Cur = $sValue;
				  }elseif($sKey == "r5_Pid"){								#商品名称
					  $r5_Pid = $sValue;
				  }elseif($sKey == "r6_Order"){							#商户订单号
					  $r6_Order = $sValue;
				  }elseif($sKey == "r8_MP"){							  #商户扩展信息
					  $r8_MP = $sValue;
				  }elseif($sKey == "rb_PayStatus"){					#支付状态
					  $rb_PayStatus = $sValue;
				  }elseif($sKey == "rc_RefundCount"){				#已退款次数
					  $rc_RefundCount = $sValue;
				  }elseif($sKey == "rd_RefundAmt"){					#已退款金额
					  $rd_RefundAmt = $sValue;
				  }elseif($sKey == "hmac"){									#取得校验码
					  $hmac = $sValue;	      
				  }else{
					  //echo $result[$index];
					  //return;
				  }
			  }
				  
		  
			  #进行校验码检查 取得加密前的字符串
			  $sbOld="";
			  #加入业务类型
			  $sbOld = $sbOld.$r0_Cmd;
			  #加入查询操作是否成功
			  $sbOld = $sbOld.$r1_Code;
			  #加入易宝支付交易流水号
			  $sbOld = $sbOld.$r2_TrxId;
			  #加入支付金额
			  $sbOld = $sbOld.$r3_Amt;	
			  #加入交易币种
			  $sbOld = $sbOld.$r4_Cur;	
			  #加入商品名称
			  $sbOld = $sbOld.$r5_Pid;	
			  #加入商户订单号
			  $sbOld = $sbOld.$r6_Order;	
			  #加入商户扩展信息
			  $sbOld = $sbOld.$r8_MP;		              
			  #加入支付状态
			  $sbOld = $sbOld.$rb_PayStatus;		              
			  #加入已退款次数
			  $sbOld = $sbOld.$rc_RefundCount;		              
			  #加入已退款金额
			  $sbOld = $sbOld.$rd_RefundAmt;		              
						  
			//echo "[".$sbOld."]";
			
			//echo $sNewString;  
			//echo $sNewString;
			
			  $sNewString = hmac($sbOld,$merchantKey);
			  
			 
			  //校验码正确
			  if($sNewString==$hmac) {
				if($r1_Code=="1"){
					$ret['ret_code'] = '1';
					$ret['ret_msg'] = '查询成功';
					$ret['order_sn'] = $r6_Order;  //订单号
					$ret['trans_sn'] = $r2_TrxId;  //交易流水号
					$ret['prod_name'] = $r5_Pid;   //商品名称
					$ret['amount'] = $r3_Amt;      //支付金额
					$ret['extend'] = $r8_MP;       //商户扩展信息
					$ret['status'] = $rb_PayStatus == 'SUCCESS' ? '1' : '0';
					$ret['refund_count'] = $rc_RefundCount; //已退款次数
					$ret['refund_amount'] = $rd_RefundAmt; //已退款金额
				
				} else if($r1_Code=="50"){
					$ret['ret_code'] = '50';
					$ret['ret_msg'] = '该订单不存在';
				
				} else{
					$ret['ret_code'] = $r1_Code;
					$ret['ret_msg'] = '查询失败';
				    
				}
			  } else{
				  $ret['ret_code'] = '-1';
				  $ret['ret_msg'] = "<br>localhost:".$sNewString."<br>YeePay:".$hmac."<br>交易信息被篡改";
				 
			  }
			  
			  return $ret;
	}
}




?>