<?php
/**
 * 类
 */
class Game_xk
{

    /**
     * 构造函数
     *
     * @access  public
     * @param
     *
     * @return void
     */
    function Game_xk()
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
            'uid'           => $user_info['member_id'],
            'uname'         => $user_info['member_name'],
            'lgtime'    	   => date('YmdHis',time()),
            'uip'           => get_client_ip() ,
            'type'           => $server_info['server_type'] ,
            'sid'           => $server_info['server_sid']
        );
		
		
		//ksort($parameter);
        //reset($parameter);
		
		$html = '';
		$html_md5 = '';
        $spt  = '';
        foreach ($parameter AS $key => $val)
        {
			
            $html .= $spt . "$key=" . urlencode($val);
			$html_md5 .= $spt . "$key=$val";
            $spt = '&';
        }
		
		$sign = md5($html.'&key='.$server_info['server_key'] );
		$returl = $server_info['server_loginurl'] . '?' . $html."&sign=".$sign;

 
		/*return For example		http://user.xk.yaowan.com/User/unitelogin?uid=2470177&uname=lilizizi&lgtime=20110603145016&uip=58.62.166.217&type=yaowan&sid=95901&sign=191ca5dcb1e5cde2c9c1817d020bbd4e
		*/
        return $returl;
    }


	/**
     * 生成支付代码
     * @param   array   $server_info  服务器信息
     * @param   array   $user_info    会员信息
     */
    function to_pay($server_info, $user_info, $order_info)
    {
       
		$parameter = array(
            'uid'           => $user_info['member_id'],
            'uname'         => $user_info['member_name'],
            'serverid'    	=> $server_info['server_sid'],
            'point'           => $order_info['trans_point'] ,
            'amount'           => $order_info['trans_point'] / 10.0 ,
            'oid'           => $order_info['trans_code'],
			'time'           => date('YmdHis', $order_info['trans_intime'] ),
			'type'           => $server_info['server_type']
			
        );
		
		$html = '';
		$html_md5 = '';
        $spt  = '';
        foreach ($parameter AS $key => $val)
        {
            $html .= $spt . "$key=" . urlencode($val);
			$html_md5 .= $spt . "$key=$val";
            $spt = '&';
        }
		
		$sign = md5($html.'&key='.$server_info['server_paykey'] );
		$returl = $server_info['server_payurl'] . '?' . $html."&sign=".$sign;
		
 		$str_json = file_get_contents($returl);
		
		
		
		require_once(WEB_ROOT.'/include/json.class.php');
		$json = new JSON();
		$ret = $json->decode($str_json);
		
		$log = array();
		$log['url'] = $returl;
		$log['return'] = $ret;
		log_save('游戏充值接口日志_侠客世界','game_pay_xk',$log);
		if($ret->status == '400'){
			return true;
		}
        return false;
    }
	
	
	
   
}



?>