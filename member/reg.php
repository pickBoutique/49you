<?php

include_once('init_member.inc.php');
$gid = intval($_REQUEST['gid']);
$mid = intval($_REQUEST['mid']);
$at = intval($_REQUEST['at']);
$ad = intval($_REQUEST['ad']);
$md = intval($_REQUEST['md']);
if(empty($act)){
	$member_info = $obj_user->get_user_by_id($mid);
	$returl = strpos( $_REQUEST['returl'],'<')===false ? $_REQUEST['returl'] : '';

	if(empty($returl)){
		if(!empty($gid)){
			//if(!empty($mid)){
				//直接进游戏
				//$server = $db->getRow("select server_id from ".DB_PREFIX."server where server_gid='{$gid}' and server_status='1' order by server_num desc limit 0,1 ");
				//$returl = 'game_add.html?gid='.$gid.'&sid='.intval($server['server_id']);
			//}else{
				$returl = 'game.html?gid='.$gid;
			//}
		}else{
			$returl = 'reg_suc.html';
		}
	}
	
	if($gid>0){
		$game = $db->getRow("select * from ".DB_PREFIX."game where game_id='{$gid}' ");	
		if(!empty($game)){
			if(file_exists(WEB_ROOT.'/member/templates/reg_'.$game['game_code'].'.html')){
				$game_root = str_replace('www',$game['game_code'],YOU_ROOT);
				include_once('templates/reg_'.$game['game_code'].'.html');
				exit();
			}
		}
	}

	include_once('templates/reg.html');
}else if($act=='reg'){
	$obj_member = new User();
	$row = array();
	$row['member_name']     =add_slashes($_REQUEST['loginname']);
	$row['email']           =add_slashes($_REQUEST['email']);
	$row['member_pwd']      =add_slashes($_REQUEST['psw']);
	$row['member_truename'] =add_slashes($_REQUEST['truename']);
	$row['member_idcard']   =add_slashes($_REQUEST['idcard']);
	$row['member_reomname'] =add_slashes($_REQUEST['reomname']);
	$row['birth'] = strtotime($_REQUEST['birth']);
	$row['member_pwd'] = $obj_member->encry_pwd($row['member_name'],$row['member_pwd']);
	$row['member_recom'] = $mid;
	$row['member_advtype'] = $at;
	$row['member_advid'] = $ad;
	$row['member_metrid'] = $md;

	$validcode = $_SESSION['validationcode'];
	$_SESSION['validationcode'] = md5(time());
	$seccode = $_REQUEST['seccode'];

	if(preg_match("/[\x80-\xff]./", $row['member_name'])){
		show_error('注册失败','对不起，用户名称不能使用中文！');
	}

	//if(isset($_REQUEST['seccode']) && strtolower($validcode) != strtolower($seccode)){
		//show_error('注册失败','对不起，验证码错误，请重新输入！');
	//}
	//echo $_REQUEST['psw']."==".$_REQUEST['psw2'];
	if(empty($_REQUEST['psw']) || empty($_REQUEST['psw2'])){
		show_error('注册失败','对不起，密码不能为空，请重新输入！');
	}
	if($_REQUEST['psw']!=$_REQUEST['psw2']){
		show_error('注册失败','对不起，密码和确认码不匹配，请重新输入！');
	}
	$mid = $obj_user->register($row);
	$returl = $_REQUEST['returl'];
	if(empty($returl)){
		if(!empty($gid)){
			$returl = 'game.html?gid='.$gid;
		}else{
			$returl = 'member.html';
		}
	}
	
	if($mid>0){
		list($status,$info) = $obj_user->login($row['member_name'], $row['member_pwd']);
		$usr_info=$obj_user->get_user_by_id($mid);
		
		//记录来源网站
		reg_referrer(1,$usr_info);

		
		if($usr_info["email"]!=""){
			include_once(WEB_ROOT.'/include/email.class.php');
			$mail = new EMail();
			$code = md5(time());
			$actemail = $usr_info['email'];
			$params = array();
			$params['link'] = HTTP_ROOT . "/bind_mail.html?act=active&mid=".$mid."&code=".$code;
			$params['mid'] = $mid;
			$params['code'] = $code;
			$params['sender'] = '49you';
			$params['name'] = $usr_info['member_name'];
			$url = 'bind_mail.html?act=sendmail&mid='.$mid;
			
			if($mail->send_template($actemail,"49you-邮箱激活",null,"member_email",$params)){
				$arremail = explode('@',$actemail);
				$email_path = 'http://mail.' . $arremail[1];
				
				$reg_success = '恭喜您！系统已经发送一封激活邮件到您的邮箱！请在24小时内登录您的邮箱进行激活。<p>点击登录邮箱：<a href="' . $email_path . '" target="_blank" style=" color:#F06706;" >' . $actemail . '</a><a href="' . $url . '"  style=" color:#F06706;">(重发)</a></p>';
				$row=array();
				$row['member_id']=$mid;
				$row['email']=$actemail;
				$row['mac']  =$code;
				$ret = $db->update($row,DB_PREFIX."member");
				show_error("注册成功",$reg_success,"reg_suc.html");
			}
		}
		if($status == SUC_LOGIN){
			//同步登陆到UC
			$synlogin_script = synlogin($row['member_name'], add_slashes($_REQUEST['psw']) , $usr_info['email']);
			//TODO:登录成功
			if(empty($returl)){
				$returl = 'reg_suc.html';
			}
			//exit( "ko4");
			//exit($returl);
			$script = $synlogin_script . "<script language='javascript'>window.location.href='{$returl}';</script>";
			exit($script);
			
		}

	}else if($mid==ERR_REG_EXISTS){
		show_error('注册失败','对不起，该用户已存在，请重新输入！');
	}else if($mid==ERR_SQL_QUERY){
		show_error('注册失败','服务器繁忙，请重试！');
	}
}else if($act=='ajaxreg'){
	function show_ajaxerror($status,$msg){
		$str = array();
		$str['status'] = $status;
		$str['msg'] = $msg;
		$json = json_encode($str);
		header('Content-Encoding: plain');
		echo $_GET['jsoncallback'].'('.$json.')';
		exit();
	}
	$obj_member = new User();
	$row = array();
	$row['member_name']     =add_slashes($_REQUEST['loginname']);
	$row['email']           =add_slashes($_REQUEST['email']);
	$row['member_pwd']      =add_slashes($_REQUEST['psw']);
	$row['member_truename'] =add_slashes($_REQUEST['truename']);
	$row['member_idcard']   =add_slashes($_REQUEST['idcard']);
	$row['member_reomname'] =add_slashes($_REQUEST['reomname']);
	$row['birth'] = strtotime($_REQUEST['birth']);
	$row['member_pwd'] = $obj_member->encry_pwd($row['member_name'],$row['member_pwd']);
	$row['member_recom'] = $mid;
	$row['member_advtype'] = $at;
	$row['member_advid'] = $ad;
	$row['member_metrid'] = $md;


	if(preg_match("/[\x80-\xff]./", $row['member_name'])){
		show_ajaxerror('注册失败','帐号不能使用中文！');
	}

	//echo $_REQUEST['psw']."==".$_REQUEST['psw2'];
	if(empty($_REQUEST['psw']) || empty($_REQUEST['psw2'])){
		show_ajaxerror('注册失败','密码不能为空');
	}
	if($_REQUEST['psw']!=$_REQUEST['psw2']){
		show_ajaxerror('注册失败','两次密码不一致');
	}
	$mid = $obj_user->register($row);
	$returl = $_REQUEST['returl'];
	if(empty($returl)){
		if(!empty($gid)){
			$returl = 'game.html?gid='.$gid;
		}else{
			$returl = 'member.html';
		}
	}
	
	if($mid>0){
		list($status,$info) = $obj_user->login($row['member_name'], $row['member_pwd']);
		$usr_info=$obj_user->get_user_by_id($mid);
		
		
		if($status == SUC_LOGIN){
			//同步登陆到UC
			$synlogin_script = synlogin($row['member_name'], add_slashes($_REQUEST['psw']) , $usr_info['email']);
			//TODO:登录成功
			if(empty($returl)){
				$returl = 'reg_suc.html';
			}
			//exit( "ko4");
			//exit($returl);
			$script = $synlogin_script;
			
			show_ajaxerror('1',$script);
			
		}
	}else if($mid==ERR_REG_EXISTS){
		show_ajaxerror('注册失败','该用户已存在');
	}else if($mid==ERR_SQL_QUERY){
		show_ajaxerror('注册失败','服务器繁忙');
	}
}else if($act=='ajchkuser'){
	$member_name=add_slashes($_REQUEST['loginname']);
	echo($obj_user->check_member_name($member_name));
}else if($act=='guestaccess'){
	reg_referrer(0,null);
}


