## 传参数事项：

 - 每个php文件我都是POST方法接参数，可以按照下面例子传参

   ```javascript
   /* 只需要修改post_data, phpfile */
   var post_data = {"name":name, "screen_name":screen_name, "password":password, "province":province, "city":city, "gender":gender};  // "key"为php接收时用的参数，val为要发送给php的数据
   var phpfile = "signup.php";    // 此处修改为你想要访问的php
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
   ```

   

   

   



## 注册

 - signup.php

 - 输入关键词包括：

    - name：用户名
    - screen_name：昵称
    - password：设置的密码
    - province：
    - city：
    - gender：请确保值是 “female” or “male”

 - 返回值：

    - 注册失败：返回` {"status":"fail"}`

    - 注册成功：返回 `{"status":"success", "uid":xxxxxxxxx}`

      ​     返回的uid是用户要记住的，用户凭借uid登陆，可以用alert提醒用户记住uid



## 登陆

- login.php
- 输入关键词：
  - ID：uid
  - pwd：密码
- 返回值：
  - 用户不存在：`{"status":"no such uid"}`
  - 密码不对：`{"status":"wrong password"}`
  - 登陆成功：`{"status":"success", "uid":uid}`





## 修改用户属性

 - modifyUserInfo.php
 - 输入关键词：
    - uid：uid
    - attribute：要修改的属性名称
       - 只能修改以下几个之一："name", "screen_name", "location", "url", "gender", "password", "headimage_id"
    - newVal：新的属性值
 - 返回值：
    - 用户不存在：`{"status":"no such uid"}`
    - 属性不存在：`{"status":"no such attribute"}`
    - 修改成功：`{"status":"success"}`



## 查看关注用户的微博

 - getWeibo.php
 - 输入关键词：
   - uid：uid
   - start：从最近的第几个开始
   - len：要几个
   - 例如：`{"uid":xxx, "start":0, "len":10}`表示要获取xxx关注的人的最近10个微博
- 返回值：
  - 用户不存在：`{"status":"no such uid"}`
  - 若成功：`{"weibo_num":n, "content":{...}}`
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
      - picture：微博的图片名字，如果没有，就叫做"none"，请确保不重复



## 查看自己的微博

- getMyWeibo.php
- 输入关键词：
  - uid：uid
  - start：从最近的第几个开始
  - len：要几个
  - 例如：`{"uid":xxx, "start":0, "len":10}`表示要获取xxx关注的人的最近10个微博
- 返回值：
  - 用户不存在：`{"status":"no such uid"}`
  - 若成功：`{"weibo_num":n, "content":{...}}`
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
      - picture：微博的图片名字，如果没有，就叫做"none"，请确保不重复



## 获取用户信息

- getuserinfo.php

- 输入关键词：

  - uid：要查询的用户id

- 返回值：

  - 用户不存在：`{"status":"no such uid"}`
  - name：用户名
  - sname：昵称
  - location：地址
  - url：个人主页链接
  - gender：性别，只可能是 "f" 或者 "m"
  - followersnum：关注者数量
  - friendsnum：我关注的人数量
  - statusesnum：微博数
  - favoritesnum：无用数据
  - createdat：账号创建日期
  - headimageid：头像名，默认是 "default"，请确保有 "default.jpg" 这样的图片在服务器

  

## 发送微博

 - publish.php
 - 输入关键词：
    - uid：发布者uid
    - topic：所属话题（限制在10个字内）
    - source：发布源，可以写 `<a href=\"http://localhost/homepage\" rel=\"nofollow\">微博gStore版</a>`
       - 确保href里的链接是可以访问到个人主页的
       - 所有双引号 " 请写成 \"
    - text：文字内容
       - 若为转发，则文字版可以像群里截图那样转发（修改一下text）
    - picture：图片名，记得把图片上传。只支持一张图片。若无图，给我发送 "none"
       - 转发不可以加图片，只能使用原微博的图片
 - 返回值：
    - 用户不存在：`{"status":"no such uid"}`
    - 注册成功：返回 `{"status":"success", "wid":xxxxxxxxx}`




## 增加一条weibo之间的转发关系
- setWeiboRela.php
- 输入关键词：
  - swid：swid这条微博转发了twid这条微博，我要把这个关系加入数据库
  - twid
- 返回值：
  - swid不存在：`{"status":"no such swid"}`
  - twid不存在：`{"status":"no such twid"}`
  - 关系已存在：`{"status":"repost relation already exist"}`
  - 建立关系成功：返回 `{"status":"success"}`



## 查询本人关注的好友

 - myFollowee.php

 - 输入关键词：

    - uid：本人的uid
    - start：从第几个开始
    - len：要几个

 - 返回值：

    - 用户不存在：`{"status":"no such uid"}`

    - 若成功：`{"user_num":n, "content":{...}}`

      - n：返回n个好友信息

      - content：返回的n个好友信息，内部包含属性值如下：

        - uid：好友uid
        - screen_name：昵称

      - 若需要这些好友更详细信息，用好友id和getuserinfo.php获取

        



