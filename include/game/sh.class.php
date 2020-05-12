<?php
/**
 * 类
 */
class Game_sh
{

    /**
     * 构造函数
     *
     * @access  public
     * @param
     *
     * @return void
     */
    function Game_sh()
    {
    }

   
	
	/**
     * 生成支付代码
     * @param   array   $server_info  服务器信息
     * @param   array   $user_info    会员信息
     */
    function get_login_url($server_info, $user_info)
    {
        $cm = 'n';
		if($user_info['member_idvalid']=='0'){
			$cm = 'y';
		}
	    $time = time();
		$parameter = array(
			'coopname'		=> $server_info['server_type'],
            'userid'        => urlencode($user_info['member_name']. '['.$server_info['server_type'].']' ),
            'serverid'      => $server_info['server_sid'],
			'thetimestamp'  => $time,
			'cmflag'     => md5($cm.$time)
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
		
		
		$sign = md5('coopname='.$parameter['coopname'].'&userid='.$user_info['member_name']. '['.$server_info['server_type'].']'.'&key='.$server_info['server_key'].'&thetimestamp='.$parameter['thetimestamp']);
		
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
            'SPID'           => $server_info['server_type'],
            'GroupID'         => $server_info['server_sid'],  
            'ServerID'         => '63',  //由开发商分配的游戏id
            'UserID'           => base64_encode($user_info['member_name']),
			'GameRole'           => '', //角色名 保留 目前为空
			'Order'           => $order_info['trans_code'],
			'GamePoint'           => intval($order_info['trans_point']),
			'PayPoint'           => $order_info['trans_point'] / 10,
			'UserIP'           => $user_info['last_ip'],
			'Memo'           => base64_encode(time())
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

		$sign = md5($sign_pram.$spt2.'key='.$server_info['server_paykey']);
		$returl = $server_info['server_payurl'] . '?' . $html."&Sign=".$sign;
	
 		$ret = file_get_contents($returl);
		$result = explode('&',$ret);
		
		$log = array();
		$log['url'] = $returl;
		$log['return'] = $result;
		log_save('游戏充值接口日志_山海创世录','game_pay_sh',$log);
		if($result[0] == 'Result=succ'){
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