function setNews(tag,url){
	var c_arry=new Array('zh','gg','hd','mt');
	for(var i=0;i<4;i++){
		gID("newsLi"+i).src="/template/images/"+c_arry[i]+"1.jpg";
		gID('newsLiLst'+i).style.display='none';
	}
	showDiv('newsLiLst',4,tag);
	gID("newsLi"+tag).src="/template/images/"+c_arry[tag]+"0.jpg";
	gID("newsUrl").href=url;
}

﻿function setZhanShi(tag,url){
	var c_arry=new Array('czb','cgw','cdj');
	for(var i=0;i<2;i++){
		gID("zhanshi"+i).src="/template/images/"+c_arry[i]+"1.jpg";
		gID('zhuangbei'+i).style.display='none';
	}
	showDiv('zhuangbei',2,tag);
	gID("zhanshi"+tag).src="/template/images/"+c_arry[tag]+"0.jpg";
	gID("zhanshiurl").href=url;
} 


﻿function setPic(tag,url){
	var c_arry=new Array('cjt','cwj');
	for(var i=0;i<2;i++){
		gID("pic"+i).src="/template/images/"+c_arry[i]+"1.jpg";
		gID('piclist'+i).style.display='none';
	}
	showDiv('piclist',2,tag);
	gID("pic"+tag).src="/template/images/"+c_arry[tag]+"0.jpg";
	gID("picurl").href=url;
}  

function setAccStatus(){
	var login_game_info = unescape(getCookie('login_game_info'));
	gID('login_game_info').innerHTML=login_game_info;
}

//获取表单对象
function gID(getID){
	return document.getElementById(getID);
}

//组对象显示隐藏
function showDiv(tag,num,tid){
	for(var i=0;i<num;i++){
		gID(tag+i).style.display='none';
	}
	if(tid!=null){
		gID(tag+tid).style.display="block";
	}
}

function setClass(tag,classname){
	gID(tag).className=classname;
}

function CheckLogin(){
	var taget_obj = document.getElementById('_userboxform');
		myajax = new DedeAjax(taget_obj,false,false,'','','');
		myajax.SendGet2("/ajax_loginsta.php");
		DedeXHTTP = null;
}

var $d=function(id){ return document.getElementById(id);}



var sys = {};
var ua = navigator.userAgent.toLowerCase();

var s;

(s = ua.match(/msie ([\d.]+)/)) ? sys.ie = s[1] :

(s = ua.match(/firefox\/([\d.]+)/)) ? sys.firefox = s[1] :

(s = ua.match(/chrome\/([\d.]+)/)) ? sys.chrome = s[1] :

(s = ua.match(/opera.([\d.]+)/)) ? sys.opera = s[1] :

(s = ua.match(/version\/([\d.]+).*safari/)) ? sys.safari = s[1] : 0;

/* //////////////////////////////////////////////////////////////////////////////////////////////////  *   AJAX主函数  *   参数说明：

  *   url 提交页面  可选参数 geturl(id) 自定义 为空 ，3中状态 必选 其它不为必选

  *   fun 调用函数 默认调用client  可选参数 不调用 自定义

  *   method提交方式 get post 默认 GET 

  *   fromid 提交表单的ID或名称

  *   id     可以带一个返回参数

  *   vars 将数据返回给外部变量（注意：在使用它之间必须先定义外部变量比如：var gamehtml='';ajax_ultimate('index_.php','','','','','gamehtml');）这样才是正却的，否则报错 他的等级最高，其次外调函数，再次是ID返回值

  

  *   当method为GET时 只需调用 url 和fum两个即可

  *   当method为POST时 如果URL启用的是 geturl()函数时只需调用 url,fun,method即可 

  *   如果URL为用户定义路径时 需要把url,fun,method,id这4个参数掉齐全

  *   如果URL为空时则属要调用 url,fun,method,id 4个参数

  *   geturl(id)；AJAX附加调用函数

  *   作用：为AJAX取得FORM表单的路径 参数ID为 FORM表单ID或名称

  *   POST用法有3种 

  *   1、URL 用户自定义地址 Fun 可为空 ,method 为POST 输入FORM表单ID  ajax_ultimate(url,fun,method,id)

  *   2、URL 为空 Fun 可为空 ,method 为POST  ,id 提交表单的ID或名称 ajax_ultimate('',fun,method,id)

  *   3、URL 调入url() 函数  Fun 可为空 ,method 为POST ajax_ultimate(geturl(ID),fun,method,id)

*/////////////////////////////////////////////////////////////////////////////////////////////////



