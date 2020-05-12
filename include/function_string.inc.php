<?php
/*
creater devil 2010-08-16
*/
/*
set_magic_quotes_runtime(0); 
if (!get_magic_quotes_gpc())
{    
     add_slashes($_GET);    
     add_slashes($_POST);    
     add_slashes($_COOKIE);
}
*/

function add_slashes($string)
{    
     if (is_array($string))
	 {    
         foreach ($string as $key => $value)
		 {    
             $string[$key] = add_slashes($value);    
         }    
     }
	 else
	 {    
         $string = addslashes($string);
     }    
     return $string;    
}
//编码
function strEncode($str)
{
	if('' == $str){return $str;}
	$str = trim($str);
	//$str = htmlspecialchars($str);
	//$str = addslashes($str);
	/*$str = str_ireplace("select","sel&#101;ct",$str);
	$str = str_ireplace("join","jo&#105;n",$str);
	$str = str_ireplace("union","un&#105;on",$str);
	$str = str_ireplace("where","wh&#101;re",$str);
	$str = str_ireplace("insert","ins&#101;rt",$str);
	$str = str_ireplace("delete","del&#101;te",$str);
	$str = str_ireplace("update","up&#100;ate",$str);
	$str = str_ireplace("like","lik&#101;",$str);
	$str = str_ireplace("drop","dro&#112;",$str);
	$str = str_ireplace("create","cr&#101;ate",$str);
	$str = str_ireplace("modify","mod&#105;fy",$str);
	$str = str_ireplace("rename","ren&#097;me",$str);
	$str = str_ireplace("alter","alt&#101;r",$str);
	$str = str_ireplace("cast","ca&#115;",$str);
	$str = str_ireplace("<script>","&lt;script&gt;",$str);*/
	
	//$str=str_ireplace(chr(32),"&nbsp;",$str);
	//$str=str_ireplace(chr(9),"&nbsp;",$str);
	// $str=str_ireplace(chr(9),"&#160;&#160;&#160;&#160;",$str);
	//$str=str_ireplace(chr(34),"\"",$str);
	//$str=str_ireplace(chr(39),"&#39;",$str);
	//$str=str_ireplace(chr(13),"<br/>",$str);
	
	return $str;
}
//解码
function strDecode($str = '')
{
	if('' == $str){return $str;}
	$str = trim($str);
	//$str = htmlspecialchars_decode($str);
	//$str = stripslashes($str);
	/*$str = str_ireplace("sel&#101;ct","select",$str);
	$str = str_ireplace("jo&#105;n","join",$str);
	$str = str_ireplace("un&#105;on","union",$str);
	$str = str_ireplace("wh&#101;re","where",$str);
	$str = str_ireplace("ins&#101;rt","insert",$str);
	$str = str_ireplace("del&#101;te","delete",$str);
	$str = str_ireplace("up&#100;ate","update",$str);
	$str = str_ireplace("lik&#101;","like",$str);
	$str = str_ireplace("dro&#112;","drop",$str);
	$str = str_ireplace("cr&#101;ate","create",$str);
	$str = str_ireplace("mod&#105;fy","modify",$str);
	$str = str_ireplace("ren&#097;me","rename",$str);
	$str = str_ireplace("alt&#101;r","alter",$str);
	$str = str_ireplace("ca&#115;","cast",$str);
	$str = str_ireplace("&lt;script&gt;","<script>",$str);*/
	
	/*$str=str_ireplace("&nbsp;",chr(32),$str);
	$str=str_ireplace("&nbsp;",chr(9),$str);
	// $str=str_ireplace("&#160;&#160;&#160;&#160;",chr(9),$str);
	$str=str_ireplace("&",chr(34),$str);
	$str=str_ireplace("&#39;",chr(39),$str);
	$str=str_ireplace("<br />",chr(13),$str);*/
	
	return $str;
}
?>