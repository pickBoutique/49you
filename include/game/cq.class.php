<?php
/**
 * 类
 */
class Game_cq
{

    /**
     * 构造函数
     *
     * @access  public
     * @param
     *
     * @return void
     */
    function Game_cq()
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
            'qid'           => $user_info['member_id'],
            'time'         => time(),
			'server_id'         => $server_info['server_sid']
        );
		
		//md5("qid=".$qid."&time=".$time."&server_id=".$server_id.$key)
		//http://jtxm1.domain.com/jtxm/App/Interface/check.php?username=urlencode(username)&sid=S1&time=123465789&sign=md5(username+sid+key+time)&cm=1
		//ksort($parameter);
        //reset($parameter);
		
		$parm = '';
        $spt  = '';
        foreach ($parameter AS $key => $val)
        {
			$parm .= $spt . "$key=$val";
            $spt = '&';
        }
		
		$sign = md5($parm.$server_info['server_key']);
		
		$isAdult = '1';
		if($user_info['member_idvalid']=='0'){
			$isAdult = '0';
		}
		$returl = $server_info['server_loginurl'] . '?' . $parm."&sign=".$sign . "&isAdult=$isAdult";

 		
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
            'qid'           => $user_info['member_id'],
            'order_amount'         => $order_info['trans_point'],
            'order_id'           => $order_info['trans_code'] ,
            'server_id'           => $server_info['server_sid']
        );
		
		$html = '';
        $spt  = '';
		$sign_pram = '';
        foreach ($parameter AS $key => $val)
        {
            $html .= $spt . "$key=$val";
            $spt = '&';
			
			$sign_pram .=  $val;
			
        }

		$sign = md5($sign_pram.$server_info['server_paykey']);
		$returl = $server_info['server_payurl'] . '?' . $html."&sign=".$sign;
	
 		$ret = file_get_contents($returl);
		
		$log = array();
		$log['url'] = $returl;
		$log['return'] = $ret;
		log_save('游戏充值接口日志_传奇国度','game_pay_cq',$log);
		if($ret == '1'){
			return true;
		}
        return false;
    }
	
	
	/*
	*	获得新手卡
	*/
	function get_card($user_info,$server_info){
		return strtoupper(md5($user_info['member_id'].$server_info['server_sid'].'F8d4Ft3huyhj9NXE'));
	}
   
}



?>