<?php
/**
 * 类
 */
class Game_xy
{

    /**
     * 构造函数
     *
     * @access  public
     * @param
     *
     * @return void
     */
    function Game_xy()
    {
    }

   
	
	/**
     * 生成支付代码
     * @param   array   $server_info  服务器信息
     * @param   array   $user_info    会员信息
     */
    function get_login_url($server_info, $user_info)
    {
		$login_key = $this->get_login_key($server_info, $user_info);
        $cm = '1';
		if($user_info['member_idvalid']=='0'){
			$cm = '0';
		}
	    $time = time();
		$parameter = array(
			//'ip'      => '121.33.203.122',
			//'xmlport'		=> '843',
            //'getvport'        => '10000',
			'sid'      => '0',
			'userId'  =>  $user_info['member_id'],
			'pass'  =>  $login_key,
			'tsp'  => $time
        );
		
		
		
		$parm = '';
        $spt  = '';
		
		$md5_parm = '';
		$spt2  = '';
        foreach ($parameter AS $key => $val)
        {
			$parm .= $spt . "$key=".urlencode($val);
            $md5_parm .= $spt2 . "$val";
            $spt = '&';
			$spt2  = '';
        }
		
		
		
		$sign = md5($parm.$server_info['server_key']);
		
		$force = '';
		//if(isset($_REQUEST['49youdebug'])){
			//$force = '&force=1';
		//}
		//$returl = 'http://www.mydao.com.cn/' . '?'  . $parm . "&key=" . $sign;
		$returl = 'http://static.xy.49you.com/OnlineGame.html' . '?'  . $parm . "&key=" . $sign;
		//echo($parm);
 		//exit($returl);
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
			'sindex' => $server_info['server_sid'],
			'userId' => $user_info['member_id'],
			'orderId' => $order_info['trans_code'],
			'money' => ceil($order_info['trans_point'] / 10),
			'time'  => $time,
			'type'  => '101'
        );
		
		$html = '';
        $spt  = '';
		$sign_pram = '';
		$spt2 = '';
        foreach ($parameter AS $key => $val)
        {
			
            $html .= $spt . "$key=".urlencode($val);
            $spt = '&';
			
			$sign_pram .= $spt2 . "$key=$val";
			$spt2 = '&';
        }
		
		$sign = md5($sign_pram . $server_info['server_paykey']);
		$parameter['key'] = $sign;
		$returl = $server_info['server_payurl'] . '?' . $html."&key=".$sign;
		$result = $this->post($server_info['server_payurl'], $parameter);
		
		
		$log = array();
		$log['url'] = $returl;
		$log['return'] =  $result;
		log_save('游戏充值接口日志_飞音西游','game_pay_xy',$log);
		if($result == '1'){
			return true;
		}
        return false;
    }
	
	function get_login_key($server_info, $user_info){
		$time = time();
		$cm = '1';
		if($user_info['member_idvalid']=='1'){
			$cm = '0';
		}
		$parameter = array(
			'sindex'  => $server_info['server_sid'],
			'userId'		=> $user_info['member_id'], 
			'fangchenmi'      => $cm
			//'addFavorite'  => '1'
        );
		
		$html = '';
        $spt  = '';
		$sign_pram = '';
		$spt2 = '';
        foreach ($parameter AS $key => $val)
        {
			
            $html .= $spt . "$key=".urlencode($val);
            $spt = '&';
			
			$sign_pram .= $spt2 . "$key=$val";
			$spt2 = '&';
        }
		
		$sign = md5($sign_pram.$server_info['server_key']);
		
		$parameter['key'] = $sign;
		$result = $this->post("http://s1.xy.49you.com:30000/serverCentral/login", $parameter);
		
		
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
	
	

	
	/*
	*	获得新手卡
	*/
	//function get_card($user_info,$server_info){
		//return md5($user_info['member_id'].'_'.$server_info['server_sid']);
	//}
   
}



?>