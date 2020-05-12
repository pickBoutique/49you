
var focus_width=466
var focus_height=166
var text_height=0
var swf_height = focus_height+text_height

var pics="/uploads/2011/0819/144635_W3Om7U.jpg|/uploads/2011/0815/120438_Vmyf1i.jpg|/uploads/2011/0729/115832_59Ycuq.jpg"
var links="/html/news/2011/0819/119.html|/html/news/2011/0815/112.html|/html/news/2011/0729/84.html"
var texts=""

var flashCode = '<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/hotdeploy/flash/swflash.cab#version=6,0,0,0" width="'+ focus_width +'" height="'+ swf_height +'">';
flashCode = flashCode + '<param name="allowScriptAccess" value="sameDomain"><param name="movie" value="/js/focus2.swf"><param name="quality" value="high"><param name="wmode" value="transparent">';
flashCode = flashCode + '<param name="menu" value="false">';
flashCode = flashCode + '<param name="FlashVars" value="pics='+pics+'&links='+links+'&texts='+texts+'&borderwidth='+focus_width+'&borderheight='+focus_height+'&textheight='+text_height+'">';
flashCode = flashCode + '<embed src="/js/focus2.swf" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="'+ focus_width +'" height="'+ swf_height +'" FlashVars="pics='+pics+'&links='+links+'&texts='+texts+'&borderwidth='+focus_width+'&borderheight='+focus_height+'&textheight='+text_height+'"></embed>';
flashCode = flashCode + '</object>';
document.write(flashCode) 

 <!--该行不能删除-->

<!--这个不要去掉-->
