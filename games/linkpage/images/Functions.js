window.onerror=function(a,b,c){return true;}

var openview=true;
var js_ver=false;//true;
//if (ScriptEngineMinorVersion()<5) js_ver=false;
//alert(ScriptEngineMajorVersion()+"."+ScriptEngineMinorVersion()+"."+ScriptEngineBuildVersion());

//改变图片大小
function resizepic(thispic)
{
	if(thispic.width>600) thispic.width=600;
}
//无级缩放图片大小
function bbimg(o)
{
  var zoom=parseInt(o.style.zoom, 10)||100;
  zoom+=event.wheelDelta/12;
  if(zoom>0){
	  o.style.zoom=zoom+'%';
	}
  return false;
}

//魔法表情
function ShowMagicFace()
{
    var obj=document.getElementById("magicFrame");
    var buttonElement = document.getElementById("magicImage");
    if (obj.style.visibility=="hidden")
    {
        obj.style.top = (getOffsetTop(buttonElement) + buttonElement.offsetHeight - 5)+"px";
        obj.style.left = (getOffsetLeft(buttonElement) - 410 + 5)+"px";
        obj.style.visibility="visible";
    }else {
        obj.style.visibility="hidden";
    }
}

function MM_showHideLayers()
{
	var i,p,v,obj,args=MM_showHideLayers.arguments;obj=document.getElementById("MagicFace");
	for (i=0; i<(args.length-2); i+=3)
	if (obj) { v=args[i+2];
	if (obj.style)
	{ obj=obj.style; v=(v=='show')?'visible':(v=='hide')?'hidden':v; }obj.visibility=v; }
}

function ShowMagicFace(MagicID)
{
	var MagicFaceUrl = "images/face/flash/" + MagicID + ".swf";
	document.getElementById("MagicFace").innerHTML = '<object codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="500" height="350"><param name="movie" value="'+ MagicFaceUrl +'"><param name="menu" value="false"><param name="quality" value="high"><param name="play" value="false"><param name="wmode" value="transparent"><embed src="' + MagicFaceUrl +'" wmode="transparent" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="500" height="350"></embed></object>';
	document.getElementById("MagicFace").style.top = (document.body.scrollTop+((document.body.clientHeight-300)/2))+"px";
	document.getElementById("MagicFace").style.left = (document.body.scrollLeft+((document.body.clientWidth-480)/2))+"px";
	document.getElementById("MagicFace").style.visibility = 'visible';
	MagicID += Math.random();
	setTimeout("MM_showHideLayers('MagicFace','','hidden')",8000);
	var NowMeID = MagicID;
}

function SelectColor(tcstr)
{
  var c=window.showModalDialog("../../Editor/Dialog/selcolor.htm","s","dialogWidth=300px;dialogHeight=270px;status=0");
  var d=c;
  if (c && c!="")
  {
    if (d.length==7) { d=d.substr(1,d.length); }
    eval("window.document.all."+tcstr+".value=d;window.document.all."+tcstr+".style.backgroundColor=c;");
  }
}
function ClearColor(tcstr)
{
  eval("window.document.all."+tcstr+".value='';window.document.all."+tcstr+".style.backgroundColor='';");
}

//<input type=test name=tim value='2003-5-19' size=12 maxlength=10 readonly>
//<input type=button name=st_btn value="选择" onclick="javascript:select_time(tim);return false;">

function select_time(st_obj)
{
  var showx=event.screenX-event.offsetX-14;
  var showy=event.screenY-event.offsetY-168;
  var retval=window.showModalDialog("../Admin/Include/Admin_Select_Data.asp?"+st_obj.value,"","dialogWidth:197px; dialogHeight:210px; dialogLeft:"+showx+"px; dialogTop:"+showy+"px; status:no; directories:yes;scrollbars:no;Resizable=no;");
  if( retval!=null ) { st_obj.value=retval; }
  //else { }
}

