<?php
/**
 * 类
 */
class Game_txc
{

    /**
     * 构造函数
     *
     * @access  public
     * @param
     *
     * @return void
     */
    function Game_txc()
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
            'account'         => $user_info['member_name'],
			'time'		=> time(),
			'isAdult'		=>$fcm			
        );
		
		$parm = '';
        $spt  = '';
        foreach ($parameter AS $key => $val)
        {
			$parm .= $spt . "$key=".urlencode($val);
            $spt = '&';
        }
		
		$token = md5($parameter['account'].$parameter['time'].$server_info['server_key']);		
		$returl = $server_info['server_loginurl'] . '?' . $parm."&token=".$token;
		
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
            'account'           => $user_info['member_name'],
			'num'           => $order_info['trans_point'],
            'order'         => $order_info['trans_code'],
			'comment'       =>''
        );
		
		$html = '';
        $spt  = '';
        foreach ($parameter AS $key => $val)
        {
			$html .= $spt . "$key=$val";
            $spt = '&';
        }
		
		$returl = $server_info['server_payurl']."?type=charging&".$html;	
 		$ret = file_get_contents($returl);
		
		$log = array();
		$log['url'] = $returl;
		$log['return'] = $ret;
		$ret = json_decode($ret,true);
		log_save('游戏充值接口日志_天下策','game_pay_txc',$log);
		if($ret['code'] == 0){
			return true;
		}
        return false;
    }
	
	
	/*
	*	获得新手卡
	*/
	function get_card($user_info,$server_info){
		$game = "txc";
		$agent = "49you";
		$cardtype = 1;
		return md5($game.$agent.$cardtype.$user_info['member_name']);
	}
   
}



?>