<?php
/**
 * 类
 */
class Game_jhl
{

    /**
     * 构造函数
     *
     * @access  public
     * @param
     *
     * @return void
     */
    function Game_jhl()
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
			'agent'		=> $server_info['server_type'],
			'serverid'      => $server_info['server_sid'],
            'account'        => urlencode($user_info['member_name']),
            'fcm'       => $cm ,
			'fcm_time'  => '-1',
			'time'  => $time
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
			$parm .= $spt . "$key=".$val;
            $md5_parm .= $spt2 . "$key=$val";
            $spt = '&';
			$spt2  = '&';
        }
		
		
		$sign = md5($server_info['server_key'].'|'.$md5_parm);
		
		$force = '';
		//if(isset($_REQUEST['49youdebug'])){
			//$force = '&force=1';
		//}
		$returl = $server_info['server_loginurl'] . '?'  . $parm . "&token=" . $sign;

 		
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
			'agent'		=> $server_info['server_type'],
			'serverid'      => $server_info['server_sid'],
            'account'        => urlencode($user_info['member_name']),
			'amount'    =>  $order_info['trans_point'] / 10.0,
			'order'     =>  $order_info['trans_code'],
			'time'      =>  $time
        );
		
		$html = '';
        $spt  = '';
		$sign_pram = '';
		$spt2 = '';
        foreach ($parameter AS $key => $val)
        {
            $html .= $spt . "$key=".$val;
            $spt = '&';
			
			$sign_pram .= $spt2 . "$key=$val";
			$spt2 = '&';
        }

		$sign = md5($server_info['server_paykey'] . '|' . $sign_pram);
		$returl = $server_info['server_payurl'] . '?' . $html."&token=".$sign;
	
 		$result = file_get_contents($returl);
		
		$log = array();
		$log['url'] = $returl;
		$log['return'] = $result;
		log_save('游戏充值接口日志_江湖令','game_pay_jhl',$log);
		if($result == 'SUCCESS'){
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