function click_return(cvar,ct)
{
  var nvar='';
  switch (ct)
  {
    case 1:
      nvar=cvar;
      break;
    default :
      nvar='您确定要'+cvar+'吗？\n\n执行该操作后将不可恢复！';
      break;
  }
  var cf=window.confirm(nvar);
  if (cf) { return true; }
  return false;
}

function note_emoney(nvar,nemoney,npower)
{
  if (nemoney==0) { return true; }
  var t1=window.confirm(nvar+" 将扣除你 "+nemoney+" 货币！\n\n1、如已扣除过将不再二次扣除；\n2、未过期的计时用户将不扣除；\n\n扣除后将不能恢复！ 你确定吗？");
  if (t1) { return true; }
  return false;
}

function login_true()
{
  if (document.login_frm.username.value=="") { alert("请输入您在本站注册时的 用户名称 ！"); document.login_frm.username.focus(); return false; }
  if (document.login_frm.password.value=="") { alert("请输入您在本站注册时的 登陆密码 ！"); document.login_frm.password.focus(); return false; }
  if (document.login_frm.valcode.value=="") { alert("请输入系统提示的正确验证码信息 ！"); document.login_frm.password.focus(); return false; }
}

function open_win(url,name,width,height,scroll)
{
  var Left_size = (screen.width) ? (screen.width-width)/2 : 0;
  var Top_size = (screen.height) ? (screen.height-height)/2 : 0;
  var open_win=window.open(url,name,'width=' + width + ',height=' + height + ',left=' + Left_size + ',top=' + Top_size + ',toolbar=no,location=no,directories=no,status=yes,menubar=no,scrollbars=' + scroll + ',resizable=no' );
}

function frm_submitonce(theform)
{
  if (document.all||document.getElementById)
  {
    for (i=0;i<theform.length;i++)
    {
      var tempobj=theform.elements[i]
      if(tempobj.type.toLowerCase()=="submit"||tempobj.type.toLowerCase()=="reset") { tempobj.disabled=true; }
    }
  }
}

function frm_quicksubmit(eventobject)
{
  if (event.keyCode==13 && event.ctrlKey)
  {
    frm_submitonce(eval("document.write_frm"));
    write_frm.submit();
  }
}

function open_view(ourl,ot)
{
  if (openview==true)
  { window.open(ourl); }
  else
  { document.location.href=""+web_dir+ourl+""; }
}


var divTop,divLeft,divWidth,divHeight,docHeight,docWidth,objTimer,i = 0;
function getMsg()
{
	try{
	divTop = parseInt(document.getElementById("eMeng").style.top,10)
	divLeft = parseInt(document.getElementById("eMeng").style.left,10)
	divHeight = parseInt(document.getElementById("eMeng").offsetHeight,10)
	divWidth = parseInt(document.getElementById("eMeng").offsetWidth,10)
	docWidth = document.body.clientWidth;
	docHeight = document.body.clientHeight;
	document.getElementById("eMeng").style.top = parseInt(document.body.scrollTop,10) + docHeight + 10;//  divHeight
	document.getElementById("eMeng").style.left = parseInt(document.body.scrollLeft,10) + docWidth - divWidth
	document.getElementById("eMeng").style.visibility="visible"
	objTimer = window.setInterval("moveDiv()",10)
	}
	catch(e){}
}

function resizeDiv()
{
	i+=1
	if(i>1500) closeDiv()
	try{
	divHeight = parseInt(document.getElementById("eMeng").offsetHeight,10)
	divWidth = parseInt(document.getElementById("eMeng").offsetWidth,10)
	docWidth = document.body.clientWidth;
	docHeight = document.body.clientHeight;
	document.getElementById("eMeng").style.top = docHeight - divHeight + parseInt(document.body.scrollTop,10)
	document.getElementById("eMeng").style.left = docWidth - divWidth + parseInt(document.body.scrollLeft,10)
	}
	catch(e){}
}

