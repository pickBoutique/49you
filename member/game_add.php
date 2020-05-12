<?php
define('PERMI_CODE','login');
include_once('init_member.inc.php');
$game_id = intval($_REQUEST['gid']);
$server_id = intval($_REQUEST['sid']);
$dir = intval($_REQUEST['dir']);
$games = $db->getAll("select * from " .DB_PREFIX."game where game_id='$game_id' ",300);
$game = $games[0];

$scode = add_slashes($_REQUEST['scode']);
if(!empty($game_id) && !empty($scode)){
	$s_ids = $db->getAll("SELECT server_id FROM " .DB_PREFIX."server where  server_gid = '$game_id' and server_num='$scode' ",300);
	$s_id = $s_ids[0][0];
	if(!empty($s_id)){
		$server_id = $s_id;
	}
}

function show_error_mainten($title = '',$desc = '',$ret_url=''){
	global $db,$game_id;
	include(WEB_ROOT."/member/templates/show_error_mainten.html");
	exit();
}

function show_error_beta($title = '',$desc = '',$ret_url=''){
	global $db,$game_id;
	include(WEB_ROOT."/member/templates/show_error_beta.html");
	exit();
}



$isOk = false;
if(!empty($game)){
	$servers = $db->getAll("select * from " .DB_PREFIX."server where server_id='$server_id'  limit 0,1 ");
	$server = $servers[0];
	if(!empty($server)){
		if($server['server_gid']!=$game_id){
			redir('game_add.html?gid='.$server['server_gid'].'&sid='.$server_id,true);
		}
		
		$can_play = false;
		if($server['server_status']==0 && !isset($_GET['49youdebug']) ){
			$curtime = time();
			$maint = $db->getRow("select * from " .DB_PREFIX."mainten where mainten_sid='$server_id' and mainten_status='1' and mainten_start <= '$curtime' and mainten_end >= '$curtime' ");
			if($maint['mainten_type']=='1'){
				$beta = $db->getRow("select * from " .DB_PREFIX."beta where beta_mainten_id='$maint[mainten_id]' and beta_mid='{$login_info[2]}'  ");
				if(!empty($beta)){
					$can_play = true;
				}else{
					if(empty($act)){
						show_error_beta("",$maint['mainten_desc']);
					}else if($act=='check_beta_code'){
						$beta_code = add_slashes($_REQUEST['beta_code']);
						
						$in_beta = $db->getRow("select * from " .DB_PREFIX."beta where beta_mainten_id='{$maint[mainten_id]}' and beta_code='$beta_code' ");
						if(!empty($in_beta)){
							if($in_beta['beta_mid']!='0'){
								show_error("提示","邀请码已被使用！");
							}else{
								$in_beta['beta_mid'] = $login_info['2'];
								$in_beta['beta_bindtime'] = time();
								$db->update($in_beta,DB_PREFIX.'beta');
								redir('game_add.html?gid='.$game_id.'&sid='.$server_id,true);
							}
						}else{
							show_error("提示","邀请码错误！");
						}
						
					}
				}
			}else{
				if(!empty($maint['mainten_desc'])){
					//show_error("提示",$maint['mainten_desc']);
					show_error_mainten("",$maint['mainten_desc']);
				}else{
					$desc = "游戏正在维护中，请稍后再试...";
					//服务器未到开服时间 默认提示
					if($server['server_start']>time()){
						$desc = '"'.$server['server_name'].'"正式开服时间为：'.date('m月d日 H:i',$server['server_start']).'。敬请期待！';
					}
					show_error_mainten("",$desc);
				}
			}
		}else{
			$can_play = true;
		}
		if($can_play){
			$user_info = $obj_user->get_user_by_id($login_info[2]);
			$count = $db->getOne("select count(*) from " .DB_PREFIX."mygame where mg_mid='{$login_info[2]}'  ");
			$mygame = $db->getRow("select * from " .DB_PREFIX."mygame where mg_mid='{$login_info[2]}' and mg_game_id='$game_id' and mg_server_id='$server_id' ");
			if(!empty($mygame)){
				
				if( (time() - $mygame['mg_time']) > 590){
					$_REQUEST['fromadv'] = '';					
				}
				
				$row = array();
				
				$today = date('Y-m-d');
				$lastday = date('Y-m-d', $mygame['mg_last_time'] );
				//登陆时间与上次登陆时间日期不一致则增加一天活跃数
				if($today != $lastday ){
					$row['mg_login_day'] = $mygame['mg_login_day'] + 1;
					
				}
				
				$row['mg_last_time'] = time();
				$ret = $db->update( $row ,DB_PREFIX."mygame", $mygame['mg_id']);
				if($ret){
					$isOk = true;
				}else{
					show_error("提示","系统繁忙，请稍后再试！");
				}
				
			}else{
				//$user_info = $obj_user->get_user_by_id($login_info[2]);
				$row = array();
				$row['mg_mid'] = $login_info[2];
				$row['mg_member_name'] = $login_info[1];
				$row['mg_game_id'] = $game_id;
				$row['mg_game_name'] = $game['game_name'];
				$row['mg_server_id'] = $server_id;
				$row['mg_server_name'] = $server['server_name'];
				$row['mg_last_time'] = time();
				$row['mg_login_day'] = '1';
				$row['mg_advtype']=$user_info["member_advtype"];
				$row['mg_adv']=$user_info["member_advid"];
				$row['mg_mat']=$user_info["member_metrid"];
				$row['mg_subtype']=$user_info["account_type"];
				$row['mg_time'] = time();
				$row['mg_regtime'] = $user_info['add_time'];
				$row['mg_recom'] = $user_info['member_recom'];
				$row['mg_first'] = $count == 0 ? '1' : '0';
				$ret = $db->insert($row,DB_PREFIX."mygame");
				if($ret){
					$isOk = true;
				}else{
					show_error("提示","系统繁忙，请稍后再试！");
				}
				
				if($count==0){
					//网站来源记录修改会员首款游戏
					$myref = $db->getRow("select ref_id from " .DB_PREFIX."member_ref where ref_memberid='{$login_info[2]}'");
					if(!empty($myref)){
						$row_ref = array();
						$row_ref["ref_id"] = $myref["ref_id"];
						$row_ref['ref_gid'] = $game_id;
						$row_ref['ref_sid'] = $server_id;
						$ref_ret = $db->update($row_ref,DB_PREFIX."member_ref",$row_ref["ref_id"]);
					}
				}
			}
			
			if($isOk){
				//$user_info = $obj_user->get_user_by_id($login_info[2]);
				$code = $game['game_code'];
				$clsname = "Game_{$code}";				
				if(file_exists(WEB_ROOT . '/include/game/'.$code.'.class.php')){
					include(WEB_ROOT . '/include/game/'.$code.'.class.php');
					$login_obj = new $clsname();
					$returl = $login_obj->get_login_url($server,$user_info);
					//是否为直接进游戏
					if($dir=='1'){
						redir($returl . '&client=pc',true);
					}else{
						include(WEB_ROOT.'/member/templates/game_add.html');
					}
					//redir($returl);	
					//show_error("提示","加入游戏成功");
				}else{
					show_error("提示","游戏繁忙，请稍后再试");
				}
			}
		}
	}else{
		show_error("提示","服务器不存在");

	}
}else{
	show_error("提示","游戏不存在");
}
?>