function ajax_ultimate(url,fun,method,fromid,id,vars)

{

	new ajaxsends(url,fun,method,fromid,id,vars);

	return ;

}



function ajaxsends(url,fun,method,fromid,id,vars)

{

	var this_=this;

	this_.url=url;

	this_.fun=fun;

	this_.method=method;

	this_.fromid=fromid;

	this_.id=id;

	this_.vars=vars;

	

	/*AJAX执行状态*/

	ajaxsends.prototype.ajax_yun=function()

	{

		//获取执行状态

		this_.ajax.onreadystatechange = function() 

		{

			//如果执行是状态正常，那么就把返回的内容赋值给上面指定的层

			if (this_.ajax.readyState == 4 && this_.ajax.status == 200)

			{

				var	strdiv = this_.ajax.responseText;  //读取PHP页面打印出来的文字

				

				var jsstr=strdiv;

				

				strdiv=strdiv.replace(/<script[\s\S]+?<\/script>/igm,"");

				if(vars)

				{

					eval(this_.vars +"=strdiv");

				}else{

					if(this_.fun)

					{

						eval(this_.fun + "(strdiv,id)");

					}else if(this_.id){

						 document.getElementById(this_.id).innerHTML=strdiv;

					}

				}

				this_.ajaxjs(jsstr);

				

				/*释放变量及缓存*/

				this_.url=this_.fun=this_.method=this_.fromid=this_.id=strdiv=jsstr=this_.ajax=this_.vars=null;

				delete this_.ajax ; 

				this_=null;

				if(sys.ie){CollectGarbage;}

				

		//////////////////////////////////上面是处理//////////////////////////////////////////////////

			}

		}

	}

	

	

	/* 实例化AJAX*/

	ajaxsends.prototype.user_InitAjaxw=function()

	{

		 if (window.ActiveXObject)

		 {

			//IE

			try {

				//IE6.0以上

				return new ActiveXObject("Microsoft.XMLHTTP");

			}catch (e1) {

					//IE5.5以下

					return new ActiveXObject("Msxml2.XMLHTTP");

			}

		} else if (window.XMLHttpRequest) {

			//FireFox

			return new XMLHttpRequest();

		}



	}

	

	/*获取表单URL*/

	ajaxsends.prototype.geturl=function()

	{	

		var u=new Array();

		

		try{

			var url=document.getElementById(this_.fromid).action; 

		}catch(err){

			var  url=document.getElementsByName(this_.fromid)[0].action; 

		}

		

		if(!url)

		{

			alert('表单action属性为空（要提交的地址）！');

			return false;

		}

		

		u[0]=url;

		u[1]=fromid;

		

		return u;

	}

	

	/*执行JS */

	ajaxsends.prototype.ajaxjs=function(msg)

	{

		var str2=msg.split("\r\n").join('');

		str2=str2.split("\n").join('');

		str2=str2.split("\r").join('');

		

		var reg=/<script[^>]*?>(.*?)<\/script>/ig;

		var str=str2.match(reg);

		


		if(!str)

		{

			return false;

		}

		

		for(var i=0;i<str.length;i++)

		{

			str[i]=str[i].replace(reg,"$1");

			try {

				eval(str[i]);

			}catch(e){}



		}

	}

	

	

	/*POST提交*/

	ajaxsends.prototype.post=function()

    {

		if(!this_.url)

		{

			this_.url=this_.geturl();

		}else{

			if ( this_.url.constructor != window.Array)

			{

				var url_=this_.url;

				this_.url = new Array () ; 

				this_.url[0]=url_;

				this_.url[1]=this_.fromid;

			}

		}

		

		

		if (!typeof(this_.url[0])){return;}



		if(!this_.url[0]){return;}

       

		this_.ajax_yun();

		

        this_.ajax.open("POST", this_.url[0], true);

		

        this_.ajax.setRequestHeader("If-Modified-Since","0");

        this_.ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");

		//发送空

		

		try

		{

			var oForm=document.getElementById(this_.url[1]);



		}catch(err){

		

		   var oForm=document.getElementsByName(this_.url[1])[0];

		}

		

		var sBody=getRequestBody(oForm);

		

		this_.ajax.send(sBody);

	}



	



	/*GET提交*/

	ajaxsends.prototype.get=function()

	{

		if (!typeof(this_.url)){return;}

		

		if(!this_.url){return;}

		

		this_.ajax.open("GET",this_.url,true);

		this_.ajax_yun();

		this_.ajax.setRequestHeader("Content-Type","text/html; charset=UTF-8");

		this_.ajax.setRequestHeader("If-Modified-Since","0");

		this_.ajax.send(null);

	}

	//////////////////////////////////////////

	this_.ajax = this_.user_InitAjaxw();

	

	if(!this_.method || this_.method=='get'  || this_.method=='GET')

	{

		

		this_.get();//GET方式

	}else{

		this_.post();//POST方式	

	}

	

	if(sys.ie){CollectGarbage();}//释放缓存

}