function moveDiv()
{
	try
	{
	if(parseInt(document.getElementById("eMeng").style.top,10) <= (docHeight - divHeight + parseInt(document.body.scrollTop,10)))
	{
	window.clearInterval(objTimer)
	objTimer = window.setInterval("resizeDiv()",1)
	}
	divTop = parseInt(document.getElementById("eMeng").style.top,10)
	document.getElementById("eMeng").style.top = divTop - 1
	}
	catch(e){}
}
function closeDiv()
{
	document.getElementById('eMeng').style.visibility='hidden';
	if(objTimer) window.clearInterval(objTimer)
}

//window.onerror=function(a,b,c){return true;}

//浏览器类型变量
var InternetExplorer = navigator.appName.indexOf("Microsoft") != -1;

var client_browse="";
if (document.getElementById)	// IE5+,NN6+	document.getElementById("id");
{ client_browse="ie5"; }
else if (document.all)		// IE4		document.all("id");
{ client_browse="ie4"; }
else if (document.layers)	// NN4		document.layers["id"];
{ client_browse="ns4"; }

function get_id(strname)
{
  switch (client_browse)
  {
    case "ie5":
      return document.getElementById(strname);
      break;
    case "ns4":
      return document.layers[strname];
      break;
    default :	//"ie4"
      return document.all(strname);
      break;
  }
}

function insert_value(strinput,strvalue)
{
  var tmpstr="";
  switch (client_browse)
  {
    case "ie5":
      tmpstr="document.all."+strinput+".value='"+strvalue+"';";
      break;
    case "ns4":
      tmpstr="document.layers.[\""+strinput+"\"].value='"+strvalue+"';";
      break;
    default :	//"ie4"
      tmpstr="document.all."+strinput+".value='"+strvalue+"';";
      break;
  }
  if (tmpstr!="") eval(tmpstr);
}

function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_findObj(n, d) { //v3.0
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}

