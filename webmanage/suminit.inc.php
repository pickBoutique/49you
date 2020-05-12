<?php
//记录开始时间
$runtime = time();
//清除缓存
$cachefile=$_REQUEST["cachefile"];
//转换 rs 为sql语句表
//$trs=需要转换的查询结果组
//$strField=返回该表的字段项字符 a,b,c
//return (1 as a,2 as b) b 结构的sql语句表
function retsqltable($trs,$strField){
	if(empty($trs))return;
	$strField="";
	$tmptable ="(";
	foreach($trs as $k=>$v){
		if($k==0){
			$tmptable.="select ";
			foreach(array_keys($trs[0]) as $ki=>$vi){
				$tmptable.=($ki>0 ? ",": "")."'".add_slashes($v[$vi])."' as ".$vi;
				$strField.=($ki>0 ? ",": "").$vi;
			}
		}else{
			$tmptable.=" union all select ";
			foreach(array_keys($trs[0]) as $ki=>$vi){
				$tmptable.=($ki>0 ? ",": "")."'".add_slashes($v[$vi])."'";
			}
		}
	}
	$tmptable.=") b";
	return $tmptable;
}
//结束时运行
function fun_endrun(){
	global $db,$runtime,$cachefile;
	//清除缓存
	//echo "<br>清除缓存：".$cachefile;
	if(!empty($cachefile)){
		$db->clear_cache($cachefile);
		echo "<br>清除缓存成功：".(time()-$runtime);
	}
	//输出运行时间
	echo "<br>运行时间(秒)：".(time()-$runtime);
}
?>