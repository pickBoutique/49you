// JavaScript Document
var _toptype="plt";
var _server="";
var js_list_url="";
function getTopInfo() {
	$.getJSON(js_list_url+'?act=getorderjson&gamecode=sxd&_server='+_server+'_'+_toptype+'&format=json&jsoncallback=?',function(data){
	if(data == ''){
		return;
	}
var obj=data;//转换为json对象 
var i = 1;
var shtml;
myrs=eval("("+obj.result+")");
	if(_toptype=="plt"){
		tval="等级";
	}else if(_toptype=="mt"){
		tval="副本";
	}else if(_toptype=="pft"){
		tval="声望";
	}
shtml = '<tr>';
shtml +='<th width="50" height="19" align="center" id="tl_0">名次</th>';
shtml +='<th width="131" align="left" id="tl_1">角色名称</th>';
shtml +='<th width="76" align="left" id="tl_2">'+tval+'</th>';
shtml +='</tr>';
i=1;
var tval="";
for(var j in myrs){
	if(_toptype=="plt"){
		tval=myrs[j].level;
	}else if(_toptype=="mt"){
		tval=myrs[j].mission_name;
	}else if(_toptype=="pft"){
		tval=myrs[j].fame;
	}
	shtml +='<tr id="tr'+i+'">';
	shtml +='<td class="num">'+i+'</td>';
	shtml +='<td><div class="jslist1">'+myrs[j].nickname+'</div></td>';
	shtml +='<td height="21">'+tval+'</td>';
	shtml +='</tr>';
	i+=1;
	if(i>8)break;
}
$('#rankTable1').html(shtml);
});
}
function repchange(id,_tt){
	$(".on").removeClass('on');
	$("#li_"+id).addClass('on');
	
	_toptype=_tt;
	getTopInfo();
return;
}
function serverchange(_ss){
	_server=_ss;
	getTopInfo();
}
function set_url(turl){
	js_list_url=turl;
}
