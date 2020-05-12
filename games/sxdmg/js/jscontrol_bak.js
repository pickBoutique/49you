var mginfo=Array();
var mgdata=Array();
var mgcount=0;
var mgpg=1;
var mgpgc=2;
var mgpglist=5;
var ser_id=Array();
function get_sxdmg(){
	var url="ajax_sxdmg.html?act=getdate";
	ajax_action(url,{},null,function(data){
		var mydata=eval('('+data+')');
		mginfo=mydata.mginfo;
		mgdata=mydata.mgdata;
		mgcount=0;
		for(var i in mginfo){
			ser_id[mgcount]=i;
			mgcount+=1
		};
		set_data();
		set_page();
	})
}

function mgsearch(seatype){
	var serq=$("#quality").val();
	var sere=$("#effect").val();
	var sern=$("#mgname").val();
	mgcount=0;
	for(var i in mginfo){
		if(serq!=""&seatype=='c')if(mginfo[i].quality!=serq)continue;
		if(sere!=""&seatype=='s')if(mginfo[i].effect!=sere)continue;
		if(sern!=""&seatype=='n')if(mginfo[i].name.indexOf(sern)<0)continue;
		ser_id[mgcount]=i;
		mgcount+=1;
	};
	mgpg=1;
	set_data();
	set_page();
};
function topage(pgindex){
	mgpg=pgindex;
	set_page();
	set_data();
};
function txtclean(){
	$("#mgname").val('');
	mgsearch('n');
};
$(document.body).ready(function(){
	get_sxdmg();
	document.getElementById("mgname").onclick=(function(){
		if($("#mgname").val()=="请在这里输入名称"){
			$("#mgname").val("");
		};
		$("#mgname").css("color","#000");
	});
	document.getElementById("mgname").onkeyup=(function(){
		mgsearch('n');
	})
});
function set_page(){
	if(mgcount<=0){
		$("#mgpage").html('<a href="###">首页</a><a href="###">尾页</a>');
		return
	};
	var shtml='';
	var v_pc=Math.ceil(mgcount/mgpgc);
	var pi=1;
	shtml+='<a href="###" onclick="topage(1);">首页</a>';
	if(mgpg>1)shtml+='<a href="###" onclick="topage('+(mgpg-1)+');">上一页</a>';
	else shtml+='<a href="###">上一页</a>';
	for(var i=1+(Math.ceil(mgpg/mgpglist)-1)*mgpglist;
	i<=v_pc;
	i++){
		if(pi>mgpglist)break;
		if(i!=mgpg)shtml+='<a href="###" onclick="topage('+i+');">'+i+'</a>';
		else shtml+='<strong>'+i+'</strong>';
		pi++;
	};
	if(mgpg<v_pc)shtml+='<a href="###" onclick="topage('+(mgpg+1)+');">下一页</a>';
	else shtml+='<a href="###">下一页</a>';
	shtml+='<a href="###" onclick="javascript:topage('+v_pc+');">末页</a>';
	shtml+='['+mgpg+'/'+v_pc+']';
	$("#mgpage").html(shtml);
}
function set_data(){
	var shtml='';
	var ibr=1;
	var objs;
	if(mgcount<=0){
		shtml+='<table width="100%" height="100%"><tr><td align="center" valign="middle">无符合记录！</td></tr></table>';
		$("#mgdata").html(shtml);
		return
	};
	for(var i=mgpg*mgpgc-mgpgc;i<mgpg*mgpgc;i++){
		if(i>=mgcount)break;
		shtml+='<table width="100%" border="0" cellspacing="1" cellpadding="0" style="background-color:#bc7f3c;">';
		shtml+='<tr class="tab">';
		shtml+='<td width="25%" height="24" align="center"><strong>命格</strong></td>';
		shtml+='<td width="17%" height="24" align="center"><strong>品质</strong></td>';
		shtml+='<td colspan="2" align="center"><strong>效果</strong></td>';
		shtml+='</tr>';
		objs=objsize(mgdata[ser_id[i]]);
		for(var j=1;j<=objs/2|j==1;j+=1){
			shtml+='<tr>';
			if(j==1){
				shtml+='<td rowspan="10" align="center" valign="middle" bgcolor="#f5eace"><img src="mgpic/'+mginfo[ser_id[i]].pic+'"/></td>';
				shtml+='<td rowspan="10" align="center" bgcolor="#f5eace">'+mginfo[ser_id[i]].quality+'</td>'
			};
			shtml+='<td align="center"  height="23" bgcolor="#f5eace">'+mgdata[ser_id[i]][j].level+' '+mgdata[ser_id[i]][j].content+'</td>';
			if(j+5<=objs)shtml+='<td align="center" bgcolor="#f5eace">'+mgdata[ser_id[i]][j+5].level+' '+mgdata[ser_id[i]][j+5].content+'</td>';
			shtml+='</tr>'
		};
		shtml+='</table>';
		if(ibr==1){
			shtml+='<br>';
			ibr=0;
		}
	};
	$("#mgdata").html(shtml);
};
function objsize(obj){
	var size=0;
	for(var i in obj){
		size++
	};
	return size;
}