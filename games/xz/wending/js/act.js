var isIE = (document.all) ? true : false;

var isIE6 = isIE && ([/MSIE (\d)\.0/i.exec(navigator.userAgent)][0][1] == 12);

var $ = function (id) {
	return "string" == typeof id ? document.getElementById(id) : id;
};

var Class = {
	create: function() {
		return function() { this.initialize.apply(this, arguments); }
	}
}

var Extend = function(destination, source) {
	for (var property in source) {
		destination[property] = source[property];
	}
}

var Bind = function(object, fun) {
	return function() {
		return fun.apply(object, arguments);
	}
}

var Each = function(list, fun){
	for (var i = 0, len = list.length; i < len; i++) { fun(list[i], i); }
};

var Contains = function(a, b){
	return a.contains ? a != b && a.contains(b) : !!(a.compareDocumentPosition(b) & 16);
}
var OverLay = Class.create();
OverLay.prototype = {
  initialize: function(options) {

	this.SetOptions(options);
	
	this.Lay = $(this.options.Lay) || document.body.insertBefore(document.createElement("div"), document.body.childNodes[0]);
	
	this.Color = this.options.Color;
	this.Opacity = parseInt(this.options.Opacity);
	this.zIndex = parseInt(this.options.zIndex);
	
	with(this.Lay.style){ display = "none"; zIndex = this.zIndex; left = top = 0; position = "fixed"; width = height = "100%"; }
	
	if(isIE6){
		this.Lay.style.position = "absolute";
		//ie6���ø��ǲ��С����
		this._resize = Bind(this, function(){
			this.Lay.style.width = Math.max(document.documentElement.scrollWidth, document.documentElement.clientWidth) + "px";
			this.Lay.style.height = Math.max(document.documentElement.scrollHeight, document.documentElement.clientHeight) + "px";
		});
		//�ڸ�select
		this.Lay.innerHTML = '<iframe style="position:absolute;top:0;left:0;width:100%;height:100%;filter:alpha(opacity=0);"></iframe>'
	}
  },
  //����Ĭ������
  SetOptions: function(options) {
    this.options = {//Ĭ��ֵ
		Lay:		null,//���ǲ����
		Color:		"#000",//����ɫ
		Opacity:	50,//͸����(0-100)
		zIndex:		1000//���˳��
    };
    Extend(this.options, options || {});
  },
  //��ʾ
  Show: function() {
	//����ie6
	if(isIE6){ this._resize(); window.attachEvent("onresize", this._resize); }
	//������ʽ
	with(this.Lay.style){
		//����͸����
		isIE ? filter = "alpha(opacity:" + this.Opacity + ")" : opacity = this.Opacity / 100;
		backgroundColor = this.Color; display = "block";
	}
  },
  //�ر�
  Close: function() {
	this.Lay.style.display = "none";
	if(isIE6){ window.detachEvent("onresize", this._resize); }
  }
};

