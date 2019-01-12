//获得scrolltop
function getScrollTop() {
var scrollTop = 0;
    if (document.documentElement && document.documentElement.scrollTop) {
        scrollTop = document.documentElement.scrollTop;
    }
        else if (document.body) {
        scrollTop = document.body.scrollTop;
    }
    return scrollTop;
}
//获取body的总高度
function getScrollHeight() {
    return Math.max(document.body.scrollHeight, document.documentElement.scrollHeight);
}

function getCookie(cname)
{
  var name = cname + "=";
  var ca = document.cookie.split(';');
  for(var i=0; i<ca.length; i++) 
  {
    var c = ca[i].trim();
    if (c.indexOf(name)==0) return c.substring(name.length,c.length);
  }
  return "";
}

function checkCookie(cname)
{
  var username=getCookie(cname);
  if (username!="")
    return true;
  else 
    return false;
}

function delCookie(cname){
    document.cookie = cname + "=; expires=Thu, 01 Jan 1970 00:00:00 GMT";
}

function setCookie(cname,id){
    document.cookie = cname + "=" + id;
}

function getUserinfo(userid){
    var phpfile="./php/getuserinfo.php";
    $.ajax({ 
    url:phpfile+"?keyWord="+userid,
    success:function(data,status){ 
    // console.log(data);
    /*  
    假设返回来的json对象是这样的：
    {  
     "screen_name":"xxx", 
     "gender":"x", 
     "province":"xx", 
     "city":"xx", 
     "follownum":xx,
     "followersnum":xx,
     weibonum:xx
    }
    */
    var obj = eval( '(' + data + ')' );//把json串解析成json对象  
    return obj;
    },
    error:function(data,status){ 
          alert('failed!'); 
          } 
    });     
}


//start here
if(checkCookie("suid") == false){
    window.location.href="signin.html";
}
else{
    var suid = getcookie("suid");
}

var obj = getUserinfo(suid);
document.getElementById("followersnum").innerHTML = obj.followersnum;
document.getElementById("follownum").innerHTML = obj.follownum;
document.getElementById("weibonum").innerHTML = obj.weibonum;
document.getElementById("suid_avatar").src = "avatar/" + suid + ".png";
document.getElementByClass("suid_screen_name").innerHTML = obj.screen_name;

var count = 0;
getWeibo(++count);
window.onscroll = function () {
    if (getScrollTop() + getScreenSize()[1] == getScrollHeight()) {
        console.log("到达底部,加载");
        getWeibo(++count);               
    }
}