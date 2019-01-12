//获得窗口的高度和长度
function getScreenSize(){
    var pageWidth = window.innerWidth,
        pageHeight = window.innerHeight;
    if (typeof pageWidth != "number"){
        if (document.compatMode == "CSS1Compat"){
            pageWidth = document.documentElement.clientWidth;
            pageHeight = document.documentElement.clientHeight;
        } else {
            pageWidth = document.body.clientWidth;
            pageHeight = document.body.clientHeight;
        }
    }
    return [pageWidth, pageHeight];
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


function publish(){
    var phpfile="./php/publish.php";
    var suid = getCookie("suid");
    var text = document.getElementsByID("weibo-text").value;
    var image = document.getElementsById("weiboimage").value;
    var flag = false;
    $.ajax({ 
    url:phpfile+"?keyWord="+suid+"~&"+text,
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
    ;
    },
    error:function(data,status){ 
          alert('failed!'); 
          } 
    });  

    if(flag){
        var fso, tf;
        fso = new ActiveXObject("Scripting.FileSystemObject");
        tf = fso.CreateTextFile("image/"+suid+".png", true);
        tf.Write(image);
        tf.Close();
    }   
}

//点击右上角发布按钮显示发布框。
var p = document.getElementsByClassName("post")[0];
var p_btn = document.getElementsByClassName("publish")[0];
var bg = document.getElementsByClassName("opacity-bg")[0];
var p_txt = document.getElementsByTagName("textarea")[0];
p_btn.onclick=function(){
    //计算发布框的左偏移left和上偏移top
    var screenSize = getScreenSize();
    console.log(screenSize[0], screenSize[1]);
    var offleft = Math.floor( (screenSize[0]-460)/2 );
    var offtop = Math.floor( (screenSize[1]-209)/2 );

    p.style.left=offleft+"px";
    p.style.top=offtop+"px";
    bg.style.display = "block";
    p.style.display = "block";
}
var close = document.getElementsByClassName("post-close")[0];
close.onclick= function(){
    p_txt.value="";
    bg.style.display = "none";
    p.style.display = "none";
}
//发布微博
var post_button = document.getElementsByID("post-btn");
post_button.onclick() = publish();
