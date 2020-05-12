<?php
//Session保存路径
//$sessSavePath = dirname(__FILE__)."/../data/sessions/";
//if(is_writeable($sessSavePath) && is_readable($sessSavePath)){ session_save_path($sessSavePath); }
session_start();

//获取随机字符
$rndstring = '';
for($i=0; $i<4; $i++) $rndstring .= chr(mt_rand(65,90));
$img_height=45;    //先定义图片的长、宽
$img_width=10;
//如果支持GD，则绘图
if(function_exists("imagecreate"))
{
 //Firefox部份情况会多次请求的问题，5秒内刷新页面将不改变session
 $ntime = time();
 if(empty($_SESSION['dd_ckstr_last']) || empty($_SESSION['dd_ckstr']) || ($ntime - $_SESSION['dd_ckstr_last'] > 5))
 {
  $_SESSION['dd_ckstr'] = strtolower($rndstring);
  $_SESSION['dd_ckstr_last'] = $ntime;
 }
 $rndstring = $_SESSION['dd_ckstr'];
 $rndcodelen = strlen($rndstring);
 //创建图片，并设置背景色
 $im = imagecreate(46,20);
 ImageColorAllocate($im, 240,243,248);
 //干扰线
 $lineColor1 = ImageColorAllocate($im, mt_rand(174,218),mt_rand(190,225),mt_rand(217,237));
 for($j=1;$j<=2;$j=$j+3)
 {
  imageline($im,0,$j+mt_rand(1,15),48,$j+mt_rand(1,15),$lineColor1);
 }
 //输出文字
 $fontColor = ImageColorAllocate($im, mt_rand(0,150),mt_rand(0,150),mt_rand(0,150));
 for($i=0;$i<$rndcodelen;$i++)
 {
  $bc = mt_rand(0,1);
  $rndstring[$i] = strtoupper($rndstring[$i]);
  imagestring($im,mt_rand(3,5),$i*$img_height/4+mt_rand(1,5),mt_rand(1,$img_width/2), $rndstring[$i], $fontColor);
 }
 header("Pragma:no-cache\r\n");
 header("Cache-Control:no-cache\r\n");
 header("Expires:0\r\n");
 //输出特定类型的图片格式，优先级为 gif -> jpg ->png
 if(function_exists("imagepng"))
 {
  header("content-type:image/png\r\n");
  imagepng($im);
 }
 else
 {
  header("content-type:image/jpeg\r\n");
  imagejpeg($im);
 }
 ImageDestroy($im);
 exit();
}
else
{
 //不支持GD，只输出字母 ABCD
 $_SESSION['dd_ckstr'] = "abcd";
 $_SESSION['dd_ckstr_last'] = ''; 
 header("content-type:image/png\r\n");
 header("Pragma:no-cache\r\n");
 header("Cache-Control:no-cache\r\n");
 header("Expires:0\r\n");
 $fp = fopen("data/vdcode.jpg","r");
 echo fread($fp,filesize("data/vdcode.jpg"));
 fclose($fp);
 exit();
}
?>