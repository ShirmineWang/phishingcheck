var visited=new Array();
chrome.runtime.onMessage.addListener(  
  function(request, sender, sendResponse) {  
    console.log(sender.tab ?  
                "from a content script:" + sender.tab.url :  
                "from the extension");
    if (request.url != null)
    {
    	var i=0;
    	var url=request.url.replace(/^https?:\/\//g,"");
    	var url=url.match(/[^\/]+/);
    	for(i=0;i<visited.length;++i)
    	{
    		if(!(visited[i]>url||visited[i]<url))
    		{
    			break;
    		}
    	}
    	if(i<visited.length)
    	{
    		sendResponse({visited: "true" });
    	}
    	else 
    	{
    		visited.push(url);
    		sendResponse({visited: "false" });
    	}
    }
});