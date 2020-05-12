// JavaScript Document
var systems=Array(1,2,5);
//重算日期段
function sum_resetdatas(reset_type,reset_dh,redt_star,redt_end,cachefile,system_id){
	if(redt_star==""){
		alert("日期不能为空！");
		return;
	}
	if(!confirm("你确定要重算所有平台的汇总吗?")){return;}
	sum_resdatas(reset_type,reset_dh,redt_star,redt_end,cachefile,0,"",0);
}
//重算单日
function sum_resetday(reset_type,reset_dh,redt_star,cachefile,system_id,reset_t){
	var pdids="";
	if(redt_star==""){
		alert("日期不能为空！");
		return;
	}
	if(!confirm("你确定要重算所有平台的汇总吗?")){return;}
	sum_resday(reset_type,reset_dh,redt_star,cachefile,0,"",0,reset_t);
}
/*
sumact:advmetpopup 为 素材统计
reset_dh:d 为日,h为时
redt:时间日期
*/
function sum_advpopup(sumact,reset_dh,redt,cachefile){
	if(!confirm("你确定要重算所有平台的汇总吗?")){return;}
	sum_resday("advpopup",reset_dh,redt,cachefile,0,sumact,0,"");
}

function sum_resdatas(reset_type,reset_dh,redt_star,redt_end,cachefile,system_id,sumact,cln){
	var myget="";
	var agtype="";
	var agdhtype="";
	var again_link="sum_again.php";
	
	if(reset_dh!="d" && reset_dh!="h") reset_dh="d";
	agdhtype="sum"+reset_dh;
	agtype=reset_type;
	myget = 'act=reset_days$$$redt_star=' + redt_star+'$$$redt_end=' + redt_end + '$$$cachefile='+cachefile+ '$$$sumact='+sumact + '$$$cln='+cln;
	again_link=again_link+"?myget="+myget+"&agtype="+agtype+"&agdhtype="+agdhtype;
	show_editor({ title:'统计重算('+redt_star+'/'+redt_end+')',src:again_link,  onclose:function(){
	}});
}


function sum_resday(reset_type,reset_dh,redt_star,cachefile,system_id,sumact,cln,reset_t){
	var myget="";
	var agtype="";
	var agdhtype="";
	var again_link="sum_again.php";
	var act="resetday";
	if(reset_t!=""){
		redt_star=redt_star+' '+reset_t;
		act="resethour";
	}
	if(reset_dh!="d" && reset_dh!="h") reset_dh="d";
	agdhtype="sum"+reset_dh;
	agtype=reset_type;
	myget = 'act='+act+'$$$redt=' + redt_star + '$$$cachefile='+cachefile+ '$$$sumact='+sumact + '$$$cln='+cln;
	again_link=again_link+"?myget="+myget+"&agtype="+agtype+"&agdhtype="+agdhtype;
	show_editor({ title:'统计重算('+redt_star+')',src:again_link,  onclose:function(){
	}});
}

function syn_data(redt_star,redt_end){
	var myget="";
	var agtype="datasyn";
	var again_link="sum_again.php";
	myget = 'act=reset_days$$$redt_star=' + redt_star+'$$$redt_end=' + redt_end;
	again_link=again_link+"?myget="+myget+"&agtype="+agtype;

	show_editor({ title:'平台数据同步('+redt_star+'/'+redt_end+')',src:again_link,  onclose:function(){
	}});
}

