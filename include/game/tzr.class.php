<?php
/**
 * 类
 */
class Game_tzr
{

    /**
     * 构造函数
     *
     * @access  public
     * @param
     *
     * @return void
     */
    function Game_tzr()
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
			$cm = '0';
		}
		$parameter = array(
            'account'           => $user_info['member_name'],
            'agentid'         => $server_info['server_type'],
			'serverid'		=> $server_info['server_sid'],
			'tstamp' 			=> $time,
			'fcm'		=> $cm
			
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
		
		
		$sign = md5($server_info['server_key'] . $parameter['account'] . $parameter['tstamp'] . $parameter['agentid'] . $parameter['serverid'] . $parameter['fcm']);
		
		$returl = $server_info['server_loginurl'] . '?' . $parm."&ticket=".$sign;

 		
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
            'game'           => 'tzr',
			'agent'			=> '49you',
            'user'         => $user_info['member_name'],
			'order'	=> $order_info['trans_code'],
			'money'		=> $order_info['trans_point']  / 10,
            'server'           => 'S'.$server_info['server_sid'] ,
			'time'  => $time
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

		//$agent.urlencode($user).$server.$key.$game.$order.$time

		$sign = md5($parameter['agent'].urlencode($parameter['user']).$parameter['server'].$server_info['server_paykey'].$parameter['game'].$parameter['order'].$parameter['time']);
		
		$parameter['sign'] = $sign;
		
		$returl = $server_info['server_payurl'] . '?' . $html  . '&sign=' .  $sign;
		$result = $this->post($server_info['server_payurl'], $parameter);
		
		
		$log = array();
		$log['url'] = $returl;
		$log['return'] = $result;
		log_save('游戏充值接口日志_天之刃','game_pay_tzr',$log);
		if($result == '1'){
			return true;
		}
        return false;
    }
	
	
	
	/*
	*	获得新手卡
	*/
	function get_card($user_info,$server_info){
		
		$sign = md5($user_info['member_name'].$server_info['server_sid'].'11'.'49you::TZR::INFO::KEY::w9cUUc3eBKLDkyFM');
		$url = 'http://s' . $server_info['server_sid']  . '.tzr.49you.com/api/activity_card.php?qid=' . urlencode($user_info['member_name']) . '&server_id=' . $server_info['server_sid'] . '&card_type=11&sign=' . $sign;
		
		$result = file_get_contents($url);
		
		return $result;
	}
	
	
	function post($url, $post = null)
	{
		 $context = array();
	
		 if (is_array($post))
		 {
			 ksort($post);
	
			 $context['http'] = array
			 (   
	
				 'timeout'=>60,
				 'method' => 'POST',
				 'content' => http_build_query($post, '', '&'),
			 );
		 }
		// exit(http_build_query($post, '', '&'));
		 return file_get_contents($url, false, stream_context_create($context));
	}
   
}



?>