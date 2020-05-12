<?php
/**
 * 类
 */
class Game_sxd
{

    /**
     * 构造函数
     *
     * @access  public
     * @param
     *
     * @return void
     */
    function Game_sxd()
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
            'user'           => $user_info['member_id'],
            'time'         => time()
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
			$spt2  = '_';
        }
		
		
		$sign = md5($md5_parm.'_'.$server_info['server_key']);
		
		$force = '';
		if(isset($_REQUEST['49youdebug'])){
			$force = '&force=1';
		}
		$returl = $server_info['server_loginurl'] . '?source='.$user_info['member_advid'].'&regdate='.$user_info['add_time'].$force.'&' . $parm."&hash=".$sign;

 		
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
            'user'           => $user_info['member_id'],
            'gold'         => $order_info['trans_point'],
            'order'           => $order_info['trans_code'] ,
            'domain'           => $server_info['server_sid']
        );
		
		$html = '';
        $spt  = '';
		$sign_pram = '';
		$spt2 = '';
        foreach ($parameter AS $key => $val)
        {
            $html .= $spt . "$key=$val";
            $spt = '&';
			
			$sign_pram .= $spt2 . $val;
			$spt2 = '_';
        }

		$sign = md5($sign_pram.'_'.$server_info['server_paykey']);
		$returl = $server_info['server_payurl'] . '?' . $html."&sign=".$sign;
	
 		$ret = file_get_contents($returl);
		
		$log = array();
		$log['url'] = $returl;
		$log['return'] = $ret;
		log_save('游戏充值接口日志_神仙道','game_pay_sxd',$log);
		if($ret == '1'){
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