function reg_referrer($ret_type,$user_info){
	global $db;
	$ret_type = $ret_type == 1 ? 1 : 0;
	$member_ref=add_slashes($_COOKIE["member_referrer"]);
	$ref_desturl=add_slashes($_COOKIE["location_ref"]);
	if(empty($member_ref)){
		return;
	}
	$refarr=array();
	$ref_urlinfo=get_urlinfo($member_ref);
	if(strpos(YOU_ROOT,$ref_urlinfo["ref_domainbs"])){
		return;
	}
	$refarr["ref_platform"]=5;
	if($user_info){
		$refarr["ref_memberid"]=$user_info["member_id"];
		$refarr["ref_membername"]=$user_info["member_name"];
	}
	$refarr["ref_referrer"]=$member_ref;
	$refarr["ref_desturl"]=$ref_desturl;
	$refarr["ref_refmd5"]= empty($ref_urlinfo["keyword"]) ? substr(md5($member_ref),1,16) : substr(md5($ref_urlinfo["keyword"]),1,16);
	$refarr["ref_desturl"]= $ref_desturl;
	$refarr["ref_destmd5"]= substr(md5($ref_desturl),1,16);
	$refarr["ref_domain"]=$ref_urlinfo["domain"];
	$refarr["ref_domainbs"]=$ref_urlinfo["domainbs"];
	$refarr["ref_keyword"]=$ref_urlinfo["keyword"];
	$refarr["ref_ip"]=get_client_ip();
	$refarr["ref_addtime"]=time();
	$refarr["ret_type"]=$ret_type;
	$ret = $db->insert($refarr,DB_PREFIX."member_ref");
}

