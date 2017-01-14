function visit()
{
	html.innerHTML=test;
}
chrome.runtime.onMessage.addListener(  
  function(request, sender, sendResponse) {  
    console.log(sender.tab ?  
                "from a content script:" + sender.tab.url :  
                "from the extension");  
    if (request.greeting == "hello"){  
      sendResponse({farewell: "I'm contentscript,goodbye!"});  
      }  
  });
function dadaHttpRequest(url){
	if (window.XMLHttpRequest) {
		xmlhttp = new XMLHttpRequest;
		if (xmlhttp.overrideMimeType) {
			  xmlhttp.overrideMimeType('text/xml');
		};

	} else if (window.ActiveXObject){
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	};
	xmlhttp.onreadystatechange = callback;
	xmlhttp.open("POST","http://1.phishingcheck.sinaapp.com/index.php",true);
	xmlhttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	xmlhttp.send("url="+url+"&empty_href="+empty_href+"&exter_link="+exter_link);
}
function callback(){
	if (xmlhttp.readyState==4 && xmlhttp.status==200)
	{
		var responseText = xmlhttp.responseText;
		//alert(responseText);
		switch(responseText)
		{
		case '4':
			var str='<h1 align="center">经过数据库检测，您正在访问的网站可能是钓鱼网站</h1>';
			str += '<h2 align="center"><a href="#" onclick="location.reload()">继续访问</a></h2>';
			html.innerHTML=str;
			break;
		case '2':
			var str='<h1 align="center">您正在访问的网站安全情况不确定</h1>';
			str += '<h2 align="center"><a href="#" onclick="location.reload()">继续访问</a></h2>';
			html.innerHTML=str;
			break;
		case '3':
			//alert("site");
			break;
		case '5':
		    var str='<h1 align="center">经过url检测，您正在访问的网站可能是钓鱼网站</h1>';
		    str += '<h2 align="center"><a href="#" onclick="location.reload()">继续访问</a></h2>';
		    html.innerHTML=str;
		    break;
		case '6':
		    var str='<h1 align="center">经过内容检测，您正在访问的网站可能是钓鱼网站</h1>';
		    str += '<h2 align="center"><a href="#" onclick="location.reload()">继续访问</a></h2>';
		    html.innerHTML=str;
		    break;
		default:
			//html.innerHTML=responseText;
		}
	}    
}
var html=document.getElementsByTagName('html')[0];
var test=html.innerHTML;
var empty_href=test.match(/href\s*=\s*"#?"/ig);
if(empty_href)empty_href=test.match(/href\s*=\s*"#?"/ig).length;
var exter_link=test.match(/https?:/ig);
if(exter_link)exter_link=test.match(/https?:/ig).length;
chrome.runtime.sendMessage({url:window.location.href}, function(response) {  
  	if(response.visited=="false")
  	{
		dadaHttpRequest(window.location.href);
  	}
  	else if(response.visited=="true")
  	{

  	}
});