//---------下拉菜单-------------------------------------------------------------------------------------------
var menuOffX=0	//菜单距连接文字最左端距离
var menuOffY=18	//菜单距连接文字顶端距离
var vBobjects = new Array();
var fo_shadows=new Array()
var linkset=new Array()
////No need to edit beyond here
var ie4=document.all&&navigator.userAgent.indexOf("Opera")==-1
var ns6=document.getElementById&&!document.all
var ns4=document.layers
function showmenu(e,vmenu,mod){
	if (!document.all&&!document.getElementById&&!document.layers)
		return
	which=vmenu
	clearhidemenu()
	ie_clearshadow()
	menuobj=ie4? document.all.popmenu : ns6? document.getElementById("popmenu") : ns4? document.popmenu : ""
	menuobj.thestyle=(ie4||ns6)? menuobj.style : menuobj
	
	if (ie4||ns6)
		menuobj.innerHTML=which
	else{
		menuobj.document.write('<layer name=gui bgColor=#E6E6E6 width=165 onmouseover="clearhidemenu()" onmouseout="hidemenu()">'+which+'</layer>')
		menuobj.document.close()
	}
	menuobj.contentwidth=(ie4||ns6)? menuobj.offsetWidth : menuobj.document.gui.document.width
	menuobj.contentheight=(ie4||ns6)? menuobj.offsetHeight : menuobj.document.gui.document.height
	
	eventX=ie4? event.clientX : ns6? e.clientX : e.x
	eventY=ie4? event.clientY : ns6? e.clientY : e.y
	
	var rightedge=ie4? document.body.clientWidth-eventX : window.innerWidth-eventX
	var bottomedge=ie4? document.body.clientHeight-eventY : window.innerHeight-eventY
		if (rightedge<menuobj.contentwidth)
			menuobj.thestyle.left=ie4? document.body.scrollLeft+eventX-menuobj.contentwidth+menuOffX : ns6? window.pageXOffset+eventX-menuobj.contentwidth : eventX-menuobj.contentwidth
		else
			menuobj.thestyle.left=ie4? ie_x(event.srcElement)+menuOffX : ns6? window.pageXOffset+eventX : eventX
		
		if (bottomedge<menuobj.contentheight&&mod!=0)
			menuobj.thestyle.top=ie4? document.body.scrollTop+eventY-menuobj.contentheight-event.offsetY+menuOffY-23 : ns6? window.pageYOffset+eventY-menuobj.contentheight-10 : eventY-menuobj.contentheight
		else
			menuobj.thestyle.top=ie4? ie_y(event.srcElement)+menuOffY : ns6? window.pageYOffset+eventY+10 : eventY
	menuobj.thestyle.visibility="visible"
	ie_dropshadow(menuobj,"#999999",3)
	return false
}
//showmenu vmenu:内容，允许为空,MOD 0=关闭浏览器自适应，用于版面导航菜单
function showmenu(e,vmenu,mod){
	if (!document.all&&!document.getElementById&&!document.layers)
		return
	var which=vmenu;
	if (!which)
	{
		return
	}
	clearhidemenu();
	ie_clearshadow();
	menuobj=ie4? document.all.popmenu : ns6? document.getElementById("popmenu") : ns4? document.popmenu : ""
	menuobj.thestyle=(ie4||ns6)? menuobj.style : menuobj
	if (ie4||ns6)
		menuobj.innerHTML=which
	else{
		menuobj.document.write('<layer name=gui bgcolor=#E6E6E6 width=165 onmouseover="clearhidemenu()" onmouseout="hidemenu()">'+which+'</layer>')
		menuobj.document.close()
	}
	menuobj.contentwidth=(ie4||ns6)? menuobj.offsetWidth : menuobj.document.gui.document.width
	menuobj.contentheight=(ie4||ns6)? menuobj.offsetHeight : menuobj.document.gui.document.height
	eventX=ie4? event.clientX : ns6? e.clientX : e.x
	eventY=ie4? event.clientY : ns6? e.clientY : e.y
	var rightedge=ie4? document.body.clientWidth-eventX : window.innerWidth-eventX
	var bottomedge=ie4? document.body.clientHeight-eventY : window.innerHeight-eventY
	var getlength
		if (rightedge<menuobj.contentwidth){
			getlength=ie4? document.body.scrollLeft+eventX-menuobj.contentwidth+menuOffX : ns6? window.pageXOffset+eventX-menuobj.contentwidth : eventX-menuobj.contentwidth
		}else{
			getlength=ie4? ie_x(event.srcElement)+menuOffX : ns6? window.pageXOffset+eventX : eventX
		}
		menuobj.thestyle.left=getlength+'px'
		if (bottomedge<menuobj.contentheight&&mod!=0){
			getlength=ie4? document.body.scrollTop+eventY-menuobj.contentheight-event.offsetY+menuOffY-23 : ns6? window.pageYOffset+eventY-menuobj.contentheight-10 : eventY-menuobj.contentheight
		}	else{
			getlength=ie4? ie_y(event.srcElement)+menuOffY : ns6? window.pageYOffset+eventY+10 : eventY
		}
	menuobj.thestyle.top=getlength+'px'
	menuobj.thestyle.visibility="visible"
	ie_dropshadow(menuobj,"#999999",3)
	return false
}

