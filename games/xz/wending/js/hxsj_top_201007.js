function ath_tb(e,name,func,cap){if(e.addEventListener){e.addEventListener(name,func,cap);}else if(e.attachEvent){e.attachEvent('on'+name,func);}}
ath_tb(window,"load",function(){
	var g_id=function(id){return document.getElementById(id);},c_obj=function(elem){return document.createElement(elem);},g_obj=function(pnt,elem){return pnt.getElementsByTagName(elem);}
	var furl="http://ossweb-img.qq.com/images/js/top_js/hxsj/",pic="pics_hs.png",css="hxsj.css";
	var rd="?rd="+Math.random(),wp=c_obj("div"),tgBox,ul,c=0,bg=' style="background-image:url('+furl+pic+rd+');"';
	var check=function(){if(ul.offsetWidth==1000){clearInterval(ck);ul.style.height=42+"px";ul.style.overflow="visible";ul.setAttribute("style","");};};
	var delay=function(url){var type=url.split("."),file=type[type.length-1];if(file=="css"){var obj=c_obj("link"),lnk="href",tp="text/css";obj.setAttribute("rel","stylesheet");}else var obj=c_obj("script"),lnk="src",tp="text/javascript";url=furl+url+rd;obj.setAttribute(lnk,url);obj.setAttribute("type",tp);file=="css"?g_obj(document,"head")[0].appendChild(obj):document.body.appendChild(obj);return obj;};delay(css);
	wp.id="tgTB";wp.style.position="absolute";wp.style.left=wp.style.top=0;wp.style.backgroundImage='url('+furl+pic+rd+')';document.body.appendChild(wp);document.body.style.paddingTop=42+"px";
	wp.innerHTML=[
	'<ul style="height:0;overflow:hidden;">',
		'<li class="tgIcons">',
			'<a href="http://hxsj.qq.com/main.shtml"'+bg+'onclick="pgvSendClick({hottag:\'IED.act.title.logo\'});" target="_blank">幻想世界首页</a>',
			'<a class="tgI2"'+bg+'onclick="pgvSendClick({hottag:\'IED.act.title.pic\'});" target="_blank">幻想世界活动</a>',
		'</li>',
		'<li class="tgMore"'+bg+'>',
			'<a href="http://hxsj.qq.com/" onclick="pgvSendClick({hottag:\'IED.act.title.more\'});" target="_blank">更多精彩活动</a>',
		'</li>',
		'<li class="tgNavi">',
			'<a href="http://hxsj.qq.com/" onclick="pgvSendClick({hottag:\'IED.act.title.index\'});" target="_blank">首页</a>',
			'<a href="http://hxsj.qq.com/version2010/newer/newer_index.htm" onclick="pgvSendClick({hottag:\'IED.act.title.down\'});" target="_blank">游戏指南</a>',
			'<a href="http://hxsj.qq.com/down.shtml" onclick="pgvSendClick({hottag:\'IED.act.title.ts\'});" target="_blank">游戏下载</a>',
			'<a href="http://service.qq.com/category/1854_1.html" onclick="pgvSendClick({hottag:\'IED.act.title.kf\'});" target="_blank">客服</a>',
			'<a href="http://hxsj.gamebbs.qq.com/" onclick="pgvSendClick({hottag:\'IED.act.title.bbs\'});" target="_blank">官方论坛</a>',
		'</li>',
	'</ul>'].join("");
	delay("gg.js");
	var ul=g_obj(wp,"ul")[0];
	var ck=setInterval(check,10);
});/*  |xGv00|cbbb0c0ac0d8e9b06fe855c5b8ac7f52 */