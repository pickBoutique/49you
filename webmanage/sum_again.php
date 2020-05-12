<?php
include_once('init.inc.php');
//指定平台重算文件名 2 56uu平台 ，1 快乐营(joy400) 5 49you
$systems=array(
	0=>array('id'=>1,'name'=>'快乐营','refile'=>'joy'),
	1=>array('id'=>2,'name'=>'56uu','refile'=>'56uu'),
	2=>array('id'=>5,'name'=>'49you','refile'=>'')
);
//指定重算类型文件名 adv 广告,advtype 广告子站,advpopup 广告弹窗, server 服务器, datasyn 数据同步, member_ref 来源统计 ,adv_server 
$typefile=array(
	"adv"=>"adv.php",
	"advtype"=>"advsubtype.php",
	"advpopup"=>"adv-popup.php",
	"server"=>"server.php",
	"datasyn"=>"data-syn.php",
	"member_ref"=>"member-ref.php",
	"adv_server"=>"adv-server.php"
	
);
//
$myget=$_REQUEST["myget"];
$myget=str_replace("$$$","&",$myget);
//指定重算类型文件名 adv 广告,advtype 广告子站,advpopup 广告弹窗, else   server 服务器
$agtype=$_REQUEST["agtype"];
$agfile = $typefile[$agtype];
//重算时间类型 sumd 日 sumh 时
$agdhtype=$_REQUEST["agdhtype"];
$agdhtype = empty($agtype) ? "sumd" : $agdhtype;
//重算平台 
$sys_id=$_REQUEST["sys_id"];
//如果是广告弹出统计，只需统计 49you
if($agtype=="advpopup"){
	$sys_id=-1;
	$sysids=array(5);
}else if($agtype=="datasyn"){
	$sys_id=-1;
	$sysids=array(1,2);
}
$resetlink="";

?>
<html>
<style>
.divmain{ width:250px; height:300px; float:left; margin-left:10px; border:#D9EDFF 1px double;}
.divtitle{ width:250px; height:27px; background-color:#F2FAFD; line-height: 27px; background: url("/images/admin_icon.png") repeat scroll 0 -151px;}
.divif{width:100%;height:100%;}
</style>
<body>
<?
foreach($systems as $k => $v){
	//$title="";
	if(empty($sys_id) || $sys_id == $v["id"] || in_array($v["id"],$sysids)){
		
		$resetlink=$v["refile"];
		if(!empty($resetlink)){
			$resetlink.=("-");
		}
		$resetlink.= $agdhtype;
		$resetlink.= (empty($agdhtype) ? "" : "-").$agfile;
		$resetlink.=("?".$myget);
?>
<div class="divmain">
<div class="divtitle">　<?=$v["name"]?></div>
<div class="divif">
<iframe src="<?=$resetlink?>" width="100%" height="100% scrolling="no" frameborder="no" border="0" ></iframe>
</div>
</div>
        <?
	}
}
?>


</body>
</html>