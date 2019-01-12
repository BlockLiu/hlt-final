    if(checkcookie("suid") == false){
            window.location.href="signin.html";
        }
    else{
        var suid = getcookie("suid");
        var tuid = getcookie("tuid");
        }
    delCookie("tuid");

    // 加载头像
    var avatarSrc = "./avatar/" + tuid + ".png" ;
    document.getElementById("avatar").src = avatarSrc;

    //加载内容
    var phpfile="./php/homepage.php";
    $.ajax({ 
        url:phpfile+"?sidu="+suid+"&uid="+tuid,
        success:function(data,status){ 
            // console.log(data);
            /*  
            假设返回来的json对象是这样的：
            {  
                "suid_screen_name": "xxx",
                "tuid_screen_name":"xxx",
                "suid_follow_tuid":true/false,
                "followersnum": xx,
                "favouritesnum": xx,
                "weibo":[ {"time":"xxx", "imageURL":"xxx", "Text":"xxx"}
                         {"time":"xxx", "imageURL":"xxx", "Text":"xxx"}
                        ]  
            }
            */
            var obj = eval( '(' + data + ')' );//把json串解析成json对象  
            
            //加载用户信息
            getElementByClass("suid_screen_name").innerHTML=obj.suid_screen_name;
            getElementByClass("tuid_screen_name").innerHTML=obj.tuid_screen_name;
            getElementByClass("favouritesnum").innerHTML=obj.favouritesnum;
            getElementByClass("followersnum").innerHTML=obj.followersnum;
            if(obj.suid_follow_tuid)
                getElementByClass("follow-btn").innerHTML="取消关注";
            else
                getElementByClass("follow-btn").innerHTML="关注ta";

            //加载微博信息
            var content = getElementByClass("content");
            for(var i=0; i<obj.weibo.length; i++){
                var str = "";
                str += "            <!-- 说说" + str(i) + " -->\n";//need i
                str += "            <div class=\"item\">\n";
                str += "                <div class=\"u-info\">\n";
                str += "                    <div class=\"u-icon\">\n";
                str += "                        <img src=\""+ tuid + ".png\" alt=\""+ tuid + "\" class=\"weiboAvatar\" width=\"50px\"/>\n";//need tuid
                str += "                    </div>\n";
                str += "                    <div class=\"name-pub\">\n";
                str += "                        <div class=\"u-name\">\n";
                str += "                            <a href=\"\">"+ obj.screen_name + "</a>\n";//need screen_name and url
                str += "                        </div>\n";
                str += "                        <div class=\"u-pub\">\n";
                str += "                            "+ obj.weibo[i].time + "\n";//need weibo time
                str += "                        </div>\n";
                str += "                    </div>\n";
                str += "                </div>\n";
                str += "                <div class=\"itemcontent\">\n";
                str += "                    <div class=\"thumbnail\">\n";
                str += "                        <img src=\""+ obj.weibo[i].imageURL + "\" alt=\""+ obj.weibo[i].imageURL + "\">\n";//need weibo image
                str += "                    </div>\n";
                str += "                    <div class=\"full-text\">\n";
                str += "                        <p>"+ obj.weibo[i].Text + "</p>\n";//need weibo text
                str += "                    </div>\n";
                str += "                </div>\n";
                str += "                <div class=\"u-list\">\n";
                str += "                    <ul>\n";
                str += "                        <li><a href=\"\"><span>分享</span></a></li>\n";//need repostsnum
                str += "                        <li><a href=\"\"><span>评论</span><span> "+ obj.weibo[i].commentsnum + "</span></a></li>\n";//need commentsnum
                str += "                        <li><a href=\"\"><span>喜欢</span><span> "+ obj.weibo[i].likesnum + "</span></a></li>\n";//need likesnum
                str += "                    </ul>\n";
                str += "                </div>\n";
                str += "            </div>\n";

                content.innerHTML= content.innerHTML + str;
            }       
        },
        error:function(data,status){ 
          alert('failed!'); 
        } 
    }); 

    document.getElementByClass("follow-btn").onclick() = follow();




function follow(){
    var follow_button = document.getElementByClass("follow-btn");
    var phpfile="./php/follow.php";
    if(follow_button.innerHTML == "关注ta"){
        $.ajax({ 
        url:phpfile+"?keyWord="+suid+"&add_follow&"+tuid,
        success:function(data,status){ 
            // console.log(data);
            follow_button.innerHTML = "取消关注" ;     
        },
        error:function(data,status){ 
          alert('failed!'); 
        } 
     });
    }else{
        $.ajax({ 
        url:phpfile+"?keyWord="+suid+"&del_follow&"+tuid,
        success:function(data,status){ 
            // console.log(data);
            follow_button.innerHTML = "关注ta" ;     
        },
        error:function(data,status){ 
          alert('failed!'); 
        } 
     });
    }
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