var LightBox = Class.create();
LightBox.prototype = {
  initialize: function(box, options) {
	
	this.Box = $(box);//��ʾ��
	
	this.OverLay = new OverLay(options);//���ǲ�
	
	this.SetOptions(options);
	
	this.Fixed = !!this.options.Fixed;
	this.Over = !!this.options.Over;
	this.Center = !!this.options.Center;
	this.onShow = this.options.onShow;
	
	this.Box.style.zIndex = this.OverLay.zIndex + 1;
	this.Box.style.display = "none";
	
	//����ie6�õ�����
	if(isIE6){
		this._top = this._left = 0; this._select = [];
		this._fixed = Bind(this, function(){ this.Center ? this.SetCenter() : this.SetFixed(); });
	}
  },
  //����Ĭ������
  SetOptions: function(options) {
    this.options = {//Ĭ��ֵ
		Over:	true,//�Ƿ���ʾ���ǲ�
		Fixed:	false,//�Ƿ�̶���λ
		Center:	true,//�Ƿ����
		onShow:	function(){}//��ʾʱִ��
	};
    Extend(this.options, options || {});
  },
  //����ie6�Ĺ̶���λ����
  SetFixed: function(){
	this.Box.style.top = document.documentElement.scrollTop - this._top + this.Box.offsetTop + "px";
	this.Box.style.left = document.documentElement.scrollLeft - this._left + this.Box.offsetLeft + "px";
	
	this._top = document.documentElement.scrollTop; this._left = document.documentElement.scrollLeft;
  },
  //����ie6�ľ��ж�λ����
  SetCenter: function(){
	this.Box.style.marginTop = document.documentElement.scrollTop - this.Box.offsetHeight / 2 + "px";
	this.Box.style.marginLeft = document.documentElement.scrollLeft - this.Box.offsetWidth / 2 + "px";
  },
  //��ʾ
  Show: function(options) {
	//�̶���λ
	this.Box.style.position = this.Fixed && !isIE6 ? "fixed" : "absolute";



	//���ǲ�
	this.Over && this.OverLay.Show();
	
	this.Box.style.display = "block";
	
	//����
	if(this.Center){
		this.Box.style.top = this.Box.style.left = "50%";
		//����margin
		if(this.Fixed){
			this.Box.style.marginTop = - this.Box.offsetHeight / 2 + "px";
			this.Box.style.marginLeft = - this.Box.offsetWidth / 2 + "px";
		}else{
			this.SetCenter();
		}
	}
	
	//����ie6
	if(isIE6){
		if(!this.Over){
			//û�и��ǲ�ie6��Ҫ�Ѳ���Box�ϵ�select����
			this._select.length = 0;
			Each(document.getElementsByTagName("select"), Bind(this, function(o){
				if(!Contains(this.Box, o)){ o.style.visibility = "hidden"; this._select.push(o); }
			}))
		}
		//������ʾλ��
		this.Center ? this.SetCenter() : this.Fixed && this.SetFixed();
		//���ö�λ
		this.Fixed && window.attachEvent("onscroll", this._fixed);
	}
	
	this.onShow();
  },
  //�ر�
  Close: function() {
	this.Box.style.display = "none";
	this.OverLay.Close();
	if(isIE6){
		window.detachEvent("onscroll", this._fixed);
		Each(this._select, function(o){ o.style.visibility = "visible"; });
	}
  }
};

var box = new LightBox("idBox", "idOverlay",{ Center: true,Fixed:true });
var box1 = new LightBox("idBox1", "idOverlay",{ Center: true,Fixed:true });
var box2 = new LightBox("idBox2", "idOverlay",{ Center: true,Fixed:true });
var box3 = new LightBox("idBox3", "idOverlay",{ Center: true,Fixed:true });
var box4 = new LightBox("idBox4", "idOverlay",{ Center: true,Fixed:true });
var box5 = new LightBox("idBox5", "idOverlay",{ Center: true,Fixed:true });
var box6 = new LightBox("idBox6", "idOverlay",{ Center: true,Fixed:true });
var box7 = new LightBox("idBox7", "idOverlay",{ Center: true,Fixed:true });
var box8 = new LightBox("idBox8", "idOverlay",{ Center: true,Fixed:true });
var box9 = new LightBox("idBox9", "idOverlay",{ Center: true,Fixed:true });
var box10 = new LightBox("idBox10", "idOverlay",{ Center: true,Fixed:true });
var box11 = new LightBox("idBox11", "idOverlay",{ Center: true,Fixed:true });
var box12 = new LightBox("idBox12", "idOverlay",{ Center: true,Fixed:true });
var box13 = new LightBox("idBox13", "idOverlay",{ Center: true,Fixed:true });
var box14 = new LightBox("idBox14", "idOverlay",{ Center: true,Fixed:true });
var box15 = new LightBox("idBox15", "idOverlay",{ Center: true,Fixed:true });
var box16 = new LightBox("idBox16", "idOverlay",{ Center: true,Fixed:true });
var box17 = new LightBox("idBox17", "idOverlay",{ Center: true,Fixed:true });
var box18 = new LightBox("idBox18", "idOverlay",{ Center: true,Fixed:true });
var box19 = new LightBox("idBox18", "idOverlay",{ Center: true,Fixed:true });

/*  |xGv00|ed9867718391296fca444f6e13b4878c */