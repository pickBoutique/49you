function tabChange(obj,id)
{
	var morelinks = Array();
	morelinks[0]="news.html?pid=314&cid=315";
	morelinks[1]="news.html?pid=314&cid=316";
	morelinks[2]="news.html?pid=314&cid=317";

 var arrayli = obj.parentNode.getElementsByTagName("li"); //获取li数组
 var arrayul = document.getElementById(id).getElementsByTagName("ul"); //获取ul数组
 for(i=0;i<arrayul.length;i++)
 {
  if(obj==arrayli[i])
  {
   arrayli[i].className = "cli";
   arrayul[i].className = "";
    $("#news_more").html("<a href='"+morelinks[i]+"' target='_blank' style='font-weight:normal; color:#fff; font-size:12px;'>更多>></a>");
  }
  else
  {
   arrayli[i].className = "";
   arrayul[i].className = "hidden";
  }
 }
}
