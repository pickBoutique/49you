$("document").ready(function(){
	for (i=1;i<=5;i++)
	{
		$(".stepbox"+i).live('click',function(){
			var name = $(this).children().attr('ref');
			var box = $.uboxs.open(name,{type:'moterPopBar',contentType:'ajax',onopen:function(self){
				$('.btn_close').click(function(){
					self.close();
					return false;
				});
				self.dh.css({top:100});
				}
			});
		});
	}
});
