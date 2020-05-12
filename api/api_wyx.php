<?php
include_once('init.inc.php');

//登陆接口
$pram_info['UserName'] = $_REQUEST['UserName'];
$pram_info['Password'] = $_REQUEST['Password'];
$pram_info['Time'] = $_REQUEST['Time'];
$pram_info['GameId'] = $_REQUEST['GameId'];
$pram_info['GameAreaID'] = $_REQUEST['GameAreaID'];
$pram_info['VerifyKey'] = $_REQUEST['VerifyKey'];
$parm_info['advtype'] = '129';
$SecurityCode = "35srg93zserg0456kj40tsergnmzesip";





/*
http://www.d8pk.com/api/api_wyx.php?GameAreaID=320&UserName=liang1@up&Password=986ACF3F48CE49E0F19CF4640ADE78D0&Time=1330091662&test=35srg93zserg0456kj40tsergnmzesipliang1@up986ACF3F48CE49E0F19CF4640ADE78D01330091662&VerifyKey=99BC11B130803A7525214F4E390FE402&GameId=16&Title=%e9%be%99%e5%b0%86%2049you%e9%be%99%e5%b0%8658%e6%9c%8d(liang1


$pram_info['UserName'] = "cs001";
$pram_info['Password'] = "chenshun";
$pram_info['Time'] = time();
$pram_info['GameId'] = 13;
$pram_info['GameAreaID'] = 141;
$SecurityCode = "35srg93zserg0456kj40tsergnmzesip";
$pram_info['VerifyKey'] = strtoupper(md5($SecurityCode.$pram_info['UserName'].$pram_info['Password'].$pram_info['Time']));
$pram_info['advtype'] = 129;
foreach($pram_info as $k=>$v){
	$parm .= $parm?"&{$k}={$v}":"{$k}={$v}";
}
$url = "http://www.d8pk.com/api/api_wyx.php?".$parm;
echo $url."<br>";
echo $SecurityCode.$pram_info['UserName'].$pram_info['Password'].$pram_info['Time'];
#die();*/



$login_ok = false;
$error_id = "";
$isOk = false;

//检查密钥
$key_flag = strtoupper(md5($SecurityCode . $pram_info['UserName'] . strtoupper($pram_info['Password']) . $pram_info['Time']));
if($pram_info['VerifyKey']!=$key_flag){
	//密钥不正确
	exit("-1");
}


	$username = add_slashes($pram_info['UserName']."@49you");
	$password = add_slashes($pram_info['Password']);
	
	if(!empty($username) && !empty($password) ){
		$password = $obj_user->encry_pwd($username,$password);
		list($status,$info) = $obj_user->login($username,$password);
		
		if($status == SUC_LOGIN){
			//TODO:登录成功
			
			
			$login_ok = true;
			
		}else if($status == ERR_LOGIN_PWD){
			exit("-2");//密码错误
		}else if($status == ERR_LOGIN_NOT_EXISTS){
			//账号不存在创建一个账号
			$row = array();
			$row['member_name']     = $username;
			$row['email']           ="";
			$row['member_pwd']      =add_slashes($pram_info['Password']);
			$row['member_pwd'] = $obj_user->encry_pwd($row['member_name'],$row['member_pwd']);
			$row['member_advtype'] = $parm_info['advtype'];
			$row['member_advid'] = 443;
			if(preg_match("/[\x80-\xff]./",$username)){
				exit("-3");
			}
			$mid = $obj_user->register($row);
			if($mid){
				$obj_user->login($username,$password);
				$login_ok = true;	
			}
		}else if($status == ERR_LOGIN_ACTIVE){
			exit("-4");//账号未激活
		}
	}


if($login_ok){
	 redir('../game_add.html?sid='.$pram_info['GameAreaID'].'&gid='.$pram_info['GameId'],true);
}



?>