<?php
/**
 * 类
 */
class Game_zzsf
{

    /**
     * 构造函数
     *
     * @access  public
     * @param
     *
     * @return void
     */
    function Game_zzsf()
    {
    }

   
	
	/**
     * 生成支付代码
     * @param   array   $server_info  服务器信息
     * @param   array   $user_info    会员信息
     */
    function get_login_url($server_info, $user_info)
    {
        $fcm = '1';
		if($user_info['member_idvalid']=='0'){
			$fcm = '0';
		}
	   
		$parameter = array(
            'yx'         		=> '49you',
			'userId'			=> $user_info['member_id'],
			'tp'				=> time(),
			'sfid'				=> "",
			'adult'				=>$fcm,
			'additionalKey'		=>time()				//预登陆用时间戳做唯一标识
        );
		
		$parm_prelogin = "yx=".$parameter['yx']."&userId=".$parameter['userId']."&tp=".$parameter['tp']."&additionalKey=".$parameter['additionalKey'];
		$ticket = md5($parameter['yx'].$parameter['userId'].$parameter['tp'].$server_info['server_key']);
		$returl = $server_info['server_loginurl']."prelogin.action?".$parm_prelogin."&ticket=".$ticket;
		$info = file_get_contents($returl);
		
		if($info=="1"){
				$parm_login = "yx=".$parameter['yx']."&userId=".$parameter['userId']."&tp=".$parameter['tp']."&sfid=".$parameter['sfid']."&adult=".$parameter['adult'];
				$ticket = md5($parameter['yx'].$parameter['userId'].$parameter['tp'].$parameter['additionalKey'].$server_info['server_key']);
				$returl = $server_info['server_loginurl']."login.action?".$parm_login."&ticket=".$ticket;
				
				
		}
        return $returl;
    }

	
	/**
     * 生成支付代码
     * @param   array   $server_info  服务器信息
     * @param   array   $user_info    会员信息
	 *
	 *	
     */
    function to_pay($server_info, $user_info, $order_info)
    {
        $time = time();
		$parameter = array(
            'yx'           		=>'49you',
			'userId'            => $user_info['member_id'],
            'orderId'         	=> $order_info['trans_code'],
			'gold'				=>$order_info['trans_point'],
			'tp'				=>$time
        );
		
		$html = '';
        $spt  = '';
        foreach ($parameter AS $key => $val)
        {
			$html .= $spt . "$key=$val";
            $spt = '&';
        }
		
		$ticket = md5($parameter['orderId'].$parameter['yx'].$parameter['userId'].$parameter['gold'].$parameter['tp'].$server_info['server_paykey']);
		$returl = $server_info['server_payurl']."?".$html."&ticket=".$ticket;	
 		$ret = file_get_contents($returl);
		
		$log = array();
		$log['url'] = $returl;
		$log['return'] = $ret;
		
		log_save('游戏充值接口日志_征战四方','game_pay_zzsf',$log);
		if($ret==1){
			return true;
		}
        return false;
    }
	
	
	/*
	*	获得新手卡
	*/
	/*function get_card($user_info,$server_info){
		return "";
	}*/
   
}



?>