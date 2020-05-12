<?php
include_once('init_member.inc.php');


//account						帐号 传送之前需要urlencode
//truename					用户真名 传送之前需要urlencode
//card
$account = add_slashes($_REQUEST['account']);
$truename = add_slashes($_REQUEST['truename']);
$card = add_slashes($_REQUEST['card']);
$key = 'vsqNuoksZTUhZtTkihjYdvir';
$sign = $_REQUEST['sign'];


if(!empty($sign) && !empty($account) && !empty($truename) && !empty($card)){
	if($sign != md5($_REQUEST['truename'].$_REQUEST['account'].$key.$_REQUEST['card']) ){
		exit('-2');
	}
	$usr_info = $db->getRow("select member_id, member_idvalid from ".DB_PREFIX."member where member_gamename='$account' ");
	if(empty($usr_info)){
		exit('-6');
	}
	if($usr_info['member_idvalid'] == '1'){
		exit('-4');
	}else if( preg_match('/^(\d{18,18}|\d{15,15}|\d{17,17}x)$/',$card) == false){
		exit('-3');
	//}else if( preg_match('/^[\u4e00-\u9fa5]{0,}$/',$truename) == false){
		//exit('-2');
	}else{
		
		$db->update(array('member_idvalid'=>'1','member_truename'=>$truename,'member_idcard'=>$card),DB_PREFIX.'member',$usr_info['member_id']);
		exit('1');
	}
}else{
	exit('-1');
}

?>