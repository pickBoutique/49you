
/*****菜单显示类*****/
	var Tro_MenuCls=function(menuID,desID,exceptionID,offset_x,offset_y,instanceName){
		this.m_Timer=null;
		this.lm_vcss="";
		this.m_bIE;
		this.m_bIE=navigator.userAgent.indexOf("IE")!=-1?true:false;
		
		this.ShowMenu=function(){
			this.lm_vcss=$("#"+menuID)[0].style.visibility=="visible"?"hidden":"visible";
			$("#"+menuID).css({left:$("#"+desID).offset().left+offset_x+"px",top:$("#"+desID).offset().top+offset_y+"px",visibility:this.lm_vcss});

			if(this.lm_vcss=="visible"){
				this.m_hTimer=setInterval(this.KeepPosFun,100);
				this.m_bIE?document.attachEvent("onclick",this.onDocClick):document.addEventListener("click",this.onDocClick,true);
			}else{
				this.clearTimer();
			}
		}
		
		this.onDocClick=function(e){
			eval("var curObj="+instanceName+";");
			
			if(curObj.lm_vcss!="visible")
				return;
			
			var target_id=curObj.m_bIE?e.srcElement.id:e.target.id;
			if(target_id==menuID||target_id==desID||target_id==exceptionID)
				return;
				
			curObj.lm_vcss="hidden";
			$("#"+menuID).css({visibility:curObj.lm_vcss});
			curObj.clearTimer();
		}
		
		this.clearTimer=function(){
			if(this.m_hTimer!=null){
				clearInterval(this.m_hTimer);
				this.m_hTimer=null;
			}
			this.m_bIE?document.detachEvent("onclick",this.onDocClick):document.removeEventListener("click",this.onDocClick,true);
		}
	
		this.KeepPosFun=function(){
			$("#"+menuID).css({left:$("#"+desID).offset().left+offset_x+"px",top:$("#"+desID).offset().top+offset_y+"px"});
		}

	}