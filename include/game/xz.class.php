<?php
/**
 * 类
 */
class Game_xz
{

    /**
     * 构造函数
     *
     * @access  public
     * @param
     *
     * @return void
     */
    function Game_xz()
    {
    }

   
	
	/**
     * 生成支付代码
     * @param   array   $server_info  服务器信息
     * @param   array   $user_info    会员信息
     */
    function get_login_url($server_info, $user_info)
    {
        $time = time();
		$cm = '1';
		if($user_info['member_idvalid']=='0'){
			$cm = '-1';
		}
		$parameter = array(
            'username'           => $user_info['member_name'],
            'ServerId'         => $server_info['server_sid'],
			'isAdult'         => $cm,
            'time'    	   => time()
			
        );
		
	
		$parm = '';
		$html = '';
        $spt  = '';
		$spt2 = '';
        foreach ($parameter AS $key => $val)
        {
		
			$parm .= $spt . "$key=" . urlencode($val);
	
            $html .= $spt2 . "$val";
            $spt = '&';
			$spt2 = '';
        }
		
		$sign = md5("Uname=" . $parameter['username'] . "&ServerId=" . $parameter['ServerId'] . "&Key=" . $server_info['server_key'] . "&Time=" . $time . "&isAdult=".$cm);
		$returl = $server_info['server_loginurl'] . '?' . $parm."&flag=".$sign;

 		
        return $returl;
    }


	/**
     * 生成支付代码
     * @param   array   $server_info  服务器信息
     * @param   array   $user_info    会员信息
     */
    function to_pay($server_info, $user_info, $order_info)
    {
        $time = time();
		$parameter = array(
            'username'           => $user_info['member_name'],
            'Money'         => $order_info['trans_point'],
            'Transactionid'           => $order_info['trans_code'] ,
            'ServerId'           => $server_info['server_sid'] 
        );
		
		
		$html = '';
        $spt  = '';
        foreach ($parameter AS $key => $val)
        {
            $html .= $spt . "$key=" . urlencode($val);
            $spt = '&';
        }

		$sign = md5("Uname=" . $parameter['username'] . "&Money=" . $parameter['Money'] . "&ServerId=" . $parameter['ServerId'] . "&Transactionid=" . $parameter['Transactionid'] . "&Key=" . $server_info['server_paykey']);
		$returl = $server_info['server_payurl'] . '?' . $html."&Sign=".$sign;
	
 		$ret = file_get_contents($returl);
		
		$log = array();
		$log['url'] = $returl;
		$log['return'] = $ret;
		log_save('游戏充值接口日志_凡人修真2','game_pay_xz',$log);
		if($ret == '1'){
			return true;
		}
        return false;
    }
	
	
	
   
}



?>