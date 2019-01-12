function login(){
	var loginID = document.getElementById('loginID').value;
	var loginPWD = document.getElementById('loginPWD').value;
	var login_json = {"ID":loginID, "pwd":loginPWD};
	var phpfile = "./php/login.php";
	$.ajax({
		type:"POST",
		url:phpfile,
		data:login_json,
		success:function(data,status){
			var data = data;
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