function ie_y(e){  
	var t=e.offsetTop;  
	while(e=e.offsetParent){  
		t+=e.offsetTop;  
	}  
	return t;  
}  
function ie_x(e){  
	var l=e.offsetLeft;  
	while(e=e.offsetParent){  
		l+=e.offsetLeft;  
	}  
	return l;  
}  
function ie_dropshadow(el, color, size)
{
	var i;
	for (i=size; i>0; i--)
	{
		var rect = document.createElement('div');
		var rs = rect.style
		rs.position = 'absolute';
		rs.left = (el.style.posLeft + i) + 'px';
		rs.top = (el.style.posTop + i) + 'px';
		rs.width = el.offsetWidth + 'px';
		rs.height = el.offsetHeight + 'px';
		rs.zIndex = el.style.zIndex - i;
		rs.backgroundColor = color;
		var opacity = 1 - i / (i + 1);
		rs.filter = 'alpha(opacity=' + (100 * opacity) + ')';
		//el.insertAdjacentElement('afterEnd', rect);
		fo_shadows[fo_shadows.length] = rect;
	}
}
function ie_clearshadow()
{
	for(var i=0;i<fo_shadows.length;i++)
	{
		if (fo_shadows[i])
			fo_shadows[i].style.display="none"
	}
	fo_shadows=new Array();
}


function contains_ns6(a, b) {
	while (b.parentNode)
		if ((b = b.parentNode) == a)
			return true;
	return false;
}

function hidemenu(){
	if (window.menuobj)
		menuobj.thestyle.visibility=(ie4||ns6)? "hidden" : "hide"
	ie_clearshadow()
}

function dynamichide(e){
	if (ie4&&!menuobj.contains(e.toElement))
		hidemenu()
	else if (ns6&&e.currentTarget!= e.relatedTarget&& !contains_ns6(e.currentTarget, e.relatedTarget))
		hidemenu()
}

function delayhidemenu(){
	if (ie4||ns6||ns4)
		delayhide=setTimeout("hidemenu()",500)
}

function clearhidemenu(){
	if (window.delayhide)
		clearTimeout(delayhide)
}

function highlightmenu(e,state){
	if (document.all)
		source_el=event.srcElement
	else if (document.getElementById)
		source_el=e.target
	if (source_el.className=="menuitems"){
		source_el.id=(state=="on")? "mouseoverstyle" : ""
	}
	else{
		while(source_el.id!="popmenu"){
			source_el=document.getElementById? source_el.parentNode : source_el.parentElement
			if (source_el.className=="menuitems"){
				source_el.id=(state=="on")? "mouseoverstyle" : ""
			}
		}
	}
}

if (ie4||ns6)
document.onclick=hidemenu
function doSClick() {
	var targetId, srcElement, targetElement, imageId, imageElement;
	srcElement = window.event.srcElement;
	targetId = srcElement.id + "content";
	targetElement = document.all(targetId);
	imageId = srcElement.id;
	imageId = imageId.charAt(0);
	imageElement = document.all(imageId);
	if (targetElement.style.display == "none") {
		imageElement.src = "Skins/Default/minus.gif"
		targetElement.style.display = "";
	} else {
		imageElement.src = "Skins/Default/plus.gif"
		targetElement.style.display = "none";
	}
}
function doClick() {
	var targetId, srcElement, targetElement;
	srcElement = window.event.srcElement;
	targetId = srcElement.id + "_content";
	targetElement = document.all(targetId);
	if (targetElement.style.display == "none") {
		srcElement.src = "Skins/Default/minus.gif"
		targetElement.style.display = "";
	} else {
		srcElement.src = "Skins/Default/plus.gif"
		targetElement.style.display = "none";
	}
}
function shop_fucchecknum(num)
{
  var i,j,strTemp="0123456789";
  if (num.length== 0)
    return 0
  for (i=0;i<num.length;i++)
  {
    j=strTemp.indexOf(num.charAt(i));  
    if (j==-1)
      return 0;
  }
  return 1;
}

function shop_clear(){ 
  if (confirm("确定要清空购物车？"))
  { window.location.href="?action=clear"; }
}

function shop_checknum(num1,num2) 
{
  if ((shop_fucchecknum(num1.value)==0) )
  {  
    num1.value=num2.value;
    return false;
  }
}

function shop_checknumnull(num1)
{
  if (num1.value=="")
  {  
    alert("请填写购买产品的数量");
    theform.focus();
    return false;
  }
}