
// Javascript语言
// 通知iPhone UIWebView 加载url对应的资源
// url的格式为: fun:param1:param2:param3.....
function ums(url) {
    var iFrame;
    iFrame = document.createElement("iframe");
    var pre = "ums:$";
    url=pre+url;
    iFrame.setAttribute("src", url);
    iFrame.setAttribute("style", "display:none;");
    iFrame.setAttribute("height", "0px");
    iFrame.setAttribute("width", "0px");
    iFrame.setAttribute("frameborder", "0");
    document.body.appendChild(iFrame);
    // 发起请求后这个iFrame就没用了，所以把它从dom上移除掉
    iFrame.parentNode.removeChild(iFrame);
    iFrame = null;
}

function onEvent(eventIdentifier)
{
    ums("onevent:$"+eventIdentifier);
}

function onEventACC(eventIdentifier,acc)
{
    ums("oneventacc:$"+eventIdentifier+":$"+acc);
}

function onEventJSON(eventIdentifier,json)
{
    ums("oneventjson:$"+eventIdentifier+":$"+json);
}