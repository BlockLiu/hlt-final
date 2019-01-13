1. 传参数事项：

- 每个php文件我都是POST方法接参数，可以按照下面例子传参
      /* 只需要修改post_data, php_file */
      var post_data = {"name":name, "screen_name":screen_name, "password":password, "province":province, "city":city, "gender":gender};	// "key"为php接收时用的参数，val为要发送给php的数据
      var phpfile = "signup.php";		// 此处修改为你想要访问的php
      $.ajax({
          type:"POST",
          url:phpfile,
          data:post_data,
          success:function(data, status){
              ....
          },
          error:function(data, status){
              ....
          }
      });
  
  
  



注册

- signup.php
- 输入关键词包括：
  - name：用户名
  - screen_name：昵称
  - password：设置的密码
  - province：
  - city：
  - gender：请确保值是 “female” or “male”
- 返回值：
  - 注册失败：返回{"status":"fail"}
  - 注册成功：返回 {"status":"success", "uid":xxxxxxxxx}
    		  返回的uid是用户要记住的，用户凭借uid登陆，可以用alert提醒用户记住uid



登陆

- login.php
- 输入关键词：
  - ID：uid
  - pwd：密码
- 返回值：
  - 用户不存在：{"status":"no such did"}
  - 密码不对：{"status":"wrong password"}
  - 登陆成功：{"status":"success", "uid":uid}





修改用户属性

- modifyUserInfo.php
- 输入关键词：
  - uid：uid
  - attribute：要修改的属性名称
    - 只能修改以下几个之一："name", "screen_name", "location", "url", "gender", "password", "headimage_id"
  - newVal：新的属性值
- 返回值：
  - 用户不存在：{"status":"no such did"}
  - 属性不存在：{"status":"no such attribute"}
  - 修改成功：{"status":"success"}



查看关注用户的微博

- getWeibo.php
- 输入关键词：
  - uid：uid
  - start：从最近的第几个开始
  - len：要几个
  - 例如：{"uid":xxx, "start":0, "len":10}表示要获取xxx关注的人的最近10个微博
- 返回值：
  - 用户不存在：{"status":"no such did"}
  - 若成功：{"weibo_num":n, "content":{...}}
    - n：返回n条微博
    - content：返回的n条微博内容，内部包含属性值如下：
      - wid：微博id
      - publish_time：发布时间
      - repostsnum：转发量
      - commentsnum：评论量
      - attitudesnum：点赞量
      - fromid：发布者的uid
      - topic：话题
      - source：一个链接
      - text：文字内容
      - sname：发布者的昵称



查看自己的微博

- getMyWeibo.php
- 输入关键词：
  - uid：uid
  - start：从最近的第几个开始
  - len：要几个
  - 例如：{"uid":xxx, "start":0, "len":10}表示要获取xxx关注的人的最近10个微博
- 返回值：
  - 用户不存在：{"status":"no such did"}
  - 若成功：{"weibo_num":n, "content":{...}}
    - n：返回n条微博
    - content：返回的n条微博内容，内部包含属性值如下：
      - wid：微博id
      - publish_time：发布时间
      - repostsnum：转发量
      - commentsnum：评论量
      - attitudesnum：点赞量
      - fromid：发布者的uid
      - topic：话题
      - source：一个链接
      - text：文字内容
      - sname：发布者的昵称





发送微博

- 待实现





查询本人关注的好友

- 待实现



查询谁关注了我

- 待实现



取消关注

- 待实现

关注

- 待实现



查询不同用户之间的联系（显示两个用户之间所有4条边以内的联系）

- 待实现




