/**
 * ÷‹ƒÍ«Ï
 * ∞¢≤º(chenz@uuzu.com)
 * 2011.09.09

$(function(){
	$("[op=showwin]").each(function(){
        $(this).live('click',function(){
            var url = $(this).attr('href'); alert(url);
			var box = $.uboxs.open('pop'+i+'.php',{type:'moterPopBar',contentType:'ajax',onopen:function(self){
				$(".btn_close").click(function(){
					self.close();
				});
				if(self.dh.find('#go_center').length==1){
					self.dh.css('top',$(window).scrollTop());
				}
			}
			});
        });
	});
});
 */
$(function(){
    $("[op=showwin]").each(function(){
        $(this).click(function(){
            var url = $(this).attr('href');
            var box = $.uboxs.open(url,{type:'moterPopBar',contentType:'ajax',onopen:function(self){
                $(".btn_close").click(function(){
                    self.close();
                    return false;
                });
            }});
            return false;
        });
    });
});