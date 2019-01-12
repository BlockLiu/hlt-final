function signup(){
    //查找对象
    var name = document.getElementById('name').value;
    var screen_name = document.getElementById('screen_name').value;
    var password = document.getElementById('password').value;
    var password_confirm = document.getElementById('password_confirm').value;
    var province = document.getElementById('province').value;
    var city = document.getElementById('city').value;
    var sex_temp = document.getElementsByName('sex');
    var gender;
    for(var i = 0; i < sex_temp.length; i++)//获取性别
        if(sex_temp[i].checked == true)
            var gender = sex_temp[i].value;

    
    //检查信息是否符合要求
    if(name == ""){
        alert("用户名不能为空!");
        return;
    }
    if(screen_name == ""){
        alert("昵称不能为空!");
        return;
    }
    if(password == ""){
        alert("密码不能为空！");
        return;
    }
    if(password != password_confirm){
        alert("密码不一致！");
        return;
    }
    if(province == "None"){
        alert("请填写地区信息！");
        return;
    }
    if(city =="None"){
        alert("请输入城市！");
        return;
    }

    //与后台交互
    var signup_json = {"name":name, "screen_name":screen_name,"password":password,"province":province,"city":city,"gender":gender};
    var phpfile = "./php/signup.php";
    $.ajax({ 
        type:"POST",
        url:phpfile,
        data:signup_json,
        success:function(data,status){ 
            console.log(data);
            // var obj = eval( '(' + data + ')' );//把json串解析成json对象  
            // //数据从后台传回来以后。。。。。。。
            // document.cookie = "uid="+uid;//uid是后台传回来的用户id
        },
        error:function(data,status){ 
            alert('failed!'); 
        } 
    }); 

}
