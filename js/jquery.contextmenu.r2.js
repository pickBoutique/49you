
 var menu_mgr = new Object();
(function($) {

 	var menu, shadow, trigger, content, hash, currentTarget;
  var defaults = {
    menuStyle: {
      listStyle: 'none',
      padding: '1px',
      margin: '0px',
      backgroundColor: '#fff',
      border: '1px solid #999'
    },
    itemStyle: {
      margin: '0px',
      color: '#000',
      display: 'block',
      cursor: 'default',
      padding: '3px',
      border: '1px solid #fff',
      backgroundColor: 'transparent'
    },
    itemHoverStyle: {
      border: '1px solid #0a246a',
      backgroundColor: '#b6bdd2'
    },
    eventPosX: null,
    eventPosY: null,
    shadow : true,
    onContextMenu: null,
    onShowMenu: null
 	};

  function addEventHandler(oTarget, sEventType, fnHandler) {

    if (oTarget.addEventListener) {

        oTarget.addEventListener(sEventType, fnHandler, false);
    } else if (oTarget.attachEvent) {

        oTarget.attachEvent("on" + sEventType, fnHandler);

    } else {
        oTarget["on" + sEventType] = fnHandler;
    }
  };
  function callEvent(obj, eventname) {
	  if (navigator.appName.indexOf("Microsoft") != -1) {
		  /*IE*/
		  obj.fireEvent("on" + eventname);
	  }
	  else {
		  /*Other*/
		  var e = document.createEvent("Events");
		  e.initEvent(eventname, true, true);
		  obj.dispatchEvent(e);
	  }
  };

  $.fn.contextMenu = function(id, options) {
    if (!menu) {                                      // Create singleton menu
      menu = $('<div id="jqContextMenu"></div>')
               .hide()
               .css({position:'absolute', zIndex:'500'})
               .appendTo('body')
               .bind('click', function(e) {
                 e.stopPropagation();
               });
    }
    if (!shadow) {
      shadow = $('<div></div>')
                 .css({backgroundColor:'#000',position:'absolute',opacity:0.2,zIndex:499})
                 .appendTo('body')
                 .hide();
    }

    hash = hash || [];
    hash.push({
      id : id,
      menuStyle: $.extend({}, defaults.menuStyle, options.menuStyle || {}),
      itemStyle: $.extend({}, defaults.itemStyle, options.itemStyle || {}),
      itemHoverStyle: $.extend({}, defaults.itemHoverStyle, options.itemHoverStyle || {}),
      bindings: options.bindings || {},
      shadow: options.shadow || options.shadow === false ? options.shadow : defaults.shadow,
      onContextMenu: options.onContextMenu || defaults.onContextMenu,
      onShowMenu: options.onShowMenu || defaults.onShowMenu,
      eventPosX: options.eventPosX || defaults.eventPosX,
      eventPosY: options.eventPosY || defaults.eventPosY
    });

    var index = hash.length - 1;
	
	for(var i=0;i<this.length;i++){
		var obj = this[i];
		obj.oncontextmenu = function(e){
			e = (e)?e:window.event;
			var bShowContext = (!!hash[index].onContextMenu) ? hash[index].onContextMenu(e) : true;
	  		if (bShowContext) display(index, this, e, options);
	  		return false;
		};
		menu_mgr.show_last = function(){
			callEvent(obj,'contextmenu');
		};
	}
	/*
	this.bind('mousedown', function(e) {
		alert(1);
	  // Check if onContextMenu() defined
	  //var bShowContext = (!!hash[index].onContextMenu) ? hash[index].onContextMenu(e) : true;
	  //if (bShowContext) display(index, this, e, options);
	  //return false;
	});
	*/
    return this;
  };

  function display(index, trigger, e, options) {
    var cur = hash[index];
    content = $('#'+cur.id).find('ul:first').clone(true);
    content.css(cur.menuStyle).find('li').css(cur.itemStyle).hover(
      function() {
        $(this).css(cur.itemHoverStyle);
      },
      function(){
        $(this).css(cur.itemStyle);
      }
    ).find('img').css({verticalAlign:'middle',paddingRight:'2px'});

    // Send the content to the menu
    menu.html(content);
	
    // if there's an onShowMenu, run it now -- must run after content has been added
		// if you try to alter the content variable before the menu.html(), IE6 has issues
		// updating the content
	var left = e['pageX'];
	var top = e['pageX'];
    if (!!cur.onShowMenu) menu = cur.onShowMenu(e, menu, trigger);
	if(cur.eventPosX){
		left = cur.eventPosX;
	}
	if(cur.eventPosY){
		top = cur.eventPosY;
	}
	
    $.each(cur.bindings, function(id, func) {
      $('#'+id, menu).bind('click', function(e) {
        hide();
        func(trigger, currentTarget);
      });
    });

    menu.css({'left':left,'top':top}).show();
    if (cur.shadow) shadow.css({width:menu.width(),height:menu.height(),left:left+2,top:top+2}).show();
    $(document).one('click', hide);
  }

  function hide() {
	  if(menu){
    	menu.hide();
	  }
	  if(shadow){
    	shadow.hide();
	  }
  }
  menu_mgr.hide = hide;

  // Apply defaults
  $.contextMenu = {
    defaults : function(userDefaults) {
      $.each(userDefaults, function(i, val) {
        if (typeof val == 'object' && defaults[i]) {
          $.extend(defaults[i], val);
        }
        else defaults[i] = val;
      });
    }
  };

})(jQuery);

$(function() {
  $('div.contextMenu').hide();
});