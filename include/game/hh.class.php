<?php
/**
 * 类
 */
class Game_hh
{

    /**
     * 构造函数
     *
     * @access  public
     * @param
     *
     * @return void
     */
    function Game_hh()
    {
    }

   
	
	/**
     * 生成支付代码
     * @param   array   $server_info  服务器信息
     * @param   array   $user_info    会员信息
     */
    function get_login_url($server_info, $user_info)
    {
        $cm = '1';
		if($user_info['member_idvalid']=='0'){
			$cm = '0';
		}
		
		$parameter = array(
            'accname'           => $user_info['member_id'],
            'sessionid'         => session_id(),
			'partner'			=> $server_info['server_type'],
			'fcm'			=> $cm,
			
        );
		
		//md5("qid=".$qid."&time=".$time."&server_id=".$server_id.$key)
		//http://jtxm1.domain.com/jtxm/App/Interface/check.php?username=urlencode(username)&sid=S1&time=123465789&sign=md5(username+sid+key+time)&cm=1
		//ksort($parameter);
        //reset($parameter);
		
		$parm = '';
        $spt  = '';
		
		$md5_parm = '';
		$spt2  = '';
        foreach ($parameter AS $key => $val)
        {
			$parm .= $spt . "$key=$val";
			$md5_parm .= $spt2 . $val;
            $spt = '&';
			$spt2  = '';
        }
		
		
		$sign = md5( $md5_parm . md5($server_info['server_key']) );
		
		
		$returl = $server_info['server_loginurl'] . '?' . $parm."&ticket=".$sign;
		
		$ret = file_get_contents($returl);
		if($ret=='login_succ'){
			$returl = 'http://static.hh.49you.com/?'.$server_info['server_sid'].session_id();
		}else{
			$returl = 'http://hh.49you.com';
		}
 		
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
            'orderid'           => $order_info['trans_code'],
            'accid'         =>  $user_info['member_id'],
            'accname'           =>  $user_info['member_id'],
            'money'           => intval($order_info['trans_money']),
			'Coin'           => intval($order_info['trans_point'])
			//'partner'           => $server_info['server_type'],
			//'tstamp'           => time()
			
        );
		
		$html = '';
        $spt  = '';
		$sign_pram = '';
		$spt2 = '';
        foreach ($parameter AS $key => $val)
        {
            $html .= $spt . "$val";
            $spt = '|';
			
			$sign_pram .= $spt2 . $val;
			$spt2 = '';
        }

	
		$sign = md5( $sign_pram . $server_info['server_type'] . $time . md5($server_info['server_paykey']) );
		
		$returl = $server_info['server_payurl'] . '?p=' . $html."|".$time."|".$server_info['server_type']."|".$sign;
	
 		$ret = file_get_contents($returl);
		
		$log = array();
		$log['url'] = $returl;
		$log['return'] = $ret;
		log_save('游戏充值接口日志_航海之王','game_pay_hh',$log);
		if($ret == 'pay_succ'){
			return true;
		}
        return false;
    }
	
	
	/*
	*	获得新手卡
	*/
	function get_card($user_info,$server_info){
		return md5($user_info['member_id'].'_'.$server_info['server_sid']);
	}
   
}



?>