function getRequestBody(oForm)

{

	var aParams=new Array();

	for(var i=0;i<oForm.elements.length;i++)

	{

		if(oForm.elements[i].type=="radio" || oForm.elements[i].type=="checkbox")

		{

			if(oForm.elements[i].checked==true)

			{

				var sParam=encodeURIComponent(oForm.elements[i].name);

				sParam+="=";

				sParam+=encodeURIComponent(oForm.elements[i].value);

				aParams.push(sParam);

			}

		}else{

			var sParam=encodeURIComponent(oForm.elements[i].name);

			sParam+="=";

			sParam+=encodeURIComponent(oForm.elements[i].value);

			aParams.push(sParam);

		}

	}

	return aParams.join("&");

}

//ajax_ultimate("http://jtxm.56uu.com/ajax_loginsta3.php","getlogin","get");
function loadContent(url,callback,parameter,flag)
{
	var f=url.split("?").length>=2?"&":"?";
	var timestamp = Date.parse(new Date());	
	flag=flag?flag:0;
	var obj=document.body.getElementsByTagName('SCRIPT');
	if(obj && obj.length && obj[0].id=="sys_tmp_script_"+flag)
	{
		obj[0].parentNode.removeChild(obj[0]);
		var s=document.createElement('SCRIPT');
		s.id="sys_tmp_script_"+flag;
		s.src=url+f+"callback="+callback+"&parameter="+parameter+"&t="+timestamp;
		document.body.insertBefore(s,document.body.childNodes[0]);
	}else{
		var s=document.createElement('SCRIPT');
		s.id="sys_tmp_script_"+flag;
		s.src=url+f+"callback="+callback+"&parameter="+parameter+"&t="+timestamp;
		document.body.insertBefore(s,document.body.childNodes[0]);

	}
}
//ajax_ultimate("http://jtxm.56uu.com/ajax_loginsta3.php","getlogin","get");

function sendData_callback(data,parameter)
{	
 // alert(data.username);return;
	if(data){

		$d("_userboxform").style.display="none"; 
		$d("puserboxform").style.display="block"; 
		$d("username").innerHTML=data.username;
		//alert(data.username);return;
		if(data.servernum){
			$d("oldgame").innerHTML="<a href="+data.gameurl+" target='_blank'>S"+data.servernum+data.title+"</a>";
		}else{
			$d("oldgame").innerHTML="<a href='http://108.56uu.com/server/' target='_blank'>快点我</a>";
		}
	
	//ajax_ultimate("http://jtxm.56uu.com/ajax_loginsta3.php","getlogin","get","",data);
		
	}
} 


function getlogin(string,data)
{
	$d("_userboxform").innerHTML=string;
	
	$d("username").innerHTML=data.username;
	//alert(data.username);return;
	if(data.servernum){
		$d("oldgame").innerHTML="<a href="+data.gameurl+" target='_blank'>S"+data.servernum+data.title+"</a>";
	}else{
		$d("oldgame").innerHTML="<a href='http://108.56uu.com/server/' target='_blank'>快点我</a>";
	}
	//loadContent('http://www.56uu.com/?m=lgame&action=lgame&game_id=24','sendData_callback',0);
}