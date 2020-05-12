<?php
/**
 * 类
 */
class Game_sg
{

    /**
     * 构造函数
     *
     * @access  public
     * @param
     *
     * @return void
     */
    function Game_sg()
    {
    }

   
	
	/**
     * 生成支付代码
     * @param   array   $server_info  服务器信息
     * @param   array   $user_info    会员信息
     */
    function get_login_url($server_info, $user_info)
    {
        $cm = '0';
		if($user_info['member_idvalid']=='0'){
			$cm = '1';
		}
	   	$time = time();
		$source = '49you';
		if(!empty($user_info['member_advid'])){
			$source = $user_info['member_advid'];
		}
		$parameter = array(
            'user'           => urlencode($user_info['member_name']),
            'time'         => $time,
			'source'        => $source,
			'fangchenmi'    => $cm
        );
		

		
		$parm = '';
        $spt  = '';
		
		$md5_parm = '';
		$spt2  = '';
        foreach ($parameter AS $key => $val)
        {
			$parm .= $spt . "$key=$val";
			$md5_parm .= $spt2 . $val;
            $spt = '&';
			$spt2  = '';
        }
		
		
		$sign = md5($user_info['member_name'].'_'.$time.'_'.$source.'_'.$server_info['server_key']);
		
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
            'user'           => urlencode($user_info['member_name']),
            'time'         => $time,
            'order'         => $order_info['trans_code'],
            'gold'           => intval($order_info['trans_point'])
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
			$spt2 = '';
        }

		$sign = md5($user_info['member_name'].'_'.$time.'_'.$order_info['trans_code'].'_'.intval($order_info['trans_point']).'_'.$server_info['server_paykey']); 
		$returl = $server_info['server_payurl'] . '?' . $html."&sign=".$sign;
	
 		$result = file_get_contents($returl);
		
		$log = array();
		$log['url'] = $returl;
		$log['return'] = $result;
		log_save('游戏充值接口日志_盛世三国','game_pay_sg',$log);
		if($result === '1'){
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