function get_urlinfo($url){
	$parts = parse_url($url);
	$url_arr = explode(".",$parts["host"]);
	$url_info=array();
	$url_info["host"]=$parts["host"];
	$url_info["domainbs"]=$url_arr[1];
	for($i=1;$i<sizeof($url_arr);$i++){
		if(!empty($url_info["domain"])){
			$url_info["domain"].=".";
		}
		$url_info["domain"].=$url_arr[$i];
	}
	
	$strget="query";

	$url_valarr = explode("&",urldecode($parts[$strget]));
	$url_key =array();
	foreach($url_valarr as $v){
		$tkey = explode("=",$v);
		$url_key[$tkey[0]]=$tkey[1];
	}
	//print_r($url_key);
	switch($url_info["domainbs"]){
		case "baidu":
			if(!empty($url_key["wd"])){
				$url_info["keyword"] = iconv('gb2312','utf-8',$url_key["wd"]);
			}
			if(!empty($url_key["word"])){
				$url_info["keyword"] = iconv('gb2312','utf-8',$url_key["word"]);
			}
		break;
		case "google":
			$url_info["keyword"] =  $url_key["q"];
		break;
		case "soso":
			$url_info["keyword"] =  iconv('gb2312','utf-8',$url_key["w"]);
		break;
		case "sogou":
			$url_info["keyword"] =  iconv('gb2312','utf-8',$url_key["query"]);
		break;
		case "bing":
			$url_info["keyword"] =  $url_key["q"];
		break;
		case "youdao":
			$url_info["keyword"] =  $url_key["q"];
		break;
		case "zhongsou":
			$url_info["keyword"] =  iconv('gb2312','utf-8',$url_key["w"]);
		break;
		
	}
	return $url_info;
}
?>