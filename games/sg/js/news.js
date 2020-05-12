$(document).ready(function() {
    $("#news_btn").mouseover(function() {
        $("#news").css({"display":"block"});
        $("#hd").css({"display":"none"});
        $("#mt").css({"display":"none"});
    	$(".news_top").removeClass("hd_on");
        $("#news_btn").addClass("on");
        $("#hd_btn").removeClass("on");
        $("#mt_btn").removeClass("on");
        $("#more").html("<a href='/xinwen/'>更多>></a>");
    });
    $("#hd_btn").mouseover(function() {
        $("#news").css({"display":"none"});
        $("#hd").css({"display":"block"});
        $("#mt").css({"display":"none"});
    	$(".news_top").addClass("hd_on");
        $("#news_btn").removeClass("on");
        $("#hd_btn").addClass("on");
        $("#mt_btn").removeClass("on");
        $("#more").html("<a href='/huodong/'>更多>></a>");
    });
    $("#mt_btn").mouseover(function() {
        $("#news").css({"display":"none"});
        $("#hd").css({"display":"none"});
        $("#mt").css({"display":"block"});
    	$(".news_top").addClass("hd_on");
        $("#news_btn").removeClass("on");
        $("#hd_btn").removeClass("on");
        $("#mt_btn").addClass("on");
        $("#more").html("<a href='/media/'>更多>></a>");
    });

    ///////////////////////////////////
    $("#paihanglist").html(writehtml(1,'btn_1'));
    $("#server_id").change(function(){
    	$("#paihanglist").css({"display":"block"});
    	$("#paihanglist").html(writehtml($("select[name='server_id'] option:selected").val(),$("select[name='btn_on'] option:selected").val()));
    });
    $("#btn_on").change(function(){
    	$("#paihanglist").css({"display":"block"});
    	$("#paihanglist").html(writehtml($("select[name='server_id'] option:selected").val(),$("select[name='btn_on'] option:selected").val()));
    });

    $("#btn_1").mouseover(function(){
    	$("#range > li").removeClass("on");
        $("#btn_1").addClass("on");
    	$("#paihanglist").html(writehtml($("select[name='server_id'] option:selected").val(),"btn_1"));
    });
    $("#btn_2").mouseover(function(){
    	$("#range > li").removeClass("on");
        $("#btn_2").addClass("on");
    	$("#paihanglist").html(writehtml($("select[name='server_id'] option:selected").val(),"btn_2"));
    });
    $("#btn_3").mouseover(function(){
    	$("#range > li").removeClass("on");
        $("#btn_3").addClass("on");
    	$("#paihanglist").html(writehtml($("select[name='server_id'] option:selected").val(),"btn_3"));
    });
    

	$("#linkurl").change(function(){
		if($("select[name='linkurl'] option:selected").val()!=''){
			window.open ($("select[name='linkurl'] option:selected").val()) ;
		}
    });
});

function info(server,type){
	var html="待定,待定";
	$.ajax({
        url:"/gamerange.php",
        data:{server:server,btn:type},
        type: "POST",
        async: false,
        success:function(json) {
			html = json
        }
    });
	return html;
}

function getinfo(temp){
	var temps = new Array();

    temps = temp.split("|");
	for(var i=0;i<temps.length;i++){
		var tempss = temps[i];
		temps[i] = tempss.split(",");
	}
    return temps;
}

function writehtml(server,btn){

	var temps=getinfo(info(server,btn));
	var htmlcon = "<table width='100%' border='0' cellspacing='1' cellpadding='0'><tr class='bold'>";
	if(btn=='btn_1'){
		htmlcon +="<th>排名</th><th>角色名</th><th>声望</th></tr>";
	}else if(btn=='btn_2'){
		htmlcon +="<th>排名</th><th>角色名</th><th>等级</th></tr>";
	}else if(btn=='btn_3'){
		htmlcon +="<th>排名</th><th>角色名</th><th>财富</th></tr>";
	}
	for(var i=0;i<10;i++){
		htmlcon +="<tr><td class='r_font'>"+(i+1)+"";
		for(var j=0;j<2;j++){
			htmlcon +="</td><td class='r_font_"+j+"'>"+temps[i][j];
		}
		htmlcon += "</td></tr>";
	}
	htmlcon +="</table>";
	return htmlcon;
}

function addcookie(name,value,expireHours){
	var cookieString=name+"="+escape(value);
	//判断是否设置过期时间
	if(expireHours>0){
		var date=new Date();
		date.setTime(date.getTime+expireHours*3600*1000);
		cookieString=cookieString+"; expire="+date.toGMTString();
	}
	document.cookie=cookieString;
}
function getcookie(name){
	var strcookie=document.cookie;
	var arrcookie=strcookie.split("; ");
	for(var i=0;i<arrcookie.length;i++){
		var arr=arrcookie[i].split("=");
		if(arr[0]==name)return arr[1];
	}
	return "";
}
function deletecookie(name){
	var date=new Date();
	date.setTime(date.getTime()-10000);
	document.cookie=name+"=v; expire="+date.toGMTString();
}

function getRefer(){
	var str = document.referrer;
	if(str){
		temp = str.split("//");
		website = temp[1].split("/");
		websiteFrame = website[0].split(".");
		if(websiteFrame[1] + "." + websiteFrame[2] != '37wan.com'){
			//alert(website[0]);
			addcookie("37wanrefer",website[0],1);
		}
	}
}