/*
sxdinfos		___
xlcount			____
ser_id			_____
arr_type		______
arr_sw			_______
arr_act			________
op_type			_________
op_sw			__________
op_act			___________
get_sxdxl		____________
xlsearch		_____________
set_form		______________
set_option		_______________
set_condition	________________
set_data		_________________
set_hbdata		__________________
*/

var sxdinfos=Array();
var xlcount = 0;
var ser_id=Array();
var arr_type= Array('不限','剑灵','将星','武圣','飞羽','术士','神秘伙伴');
var arr_sw= Array('不限',0,1000,5000,20000,60000,100000,150000,200000,260000,330000,400000,480000,560000,720000,800000,1000000);
var arr_act= Array('不限','全体','纵向','横向','单体');
var op_type=0;var op_sw=0;var op_act=0;
function get_sxdxl(){
	var url="ajax_sxddata.html?act=getdata";
	ajax_action(url,{ },null,function(data){
		var mydata=eval('(' + data + ')');
		sxdinfos=mydata.sxdinfo;
		
		xlcount=0;
		for(var i in sxdinfos){
			ser_id[i]=i;
			xlcount+=1;
		}

		set_data();
		set_option();
	});
}
$(document.body).ready(function(){
	get_sxdxl();
}
);

function xlsearch(){
	xlcount=0;
	for(var i in sxdinfos){
		if(op_type>0){
			if(sxdinfos[i].type.indexOf(arr_type[op_type])< 0) continue;
		}
		if(op_sw>0){
			if(sxdinfos[i].sw != arr_sw[op_sw]) continue;
		}
		if(op_act>0){
			if(sxdinfos[i].mb.indexOf(arr_act[op_act])< 0) continue;
		}
		ser_id[xlcount]=i;
		xlcount+=1;
	}
	set_data();
}

function set_form(showlist){
	if(showlist==1){
		document.getElementById("xldata").style.display="";
		document.getElementById("xlhbdata").style.display="none";
	}else{
		document.getElementById("xldata").style.display="none";
		document.getElementById("xlhbdata").style.display="";
	}
}
function set_option(){
	var shtml = '';
	var swval='';
	for(var i=0;i<arr_type.length;i++){
		if(i==op_type){
			shtml+='<strong>'+arr_type[i]+'</strong>';
		}else{
			shtml+='<a href="###" onclick="set_condition(\'t\','+i+')">'+arr_type[i]+'</a>';
		}
	}
	$("#div_type").html(shtml);
	
	shtml = '';
	for(var i=0;i<arr_sw.length;i++){
		swval=arr_sw[i]+"";
		if(swval.length>4){
			swval=swval.substr(0,swval.length-4)+"W";
		}else{
			swval=arr_sw[i];
		}
		if(i==op_sw){
			shtml+='<strong>'+swval+'</strong>';
		}else{
			shtml+='<a href="###" onclick="set_condition(\'s\','+i+')">'+swval+'</a>';
		}
	}
	$("#div_sw").html(shtml);
	
	
	shtml = '';
	for(var i=0;i<arr_act.length;i++){
		if(i==op_act){
			shtml+='<strong>'+arr_act[i]+'</strong>';
		}else{
			shtml+='<a href="###" onclick="set_condition(\'a\','+i+')">'+arr_act[i]+'</a>';
		}
	}
	$("#div_act").html(shtml);
}

function set_condition(tt,index){
	if(tt=="t") op_type=index;
	else if(tt=="s") op_sw=index;
	else if(tt=="a") op_act=index;
	set_option();
	xlsearch();
}

function set_data(){
	var shtml = '';
	var obj;

	if(xlcount<=0){
		shtml +='<table width="100%" height="100%" cellpadding="0" cellspacing="0"><tr><td align="center" valign="middle" style="color:#F93">暂无相关内容，建议登录49you神仙道官网<br>查询更多资料：<a href="http://sxd.49you.com/" style="text-decoration: underline; color:#fff;" target="_blank">sxd.49you.com</a></td></tr></table>';
		$("#xldata").html(shtml);
		set_form(1);
		return;
	}
	for(var i=0;i<xlcount;i++){
		if(i >= xlcount) break;
		obj=sxdinfos[ser_id[i]];

		shtml +='<div class="person_img">';
		shtml +='<a href="###" onclick="set_hbdata('+ser_id[i]+')"><img src="imghb/'+obj.imgx+'" width="74" height="74" /></a>';
		shtml +='<p><a href="#" target="_self">'+obj.name+'</a></p>';
		shtml +='</div>';
		
	}
	$("#xldata").html(shtml);
	set_form(1);
}

function set_hbdata(index){
	var shtml = '';
	var obj=sxdinfos[index];;

shtml +='<div class="mleft" >';
shtml +='<table width="100%" border="0" cellspacing="1" cellpadding="0">';
shtml +='<tr>';
shtml +='    <td width="19%" height="21"><a href="###" onclick="set_form(1);" >&lt;&lt;返回</a></td>';
shtml +='    <td colspan="5" align="center" class="f14">'+obj.name+'</td>';
//shtml +='    <td width="18%" align="right"><p></p></td>';
shtml +='  </tr>';
shtml +='  <tr>';
shtml +='    <td height="21" align="center" class="f12">职 业</td>';
shtml +='    <td height="21" colspan="5">'+obj.name+'</td>';
shtml +='  </tr>';
shtml +='  <tr>';
shtml +='    <td height="21" align="center" class="f12">类 型</td>';
shtml +='    <td height="21" colspan="5">'+obj.type+'</td>';
shtml +='  </tr>';
shtml +='  <tr>';
shtml +='    <td height="21" align="center" class="f12">武 器</td>';
shtml +='    <td height="21" colspan="5" align="left">'+obj.fb+'</td>';
shtml +='    </tr>';
shtml +='  <tr>';
shtml +='    <td height="21" align="center" class="f12">进攻技能</td>';
shtml +='    <td height="21" colspan="5" align="left">'+obj.jg+'</td>';
shtml +='    </tr>';
shtml +='  <tr>';
shtml +='    <td height="21" align="center" class="f12">守护技能</td>';
shtml +='    <td height="21" colspan="5" align="left">'+obj.sw+'</td>';
shtml +='    </tr>';
shtml +='  <tr>';
shtml +='    <td height="21" align="center" class="f12">干扰技能</td>';
shtml +='    <td height="21" colspan="5" align="left">'+obj.fy+'</td>';
shtml +='    </tr>';
shtml +='  <tr>';
shtml +='    <td height="21" align="center" class="f12">绝 技</td>';
shtml +='    <td height="21" colspan="5">'+obj.zf+'</td>';
shtml +='  </tr>';
shtml +='  <tr>';
shtml +='    <td height="84" align="center" class="f12">描 述</td>';
shtml +='    <td colspan="5">'+obj.mb+'</td>';
shtml +='  </tr>';

shtml +='</table>';
shtml +='</div>';
shtml +='<div class="mright" ><img src="imghb/'+obj.imgd+'" width="175" height="341" /></div>';

	$("#xlhbdata").html(shtml);
	set_form(0);
}