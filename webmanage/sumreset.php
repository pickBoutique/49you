<?php
define('PERMI_CODE','close');
include_once('init.inc.php');
if($act=="resetday"){
	$pagename=$_REQUEST["pn"];
	$st=$_REQUEST["st"];
	if(empty($st))
		$st=strtotime("2011-07-01");
	else
		$st=strtotime($st);

	while($st<time()){
		echo "<a href='".$pagename."?act=resetday&redt=".date("Y-m-d",$st)."' target='_blank'>"."[".date("Y-m-d",$st)."]</a><br>";
		$st+=3600*24;
	}
}
?>