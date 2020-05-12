<?php
/**
 * 类
 */
class Game_jtxm
{

    /**
     * 构造函数
     *
     * @access  public
     * @param
     *
     * @return void
     */
    function Game_jtxm()
    {
    }

   
	
	/**
     * 生成支付代码
     * @param   array   $server_info  服务器信息
     * @param   array   $user_info    会员信息
     */
    function get_login_url($server_info, $user_info)
    {
       
		$parameter = array(
            'username'           => $user_info['member_gamename'],
            'sid'         => $server_info['server_sid'],
			'key'         => $server_info['server_key'],
            'time'    	   => time()
        );
		
		//http://jtxm1.domain.com/jtxm/App/Interface/check.php?username=urlencode(username)&sid=S1&time=123465789&sign=md5(username+sid+key+time)&cm=1
		//ksort($parameter);
        //reset($parameter);
		
		$parm = '';
		$html = '';
        $spt  = '';
		$spt2 = '';
        foreach ($parameter AS $key => $val)
        {
			if($key != 'key'){
			$parm .= $spt . "$key=$val";
			}
            $html .= $spt2 . "$val";
            $spt = '&';
			$spt2 = '';
        }
		
		$sign = md5($html);
		$cm = '1';
		if($user_info['member_idvalid']=='0'){
			$cm = '2';
		}
		$returl = $server_info['server_loginurl'] . '?' . $parm."&sign=".$sign . "&cm=$cm";

 		
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
            'username'           => $user_info['member_gamename'],
            'Money'         => $order_info['trans_point'],
            'PayMoney'    	=> $order_info['trans_money'],
            'Transactionid'           => $order_info['trans_code'] ,
            'ServerId'           => $server_info['server_sid'] ,
            'Time'           => $time
        );
		
		
		$html = '';
        $spt  = '';
        foreach ($parameter AS $key => $val)
        {
            $html .= $spt . "$key=$val";
            $spt = '&';
        }

		$sign = md5("Uname={$user_info[member_gamename]}&Money={$order_info[trans_point]}&ServerId={$server_info[server_sid]}&Transactionid={$order_info[trans_code]}&Key={$server_info[server_paykey]}&Time={$time}");
		$returl = $server_info['server_payurl'] . '?' . $html."&Sign=".$sign;
	
 		$ret = file_get_contents($returl);
		
		$log = array();
		$log['url'] = $returl;
		$log['return'] = $ret;
		log_save('游戏充值接口日志_九天仙梦','game_pay_jtxm',$log);
		if($ret == '1'){
			return true;
		}
        return false;
    }
	
	
	
   
}



?>