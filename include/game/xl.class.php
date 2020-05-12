<?php
/**
 * 类
 */
class Game_xl
{

    /**
     * 构造函数
     *
     * @access  public
     * @param
     *
     * @return void
     */
    function Game_xl()
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
	    $time = time();
		$parameter = array(
			'isminor'      => $cm,
			'username'		=> $user_info['member_name'],
            'time'        => $time
        );
		
		
		
		$parm = '';
        $spt  = '';
		
		$md5_parm = '';
		$spt2  = '';
        foreach ($parameter AS $key => $val)
        {
			$parm .= $spt . "$key=".$val;
            $md5_parm .= $spt2 . "$val";
            $spt = '&';
			$spt2  = '';
        }
		
		
		$sign = md5($md5_parm.$server_info['server_key']);
		
		$force = '';
		//if(isset($_REQUEST['49youdebug'])){
			//$force = '&force=1';
		//}
		$returl = $server_info['server_loginurl'] . '?'  . $parm . "&sign=" . $sign;

 		
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
			'cost_number'  => intval($order_info['trans_money']),
			'method'		=> 'updateyb', 
			'orderid'      => $order_info['trans_code'],
            'username'        => $user_info['member_name'],
			'yb'    =>  $order_info['trans_point']
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
		$parameter['username'] = urlencode($parameter['username']);
		$parameter['sign'] = $sign;
		$returl = $server_info['server_payurl'] . '?' . $html."&sign=".$sign;
		$result = $this->post($server_info['server_payurl'], $parameter);
		
		
	
 		//$result = file_get_contents($returl);
		
		$log = array();
		$log['url'] = $returl;
		$log['return'] =  $result;
		log_save('游戏充值接口日志_降龙十八掌','game_pay_xl',$log);
		if($result == '1'){
			return true;
		}
        return false;
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