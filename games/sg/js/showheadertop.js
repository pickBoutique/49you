$(document).ready(function(){
    $("#linkmenu").html("<a href='http://www.37wan.com' target='_blank'>37wan首页</a> <font color=red>旗下游戏：</font><a href='http://sg.37wan.com' target='_blank'>盛世三国</a> | <a href='http://mogong.37wan.com' target='_blank'>墨攻</a> | <a href='http://sxd.37wan.com' target='_blank'>神仙道</a> | <a href='http://tdyx.37wan.com' target='_blank'>天地英雄</a> | <a href='http://astd.37wan.com' target='_blank'>傲视天地</a> | <a href='http://www.37wan.com' target='_blank'>更多>></a>");
	$.ajax({
            url:"/headertop.php",
            type: "GET",
            success:function(json) {
				if(json !=''){
	                $("#scroll_ul").html(json);
				}else{
					$("#scroll_ul").html("hanzh");
				}
            }
	});
    setInterval('AutoScroll("#head_top_frame2")',2000);
});
function AutoScroll(obj){
        $(obj).find("ul:first").animate({
                marginTop:"-30px"
        },500,function(){
                $(this).css({marginTop:"0px"}).find("li:first").appendTo(this);
        });
}