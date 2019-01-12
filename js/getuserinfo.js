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