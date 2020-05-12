<?php
/**
 * 类
 */
class Game_gf
{

    /**
     * 构造函数
     *
     * @access  public
     * @param
     *
     * @return void
     */
    function Game_gf()
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
            'Uname'         => $user_info['member_name'],
			'userid'		=> $user_info['member_id'],
			'GameId'		=> $server_info['server_gid'],
			'ServerId'		=> $server_info['server_sid'],
			'Time'			=> time(),
			'al'			=> $fcm,
			'from'			=> $user_info['member_advid'],
			'siteurl'		=>''		
			
        );
		
		//Uname=xx&userid=xx&GameId=xx&ServerId=S1&Time=1291105518&al=0&from=&siteurl=&Sign=xxx
		//Sign=md5("Uname=$Uname&userid=$userid&GameId=$GameId&ServerId=$ServerId&Key=$Key&Time=$Time&al=$al&from=$from&siteurl=$siteurl ")
		
		$parm = '';
        $spt  = '';
        foreach ($parameter AS $key => $val)
        {
			$parm .= $spt . "$key=".urlencode($val);
            $spt = '&';
        }
		
		$sign = md5("Uname={$parameter['Uname']}&userid={$parameter['userid']}&GameId={$parameter['GameId']}&ServerId={$parameter['ServerId']}&Key={$server_info['server_key']}&Time={$parameter['Time']}&al={$parameter['al']}&from={$parameter['from']}&siteurl={$parameter['siteurl']}");
		
		
		$returl = $server_info['server_loginurl'] . '?' . $parm."&Sign=".$sign;
		//echo "Uname={$parameter['Uname']}&userid={$parameter['userid']}&GameId={$parameter['GameId']}&ServerId={$parameter['ServerId']}&Key={$server_info['server_key']}&Time={$parameter['Time']}&al={$parameter['al']}&from={$parameter['from']}&siteurl={$parameter['siteurl']}";
		//exit($returl);
        return $returl;
    }


	/**
     * 生成支付代码
     * @param   array   $server_info  服务器信息
     * @param   array   $user_info    会员信息
	 *
	 *	http://api.xxxxxxxx.com/pay? Depay=0&gDepay=0&addcoin=0&Uname=XXX&Money=1&GameId=0&ServiceId=S1&Transactionid=XX&Sign=XXX
		Sign="md5("Depay=$Depay&gDepay=$gDepay&addcoin=$addcoin&Uname= $Uname&Money=$Money&GameId=$GameId&ServiceId=$ServiceId&Transactionid=$Transactionid&Key=$Key");
     */
    function to_pay($server_info, $user_info, $order_info)
    {
        $time = time();
		$parameter = array(
            'Depay'           => 0,
			'gDepay'           => 0,
            'addcoin'         => 0,
			'Uname'           => $user_info['member_name'],
            'Money'           => $order_info['trans_point'] / 10,
			'GameId'           => $server_info['server_gid'],
			'ServiceId'           => $server_info['server_sid'],
			'Transactionid'		=>$order_info['trans_code']
        );
		
		$html = '';
        $spt  = '';
        foreach ($parameter AS $key => $val)
        {
			$html .= $spt . "$key=$val";
            $spt = '&';
        }

	
		$sign = md5("Depay={$parameter['Depay']}&gDepay={$parameter['gDepay']}&addcoin={$parameter['addcoin']}&Uname={$parameter['Uname']}&Money={$parameter['Money']}&GameId={$parameter['GameId']}&ServiceId={$parameter['ServiceId']}&Transactionid={$parameter['Transactionid']}&Key={$server_info['server_paykey']}");
		
		$returl = $server_info['server_payurl']."?action=pay&".$html."&Sign=".$sign;	
 		$ret = file_get_contents($returl);
		
		$log = array();
		$log['url'] = $returl;
		$log['return'] = $ret;
		log_save('游戏充值接口日志_功夫','game_pay_gf',$log);
		if($ret == 1){
			return true;
		}
        return false;
    }
	
	
	/*
	*	获得新手卡
	*/
	#function get_card($user_info,$server_info){
		#return md5($user_info['member_id'].'_'.$server_info['server_sid']);
	#}
   
}



?>