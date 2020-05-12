//传入的参数
if(typeof(isleft)=='undefined')	var isleft=false; //左右飘
if(typeof(isevo)=='undefined') var isevo=true; //是否一天只显示一次
if(typeof(absolute_flash_root)=='undefined')  absolute_flash_root=''; //js所在的域
if(typeof(absolute_flash_link)=='undefined')  absolute_flash_link='#'; //flash的链接
if(typeof(absolute_flash_width)=='undefined' || absolute_flash_width=='')  absolute_flash_width='120'; //flash的链接
if(typeof(absolute_flash_height)=='undefined' || absolute_flash_height=='')  absolute_flash_height='300'; //flash的链接

var isshow=true;
var cookname ="Cusgameflash";
var aftop=250;
if(getCookie(cookname)!=null)isshow=false;

if(isshow){
	var cs='<style>';
	cs+='#Cusgameflash {';
	cs+='POSITION: absolute; TOP: '+aftop+'px; '+ (isleft==true ? 'left' : 'right') +': 0px; PADDING-TOP: 10px;z-index:9999; }';
	//cs+='position:absolute;bottom:0;right:50; z-index:200; margin-left:0;';
	cs+='.clo{height:14px;float:right;font-size:12px;margin-right:2px;}';
	cs+='.clo a{color:#FFF;}';
	cs+='</style>';
	document.write(cs);
	

	var af= '<div id="Cusgameflash">';
	af+='<div style="width:'+absolute_flash_width+'px; height:16px;background-color:#000"><div class="clo"><b><a href="javascript:closeflash();">关闭</a></b></div></div>';
	af+='<object style="position:absolute;z-index:0;" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="'+absolute_flash_width+'" height="'+absolute_flash_height+'">';
	af+='<param name="allowNetworking" value="internal" />';
	af+='<param name="movie" value="' + absolute_flash_url + '" />';
	af+='<param name="wmode" value="transparent" />';
	af+='<param name="quality" value="high" />';
	af+='<embed src="' + absolute_flash_url + '"  style="position:absolute;z-index:0;"  allowNetworking="internal"  wmode="transparent"  quality="high" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="'+absolute_flash_width+'" height="'+absolute_flash_height+'" ></embed>';
	af+='</object>';
	af+='<div style="position: relative;z-index:9999;left:0pt;top:0pt;width:'+absolute_flash_width+'px;height:'+absolute_flash_height+'px;">';
    af+='<a href="' + absolute_flash_link + '" target="_blank" >';
	af+='<img   src="' + absolute_flash_root + '/images/1px.gif"   width="'+absolute_flash_width+'"   height="'+absolute_flash_height+'"   border="0">';
    af+='</a>';
    af+='</div>';
	af+='</div>';
	
	document.write(af);
}


function closeflash(){
	document.getElementById("Cusgameflash").style.display="none";
	
	if(isevo) setCookie(cookname,1,"d1");
	
}

jQuery(document).ready(function($){
	function onscroll() {
		var bodyTop = 0;
		if (typeof window.pageYOffset != 'undefined') {
			bodyTop = window.pageYOffset
		} else if (typeof document.compatMode != 'undefined' && document.compatMode != 'BackCompat') {
			bodyTop = document.documentElement.scrollTop
		} else if (typeof document.body != 'undefined') {
			bodyTop = document.body.scrollTop
		}
		//var ssTop=$(window).height()+bodyTop-absolute_flash_height-28;
		var ssTop= parseInt($(window).height() / 2) + bodyTop - parseInt(absolute_flash_height / 2) -28;
		$("#Cusgameflash").css("top", ssTop);
	}
	onscroll();
	$(window).scroll(onscroll);
	$(window).resize(onscroll);
});

//JS操作cookies方法!
//写cookies
function setCookie(name,value,time){
	var strsec = getsec(time);
	var exp = new Date();
	exp.setTime(exp.getTime() + strsec*1);
	document.cookie = name + "="+ escape (value) + ";expires=" + exp.toGMTString();
}
//读取cookies
function getCookie(name){
	var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");
	if(arr=document.cookie.match(reg)) return unescape(arr[2]);
	else return null;
}
//删除cookies
function delCookie(name){
	var exp = new Date();
	exp.setTime(exp.getTime() - 1);
	var cval=getCookie(name);
	if(cval!=null) document.cookie= name + "="+cval+";expires="+exp.toGMTString();
}
//这是有设定过期时间的使用示例：
//s20是代表20秒
//h是指小时，如12小时则是：h12
//d是天数，30天则：d30
//暂时只写了这三种
//setCookie("name","hayden","s20″);
function getsec(str){
	var str1=str.substring(1,str.length)*1; 
	var str2=str.substring(0,1); 
	if (str2=="s"){
	return str1*1000;
	}else if (str2=="h"){
	return str1*60*60*1000;
	}else if (str2=="d"){
	return str1*24*60*60*1000;
	}
}