<?php
/**
 * 类
 */
class Game_long
{

    /**
     * 构造函数
     *
     * @access  public
     * @param
     *
     * @return void
     */
    function Game_long()
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
		$parameter = array(
            'username'           => strtolower($user_info['member_name']),
            'agent'         => $server_info['server_type'],
			'server'		=> $server_info['server_sid'],
			'time' 			=> $time,
			'isAdult'		=> '1'
			
        );
		
		$parm = '';
        $spt  = '';
		
		$md5_parm = '';
		$spt2  = '';
        foreach ($parameter AS $key => $val)
        {
			$parm .= $spt . "$key=".urlencode($val);
			$md5_parm .= $spt2 . $val;
            $spt = '&';
			$spt2  = '_';
        }
		
		
		$sign = md5($parameter['username'] . $parameter['time'] . $server_info['server_key']);
		
		
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
            'name'           => strtolower($user_info['member_name']),
			'money'			=> $order_info['trans_point']  * 10,
            'gold'         => $order_info['trans_point'],
			'coupon'	=> '0',
			'coin'		=> '0',
            'order'           => $order_info['trans_code'] 
        );
		
		$html = '';
        $spt  = '';
		$sign_pram = '';
		$spt2 = '';
        foreach ($parameter AS $key => $val)
        {
            $html .= $spt . "$key=" . urlencode($val);
            $spt = '&';
			
			$sign_pram .= $spt2 . $val;
			$spt2 = '_';
        }

		$sign = md5($parameter['order'].$server_info['server_paykey']);
		$returl = $server_info['server_payurl'] . '/pay/' . $server_info['server_type'] . '/' . $server_info['server_sid'] . '/by/username?' . $html."&sign=".$sign;
		
 		$ret = file_get_contents($returl);
		$result = json_decode($ret);
		
		$log = array();
		$log['url'] = $returl;
		$log['return'] = $ret;
		log_save('游戏充值接口日志_龙将','game_pay_long',$log);
		if($result->code == '0'){
			return true;
		}
        return false;
    }
	
	
	/*
	*	获得新手卡
	*/
	function get_card($user_info,$server_info){
		return strtoupper(md5('long'.$server_info['server_type'].$server_info['server_sid']. strtolower($user_info['member_name']) ));
	}
   
}



?>