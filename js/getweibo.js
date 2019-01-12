function getWeibo(times){
    //开始装载微博,by ajax
    var content = document.getElementsByClassName("content")[0];
    var phpfile="./php/getweibo.php";
    $.ajax({ 
    url:phpfile+"?keyWord="+str(times),
    success:function(data,status){ 
    // console.log(data);
    /*  
    假设返回来的json对象是这样的：
    {  
     "weibo":[  {"userid":"xxx", "screen_name":"xxx", "avatarURL"="xxx" ,"time":"xxx", "imageURL":"xxx", "Text":"xxx", "attitudesnum":xx},
                {"userid":"xxx", "screen_name":"xxx", "avatarURL"="xxx" ,"time":"xxx", "imageURL":"xxx", "Text":"xxx", "attitudesnum":xx}
             ]  
    }
    */
    var obj = eval( '(' + data + ')' );//把json串解析成json对象  
    //加载微博信息
    var content = getElementByClass("content");
    for(var i=0; i<obj.weibo.length; i++){
        var str = "";
        str += "            <!-- 说说" + str(i) + " -->\n";//need i
        str += "            <div class=\"item\">\n";
        str += "                <div class=\"u-info\">\n";
        str += "                    <div class=\"u-icon\">\n";
        str += "                        <img src=\""+ userid + ".png\" alt=\""+ userid + "\" class=\"weiboAvatar\" width=\"50px\"/>\n";//need tuid
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
}