(function(){
	//tab1
	$('.tab1').mouseover(function(){
			if(!$(this).hasClass('chosen_tab')){
					$(this).addClass('chosen_tab').siblings('.tab1').removeClass('chosen_tab');
					var theId = $(this).attr('id');
					if(theId == 'all_tab'){
						$('#list1').show().siblings('ul').hide();
					}
					if(theId == 'news_tab'){
						$('#list2').show().siblings('ul').hide();
					}
					if(theId == 'act_tab'){
						$('#list3').show().siblings('ul').hide();
					}
			}
	});
	//fadeButton
	function fadeButton(_id){
			$(_id).hover(function(){
			$(this).stop().fadeTo(100,1);
			},function(){
			$(this).stop().fadeTo(400,0);
			}
			);			
	}
	fadeButton('#login_button');
	fadeButton('#ucenter');
	fadeButton('#reg_button');
	fadeButton('.newbie');
	fadeButton('#nav1');
	fadeButton('#nav2');
	fadeButton('#nav3');
	fadeButton('#nav4');
	fadeButton('#nav5');
	fadeButton('.dl_button1');
	fadeButton('.dl_button2');
	//fadeButton2
	function fadeButton2(_id){
			$(_id).hover(function(){
				$(this).stop().fadeTo(100,1);
			},function(){
				if($(this).hasClass('cantsee')){
					$(this).stop().fadeTo(400,0);	
				}
			}
			);			
	}
	//tab2
	function tabChanger(_tab,_chosen,_hide){
		$(_tab).mouseover(function(){
		if(!$(this).hasClass(_chosen)){
			$(this).addClass(_chosen).siblings(_tab).removeClass(_chosen);
			var relPart = $(this).attr('id')+'0';
			$('#'+relPart).show().siblings(_hide).hide();
		}
		});
	}
	tabChanger('.tab2','chosen_tab','.pic_box');
	tabChanger('.tab3','chosen_tab','.warbook_list');
	tabChanger('.media','chosen','.media_iframe');
	tabChanger('.ask','chosen','.ask_iframe');
	
	//picture changer1
	$('#zhanshi_pic').css('z-index','1');
	function tabClick(item){
		$(item).removeClass('cantsee').css('opacity','1').css('filter','alpha(opacity = 100)').siblings('.ab').addClass('cantsee').css('opacity','0').css('filter','alpha(opacity = 0)');
	}
	
	$('#zhanshi_tab').mouseover(function(){
		tabClick(this);		
		$('#zhanshi_pic').css('z-index','1').siblings('.ab').css('z-index',0);
	});
	$('#gongshou_tab').mouseover(function(){
		tabClick(this);
		$('#gongshou_pic').css('z-index','1').siblings('.ab').css('z-index',0);
	});
	$('#daoshi_tab').mouseover(function(){
		tabClick(this);
		$('#daoshi_pic').css('z-index','1').siblings('.ab').css('z-index',0);
	});
	$('#moushi_tab').mouseover(function(){
		tabClick(this);
		$('#moushi_pic').css('z-index','1').siblings('.ab').css('z-index',0);
	});
	fadeButton2('.cantsee');
	
	//rank
	$('#level_rank').mouseover(function(){
		$(this).parent('#rank_tab').addClass('focus_level').removeClass('focus_power');
		$('#rank_table').show();
		$('#rank_table2').hide();
	});
	$('#power_rank').mouseover(function(){
		$(this).parent('#rank_tab').addClass('focus_power').removeClass('focus_level');
		$('#rank_table2').show();
		$('#rank_table').hide();
	});
	

	
	
	//swfobject.embedSWF("/wp-content/themes/sg_v2/img/start.swf?1234", "start_game", "195", "135","9.0.0","",{},{wmode:"transparent",scale:"noscale"},{})
	
	$('body').append('<div class="black_drop"></div>');
	$('.start_game').click(function(){
		var sTop = $(document).scrollTop(); 
		$('.black_drop').show();
		$('.popup_choose').css('top',(sTop + 100)+'px').show();
		
	})
	$('#popup_close').click(function(){
		$('.black_drop').hide();
		$('.popup_choose').hide();
	})
})();
