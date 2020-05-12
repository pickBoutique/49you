// JavaScript Document
function getServerInfo(_s) {
ajax_action('index.html?act=getjson&url=http://'+_s+'-ucorp.qjp.kunlun.com/rank.js',{gid:1},null,function(data){
if(data == ''){
	return;
}
var obj=eval("("+data+")");//转换为json对象 
var myrs = obj.user;
var i = 1;
var shtml;
shtml = '<tr>';
shtml +='<th width="32" align="left">名次</th>';
shtml +='<th width="101" align="left">玩家名称</th>';
shtml +='<th width="60" align="left">贡献分</th>';
shtml +='<th width="56" align="left">军功分</th>';
shtml +='<th width="53" align="left">发展分</th>';
shtml +='</tr>';
i=1;
for(var j in myrs){
	shtml +='<tr id="tr'+i+'">';
	shtml +='<td class="num">'+i+'</td>';
	shtml +='<td><div class="jslist1">'+myrs[j].name+'</div></td>';
	shtml +='<td><div class="jslist2">'+myrs[j].tribute+'</div></td>';
	shtml +='<td><div class="jslist3">'+myrs[j].martial+'</div></td>';
	shtml +='<td><div class="jslist4">'+myrs[j].develop+'</div></td>';
	shtml +='</tr>';
	i+=1;
	if(i>8)break;
}
$('#rankTable1').html(shtml);

myrs = obj.nation;
shtml = '<tr>';
shtml +='<th width="32" align="left">名次</th>';
shtml +='<th width="101" align="left">势力名称</th>';
shtml +='<th width="60" align="left">城市总数</th>';
shtml +='<th width="56" align="left">人数</th>';
shtml +='<th width="53" align="left">科技总分</th>';
shtml +='</tr>';
i=1;
for(var j in myrs){
	shtml +='<tr id="tr'+i+'">';
	shtml +='<td class="num">'+i+'</td>';
	shtml +='<td><div class="jslist1">'+myrs[j].name+'</div></td>';
	shtml +='<td><div class="jslist2">'+myrs[j].cityNum+'</div></td>';
	shtml +='<td><div class="jslist3">'+myrs[j].userNum+'</div></td>';
	shtml +='<td><div class="jslist4">'+myrs[j].techScore+'</div></td>';
	shtml +='</tr>';
	i+=1;
	if(i>8)break;
}
$('#rankTable2').html(shtml);

myrs = obj.corps;
shtml = '<tr>';
shtml +='<th width="32" align="left">名次</th>';
shtml +='<th width="101" align="left">名称</th>';
shtml +='<th width="60" align="left">军团长</th>';
shtml +='<th width="56" align="left">成员总数</th>';
shtml +='<th width="53" align="left">总军功</th>';
shtml +='</tr>';
i=1;
for(var j in myrs){
	shtml +='<tr id="tr'+i+'">';
	shtml +='<td class="num">'+i+'</td>';
	shtml +='<td><div class="jslist1">'+myrs[j].name+'</div></td>';
	shtml +='<td><div class="jslist2">'+myrs[j].master+'</div></td>';
	shtml +='<td><div class="jslist3">'+myrs[j].userNum+'</div></td>';
	shtml +='<td><div class="jslist4">'+myrs[j].martial+'</div></td>';
	shtml +='</tr>';
	i+=1;
	if(i>8)break;
}
$('#rankTable3').html(shtml);

myrs = obj.hero;
shtml = '<tr>';
shtml +='<th width="32" align="left">名次</th>';
shtml +='<th width="101" align="left">名称</th>';
shtml +='<th width="60" align="left">所属玩家</th>';
shtml +='<th width="56" align="left">所属势力</th>';
shtml +='<th width="53" align="left">等级</th>';
shtml +='</tr>';
i=1;
for(var j in myrs){
	shtml +='<tr id="tr'+i+'">';
	shtml +='<td class="num">'+i+'</td>';
	shtml +='<td><div class="jslist1">'+myrs[j].name+'</div></td>';
	shtml +='<td><div class="jslist2">'+myrs[j].user+'</div></td>';
	shtml +='<td><div class="jslist3">'+myrs[j].nation+'</div></td>';
	shtml +='<td><div class="jslist4">'+myrs[j].level+'</div></td>';
	shtml +='</tr>';
	i+=1;
	if(i>8)break;
}
$('#rankTable4').html(shtml);
});
}
function repchange(id){
$(".on").removeClass('on');
$("#li_"+id).addClass('on');

$(".rankTable").css("display","none");
$("#rankTable"+id).css("display","");
return;
}