## 查询谁关注了我

- myFollower.php
- 输入关键词：
  - uid：本人的uid
  - start：从第几个开始
  - len：要几个
- 返回值：
  - uid不存在：`{"status":"no such uid"}`
  - 若成功：`{"user_num":n, "content":{...}}`
    - n：返回n个关注者信息
    - content：返回的n个关注者信息，内部包含属性值如下：
      - uid：关注者uid
      - screen_name：昵称
    - 若需要这些关注者更详细信息，用关注者id和getuserinfo.php获取



## 查询suid是否关注了tuid

- checkUserRelation.php
- 输入关键词：
  - suid：我的uid
  - tuid：要查的人的uid
- 返回值：
  - suid不存在：`{"status":"no such suid"}`
  - tuid不存在：`{"status":"no such tuid"}`
  - 若成功：`{"status":"success", "result":xxx}`
    - "result":"true"：关注
    - "result":"false"：没关注



## 取消关注

 - offRelation.php
 - 输入关键词：
    - suid：本人的uid
    - tuid：要取关的人的uid
 - 返回值：
    - suid不存在：`{"status":"no such suid"}`
    - tuid不存在：`{"status":"no such tuid"}`
    - 本来就没有关注：`{"status":"no such relation"}`
    - 取关成功：返回 `{"status":"success"}`
    - 取关失败：返回 `{"status":"fail"}`



## 关注

- getRelation.php
- 输入关键词：
  - suid：本人的uid
  - tuid：要关注的人的uid
- 返回值：
  - suid不存在：`{"status":"no such suid"}`
  - tuid不存在：`{"status":"no such tuid"}`
  - 已经关注：`{"status":"already follow"}`
  - 关注成功：返回 `{"status":"success"}`
  - 关注失败：返回 `{"status":"fail"}`



## 上传头像

- uploadHeadImage.php
- html表单设置：
  - action改成这个
  - method必须是post
  - input的name必须是headimage
  - 记得在uploads/headimages/文件夹下放一个default.png
  - 注意，我暂时只支持png文件上传
- 返回值：
  - 上传成功：返回 `{"status":"success"}`
  - 上传失败：各种各样的信息返回



## 上传微博图片

- uploadWeiboPic.php
- html表单设置：
  - action改成这个
  - method必须是post
  - input的name必须是weiboPic
  - 注意，我暂时只支持png文件上传
- 返回值：
  - 上传成功：返回 `{"status":"success"}`
  - 上传失败：各种各样的信息返回



## 下载头像

- getHeadImage.php
- 输入参数：
  - uid
- 返回值：
  - uid不存在：`{"status":"no such suid"}`
  - 成功：`{"status":"success", "content":xxx}`
    - 返回的content是图片内容，格式是base64
    - 可以参考下列网站：
      - https://zhidao.baidu.com/question/1888052965315105428.html
      - https://www.jianshu.com/p/91bcf4c3d155





## 下载微博图片

- getWeiboPic.php
- 输入参数：
  - wid
- 返回值：
  - wid不存在：`{"status":"no such wid"}`
  - 图片不存在：`{"status":"no picture for this wid"}`
  - 成功：`{"status":"success", "content":xxx}`
    - 返回的content是图片内容，格式是base64
    - 可以参考下列网站：
      - https://zhidao.baidu.com/question/1888052965315105428.html
      - https://www.jianshu.com/p/91bcf4c3d155





## 查询不同用户之间的联系（显示两个用户之间所有4条边以内的联系）

- fourEdge.php
- 输入参数包括：
  - suid：源uid
  - tuid：目标uid
  - 供测试的数据：`suid=1000080335`，`tuid=1757353251`
- 返回值：
  - suid不存在：`{"status":"no such suid"}`
  - tuid不存在：`{"status":"no such tuid"}`
  - 若成功：`{"one":one_edge_res,"two":two_edge_res,"three":three_edge_res,"four":four_edge_res}`
    - `one_edge_res`
      - `"status"`：
        - 若为0，则表示suid与tuid无直接联系
        - 若为1，suid直接关注tuid
    - `two_edge_res`：
      - `"status"`：suid与tuid通过一个中间用户相连的关系数量`n`
      - `"content"`：是一个数组，有`n`个元素
        - 数组中每个元素是一个uid，表示 suid->这个uid->tuid
    - `three_edge_res`：
      - `"status"`：suid与tuid通过两个中间用户相连的关系数量`n`
      - `"content"`：是一个数组，有`n`个元素
        - 数组中每个元素是一个映射，其中包含两个元素：
          - `"m1"`，`"m2"`：这两个都是uid
          - 表示  suid->m1->m2->tuid
    -  `four_edge_res`：
      - `"status"`：suid与tuid通过三个中间用户相连的关系数量`n`
      - `"content"`：是一个数组，有`n`个元素
        - 数组中每个元素是一个映射，其中包三两个元素：
          - `"m1"`，`"m2"`，`"m3"`：这三个都是uid
          - 表示  suid->m1->m2->m3->tuid



