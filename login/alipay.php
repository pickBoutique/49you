<?php

/**
 * 支付宝登录接口插件
 */


/* 模块的基本信息 */
if (isset($set_modules) && $set_modules == TRUE)
{
    $i = isset($modules) ? count($modules) : 0;

    /* 代码 */
    $modules[$i]['code']    = basename(__FILE__, '.php');
	
	/* 名称 */
    $modules[$i]['name']    = '支付宝登录';

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
    $modules[$i]['pic'] = '/images/alipay_icon.jpg';

    /* 版本号 */
    $modules[$i]['version'] = '1.0.2';

    /* 配置信息 */
    $modules[$i]['config']  = array(
        array('name' => 'alipay_account',    'desc'=>'支付宝帐户',       'type' => 'text',   'value' => 'sccw.yao@8hy.cn'),
        array('name' => 'alipay_key',    'desc'=>'交易安全校验码',           'type' => 'text',   'value' => 'afjv7xc9u4w5nbjd0inzb14m6ji3av6f'),
        array('name' => 'alipay_partner',    'desc'=>'合作者身份ID',        'type' => 'text',   'value' => '2088101978327075')
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
     * 生成登陆链接代码
     * @param   array   $payment    支付方式信息
     */
    function get_code($payment)
    {
        if (!defined('EC_CHARSET'))
        {
            $charset = 'utf-8';
        }
        else
        {
            $charset = EC_CHARSET;
        }
		
		
		$login_config = get_login_config('alipay');
		
        $parameter = array(
    
			
			"service"			=> "user_authentication",	//接口名称，不需要修改

			//获取配置文件(alipay_config.php)中的值
			"partner"			=> $login_config['alipay_partner'],
			"return_url"		=> $payment['return_url'],
			"_input_charset"	=> $charset
			
			//选填参数
			//"email"				=> $email
        );

        ksort($parameter);
        reset($parameter);

        $param = '';
        $sign  = '';

        foreach ($parameter AS $key => $val)
        {
            $param .= "$key=" .urlencode($val). "&";
            $sign  .= "$key=$val&";
        }

        $param = substr($param, 0, -1);
        $sign  = substr($sign, 0, -1) . $login_config['alipay_key'];
        
		
		$button = 'https://www.alipay.com/cooperate/gateway.do?' . $param . '&sign=' . md5($sign) . '&sign_type=MD5';
        //$button = '<div style="text-align:center"><input type="button" onclick="window.open(\'https://www.alipay.com/cooperate/gateway.do?'.$param. '&sign='.md5($sign).'&sign_type=MD5\')" value="' .$GLOBALS['_LANG']['pay_button']. '" /></div>';

        return $button;
    }

    /**
     * 响应操作
     */
    function respond()
    {
		global $db;
        if (!empty($_POST))
        {
            foreach($_POST as $key => $data)
            {
                $_GET[$key] = $data;
            }
        }
        $payment  = get_login_config('alipay');
    	
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
		
		//初始化会员信息对象
		require_once(WEB_ROOT."/include/user.class.php");
		$user = new User();
	
		$info = array();
		$info['email'] = $_GET['user_id'] . '@alipay';
		$info['member_pwd'] = $user->encry_pwd($info['email'],'RHCVVSTH453#@');
		$info['member_type'] = '1';
		$info['member_status'] = '1';
		$info['account_type'] = 'alipay';
		$info['account'] = $_GET['user_id'];
		$info['name_cn'] = '支付宝'.$_GET['user_id'];
		$rs = $user->get_user_by_name($info['email']);
		if( empty($rs) ){
			
			$mid = $user->register( $info );
			return $info['email'];
		}else{
			return $info['email'];
		}
		
	
      
    }
}

?>