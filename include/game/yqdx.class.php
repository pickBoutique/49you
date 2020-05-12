<?php
/**
 * 类
 */
class Game_yqdx
{

    /**
     * 构造函数
     *
     * @access  public
     * @param
     *
     * @return void
     */
    function Game_yqdx()
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
			'tid'          => $server_info['server_type'],
			'sid'          => $server_info['server_sid'],
            'account'           => $user_info['member_name'],
			'pwd'           => md5($user_info['member_name']),
			'ip'		   => get_client_ip(),
            'time'         => $time
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
			$md5_parm .= $spt2 . $val;
            $spt = '&';
			$spt2  = '';
        }
		
		
		$sign = md5(base64_encode($parm).$server_info['server_key']);
		
		$force = '';
		//if(isset($_REQUEST['49youdebug'])){
			//$force = '&force=1';
		//}
		$returl = $server_info['server_loginurl'] . '?action=login&auth=' . base64_encode($parm) . "&verify=" . $sign;

 		
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
			'tid'          => $server_info['server_type'],
			'sid'          => $server_info['server_sid'],
            'account'           => strtolower($user_info['member_name']),
			'oid'           => $order_info['trans_code'],
			'otype'           => '0',
			'money'           => $order_info['trans_point'] / 10.0 ,
			'gold'           => $order_info['trans_point'],
			'ip'		   => get_client_ip(),
            'time'         => $time
        );
       
		$html = '';
        $spt  = '';
		$sign_pram = '';
		$spt2 = '';
        foreach ($parameter AS $key => $val)
        {
            $html .= $spt . "$key=".$val;
            $spt = '&';
			
			$sign_pram .= $spt2 . $val;
			$spt2 = '';
        }

		$sign = md5(base64_encode($html).$server_info['server_paykey']);
		$returl = $server_info['server_payurl'] . '?action=charge&auth=' . base64_encode($html) ."&verify=".$sign;
	
 		$ret = file_get_contents($returl);
		$result = json_decode($ret);
		
		$log = array();
		$log['url'] = $returl;
		$log['return'] = $ret;
		log_save('游戏充值接口日志_一骑当先','game_pay_yqdx',$log);
		if($result->result == '1'){
			return true;
		}
        return false;
    }
	
	/**
     * 生成支付代码
     * @param   array   $server_info  服务器信息
     * @param   array   $user_info    会员信息
     */
    function get_top_url($server_info)
    {
        $cm = '1';
		if($user_info['member_idvalid']=='0'){
			$cm = '0';
		}
	    $time = time();
		$parameter = array(
			'tid'          => $server_info['server_type'],
            'time'         => $time
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
			$md5_parm .= $spt2 . $val;
            $spt = '&';
			$spt2  = '';
        }
		
		
		$sign = md5(base64_encode($parm).$server_info['server_key']);
		
		$force = '';
		//if(isset($_REQUEST['49youdebug'])){
			//$force = '&force=1';
		//}
		$returl = $server_info['server_loginurl'] . '?action=rank&auth=' . base64_encode($parm) . "&verify=" . $sign;

 		$ret = file_get_contents($returl);
        return $ret;
    }
	
	/*
	*	获得新手卡
	*/
	//function get_card($user_info,$server_info){
		//return md5($user_info['member_id'].'_'.$server_info['server_sid']);
	//}
   
}



?>