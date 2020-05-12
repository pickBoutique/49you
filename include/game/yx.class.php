<?php
/**
 * 类
 */
class Game_yx
{

    /**
     * 构造函数
     *
     * @access  public
     * @param
     *
     * @return void
     */
    function Game_yx()
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
            'userid'           => $user_info['member_id'],
            'username'         => $user_info['member_name'], 
			'source'           => '0',
			'tstamp'           => $time
			
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
		
		$sign = md5($server_info['server_key'] .  $parameter['userid'] . $parameter['username'] . $time );
		$returl = $server_info['server_loginurl'] . '?' . $parm."&keys=".$sign;

 		
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
            'PayToPlayer'           => '',
            'PayToUser'         => $user_info['member_name'], 
            'PayGold'           => $order_info['trans_point'], 
            'time'           => $time,
			'PayNum'            => $order_info['trans_code'] 
        );
		
		
		$html = '';
        $spt  = '';
        foreach ($parameter AS $key => $val)
        {
            $html .= $spt . "$key=" . urlencode($val);
            $spt = '&';
        }

		$sign = md5($server_info['server_paykey'] .  $parameter['PayToPlayer'] . $parameter['PayToUser'] .  $parameter['PayGold'] . $parameter['time'] . $parameter['PayNum']);
		$returl = $server_info['server_payurl'] . '?' . $html."&ticket=".$sign;
	
 		$ret = file_get_contents($returl);
		
		$log = array();
		$log['url'] = $returl;
		$log['return'] = $ret;
		log_save('游戏充值接口日志_英雄王座','game_pay_yx',$log);
		if($ret == '1'){
			return true;
		}
        return false;
    }
	
	
	
   
}



?>