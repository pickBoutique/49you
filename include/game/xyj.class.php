<?php
/**
 * 类
 */
class Game_xyj
{

    /**
     * 构造函数
     *
     * @access  public
     * @param
     *
     * @return void
     */
    function Game_xyj()
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
            'username'           => $user_info['member_name'],
            'time'         => date('YmdHis',time()),
			'adult'        => $cm,
			'serverid'     => $server_info['server_sid']
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
			$parm .= $spt . "$key=".urlencode($val);
			$md5_parm .= $spt2 . $val;
            $spt = '&';
			$spt2  = '';
        }
		
		
		$sign = md5($md5_parm.$spt2.$server_info['server_key']);
		
		$force = '';
		//if(isset($_REQUEST['49youdebug'])){
			//$force = '&force=1';
		//}
		$returl = $server_info['server_loginurl'] . '?' . $force . '&' . $parm . "&sign=" . $sign;

 		
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
            'exchange_id'           => $order_info['trans_code'],
            'username'         => $user_info['member_name'],
            'product_coin'         => intval($order_info['trans_point']),
            'total_coin'           => $order_info['trans_point']  / 10,
			'time'           => date('YmdHis',time()),
			'serverid'           => $server_info['server_sid']
        );
		
		$html = '';
        $spt  = '';
		$sign_pram = '';
		$spt2 = '';
        foreach ($parameter AS $key => $val)
        {
            $html .= $spt . "$key=".urlencode($val);
            $spt = '&';
			
			$sign_pram .= $spt2 . $val;
			$spt2 = '';
        }

		$sign = md5($sign_pram.$spt2.$server_info['server_paykey']);
		$returl = $server_info['server_payurl'] . '?' . $html."&sign=".$sign;
	
 		$ret = file_get_contents($returl);
		$result = json_decode($ret);
		
		$log = array();
		$log['url'] = $returl;
		$log['return'] = $ret;
		log_save('游戏充值接口日志_逍遥剑','game_pay_xyj',$log);
		if($result->errno == '0'){
			return true;
		}
        return false;
    }
	
	
	/*
	*	获得新手卡
	*/
	//function get_card($user_info,$server_info){
		//return md5($user_info['member_id'].'_'.$server_info['server_sid']);
	//}
   
}



?>