var xlinfo=Array();
var xlcount = 0;
var xlpg=1;
var xlpgc=8;
var xlpglist=5;
var ser_id=Array();

function get_sxdxl(){
	var url="ajax_sxdxl.html?act=getdate";
	ajax_action(url,{ },null,function(data){
		var mydata=eval('(' + data + ')');
		xlinfo=mydata.xlinfo;
		
		xlcount=0;
		for(var i in xlinfo){
			ser_id[i]=i;
			xlcount+=1;
		}

		set_data();
		set_page();
	});
}
function xlsearch(){
	var sern = $("#xlname").val();
	xlcount=0;
	for(var i in xlinfo){
		if(sern!="")
			if(xlinfo[i].question.indexOf(sern)< 0) continue;
		
		ser_id[xlcount]=i;
		xlcount+=1;
	}
	xlpg=1;
	set_data();
	set_page();
}

function topage(pgindex){
	xlpg=pgindex;
	set_page();
	set_data();
}

function txtclean(){
	$("#xlname").val('');
}
$(document.body).ready(function(){
	get_sxdxl();
	document.getElementById("xlname").onclick=(function(){
				if($("#xlname").val()=="请输入最显著的关键词"){
					$("#xlname").val("");
				}
				$("#xlname").css("color","#000"); 
			}
		);
	document.getElementById("xlname").onkeyup=(function(){xlsearch()});
	}
);

function set_page(){
	if(xlcount<=0){
		$("#xlpage").html('<a href="###">首页</a><a href="###">尾页</a>');
		return;
	}
	var shtml = '';
	var v_pc=Math.ceil(xlcount/xlpgc);
	var pi=1;
	shtml += '<a href="###" onclick="topage(1);">首页</a>';
	if(xlpg>1)
		shtml += '<a href="###" onclick="topage('+(xlpg-1)+');">上一页</a>';
	else
		shtml += '<a href="###">上一页</a>';
	
	for(var i=1 + (Math.ceil(xlpg/xlpglist)-1) * xlpglist;i<=v_pc;i++){
		if(pi>xlpglist) break;
		if(i!=xlpg)
			shtml += '<a href="###" onclick="topage('+i+');">'+i+'</a>';
		else
			shtml += '<strong>'+i+'</strong>'
		pi++;
	}
	if(xlpg<v_pc)
		shtml += '<a href="###" onclick="topage('+(xlpg+1)+');">下一页</a>';
	else
		shtml += '<a href="###">下一页</a>';

	shtml += '<a href="###" onclick="javascript:topage('+v_pc+');">末页</a>';
	shtml += '['+xlpg+'/'+v_pc+']';
	$("#xlpage").html(shtml);
}
function set_data(){
	var shtml = '';
	if(xlcount<=0){
		shtml +='<table width="100%" height="100%"><tr><td align="center" valign="middle">无符合记录！</td></tr></table>';
		$("#xldata").html(shtml);
		return;
	}
	shtml +='<table width="100%" border="0" cellspacing="1" cellpadding="0" >';
	
	for(var i=xlpg*xlpgc-xlpgc;i<xlpg*xlpgc;i++){
		if(i >= xlcount) break;
		shtml +='<tr>';
		shtml +='<td  align="left" height="22px" width="76%">'+xlinfo[ser_id[i]].question.substring(0,30)+'</td>';
		shtml +='<td align="left" height="24px">'+xlinfo[ser_id[i]].opt1.substring(0,8)+'</td>';
		shtml +='</tr>';
	}
	shtml +='</table>';
	$("#xldata").html(shtml);
}