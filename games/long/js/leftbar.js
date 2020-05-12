// JavaScript Document

(function(){
	$("dt a").bind("click",function(){
		$(this).parents("dt").siblings("dt").removeClass("curr").end().addClass("curr");
		$("dd ul").not(".serul").hide();
		$(this).parents("dt").next("dd").find("ul").show();	
	})
	$("dd a").bind("click",function(){
		$("dd li").removeClass("active");
		$(this).parent("li").addClass("active").parents("dd").prev("dt").siblings().removeClass("curr").end().addClass("curr");
		
	})
	
})(jQuery)
