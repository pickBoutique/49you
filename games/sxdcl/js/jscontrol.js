var gvbhjnngfdew34drftvb=Array();var sdf345g1df0235235=Array();var vwsq12sd7ft8bynoim099=Array();var df89735hnumvcnrthsac=Array(0,0,0);var zaxcvbnmpwerotfihyyjmbhr=Array(1,1,1);var adfbdfgbsoauifbsn=0;var nsdvob8eiousgh987346=1;var ndvoabsdyuvgbas85623=2;var qwetweyvnoiu629784236=0;var zaxcvbnmpwerotfihyyjmbhrc=5;var zaxcvbnmpwerotfihyyjmbhrlist=5;var nnz32758239nnvn09327579=Array();var nnssjafsdf320957394572353=Array();var zlkviosdgyy302485hyg098y=Array();var in22435pifdysf98345f="请输入装备、材料或丹药名";function llskdjfs23432905jklj345(){var url="ajax_sxddata.html?act=getdata";ajax_action(url,{},null,function(data){var mydata=eval('('+data+')');gvbhjnngfdew34drftvb=mydata.gvbhjnngfdew34drftvb;sdf345g1df0235235=mydata.sdf345g1df0235235;vwsq12sd7ft8bynoim099=mydata.vwsq12sd7ft8bynoim099;df89735hnumvcnrthsac[adfbdfgbsoauifbsn]=0;df89735hnumvcnrthsac[nsdvob8eiousgh987346]=0;df89735hnumvcnrthsac[ndvoabsdyuvgbas85623]=0;for(var i in gvbhjnngfdew34drftvb){nnz32758239nnvn09327579[i]=i;df89735hnumvcnrthsac[adfbdfgbsoauifbsn]+=1};for(var i in sdf345g1df0235235){nnssjafsdf320957394572353[i]=i;df89735hnumvcnrthsac[nsdvob8eiousgh987346]+=1};for(var i in vwsq12sd7ft8bynoim099){zlkviosdgyy302485hyg098y[i]=i;df89735hnumvcnrthsac[ndvoabsdyuvgbas85623]+=1};w654w47689w76llpojb90979(mydata.hotkw);ddddddddddddddddddd();ppppppppppppppppp();zvxcmasdf23895723875683()})};function askkljs23903894262303ikj(){var sern=$("#dataname").val();df89735hnumvcnrthsac[adfbdfgbsoauifbsn]=0;df89735hnumvcnrthsac[nsdvob8eiousgh987346]=0;df89735hnumvcnrthsac[ndvoabsdyuvgbas85623]=0;for(var i in gvbhjnngfdew34drftvb){if(sern!="")if(gvbhjnngfdew34drftvb[i].name.indexOf(sern)<0)continue;nnz32758239nnvn09327579[df89735hnumvcnrthsac[adfbdfgbsoauifbsn]+1]=i;df89735hnumvcnrthsac[adfbdfgbsoauifbsn]+=1};for(var i in sdf345g1df0235235){if(sern!="")if(sdf345g1df0235235[i].name.indexOf(sern)<0)continue;nnssjafsdf320957394572353[df89735hnumvcnrthsac[nsdvob8eiousgh987346]+1]=i;df89735hnumvcnrthsac[nsdvob8eiousgh987346]+=1};for(var i in vwsq12sd7ft8bynoim099){if(sern!="")if(vwsq12sd7ft8bynoim099[i].name.indexOf(sern)<0)continue;zlkviosdgyy302485hyg098y[df89735hnumvcnrthsac[ndvoabsdyuvgbas85623]+1]=i;df89735hnumvcnrthsac[ndvoabsdyuvgbas85623]+=1};zaxcvbnmpwerotfihyyjmbhr[adfbdfgbsoauifbsn]=1;zaxcvbnmpwerotfihyyjmbhr[nsdvob8eiousgh987346]=1;zaxcvbnmpwerotfihyyjmbhr[ndvoabsdyuvgbas85623]=1;if(df89735hnumvcnrthsac[ndvoabsdyuvgbas85623]>0){fffffffffffffffff(ndvoabsdyuvgbas85623)}else if(df89735hnumvcnrthsac[nsdvob8eiousgh987346]>0){fffffffffffffffff(nsdvob8eiousgh987346)}else if(df89735hnumvcnrthsac[adfbdfgbsoauifbsn]>0){fffffffffffffffff(adfbdfgbsoauifbsn)}else{fffffffffffffffff(qwetweyvnoiu629784236)};zvxcmasdf23895723875683()};function zvxcmasdf23895723875683(){$("#equ").html("装 备("+df89735hnumvcnrthsac[adfbdfgbsoauifbsn]+")");$("#dru").html("丹 药("+df89735hnumvcnrthsac[nsdvob8eiousgh987346]+")");$("#mat").html("材 料("+df89735hnumvcnrthsac[ndvoabsdyuvgbas85623]+")")};function nmbf54_uhg34534yqwuioe39(strsea){$("#dataname").val(strsea);askkljs23903894262303ikj()};function w654w47689w76llpojb90979(strhk){var shtml='';for(var i in strhk){shtml+='<a href="###" onclick="nmbf54_uhg34534yqwuioe39(\''+strhk[i]+'\')">'+strhk[i]+'</a>'};$("#hotkey").html(shtml)};function t34897fgtsd6f99896bghh74(pgindex){zaxcvbnmpwerotfihyyjmbhr[qwetweyvnoiu629784236]=pgindex;ppppppppppppppppp();ddddddddddddddddddd()};function rrr0896666632563489(){$("#dataname").val('');askkljs23903894262303ikj()};function fffffffffffffffff(tgroup){qwetweyvnoiu629784236=tgroup;$("#equ")[0].className="banns";$("#dru")[0].className="banns";$("#mat")[0].className="banns";if(qwetweyvnoiu629784236==adfbdfgbsoauifbsn){$("#equ")[0].className="bans"}else if(qwetweyvnoiu629784236==nsdvob8eiousgh987346){$("#dru")[0].className="bans"}else if(qwetweyvnoiu629784236==ndvoabsdyuvgbas85623){$("#mat")[0].className="bans"};ppppppppppppppppp();ddddddddddddddddddd()};$(document.body).ready(function(){document.getElementById("dataname").onclick=(function(){if($("#dataname").val()==in22435pifdysf98345f){$("#dataname").val("")};$("#dataname").css("color","#000")});document.getElementById("dataname").onkeyup=(function(){askkljs23903894262303ikj()});document.getElementById("txtcln").onclick=(function(){rrr0896666632563489()});document.getElementById("equ").onclick=(function(){fffffffffffffffff(adfbdfgbsoauifbsn)});document.getElementById("dru").onclick=(function(){fffffffffffffffff(nsdvob8eiousgh987346)});document.getElementById("mat").onclick=(function(){fffffffffffffffff(ndvoabsdyuvgbas85623)});qwetweyvnoiu629784236=ndvoabsdyuvgbas85623;llskdjfs23432905jklj345()});function ppppppppppppppppp(){if(df89735hnumvcnrthsac[qwetweyvnoiu629784236]<=0){$("#datapage").html('<a href="###">首页</a><a href="###">尾页</a>');return};var shtml='';var v_pc=Math.ceil(df89735hnumvcnrthsac[qwetweyvnoiu629784236]/zaxcvbnmpwerotfihyyjmbhrc);var pi=1;shtml+='<a href="###" onclick="t34897fgtsd6f99896bghh74(1);">首页</a>';if(zaxcvbnmpwerotfihyyjmbhr[qwetweyvnoiu629784236]>1)shtml+='<a href="###" onclick="t34897fgtsd6f99896bghh74('+(zaxcvbnmpwerotfihyyjmbhr[qwetweyvnoiu629784236]-1)+');">上一页</a>';else shtml+='<a href="###">上一页</a>';for(var i=1+(Math.ceil(zaxcvbnmpwerotfihyyjmbhr[qwetweyvnoiu629784236]/zaxcvbnmpwerotfihyyjmbhrlist)-1)*zaxcvbnmpwerotfihyyjmbhrlist;i<=v_pc;i++){if(pi>zaxcvbnmpwerotfihyyjmbhrlist)break;if(i!=zaxcvbnmpwerotfihyyjmbhr[qwetweyvnoiu629784236])shtml+='<a href="###" onclick="t34897fgtsd6f99896bghh74('+i+');">'+i+'</a>';else shtml+='<strong>'+i+'</strong>';pi++}if(zaxcvbnmpwerotfihyyjmbhr[qwetweyvnoiu629784236]<v_pc)shtml+='<a href="###" onclick="t34897fgtsd6f99896bghh74('+(zaxcvbnmpwerotfihyyjmbhr[qwetweyvnoiu629784236]+1)+');">下一页</a>';else shtml+='<a href="###">下一页</a>';shtml+='<a href="###" onclick="javascript:t34897fgtsd6f99896bghh74('+v_pc+');">末页</a>';shtml+='['+zaxcvbnmpwerotfihyyjmbhr[qwetweyvnoiu629784236]+'/'+v_pc+']';$("#datapage").html(shtml)}function ddddddddddddddddddd(){var shtml='';if(df89735hnumvcnrthsac[qwetweyvnoiu629784236]<=0){shtml+='<table width="100%" height="100%"><tr><td align="center" valign="middle">暂无相关内容，建议登录49you神仙道官网<br>查询更多资料：<a href="http://sxd.49you.com/" style="text-decoration: underline;" target="_blank">sxd.49you.com</a></td></tr></table>';$("#datadata").html(shtml);return}var cls=Array();var clc=Array();var strcls="";shtml+='<table width="100%" border="0" cellspacing="0" cellpadding="0" >';if(qwetweyvnoiu629784236==adfbdfgbsoauifbsn){shtml+='<tr class="tdbg" height="24">';shtml+='<td width="19%" align="center"><strong>装 备</strong></td>';shtml+='<td width="15%" align="center"><strong>职 业</strong></td>';shtml+='<td width="16%" align="center"><strong>使用等级</strong></td>';shtml+='<td align="center"><strong>制作所需要材料</strong></td>';shtml+='</tr>';for(var i=zaxcvbnmpwerotfihyyjmbhr[qwetweyvnoiu629784236]*zaxcvbnmpwerotfihyyjmbhrc-zaxcvbnmpwerotfihyyjmbhrc+1;i<=zaxcvbnmpwerotfihyyjmbhr[qwetweyvnoiu629784236]*zaxcvbnmpwerotfihyyjmbhrc;i++){if(i>df89735hnumvcnrthsac[qwetweyvnoiu629784236])break;shtml+='<tr height="60">';shtml+='<td align="center"><img src="img_e/'+gvbhjnngfdew34drftvb[nnz32758239nnvn09327579[i]].pic+'" width="40" height="40" border="0" /><br />'+gvbhjnngfdew34drftvb[nnz32758239nnvn09327579[i]].name+'</td>';shtml+='<td align="center">'+gvbhjnngfdew34drftvb[nnz32758239nnvn09327579[i]].pro+'</td>';shtml+='<td align="center">'+gvbhjnngfdew34drftvb[nnz32758239nnvn09327579[i]].level+'</td>';strcls="";cls=gvbhjnngfdew34drftvb[nnz32758239nnvn09327579[i]].cls.split(",");clc=gvbhjnngfdew34drftvb[nnz32758239nnvn09327579[i]].clc.split(",");strcls+="";for(var j=0;j<cls.length;j++){if(cls[j]==""){strcls+="－";break}strcls+=('<div><a href="###" onclick="nmbf54_uhg34534yqwuioe39(\''+cls[j]+'\')">'+cls[j]+"</a>x"+clc[j]+"</div>")}shtml+='<td align="center">'+strcls+'</td>';shtml+='</tr>'}}else if(qwetweyvnoiu629784236==ndvoabsdyuvgbas85623){shtml+='<tr class="tdbg" height="24">';shtml+='<td width="90px"align="center"><strong>材 料</strong></td>';shtml+='<td width="130px" align="center"><strong>掉落点</strong></td>';shtml+='<td width="" align="center"><strong>可制作物品</strong></td>';shtml+='</tr>';for(var i=zaxcvbnmpwerotfihyyjmbhr[qwetweyvnoiu629784236]*zaxcvbnmpwerotfihyyjmbhrc-zaxcvbnmpwerotfihyyjmbhrc+1;i<=zaxcvbnmpwerotfihyyjmbhr[qwetweyvnoiu629784236]*zaxcvbnmpwerotfihyyjmbhrc;i++){if(i>df89735hnumvcnrthsac[qwetweyvnoiu629784236])break;shtml+='<tr height="60">';shtml+='<td align="center"><img src="img_m/'+vwsq12sd7ft8bynoim099[zlkviosdgyy302485hyg098y[i]].pic+'" width="40" height="40" border="0" /><br />'+vwsq12sd7ft8bynoim099[zlkviosdgyy302485hyg098y[i]].name+'</td>';shtml+='<td align="center">'+vwsq12sd7ft8bynoim099[zlkviosdgyy302485hyg098y[i]].gets+'</td>';strcls="";cls=vwsq12sd7ft8bynoim099[zlkviosdgyy302485hyg098y[i]].cls.split(",");strcls+="";for(var j=0;j<cls.length;j++){if(cls[j]=="")break;strcls+=('<div><a href="###" onclick="nmbf54_uhg34534yqwuioe39(\''+cls[j]+'\')">'+cls[j]+"</a></div>")}shtml+='<td align="left">'+strcls+'</td>';shtml+='</tr>'}}else if(qwetweyvnoiu629784236==nsdvob8eiousgh987346){shtml+='<tr class="tdbg" height="24">';shtml+='<td width="90px"align="center"><strong>丹 药</strong></td>';shtml+='<td width="130px" align="center"><strong>使用等级</strong></td>';shtml+='<td align="center"><strong>制作所需要材料</strong></td>';shtml+='</tr>';for(var i=zaxcvbnmpwerotfihyyjmbhr[qwetweyvnoiu629784236]*zaxcvbnmpwerotfihyyjmbhrc-zaxcvbnmpwerotfihyyjmbhrc+1;i<=zaxcvbnmpwerotfihyyjmbhr[qwetweyvnoiu629784236]*zaxcvbnmpwerotfihyyjmbhrc;i++){if(i>df89735hnumvcnrthsac[qwetweyvnoiu629784236])break;shtml+='<tr height="60">';shtml+='<td align="center"><img src="img_d/'+sdf345g1df0235235[nnssjafsdf320957394572353[i]].pic+'" width="40" height="40" border="0" /><br />'+sdf345g1df0235235[nnssjafsdf320957394572353[i]].name+'</td>';shtml+='<td align="center">'+sdf345g1df0235235[nnssjafsdf320957394572353[i]].level+'</td>';strcls="";cls=sdf345g1df0235235[nnssjafsdf320957394572353[i]].cls.split(",");clc=sdf345g1df0235235[nnssjafsdf320957394572353[i]].clc.split(",");strcls+="";for(var j=0;j<cls.length;j++){if(cls[j]=="")break;strcls+=('<div><a href="###" onclick="nmbf54_uhg34534yqwuioe39(\''+cls[j]+'\')">'+cls[j]+"</a>x"+clc[j]+"</div>")}shtml+='<td align="left">'+strcls+'</td>';shtml+='</tr>'}}shtml+='</table>';$("#datadata").html(shtml);
}